<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code.
 *
 *  @author    eKomi
 *  @copyright 2017 eKomi
 *  @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class EkomiRatingsAndReviews
 */
class EkomiRatingsAndReviews extends Module
{
    /**
     * The URL where the order data is sent.
     */
    const URL_TO_SEND_DATA = 'https://plugins-dashboard.ekomiapps.de/api/v1/order';

    /**
     * The Url to validate shop
     */
    const URL_GET_SETTINGS = 'http://api.ekomi.de/v3/getSettings';

    /**
     * The SRR URL to update the Smart Check Settings
     */
    const URL_SMART_CHECK_SETTINGS = 'https://srr.ekomi.com/api/v1/shops/setting';

    /**
     * Product Identifiers
     */
    const PRODUCT_IDENTIFIER_ID = 'id';
    const PRODUCT_IDENTIFIER_SKU = 'sku';

    /**
     * @var float
     */
    private $prestashopVersion;

    /**
     * @var bool
     */
    public $bootstrap;

    /**
     * @var string
     */
    public $confirmUninstall;

    /**
     * EkomiRatingsAndReviews constructor.
     */
    public function __construct()
    {
        $this->name = 'ekomiratingsandreviews';
        $this->tab = 'front_office_features';
        $this->version = '1.5.1';
        $this->author = 'eKomi';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->prestashopVersion = (float) _PS_VERSION_;

        parent::__construct();
        $this->displayName = $this->l('eKomi Ratings and Reviews');
        $this->description = $this->l('eKomi Ratings and Reviews allows to read the necessary order details 
        automatically from your shop system database which will enable eKomi to send a review request to your client, 
        determine which order status should trigger the review request, contact your clients via email or SMS, request 
        both seller and product reviews from your clients, display product reviews and ratings automatically on the 
        corresponding product pages through our Product Review Container (PRC). If you have any questions regarding the 
        plugin, please get in touch! Email us at support@ekomi.de, call us on +1 844-356-6487, or fill out our contact 
        form.');
        $this->confirmUninstall = $this->l(
            'Are you sure you want to uninstall?'
        );
        $this->module_key = '2ee1e1fac6f2f5872901416f2fc6501f';
    }

    /**
     * Installs the plugin.
     *
     * @return bool
     * @throws PrestaShopException
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
            !$this->installConfigurations()) {
            return false;
        }

        /**
         * Register hooks.
         */
        if (!($this->registerHook('ekomiSmartPrc') &&
            $this->registerHook('actionOrderStatusPostUpdate'))) {
            return false;
        }

        return true;
    }

    /**
     * Hooks the action on order status change.
     *
     * @param array $params Contains order details.
     * @return void
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        $configValues = $this->getConfigValues();

        $orderStatuses = $configValues['EKOMI_RNR_ORDER_STATUS[]'];
        if (in_array($params['newOrderStatus']->id, $orderStatuses)) {
            $order = new Order((int) $params['id_order']);
            if (Validate::isLoadedObject($order)) {
                if ($this->isActivated()) {
                    $orderData = $this->getRequiredFields($configValues, $order);
                    $this->sendOrderData($orderData);
                }
            }
        }
    }

    /**
     * Uninstalls the plugin.
     *
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('EKOMI_RNR_ENABLE') ||
            !Configuration::deleteByName('EKOMI_RNR_SHOP_ID') ||
            !Configuration::deleteByName('EKOMI_RNR_SHOP_PASSWORD') ||
            !Configuration::deleteByName('EKOMI_RNR_ORDER_STATUS') ||
            !Configuration::deleteByName('EKOMI_RNR_PRODUCT_REVIEWS') ||
            !Configuration::deleteByName('EKOMI_RNR_PRODUCT_IDENTIFIER') ||
            !Configuration::deleteByName('EKOMI_RNR_EXCLUDE_PRODUCTS') ||
            !Configuration::deleteByName('EKOMI_RNR_MODE') ||
            !Configuration::deleteByName('EKOMI_RNR_SMART_CHECK') ||
            !Configuration::deleteByName('EKOMI_RNR_SHOW_PRC_WIDGET') ||
            !Configuration::deleteByName('EKOMI_RNR_PRC_WIDGET_TOKEN')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Executes when displaying the contact form.
     *
     * It can also be used to catch the form submissions. You can
     * retrieve values of the submitted form here.
     *
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getContent()
    {
        $this->context->smarty->assign(
            array(
                'rnr_module_path' => $this->_path
            )
        );
        $this->context->controller->addCSS($this->_path .'views/css/config.css');
        $output = null;
        if (Tools::isSubmit('submit' . $this->name)) {
            $configValues = $this->getPostValues();
            $response = $this->verifyAccounts($configValues);
            if ($response['success']) {
                $this->updateValues($configValues);
                $output .= $this->displayConfirmation(
                    $this->l($response['message'])
                );
                $output .= $this->display(__FILE__, 'views/templates/admin/notification-text.tpl');
            } else {
                $output .= $this->displayError($this->l($response['message']));
                Configuration::updateValue('EKOMI_RNR_ENABLE', 0);
            }

            return $output.$this->displayForm().$this->interactiveScreenTop();
        } else {
            if ($this->isActivated()) {
                return $output.$this->displayForm().$this->interactiveScreenTop();
            } else {
                return $output.$this->displayForm().$this->interactiveScreenTop() .$this->interactiveScreenBottom();
            }
        }
    }

    /**
     * Returns the top part of interactive screen template.
     *
     * @return string
     */
    public function interactiveScreenTop()
    {
        return $this->display(__FILE__, 'views/templates/admin/interactive-screen-top.tpl');
    }

    /**
     * Returns the bottom part of interactive screen template.
     *
     * @return string
     */
    public function interactiveScreenBottom()
    {
        $this->context->controller->addJS($this->_path .'views/js/config.js');
        $this->context->smarty->assign(
            array(
                'rnr_booking_url' =>$this->getBookingURL()
            )
        );

        return $this->display(__FILE__, 'views/templates/admin/interactive-screen-bottom.tpl');
    }

    /**
     * Gets the booking urls for a language.
     *
     * @return string Booking url
     */
    private function getBookingURL()
    {
        switch ($this->context->language->iso_code) {
            case "es":
                $bookingUrl='https://ekomies.youcanbook.me/';
                break;
            case "pt":
                $bookingUrl='https://ekomies.youcanbook.me/';
                break;
            case "fr":
                $bookingUrl='https://ekomifr.youcanbook.me/';
                break;
            case "it":
                $bookingUrl='https://ekomiit.youcanbook.me/';
                break;
            case "de":
                $bookingUrl='https://ekomide.youcanbook.me/';
                break;
            default:
                $bookingUrl='https://ekomi.youcanbook.me/';
        }

        return $bookingUrl;
    }

    /**
     * Displays the configuration form.
     *
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function displayForm()
    {
        $formFields = array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Settings'),
                    ),
                    'input' => $this->getInputFields(),
                    'submit' => array(
                        'title' => $this->l('Save & Import Reviews'),
                        'class' => 'button'
                    )
                ),
            )
        );
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        return $this->getForm($formFields, $lang->id);
    }

    /**
     * Gets the form.
     *
     * @param array  $formFields
     * @param string $languageId
     * @return string
     */
    public function getForm($formFields, $languageId)
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        $helper->default_form_language = $languageId;
        $helper->allow_employee_form_lang = 0;
        if (Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')) {
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        }

        $helper->identifier = $this->identifier;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex .
                        '&configure=' . $this->name .
                        '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        return $helper->generateForm($formFields);
    }

    /**
     * Gets configurations for all languages
     *
     * @return array
     */
    public static function getConfigurationBylanguage()
    {
        $languages = Language::getLanguages(false);
        $configurations = array();
        foreach ($languages as $lang) {
            $configurations['EKOMI_RNR_SHOP_ID'][$lang['id_lang']] = Tools::getValue(
                'EKOMI_RNR_SHOP_ID_'.$lang['id_lang'],
                Configuration::get('EKOMI_RNR_SHOP_ID', $lang['id_lang'])
            );
            $configurations['EKOMI_RNR_SHOP_PASSWORD'][$lang['id_lang']] = Tools::getValue(
                'EKOMI_RNR_SHOP_PASSWORD_'.$lang['id_lang'],
                Configuration::get('EKOMI_RNR_SHOP_PASSWORD', $lang['id_lang'])
            );
            $configurations['EKOMI_RNR_PRC_WIDGET_TOKEN'][$lang['id_lang']] = Tools::getValue(
                'EKOMI_RNR_PRC_WIDGET_TOKEN_'.$lang['id_lang'],
                Configuration::get('EKOMI_RNR_PRC_WIDGET_TOKEN', $lang['id_lang'])
            );
        }

        return $configurations;
    }
    /**
     * Gets the post values.
     *
     * @return array
     */
    public function getPostValues()
    {
        $configurations = $this->getConfigurationBylanguage();

        $configurations['EKOMI_RNR_ENABLE'] = Tools::getValue('EKOMI_RNR_ENABLE');
        $configurations['EKOMI_RNR_PRODUCT_IDENTIFIER'] = Tools::getValue('EKOMI_RNR_PRODUCT_IDENTIFIER');
        $configurations['EKOMI_RNR_EXCLUDE_PRODUCTS'] = Tools::getValue('EKOMI_RNR_EXCLUDE_PRODUCTS');
        $configurations['EKOMI_RNR_SMART_CHECK'] = Tools::getValue('EKOMI_RNR_SMART_CHECK');
        $configurations['EKOMI_RNR_PRODUCT_REVIEWS'] = Tools::getValue('EKOMI_RNR_PRODUCT_REVIEWS');
        $configurations['EKOMI_RNR_MODE'] = Tools::getValue('EKOMI_RNR_MODE');
        $configurations['EKOMI_RNR_ORDER_STATUS'] = Tools::getValue('EKOMI_RNR_ORDER_STATUS');
        $configurations['EKOMI_RNR_SHOW_PRC_WIDGET'] = Tools::getValue('EKOMI_RNR_SHOW_PRC_WIDGET');

        return $configurations;
    }

    /**
     * Gets the configuration values.
     *
     * @return array
     */
    public static function getConfigValues()
    {
        $configurations = self::getConfigurationBylanguage();

        $states = Configuration::get('EKOMI_RNR_ORDER_STATUS');
        $configurations['EKOMI_RNR_ORDER_STATUS[]'] = explode(',', $states);
        $configurations['EKOMI_RNR_ENABLE'] = Configuration::get('EKOMI_RNR_ENABLE');
        $configurations['EKOMI_RNR_PRODUCT_REVIEWS'] = Configuration::get('EKOMI_RNR_PRODUCT_REVIEWS');
        $configurations['EKOMI_RNR_MODE'] = Configuration::get('EKOMI_RNR_MODE');
        $configurations['EKOMI_RNR_PRODUCT_IDENTIFIER'] = Configuration::get('EKOMI_RNR_PRODUCT_IDENTIFIER');
        $configurations['EKOMI_RNR_EXCLUDE_PRODUCTS'] = Configuration::get('EKOMI_RNR_EXCLUDE_PRODUCTS');
        $configurations['EKOMI_RNR_SMART_CHECK'] = Configuration::get('EKOMI_RNR_SMART_CHECK');
        $configurations['EKOMI_RNR_SHOW_PRC_WIDGET'] = Configuration::get('EKOMI_RNR_SHOW_PRC_WIDGET');

        return $configurations;
    }

    /**
     * Gets  Shop id by Language.
     *
     * @param string $languageId Language iso code
     * @return string
     */
    public function getShopIdByLang($languageId)
    {
        return Configuration::get('EKOMI_RNR_SHOP_ID', $languageId);
    }

    /**
     * Gets PRC token by language.
     *
     * @param string $languageId Language iso code
     * @return string
     */
    public function getPrcTokenByLang($languageId)
    {
        return Configuration::get('EKOMI_RNR_PRC_WIDGET_TOKEN', $languageId);
    }

    /**
     * Updates the configuration values.
     *
     * @param array $configValues Plugin configurations
     * @return void
     */
    public function updateValues($configValues)
    {
        if (is_array($configValues['EKOMI_RNR_ORDER_STATUS'])) {
            $orderStatuses = implode(',', $configValues['EKOMI_RNR_ORDER_STATUS']);
        } else {
            $orderStatuses = $configValues['EKOMI_RNR_ORDER_STATUS'];
        }

        $shopId = str_replace(' ', '', $configValues['EKOMI_RNR_SHOP_ID']);
        $shopPassword = str_replace(' ', '', $configValues['EKOMI_RNR_SHOP_PASSWORD']);
        $prcWidgetToken = str_replace(' ', '', $configValues['EKOMI_RNR_PRC_WIDGET_TOKEN']);

        Configuration::updateValue('EKOMI_RNR_ORDER_STATUS', $orderStatuses);
        Configuration::updateValue('EKOMI_RNR_ENABLE', $configValues['EKOMI_RNR_ENABLE']);
        Configuration::updateValue('EKOMI_RNR_PRODUCT_IDENTIFIER', $configValues['EKOMI_RNR_PRODUCT_IDENTIFIER']);
        Configuration::updateValue('EKOMI_RNR_EXCLUDE_PRODUCTS', $configValues['EKOMI_RNR_EXCLUDE_PRODUCTS']);
        Configuration::updateValue('EKOMI_RNR_SHOP_ID', $shopId);
        Configuration::updateValue('EKOMI_RNR_SHOP_PASSWORD', $shopPassword);
        Configuration::updateValue('EKOMI_RNR_PRODUCT_REVIEWS', $configValues['EKOMI_RNR_PRODUCT_REVIEWS']);
        Configuration::updateValue('EKOMI_RNR_MODE', $configValues['EKOMI_RNR_MODE']);
        Configuration::updateValue('EKOMI_RNR_SMART_CHECK', $configValues['EKOMI_RNR_SMART_CHECK']);
        Configuration::updateValue('EKOMI_RNR_SHOW_PRC_WIDGET', $configValues['EKOMI_RNR_SHOW_PRC_WIDGET']);
        Configuration::updateValue('EKOMI_RNR_PRC_WIDGET_TOKEN', $prcWidgetToken);
    }

    /**
     * Checks if Plugin is enabled.
     *
     * @return string
     */
    public static function isActivated()
    {
        return Configuration::get('EKOMI_RNR_ENABLE');
    }

    /**
     * Updates the smart check in SRR.
     *
     * @param int    $shopId     The shop interface id
     * @param string $shopSecret The Shop interface password
     * @param bool   $smartCheck
     * @return void
     */
    public function updateSmartCheck($shopId, $shopSecret, $smartCheck)
    {
        $httpHeader = array('shop-id: ' . $shopId, 'interface-password: ' . $shopSecret);
        $postFields = json_encode(array('smartcheck_on' => $smartCheck));

        $this->doCurl(self::URL_SMART_CHECK_SETTINGS, 'PUT', $httpHeader, $postFields);
    }

    /**
     * Verifies eKomi shop id and password.
     *
     * @param int    $shopId       The shop interface id
     * @param string $shopPassword The Shop interface password
     * @return mixed
     */
    public function verifyAccount($shopId, $shopPassword)
    {
        if (!empty($shopId) && !empty($shopPassword)) {
            $apiUrl = self::URL_GET_SETTINGS;
            $apiUrl .= "?auth={$shopId}|{$shopPassword}";
            $apiUrl .= '&version=cust-1.0.0&type=request&charset=iso';

            $serverOutput = $this->doCurl($apiUrl, 'GET');

            $response = array('success' => true);
            if ($serverOutput == 'Access denied') {
                $response = array('success' => false, 'message' => $serverOutput);
            }

            return $response;
        } else {
            return array('success' => false, 'message' => "Shop ID and Password required");
        }
    }

    /**
     * Verifies eKomi shop id and password for each language of the store.
     *
     * @param array $configValues Plugin configuration values
     * @return array
     */
    public function verifyAccounts($configValues)
    {
        $languages = Language::getLanguages(false);
        $shopCount = 0;
        $response = array();
        foreach ($languages as $lang) {
            $shopId = $configValues['EKOMI_RNR_SHOP_ID'][$lang['id_lang']];
            $shopPassword = $configValues['EKOMI_RNR_SHOP_PASSWORD'][$lang['id_lang']];

            if (!empty($shopId) && !empty($shopPassword)) {
                $shopCount++;
                $result = $this->verifyAccount($shopId, $shopPassword);

                if (!$result['success']) {
                    $response = array(
                        'success' => false,
                        'message' => 'Access denied for ' . $lang['name'] . ' shop'
                    );

                    return $response;
                } else {
                    $this->updateSmartCheck($shopId, $shopPassword, $configValues['EKOMI_RNR_SMART_CHECK']);

                    $response = array(
                        'success' => true,
                        'message' => $this->l('Settings Updated!')
                    );
                }
            }
        }

        if ($shopCount == 0) {
            $response = array(
                'success' => false,
                'message' => $this->l('Shop ID and Password Required.')
            );
        }

        return $response;
    }

    /**
     * Gets boolean options.
     *
     * @return array
     */
    public function getBoolOptions()
    {
        $options = array(
            array(
                'id_enable' => 0,
                'name' => 'No'
            ),
            array(
                'id_enable' => 1,
                'name' => 'Yes'
            ),
        );

        return $options;
    }

    /**
     * Gets Collection method options.
     *
     * @return array
     */
    public function getModeOptions()
    {
        $modeOptions = array(
            array(
                'value' => 'email',
                'name' => 'Email'
            ),
            array(
                'value' => 'sms',
                'name' => 'SMS'
            ),
            array(
                'value' => 'fallback',
                'name' => 'SMS if mobile number, otherwise Email'
            ),
        );

        return $modeOptions;
    }

    /**
     * Gets the Product Identifier options.
     *
     * @return array
     */
    public function getProductIdentifierOptions()
    {
        $modeOptions = array(
            array(
                'value' => self::PRODUCT_IDENTIFIER_ID,
                'name' => 'ID'
            ),
            array(
                'value' => self::PRODUCT_IDENTIFIER_SKU,
                'name' => 'SKU'
            ),
        );

        return $modeOptions;
    }

    /**
     * Gets order statuses array.
     *
     * @return array
     */
    public function getStatusesArray()
    {
        $orderStatuses = array();
        $statuses = OrderState::getOrderStates((int) $this->context->language->id);
        foreach ($statuses as $status) {
            $orderStatuses[] = array('id' => $status['id_order_state'], 'name' => $status['name']);
        }

        return $orderStatuses;
    }

    /**
     * Gets input fields.
     *
     * @return array
     */
    public function getInputFields()
    {
        $options = $this->getBoolOptions();
        $modeOptions = $this->getModeOptions();
        $productIdentifierOptions = $this->getProductIdentifierOptions();
        $orderStatuses = $this->getStatusesArray();

        $this->smarty->assign(array(
            "templatePath" => $this->_path
        ));

        $inputFields = array(
            array(
                'type' => 'select',
                'label' => $this->l('Plug-in Enabled'),
                'name' => 'EKOMI_RNR_ENABLE',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_enable',
                    'name' => 'name'
                ),
                'required' => true,
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Interface ID'),
                'name' => 'EKOMI_RNR_SHOP_ID',
                'size' => 20,
                'required' => true,
                'lang' => true,
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Interface Password'),
                'name' => 'EKOMI_RNR_SHOP_PASSWORD',
                'size' => 20,
                'required' => true,
                'desc' => $this->display(__FILE__, 'views/templates/admin/interface-password-link.tpl'),
                'lang' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Product Reviews'),
                'name' => 'EKOMI_RNR_PRODUCT_REVIEWS',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_enable',
                    'name' => 'name'
                ),
                'desc' => $this->l('Does your eKomi subscription include product reviews?'),
                'required' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Collection Method'),
                'name' => 'EKOMI_RNR_MODE',
                'options' => array(
                    'query' => $modeOptions,
                    'id' => 'value',
                    'name' => 'name'
                ),
                'desc' => $this->l('How would you prefer us to send out review requests to your clients?'),
                'required' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Order Statuses'),
                'name' => 'EKOMI_RNR_ORDER_STATUS[]',
                'id' => 'EKOMI_RNR_ORDER_STATUS',
                'multiple' => true,
                'options' => array(
                    'query' => $orderStatuses,
                    'id' => 'id',
                    'name' => 'name'
                ),
                'desc' => $this->l('Which order statuses should trigger a review request?'),
                'required' => true,
            ),

            array(
                'type' => 'select',
                'label' => $this->l('Product Identifier'),
                'name' => 'EKOMI_RNR_PRODUCT_IDENTIFIER',
                'options' => array(
                    'query' => $productIdentifierOptions,
                    'id' => 'value',
                    'name' => 'name'
                ),
                'desc' => $this->l('How do you identify the product? '),
                'required' => true,
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Exclude Products'),
                'name' => 'EKOMI_RNR_EXCLUDE_PRODUCTS',
                'size' => 200,
                'desc' => $this->l('Enter Product IDs/SKUs(comma separated) which should not be send to eKomi'),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Smart Check'),
                'name' => 'EKOMI_RNR_SMART_CHECK',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_enable',
                    'name' => 'name'
                ),
                'desc' => $this->l('Enable this if you have Smart Check widget on order success page'),
                'required' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Show PRC Widget'),
                'name' => 'EKOMI_RNR_SHOW_PRC_WIDGET',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_enable',
                    'name' => 'name'
                ),
                'desc' => $this->l('Enable this if you want to show PRC widget'),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('PRC Widget Token'),
                'name' => 'EKOMI_RNR_PRC_WIDGET_TOKEN',
                'size' => 20,
                'desc' => $this->display(__FILE__, 'views/templates/admin/widget-token-link.tpl'),
                'lang' => true,
            ),
        );

        return $inputFields;
    }

    /**
     * Checks if product identifier is sku.
     *
     * @return bool
     */
    public function isIdentifierSku()
    {
        if (Configuration::get('EKOMI_RNR_PRODUCT_IDENTIFIER') == self::PRODUCT_IDENTIFIER_SKU) {
            return true;
        }

        return false;
    }

    /**
     * Gets required fields to push order data on plugins-dashboard.
     *
     * @param array  $configValues
     * @param object $order
     * @return array
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getRequiredFields($configValues, $order)
    {
        $customer = new Customer((int) $order->id_customer);
        $address = new Address((int) $order->id_address_delivery);
        $country = new Country((int) $address->id_country);
        $products = array();
        foreach ($order->getProducts() as $key => $product) {
            $product['canonical_url'] = $this->context->link->getProductLink(
                $product['product_id'],
                null,
                null,
                null,
                null,
                null,
                $product['product_attribute_id'],
                Configuration::get('PS_REWRITING_SETTINGS'),
                false,
                true
            );

            $temp = new Product($product['product_id'], false, Context::getContext()->language->id);
            $product['image_url'] = $this->context->link->getImageLink(
                $temp->link_rewrite,
                $product['image']->id_image,
                ImageType::getFormatedName('home')
            );
            $products[$key] = $product;
        }
        $orderData = array(
            'customer' => get_object_vars($customer),
            'address' => get_object_vars($address),
            'order' => get_object_vars($order),
            'shop_name' => (string) Configuration::get('PS_SHOP_NAME'),
            'shop_email' => (string) Configuration::get('PS_SHOP_EMAIL'),
            'products' => $products,
            'country' => get_object_vars($country)
        );
        $configurations = array(
            'shop_id' => $configValues['EKOMI_RNR_SHOP_ID'][$order->id_lang],
            'interface_password' => $configValues['EKOMI_RNR_SHOP_PASSWORD'][$order->id_lang],
            'order_data' => $orderData,
            'mode' => $configValues['EKOMI_RNR_MODE'],
            'product_reviews' => $configValues['EKOMI_RNR_PRODUCT_REVIEWS'],
            'product_identifier' => $configValues['EKOMI_RNR_PRODUCT_IDENTIFIER'],
            'exclude_products' => $configValues['EKOMI_RNR_EXCLUDE_PRODUCTS'],
            'plugin_name' => 'prestashop'
        );

        return $configurations;
    }

    /**
     * Makes a curl request.
     *
     * @param string $requestUrl  Api End point url
     * @param string $requestType Api Request type
     * @param array  $httpHeader  Header
     * @param string $postFields  The post data to send
     * @return mixed|string
     */
    public function doCurl($requestUrl, $requestType, $httpHeader = array(), $postFields = '')
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $requestUrl);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
            if (!empty($httpHeader)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
            }

            if (!empty($postFields)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        } catch (Exception $exception) {
            PrestaShopLogger::addLog($exception->getMessage(), 1);
            return $exception->getMessage();
        }
    }

    /**
     * Sends order data to eKomi plugins-dashboard.
     *
     * @param array $orderData
     * @return mixed
     */
    public function sendOrderData($orderData)
    {
        if (!empty($orderData)) {
            $boundary = md5(time());
            $httpHeader = array('ContentType:multipart/form-data;boundary=' . $boundary);
            $this->doCurl(self::URL_TO_SEND_DATA, 'PUT', $httpHeader, json_encode($orderData));
        }
    }

    /**
     * Hooks the action on short code call.
     *
     * @param array $param
     * @return string
     */
    public function hookEkomiSmartPrc($param)
    {
        if ($this->isActivated() && Configuration::get('EKOMI_RNR_SHOW_PRC_WIDGET')) {
            $languageId = $this->context->language->id;
            $shopId = $this->getShopIdByLang($languageId);
            $currentProductId = $this->getProductIdentifier($param);
            if ($this->isIdentifierSku()) {
                $currentProductId = $this->getProductReference($param);
            }

            $widgetToken = $this->getPrcTokenByLang($languageId);
            if (!empty($currentProductId) && !empty($widgetToken)) {
                $this->smarty->assign(array(
                    "productIdentifier" => $currentProductId,
                    "customerId" => $shopId,
                    "widgetToken" => $widgetToken
                ));

                return $this->display(__FILE__, 'views/templates/front/smart-prc.tpl');
            }
        }

        return '';
    }

    /**
     * Installs the configurations.
     *
     * @return bool
     */
    protected function installConfigurations()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $this->installConfiguration((int)$lang['id_lang']);
        }

        Configuration::updateValue('EKOMI_RNR_ENABLE', '0');
        Configuration::updateValue('EKOMI_RNR_PRODUCT_IDENTIFIER', 'id');
        Configuration::updateValue('EKOMI_RNR_EXCLUDE_PRODUCTS', '');
        Configuration::updateValue('EKOMI_RNR_SMART_CHECK', '0');
        Configuration::updateValue('EKOMI_RNR_PRODUCT_REVIEWS', '0');
        Configuration::updateValue('EKOMI_RNR_MODE', '0');
        Configuration::updateValue('EKOMI_RNR_ORDER_STATUS', '');
        Configuration::updateValue('EKOMI_RNR_SHOW_PRC_WIDGET', '0');

        return true;
    }

    /**
     * Installs the configurations by language.
     *
     * @param string $languageId
     */
    protected function installConfiguration($languageId)
    {
        $values = array();

        $values['EKOMI_RNR_SHOP_ID'][(int)$languageId] = '';
        $values['EKOMI_RNR_SHOP_PASSWORD'][(int)$languageId] = '';
        $values['EKOMI_RNR_PRC_WIDGET_TOKEN'][(int)$languageId] = '';

        Configuration::updateValue('EKOMI_RNR_SHOP_ID', $values['EKOMI_RNR_SHOP_ID']);
        Configuration::updateValue('EKOMI_RNR_SHOP_PASSWORD', $values['EKOMI_RNR_SHOP_PASSWORD']);
        Configuration::updateValue('EKOMI_RNR_PRC_WIDGET_TOKEN', $values['EKOMI_RNR_PRC_WIDGET_TOKEN']);
    }

    /**
     * Gets the product Id.
     *
     * @param array $param
     * @return mixed
     */
    private function getProductIdentifier($param)
    {
        if ($this->prestashopVersion === 1.5) {
            return $param['product']->id;
        } elseif ($this->prestashopVersion === 1.6) {
            if (is_array($param['product'])) {
                return $param['product']['id_product'];
            } else {
                return $param['product']->id;
            }
        } elseif ($this->prestashopVersion === 1.7) {
            return $param['product']['id'];
        }

        return null;
    }

    /**
     * Gets the product sku/reference.
     *
     * @param array $param
     * @return mixed
     */
    private function getProductReference($param)
    {
        if ($this->prestashopVersion === 1.5) {
            return $param['product']->reference;
        } elseif ($this->prestashopVersion === 1.6) {
            if (is_array($param['product'])) {
                return $param['product']['reference'];
            } else {
                return $param['product']->reference;
            }
        } elseif ($this->prestashopVersion === 1.7) {
            return $param['product']['reference'];
        }

        return null;
    }
}
