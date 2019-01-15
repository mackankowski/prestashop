<?php
/**
 * 2012-2018 PrestaShop
 * NOTICE OF LICENSE
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * @author    NetReviews SAS <contact@avis-verifies.com>
 * @copyright 2012-2018 NetReviews SAS
 * @license   NetReviews
 * @version   Release: $Revision: 7.6.5
 * @date      20/09/2018
 * International Registered Trademark & Property of NetReviews SAS
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'netreviews/NetReviewsModel.php';

class NetReviews extends Module
{
    public $_html = null;
    public $iso_lang = null;
    public $id_lang = null;
    public $group_name = null;
    public $stats_product;

    public function __construct()
    {
        $this->name = 'netreviews';
        $this->tab = 'advertising_marketing';
        $this->version = '7.6.5';
        $this->author = 'NetReviews';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Verified Reviews');
        $this->description = $this->l('Collect service and product reviews with Verified Reviews. Display reviews on your shop and win the trust of your visitors, to increase your revenue.');
        $this->secure_key = Tools::encrypt($this->name);
        $this->module_key = 'd63d28acbac0a249ec17b6394ac5a841';
        if (self::isInstalled($this->name)) {
            $this->id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
        }
        $this->confirmUninstall = sprintf($this->l('Are you sure you want to uninstall %s module?'), $this->displayName);
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');
        // Retrocompatibility
        // if (version_compare(_PS_VERSION_, '1.5', '<') && version_compare(_PS_VERSION_, '1.4', '>=')) {
        //      $this->initContext();
        // }
    }

    public function install($keep = true)
    {
        if ($keep) {
            if (!($query = include dirname(__FILE__).'/sql/install.php')) {
                $this->context->controller->errors[] = sprintf($this->l('SQL ERROR : %s | Query can\'t be executed. Maybe, check SQL user permissions.'), $query);
            }

            Configuration::updateValue('AV_IDWEBSITE', '');
            Configuration::updateValue('AV_CLESECRETE', '');
            Configuration::updateValue('AV_LIGHTWIDGET', '1'); // simple stars by default
            Configuration::updateValue('AV_MULTILINGUE', '0');
            Configuration::updateValue('AV_MULTISITE', '');
            Configuration::updateValue('AV_PROCESSINIT', '');
            Configuration::updateValue('AV_ORDERSTATESCHOOSEN', '');
            Configuration::updateValue('AV_DELAY', '');
            Configuration::updateValue('AV_DELAY_PRODUIT', '0');
            Configuration::updateValue('AV_GETPRODREVIEWS', '');
            Configuration::updateValue('AV_DISPLAYPRODREVIEWS', '');
            Configuration::updateValue('AV_CSVFILENAME', 'Export_NetReviews_01-01-1970-default.csv');
            Configuration::updateValue('AV_SCRIPTFLOAT', '');
            Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED', '');
            Configuration::updateValue('AV_SCRIPTFIXE', '');
            Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED', '');
            Configuration::updateValue('AV_URLCERTIFICAT', '');
            Configuration::updateValue('AV_FORBIDDEN_EMAIL', '');
            Configuration::updateValue('AV_CODE_LANG', '');
            if (version_compare(_PS_VERSION_, '1.5', '>=')) {
                Configuration::updateValue('AV_DISPLAYSNIPPETPRODUIT', '4'); //product rich snippet position by defaut
            } else {
                Configuration::updateValue('AV_DISPLAYSNIPPETPRODUIT', '5'); //5 : Tabcontent - Product
            }
            Configuration::updateValue('AV_DISPLAYSNIPPETSITEGLOBAL', '3'); //global RS Review-aggregate
            Configuration::updateValue('AV_DISPLAYSNIPPETSITE', '1'); // RS enabled by defaut
            Configuration::updateValue('AV_NBOFREVIEWS', '10');
            Configuration::updateValue('AV_STARCOLOR', 'FFCD00');
            Configuration::updateValue('AV_PRODUCTUNIGINFO', '');
            Configuration::updateValue('AV_NBOPRODUCTS', '');
            Configuration::updateValue('AV_MINAMOUNTPRODUCTS', '');
            if (version_compare(_PS_VERSION_, '1.7', '>')) {
                Configuration::updateValue('AV_EXTRA_OPTION', '2'); //hookDisplayProductButtons
            } else {
                Configuration::updateValue('AV_EXTRA_OPTION', '0');  //hookExtraright
            }
            Configuration::updateValue('AV_ORDER_UPDATE', '');
            Configuration::updateValue('AV_DISPLAYSTARPLIST', '0');
            Configuration::updateValue('AV_TABSHOW', '1');
            Configuration::updateValue('AV_FORMAT_IMAGE', '');
            Configuration::updateValue('AV_TABNEWNAME', '');
            Configuration::updateValue('AV_STARSHOMESHOW', '1');
            Configuration::updateValue('AV_NRESPONSIVE', '0');
            Configuration::updateValue('AV_HELPFULHIDE', '');
            Configuration::updateValue('AV_MEDIAHIDE', '');
        }

        if (version_compare(_PS_VERSION_, '1.5', '>=')) {
            if (parent::install() == false ||
                !$this->registerHook('productTab') ||
                !$this->registerHook('productTabContent') ||
                !$this->registerHook('header') ||
                !$this->registerHook('footer') ||
                !$this->registerHook('displayProductButtons') ||
                !$this->registerHook('displayProductPriceBlock') ||
                !$this->registerHook('displayRightColumnProduct') ||
                !$this->registerHook('displayLeftColumnProduct') ||
                !$this->registerHook('displayProductExtraContent') ||
                !$this->registerHook('displayBeforeBodyClosingTag') ||
                !$this->registerHook('displayProductListReviews') ||
                !$this->registerHook('displayFooterProduct') ||
                !$this->registerHook('displayRightColumn') ||
                !$this->registerHook('displayLeftColumn') ||
                !$this->registerHook('ExtraNetreviews') ||
                !$this->registerHook('TabcontentNetreviews') ||
                !$this->registerHook('GlobalnoteNetreviews') ||
                !$this->registerHook('CategorystarsNetreviews') ||
                !$this->registerHook('CategorysummaryNetreviews') ||
                !$this->registerHook('actionOrderStatusPostUpdate') ||
                !$this->registerHook('actionValidateOrder'))
                return false;
            return true;
        } else {
            if (parent::install() == false ||
                !$this->registerHook('productTab') ||
                !$this->registerHook('productTabContent') ||
                !$this->registerHook('header') ||
                !$this->registerHook('footer') ||
                !$this->registerHook('rightColumn') ||
                !$this->registerHook('leftColumn') ||
                !$this->registerHook('extraRight') ||
                !$this->registerHook('extraLeft') ||
                !$this->registerHook('OrderConfirmation') ||
                !$this->registerHook('updateOrderStatus'))
                return false;
            return true;
        }
    }

    public function uninstall($keep = true)
    {
        $sql = 'SELECT name FROM '._DB_PREFIX_."configuration where name like 'AV_%'";
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                Configuration::deleteByName($row['name']);
            }
        }

        if (!parent::uninstall() || ($keep && !$this->deleteTables()) ||
            !$this->unregisterHook('productTab') ||
            !$this->unregisterHook('productTabContent') ||
            !$this->unregisterHook('header') ||
            !$this->unregisterHook('footer') ||
            !$this->unregisterHook('displayProductButtons') ||
            !$this->unregisterHook('displayProductPriceBlock') ||
            !$this->unregisterHook('displayRightColumnProduct') ||
            !$this->unregisterHook('displayLeftColumnProduct') ||
            !$this->unregisterHook('displayProductExtraContent') ||
            !$this->unregisterHook('displayBeforeBodyClosingTag') ||
            !$this->unregisterHook('displayProductListReviews') ||
            !$this->unregisterHook('displayFooterProduct') ||
            !$this->unregisterHook('displayRightColumn') ||
            !$this->unregisterHook('displayLeftColumn') ||
            !$this->unregisterHook('ExtraNetreviews') ||
            !$this->unregisterHook('TabcontentNetreviews') ||
            !$this->unregisterHook('GlobalnoteNetreviews') ||
            !$this->unregisterHook('CategorystarsNetreviews') ||
            !$this->unregisterHook('CategorysummaryNetreviews') ||
            !$this->unregisterHook('actionOrderStatusPostUpdate') ||
            !$this->unregisterHook('actionValidateOrder'))
            return false;
        return true;
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }
        return true;
    }

    public function deleteTables()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'av_orders`,
            `'._DB_PREFIX_.'av_products_reviews`,
            `'._DB_PREFIX_.'av_products_average`');
    }
    /**
     * Load the configuration form
     */
    public function getContent()
    {
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $this->addFiles('avisverifies-style-back-old', 'css');
        }
        $this->addFiles('avisverifies-style-admin', 'css');

        if (!empty($_POST)) {
            $this->postProcess();
        }

        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 &&
            (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP)) {
            $this->_html .= $this->displayError($this->l('Multistore feature is enabled. Please choose above the store to configure.'));
            return $this->_html;
        }

        $o_av = new NetReviewsModel();
        $nb_reviews = $o_av->getTotalReviews();
        $nb_reviews_average = $o_av->getTotalReviewsAverage();
        $nb_orders = $o_av->getTotalOrders();
        $current_avisverifies_idwebsite = array();
        $current_avisverifies_clesecrete = array();
        $order_statut_list = OrderState::getOrderStates((int)Configuration::get('PS_LANG_DEFAULT'));
        $multisite = Configuration::get('AV_MULTISITE');
        if (!empty($multisite)) {
            $idshop = $this->context->shop->getContextShopID();
            // $idshop_conf = true;
        } else {
            $idshop = null;  // if multishop but only one shop enabled or non multishop
            // $idshop_conf = false;
        }
        $current_avisverifies_idwebsite['root'] = Configuration::get('AV_IDWEBSITE', null, null, $idshop);
        $current_avisverifies_clesecrete['root'] = Configuration::get('AV_CLESECRETE', null, null, $idshop);
        $languages = Language::getLanguages(true);
        foreach ($languages as $lang) {
            $current_avisverifies_idwebsite[$lang['iso_code']] = "";
            $current_avisverifies_clesecrete[$lang['iso_code']] = "";
            $language_group_name = $this->getIdConfigurationGroup($lang['iso_code']);
            if (!Configuration::get('AV_IDWEBSITE'.$language_group_name, null, null, $idshop)) {
                Configuration::updateValue('AV_IDWEBSITE'.$language_group_name, '', false, null, $idshop);
            } elseif ($language_group_name) {
                $current_avisverifies_idwebsite[$lang['iso_code']] = Configuration::get('AV_IDWEBSITE'.$language_group_name, null, null, $idshop);
            }
            if (!Configuration::get('AV_CLESECRETE'.$language_group_name, null, null, $idshop)) {
                Configuration::updateValue('AV_CLESECRETE'.$language_group_name, '', false, null, $idshop);
            } elseif ($language_group_name) {
                $current_avisverifies_clesecrete[$lang['iso_code']] =  Configuration::get('AV_CLESECRETE'.$language_group_name, null, null, $idshop);
            }
        }
        if (version_compare(_PS_VERSION_, '1.5', '<') && version_compare(_PS_VERSION_, '1.4', '>=')) {
            global $currentIndex;
            $url_back = $currentIndex.'&configure=netreviews&token='.Tools::getAdminTokenLite('AdminModules').'&tab_module=advertising_marketing&module_name=netreviews';
        } elseif (version_compare(_PS_VERSION_, '1.5', '>=')) {
            $url_back = (($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.
                          $this->tab.'&conf=4&module_name='.$this->name));
        } else {
            $url_back ="";
        }
        $use_image = false;
        $use_star_format_image = Configuration::get('AV_FORMAT_IMAGE', null, null, $idshop);
            if (version_compare(_PS_VERSION_, '1.4', '>=') && $use_star_format_image != '1') {
                $stars_file = 'avisverifies-stars-font.tpl';
            } else {
                $stars_file = 'avisverifies-stars-image.tpl';
                $use_image = true;
        }
        $demo_rate = 4.5;
        $average_rate_percent = array();
        $average_rate_percent['floor'] = floor($demo_rate) - 1;
        $average_rate_percent['decimals'] = ($demo_rate - floor($demo_rate))*20;
        $customized_star_color = (Configuration::get('AV_STARCOLOR', null, null, $idshop))?Configuration::get('AV_STARCOLOR', null, null, $idshop):"FFCD00"; //default #FFCD00
        $this->smartyAssign(array(
            'base_url' => __PS_BASE_URI__ ,
            'current_avisverifies_urlapi' => Configuration::get('AV_URLAPI', null, null, $idshop),
            'current_lightwidget_checked' => Configuration::get('AV_LIGHTWIDGET', null, null, $idshop),
            'current_multilingue_checked' => Configuration::get('AV_MULTILINGUE', null, null, $idshop),
            'current_starproductlist_checked' => Configuration::get('AV_DISPLAYSTARPLIST', null, null, $idshop),
            'current_snippets_produit_checked' => Configuration::get('AV_DISPLAYSNIPPETPRODUIT', null, null, $idshop),
            'current_snippets_website_global_checked' => Configuration::get('AV_DISPLAYSNIPPETSITEGLOBAL', null, null, $idshop),
            'current_snippets_site_checked' => Configuration::get('AV_DISPLAYSNIPPETSITE', null, null, $idshop),
            'avisverifies_nb_reviews' => Configuration::get('AV_NBOFREVIEWS', null, null, $idshop),
            'avisverifies_stars_custom_color' => Configuration::get('AV_STARCOLOR', null, null, $idshop),
            'productuniqueginfo_checked' => Configuration::get('AV_PRODUCTUNIGINFO', null, null, $idshop),
            'customized_star_color' => $customized_star_color,
            'avisverifies_nb_products' => Configuration::get('AV_NBOPRODUCTS', null, null, $idshop),
            'avisverifies_amount_min_products' => Configuration::get('AV_MINAMOUNTPRODUCTS', null, null, $idshop),
            'avisverifies_extra_option' => Configuration::get('AV_EXTRA_OPTION', null, null, $idshop),
            'avisverifies_orders_doublecheck' => Configuration::get('AV_ORDER_UPDATE', null, null, $idshop),
            'current_nresponsive_checked' => Configuration::get('AV_NRESPONSIVE', null, null, $idshop),
            'current_hidehelpful_checked' => Configuration::get('AV_HELPFULHIDE', null, null, $idshop),
            'current_hidemedia_checked' => Configuration::get('AV_MEDIAHIDE', null, null, $idshop),
            'avisverifies_rename_tag' => Configuration::get('AV_TABNEWNAME', null, null, $idshop),
            'tabshow_checked' => Configuration::get('AV_TABSHOW', null, null, $idshop),
            'stars_image' => Configuration::get('AV_FORMAT_IMAGE', null, null, $idshop),
            'starshome_checked' => Configuration::get('AV_STARSHOMESHOW', null, null, $idshop),
            'current_avisverifies_idwebsite' => $current_avisverifies_idwebsite,
            'current_avisverifies_clesecrete' => $current_avisverifies_clesecrete,
            'version' => $this->version,
            'version_ps' => _PS_VERSION_,
            'order_statut_list' => $order_statut_list,
            'languages' => $languages,
            'debug_nb_reviews' => $nb_reviews['nb_reviews'],
            'debug_nb_reviews_average' => $nb_reviews_average['nb_reviews_average'],
            'debug_nb_orders_flagged' => $nb_orders['flagged']['nb'],
            'debug_nb_orders_not_flagged' => $nb_orders['not_flagged']['nb'],
            'debug_nb_orders_all' => $nb_orders['all']['nb'],
            'av_path' => $this->_path,
            'shop_name' =>  Configuration::get('PS_SHOP_NAME'), //(!empty($this->context->shop->name))?$this->context->shop->name:"",
            'url_back' => $url_back,
            'stars_dir' => _PS_ROOT_DIR_.'/modules/netreviews/views/templates/hook/sub/'.$stars_file,
            'use_image' => $use_image,
            'average_rate_percent' => $average_rate_percent,
            'av_rate_percent_int' =>  ($demo_rate) ?  round($demo_rate * 20) : 100,
        ));

        $tpl = 'avisverifies-backoffice';
        $this->_html .= $this->displayTemplate($tpl);
        return $this->_html;
    }

    /**
     * Save configuration form.
     */
    private function postProcess()
    {
        $av_idshop =  null;
        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
            $av_idshop =  ($this->context->shop->getContextShopID())?$this->context->shop->getContextShopID():null;
            if (Configuration::get('AV_MULTISITE') != $av_idshop) {
                Configuration::updateValue('AV_MULTISITE', $av_idshop);
            }
        }
        if (Tools::isSubmit('submit_export')) {
            try {
                $o_av = new NetReviewsModel;
                $header_colums = 'id_order;reference;order_amount;email;firstname;lastname;date_order;payment_method;carrer;delay;id_product;category;description;ean13;upc;mpn;brand;product_url;image_product_url;order_state_id;order_state;iso_lang;id_shop'."\r\n";
                $return_export = $o_av->export($header_colums, $av_idshop);
                if (file_exists($return_export[2])) {
                    $this->_html .= $this->displayConfirmation(sprintf($this->l('%s orders have been exported.'), $return_export[1]).'<a href="../modules/netreviews/Export_NetReviews_'.$return_export[0].'"> '.$this->l('Click here to download the file').'</a>');
                } else {
                    $this->_html .= $this->displayError($this->l('Writing on the server is not allowed. Please assign write permissions to the folder netreviews').$return_export[2]);
                }
            } catch (Exception $e) {
                $this->_html .= $this->displayError($e->getMessage());
            }
        }

        if (Tools::isSubmit('submit_configuration')) {
            Configuration::updateValue('AV_MULTILINGUE', Tools::getValue('avisverifies_multilingue'), false, null, $av_idshop);
            Configuration::updateValue('AV_IDWEBSITE', Tools::getValue('avisverifies_idwebsite'), false, null, $av_idshop);
            Configuration::updateValue('AV_CLESECRETE', Tools::getValue('avisverifies_clesecrete'), false, null, $av_idshop);
            // Configuration::updateValue('AV_MULTISITE',$av_idshop);
            if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
                $sql = '
                    SELECT name FROM '._DB_PREFIX_."configuration
                    where (name like 'AV_GROUP_CONF_%'
                    OR name like 'AV_IDWEBSITE_%'
                    OR name like 'AV_CLESECRETE_%')
                    AND id_shop = '".$av_idshop."'
                    ";
                $idshop_conf = true;
                if ($results = Db::getInstance()->ExecuteS($sql)) {
                    foreach ($results as $row) {
                        Configuration::deleteFromContext($row['name']);
                    }
                } else {
                    $idshop_conf = false; // if multishop but only one shop enabled or non multishop
                    $sql_without_idshop = '
                            SELECT name FROM '._DB_PREFIX_."configuration
                            where (name like 'AV_GROUP_CONF_%'
                            OR name like 'AV_IDWEBSITE_%'
                            OR name like 'AV_CLESECRETE_%')";
                    if ($results = Db::getInstance()->ExecuteS($sql_without_idshop)) {
                        foreach ($results as $row) {
                            Configuration::deleteFromContext($row['name']);
                        }
                    }
                }
                Configuration::updateValue('AV_MULTISITE', $idshop_conf); // in case that it's not multishop while configurated as multishop
                $languages = Language::getLanguages(true);
                $this->setIdConfigurationGroup($languages, $idshop_conf);
            }
        }

        if (Tools::isSubmit('submit_advanced')) {
            Configuration::updateValue('AV_LIGHTWIDGET', Tools::getValue('avisverifies_lightwidget'), false, null, $av_idshop);
            Configuration::updateValue('AV_DISPLAYSNIPPETPRODUIT', Tools::getValue('netreviews_snippets_produit'), false, null, $av_idshop);
            Configuration::updateValue('AV_DISPLAYSNIPPETSITE', Tools::getValue('netreviews_snippets_site'), false, null, $av_idshop);
            Configuration::updateValue('AV_NBOFREVIEWS', Tools::getValue('avisverifies_nb_reviews'), false, null, $av_idshop);
            Configuration::updateValue('AV_STARCOLOR', Tools::getValue('avisverifies_stars_custom_color'), false, null, $av_idshop);
            Configuration::updateValue('AV_PRODUCTUNIGINFO', Tools::getValue('avisverifies_productuniqueginfo'), false, null, $av_idshop);
            Configuration::updateValue('AV_NBOPRODUCTS', Tools::getValue('avisverifies_nb_products'), false, null, $av_idshop);
            Configuration::updateValue('AV_MINAMOUNTPRODUCTS', Tools::getValue('avisverifies_amount_min_products'), false, null, $av_idshop);
            Configuration::updateValue('AV_TABNEWNAME', Tools::getValue('avisverifies_rename_tag'), false, null, $av_idshop);
            Configuration::updateValue('AV_EXTRA_OPTION', Tools::getValue('avisverifies_extra_option'), false, null, $av_idshop);
            Configuration::updateValue('AV_ORDER_UPDATE', Tools::getValue('avisverifies_orders_doublecheck'), false, null, $av_idshop);
            Configuration::updateValue('AV_DISPLAYSTARPLIST', Tools::getValue('avisverifies_star_productlist'), false, null, $av_idshop);
            Configuration::updateValue('AV_TABSHOW', Tools::getValue('avisverifies_tab_show'), false, null, $av_idshop);
            Configuration::updateValue('AV_FORMAT_IMAGE', Tools::getValue('avisverifies_stars_image'), false, null, $av_idshop);
            Configuration::updateValue('AV_STARSHOMESHOW', Tools::getValue('avisverifies_starshome_show'), false, null, $av_idshop);
            Configuration::updateValue('AV_HELPFULHIDE', Tools::getValue('avisverifies_hidehelpful'), false, null, $av_idshop);
            Configuration::updateValue('AV_MEDIAHIDE', Tools::getValue('avisverifies_hidemedia'), false, null, $av_idshop);
            Configuration::updateValue('AV_NRESPONSIVE', Tools::getValue('avisverifies_nresponsive'), false, null, $av_idshop);
            Configuration::updateValue('AV_DISPLAYSNIPPETSITEGLOBAL', Tools::getValue('netreviews_snippets_website_global'), false, null, $av_idshop);
        }


        if (Tools::isSubmit('submit_purge')) {
            $query_id_shop = "";
            if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
                $query_id_shop = ' AND oav.id_shop = '.(int)$av_idshop;
            }

            $query = '  SELECT oav.id_order, o.date_add as date_order,o.id_customer
                        FROM '._DB_PREFIX_.'av_orders oav
                        LEFT JOIN '._DB_PREFIX_.'orders o
                        ON oav.id_order = o.id_order
                        LEFT JOIN '._DB_PREFIX_.'order_history oh
                        ON oh.id_order = o.id_order
                        WHERE (oav.flag_get IS NULL OR oav.flag_get = 0)'
                .$query_id_shop;

            $orders_list = Db::getInstance()->ExecuteS($query);
            if (!empty($orders_list)) {
                foreach ($orders_list as $order) { /* Set orders as getted */
                    Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'av_orders
                                                SET horodate_get = "'.time().'", flag_get = 1
                                                WHERE id_order = '.(int)$order['id_order']);
                }
                $this->_html .= $this->displayConfirmation(sprintf($this->l('The orders has been purged for %s'), $this->context->shop->name));
            } else {
                $this->_html .= $this->displayError(sprintf($this->l('No orders to purged for %s'), $this->context->shop->name));
            }
        }
    }

    /**
     * Return the widget flottant code to the hook header in front office if configurated
     *
     * Case 1: Return widget flottant code if configurated
     * Case 2: Return '' if not configurated
     *
     * @return javascript string in hook header
     */
    public function hookDisplayHeader()
    {
        // $this->registerHook('top');
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        $this->addFiles('avisverifies-style', 'css');
        $this->addFiles('avisverifies-tpl', 'js');
    }

    /**
 * Integration stars on category page used 3 hooks,
 * hookCategorystarsNetreviews  (parent hook)
 * hookDisplayProductListReviews
 * hookDisplayProductPriceBlock @params[type]=before_price
 */
    public function hookCategorystarsNetreviews($params)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        $show_stars_home = Configuration::get('AV_STARSHOMESHOW', null, null, $av_idshop); // 1 or null in defaut
        if ('index' != $this->context->controller->php_self || $show_stars_home != 0) {
            $id_product = (int)$params['product']['id_product'];
            $avisverifies_display_stars = Configuration::get('AV_DISPLAYSTARPLIST', null, null, $av_idshop);
            if (!isset($id_product) || empty($id_product) || !isset($avisverifies_display_stars) || empty($avisverifies_display_stars)) {
                return ;
            }
            $o_av = new NetReviewsModel();
            $stats_product = (!isset($this->stats_product) || empty($this->stats_product)) ? $o_av->getStatsProduct($id_product, $this->group_name, $av_idshop) : $this->stats_product;
            if (!isset($stats_product['nb_reviews']) || $stats_product['nb_reviews'] == 0) {
                return ;
            }
            $average_rate_percent = array();
            $average_rate_percent['floor'] = floor($stats_product['rate']) - 1;
            $average_rate_percent['decimals'] = ($stats_product['rate'] - floor($stats_product['rate']))*20;
            $lang_id = (isset($this->context->language->id) && !empty($this->context->language->id))?(int)$this->context->language->id:1;
            $product = new Product((int)$id_product, false, $lang_id);
            $product_name = $product->name[$lang_id];
            $use_star_format_image = Configuration::get('AV_FORMAT_IMAGE', null, null, $av_idshop);
            $customized_star_color = (Configuration::get('AV_STARCOLOR', null, null, $av_idshop))?Configuration::get('AV_STARCOLOR', null, null, $av_idshop):"FFCD00"; //default #FFCD00
            $this->smartyAssign(array(
                'av_nb_reviews' => $stats_product['nb_reviews'],
                'av_rate' =>  $stats_product['rate'],
                'average_rate_percent' => $average_rate_percent,
                'av_rate_percent_int' =>  ($stats_product['rate']) ?  round($stats_product['rate'] * 20) : 100,
                'link_product' => (isset($params['product']['link'])?$params['product']['link']:''),
                'use_star_format_image' => $use_star_format_image,
                'average_rate' => round($stats_product['rate'], 1),
                'product_name' =>  !empty($product_name)? $product_name: 'product name',
                'product_description' => $product->description_short[$lang_id],
                'customized_star_color' => $customized_star_color
            ));
            $tpl = 'avisverifies-categorystars';
            return $this->displayTemplate($tpl);
        }
    }

    public function hookDisplayProductListReviews($params)
    {
        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return $this->hookCategorystarsNetreviews($params);
        }
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            if ($params['type'] == "before_price") {
                return $this->hookCategorystarsNetreviews($params);
            }
        }
    }

    /**
  * Rich snippet positions:
  * AV_DISPLAYSNIPPETPRODUIT
  * Position 1 : Footer - Product
  * Position 2 : Extraright - AggregateRating (Default)
  * Position 3 : Extraright - Product
  * Position 4 : Tabcontent - AggregateRating
  * Position 5 : Tabcontent - Product
  *
  * AV_DISPLAYSNIPPETSITEGLOBAL
  * 1 Microdata
  * 2 JSON-LD (product footer + site)
  * 3 Review-aggregate
*/
    public function hookDisplayFooterProduct($params)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        if (Configuration::get('AV_DISPLAYSNIPPETSITE', null, null, $av_idshop) == '1' && Configuration::get('AV_DISPLAYSNIPPETPRODUIT', null, null, $av_idshop) == '1') { //rich snippets actived
            $rs_choice = Configuration::get('AV_DISPLAYSNIPPETSITEGLOBAL', null, null, $av_idshop);
            $id_product = (int)Tools::getValue('id_product');
            $o_av = new NetReviewsModel();
            $stats_product = (!isset($this->stats_product) || empty($this->stats_product)) ?
                $o_av->getStatsProduct($id_product, $this->group_name, $av_idshop) :
            $this->stats_product;
            $lang_id = (isset($this->context->language->id) && !empty($this->context->language->id))?(int)$this->context->language->id:1;
            $product = new Product((int)$id_product);
            $product_description = strip_tags($product->description_short[$lang_id]);
            $url_page = NetReviewsModel::getUrlProduct($product->id, $lang_id);
            $url_image = NetReviewsModel::getUrlImageProduct($product->id, null, $lang_id);
            $sku = $product->reference;
            $mpn = $product->supplier_reference;
            $gtin_upc = (isset($product->upc) && !empty($product->upc))?$product->upc:'';
            $gtin_ean = $product->ean13;
            $product_price = $product->getPrice(true, null, 2);
            $product_name = $product->name[$lang_id];
            $brand_name ='';
            $manufacturer = new Manufacturer($product->id_manufacturer, (int)$this->id_lang);
            if (isset($manufacturer->name)) {
                $brand_name = $manufacturer->name;
            }
            $this->smartyAssign(array(
                'count_reviews' => !empty($stats_product)?$stats_product['nb_reviews']:false,
                'average_rate' => !empty($stats_product)?round($stats_product['rate'], 1):false,
                'product_id' =>  $id_product,
                'product_name' => !empty($product_name)? $product_name: 'product name',
                'product_description' => $product_description,
                'product_price' =>  !empty($product_price)? $product_price: 0,
                'product_quantity' => $product->quantity,
                'product_url' =>  !empty($url_page)? $url_page: false,
                'url_image' =>  !empty($url_image)? $url_image: false,
                'sku' =>  !empty($sku)? $sku: false,
                'mpn' =>  !empty($mpn)? $mpn: false,
                'gtin_upc' =>  !empty($gtin_upc)? $gtin_upc: false,
                'gtin_ean' =>  !empty($gtin_ean)? $gtin_ean: false,
                'brand_name' =>  !empty($brand_name)? $brand_name: false,
                'rs_choice'=>$rs_choice
            ));
            $tpl = 'avisverifies-product-snippets';
            return $this->displayTemplate($tpl);
        }
    }

    public function hookDisplayFooter()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $av_scriptfixe_allowed = Configuration::get('AV_SCRIPTFIXE_ALLOWED', null, null, $av_idshop);
        $avisverifies_scriptfloat_allowed = Configuration::get('AV_SCRIPTFLOAT_ALLOWED'.$this->group_name, null, null, $av_idshop);
        $av_scriptfixe_position = Configuration::get('AV_SCRIPTFIXE_POSITION', null, null, $av_idshop);
        $av_scriptfixe = Configuration::get('AV_SCRIPTFIXE', null, null, $av_idshop);
        $av_scriptflottant = Configuration::get('AV_SCRIPTFLOAT', null, null, $av_idshop);
        $widget_fix_av = "";
        $widget_flottant_code = "";
        if ((strpos(Tools::strtolower($av_scriptfixe), 'null') != true || Tools::strlen($av_scriptfixe) > 10) && $av_scriptfixe_allowed  === "yes" && $av_scriptfixe_position === 'footer') {
            $widget_fix_av = "\n\n<div id='wigetfix_avisverifies_footer'>".Tools::stripslashes(html_entity_decode($av_scriptfixe)).
                "</div>";
        }
        if ((strpos(Tools::strtolower($av_scriptflottant), 'null') != true || Tools::strlen($av_scriptflottant) > 10) && $avisverifies_scriptfloat_allowed === "yes") {
                $widget_flottant_code .= "\n".Tools::stripslashes(html_entity_decode($av_scriptflottant));
        }
        /* $id_product = (int)Tools::getValue('id_product');
          if (Configuration::get('AV_DISPLAYSNIPPETSITE', null, null, $av_idshop) == "1") { //if rich snippets are enabled
            // $lang_id = (isset($this->context->language->id) && !empty($this->context->language->id))?(int)$this->context->language->id:1;
           $rs_choice = Configuration::get('AV_DISPLAYSNIPPETSITEGLOBAL', null, null, $av_idshop);
            if (empty($id_product)) { //the other pages other than category page nor product page
                $rate_site = Configuration::get('AV_RATE_SITE', null, null, $av_idshop);
                $nb_site = Configuration::get('AV_AVIS_SITE', null, null, $av_idshop);
                $horodate = Configuration::get('AV_HORODATE_LASTGET', null, null, $av_idshop);
                $av_idwebsite = Configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
                $av_urlcertificat = Configuration::get('AV_URLCERTIFICAT'.$this->group_name, null, null, $av_idshop);
                $shop_name = Configuration::get('PS_SHOP_NAME');
                $url_shop = _PS_BASE_URL_;
                if (!isset($av_urlcertificat) || empty($av_urlcertificat)) {
                    return;
                }
                $ex_datas = array();
                if (empty($rate_site) or empty($nb_site) or empty($horodate) or (($horodate + 86400) < time())) {
                    $nb_site = null;
                    $rate_site = null;
                    $url = "https://cl.avis-verifies.com/".$this->iso_lang."/cache/".Tools::substr($av_idwebsite, 0, 1)."/".Tools::substr($av_idwebsite, 1, 1)."/".Tools::substr($av_idwebsite, 2, 1)."/".$av_idwebsite."/AWS/".$av_idwebsite."_infosite.txt";
                    $file_headers = @get_headers($url);
                    if (strpos($file_headers[0], "200")) {
                        $datas = NetReviewsModel::avFileGetContents($url);
                        $ex_datas = explode(";", $datas);

                        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
                            Configuration::updateValue('AV_HORODATE_LASTGET', time(), false, null, $av_idshop);
                            Configuration::updateValue('AV_AVIS_SITE', $ex_datas[0], false, null, $av_idshop);
                            Configuration::updateValue('AV_RATE_SITE', $ex_datas[1], false, null, $av_idshop);
                        } else {
                            Configuration::updateValue('AV_HORODATE_LASTGET', time());
                            Configuration::updateValue('AV_AVIS_SITE', $ex_datas[0]);
                            Configuration::updateValue('AV_RATE_SITE', $ex_datas[1]);
                        }
                        $nb_site = $ex_datas[0];
                        $rate_site = $ex_datas[1];
                    } else { //integration page dosen't exist
                        return  $widget_fix_av.$widget_flottant_code;
                    }
                } else {
                    $this->smartyAssign(array(
                        'av_site_rating_avis' => $nb_site,
                        'av_site_rating_rate' => round($rate_site, 1),
                        'shop_name' => $shop_name,
                        'url' => $url_shop,
                        'rs_choice'=>$rs_choice
                    ));
                    $tpl = 'avisverifies-global-snippets';
                    return $widget_fix_av.$widget_flottant_code.$this->displayTemplate($tpl);
                }
            } else { // product pages
                return  $widget_fix_av.$widget_flottant_code;
            }
        // } else { // RS enabled */
            return  $widget_fix_av.$widget_flottant_code;
        // }
    }

    /**
     * allow to display a overview of a category,
     * {hook h='CategorysummaryNetreviews'} to be added in category.tpl
     */
    public function hookCategorysummaryNetreviews($params)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        // $lang_id = (empty($this->id_lang))?1:$this->id_lang;
        $current_page_name = $this->context->controller->php_self;
        if (($current_page_name == 'manufacturer' || $current_page_name == 'category' )) {
            // find the list of the id in a manufacturer
            if ($current_page_name == 'manufacturer') {
                $id_manufacturer=(int)Tools::getValue('id_manufacturer');
                $manu = new Manufacturer($id_manufacturer, $this->context->language->id);
                $name_subject=$manu->name;
                $description_subject = $manu->description;

                $sql = 'SELECT id_product FROM '._DB_PREFIX_.'product where id_manufacturer="'.$id_manufacturer.'"';
            } elseif ($current_page_name == 'category') {
                $id_category=(int)Tools::getValue('id_category');
                $cat = new Category($id_category, $this->context->language->id);
                $name_subject=$cat->name;
                $description_subject = $cat->description;
                $sql = 'SELECT * FROM '._DB_PREFIX_.'category_product where id_category="'.$id_category.'"';
            }

            $results = Db::getInstance()->ExecuteS($sql);

            // predefine the stats of the reviews, contains the number and the total of the rates
            $stats_product = array('nb_reviews'=>0,'somme'=>0);
            $products_info = array();
            foreach ($results as $row) {
                $id_product=(int)$row['id_product'];
                // $products_info[$id_product]['name'] = $this->getProductName($id_product, $lang_id);
                $products_info[$id_product]['price'] = round(Product::getPriceStatic($id_product), 2);

                $o_av = new NetReviewsModel();

                $reviews = $o_av->getProductReviews($id_product, $this->group_name, $av_idshop, false, 0);

                foreach ($reviews as $review) {
                    // calculate the number of review and the total of the rates
                    $stats_product['nb_reviews']++;
                    $stats_product['somme'] = $stats_product['somme'] + $review['rate'];
                }
            }
            $average_rate_percent = array();
            $average_rate_percent['floor'] = floor($review['rate']) - 1;
            $average_rate_percent['decimals'] = ($review['rate'] - floor($review['rate']))*20;
            // calcul de la moyen
            if ($stats_product['nb_reviews'] > 0) {
                $stats_product['rate'] = $stats_product['somme'] / $stats_product['nb_reviews'];
                $this->smartyAssign(array(
                    'modules_dir' => _MODULE_DIR_,
                    'count_reviews' => $stats_product['nb_reviews'],
                    'average_rate' => round($stats_product['rate'], 1),
                    'average_rate_percent' => $average_rate_percent,
                    'nom_category'=>$name_subject,
                    'description_category'=> !empty($description_subject)? $description_subject: false,
                    'page_name'=>$current_page_name,
                    'logo_lang' =>  $this->iso_lang, //$this->context->language->iso_code,
                    'products_av'=> $products_info
                ));

                $tpl = 'avisverifies-category-summary';
                return $this->displayTemplate($tpl);
            }
        }
    }

    /**
     * allow to display a overview of the website,
     * {hook h='GlobalnoteNetreviews'} to be added in header.tpl
     */
    public function HookGlobalnoteNetreviews()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        $rate_site = Configuration::get('AV_RATE_SITE', null, null, $av_idshop);
        $nb_site = Configuration::get('AV_AVIS_SITE', null, null, $av_idshop);
        $horodate = Configuration::get('AV_HORODATE_LASTGET', null, null, $av_idshop);
        $av_idwebsite = Configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
        $shop_name = Configuration::get('PS_SHOP_NAME');
        $use_image = false;
        $ex_datas = array();
        if (empty($rate_site) or empty($nb_site) or empty($horodate) or (($horodate + 86400) < time())) {
            $nb_site = null;
            $rate_site = null;
            $url = "https://cl.avis-verifies.com/".$this->iso_lang."/cache/".Tools::substr($av_idwebsite, 0, 1)."/".Tools::substr($av_idwebsite, 1, 1)."/".Tools::substr($av_idwebsite, 2, 1)."/".$av_idwebsite."/AWS/".$av_idwebsite."_infosite.txt";
            $url ="https://cl.avis-verifies.com/fr/cache/5/b/2/5b242ef5-bb8a-73d4-21be-6fd5dc3b4b9b/AWS/5b242ef5-bb8a-73d4-21be-6fd5dc3b4b9b_infosite.txt";
            $file_headers = @get_headers($url);
            if (strpos($file_headers[0], "200")) {
                $datas = NetReviewsModel::avFileGetContents($url);
                $ex_datas = explode(";", $datas);

                if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
                    Configuration::updateValue('AV_HORODATE_LASTGET', time(), false, null, $av_idshop);
                    Configuration::updateValue('AV_AVIS_SITE', $ex_datas[0], false, null, $av_idshop);
                    Configuration::updateValue('AV_RATE_SITE', $ex_datas[1], false, null, $av_idshop);
                } else {
                    Configuration::updateValue('AV_HORODATE_LASTGET', time());
                    Configuration::updateValue('AV_AVIS_SITE', $ex_datas[0]);
                    Configuration::updateValue('AV_RATE_SITE', $ex_datas[1]);
                }
                $nb_site = $ex_datas[0];
                $rate_site = $ex_datas[1];
            }
        } else {
            $use_star_format_image = Configuration::get('AV_FORMAT_IMAGE', null, null, $av_idshop);
            if (version_compare(_PS_VERSION_, '1.4', '>=') && $use_star_format_image != '1') {
                $stars_file = 'avisverifies-stars-font.tpl';
            } else {
                $stars_file = 'avisverifies-stars-image.tpl';
                $use_image = true;
            }
            $average_rate_percent = array();
            $average_rate_percent['floor'] = floor($rate_site) - 1;
            $average_rate_percent['decimals'] = ($rate_site - floor($rate_site))*20;
            $this->smartyAssign(array(
                'av_site_rating_avis' => $nb_site,
                'stars_dir' => _PS_ROOT_DIR_.'/modules/netreviews/views/templates/hook/sub/'.$stars_file,
                'use_image' => $use_image,
                'av_site_rating_rate' => round($rate_site, 1),
                'shop_name' => $shop_name,
                'average_rate_percent' => $average_rate_percent,
                'modules_dir' => _MODULE_DIR_
            ));
            $tpl = 'header_av_site';
            return $this->displayTemplate($tpl);
        }
    }

    /**
     *
     * @param $params
     * @return string|void
     */

    public function hookActionValidateOrder($params)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        //$process_init = Configuration::get('AV_PROCESSINIT');
        $id_website = configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
        $secret_key = configuration::get('AV_CLESECRETE'.$this->group_name, null, null, $av_idshop);
        $code_lang = configuration::get('AV_CODE_LANG'.$this->group_name, null, null, $av_idshop);
        $code_lang = (!empty($code_lang)) ? $code_lang : 'undef';

        if (empty($id_website) || empty($secret_key)) {
            return;
        }
        $o_av = new NetReviewsModel();
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $id_order = Tools::getValue('id_order');
            $order = new Order((int)($id_order));
            $order_total = (100 * $order->total_paid);
        } else {
            $o_order = $params['order'];
            $id_order = $o_order->id;
            $order_total = ($o_order->total_paid) ? (100 * $o_order->total_paid) : 0;
            if (!empty($o_order->id_shop)) {
                $o_av->id_shop = $o_order->id_shop;
            }
        }
        if (isset($id_order) && !empty($id_order)) {
            $o_av->id_order = (int)$id_order;
            $o_av->iso_lang = pSQL(Language::getIsoById($this->id_lang)); //pSQL(Language::getIsoById($o_order->id_lang));
            $o_av->saveOrderToRequest();
            return "<img height='1' hspace='0'
                        src='//www.netreviews.eu/index.php?action=act_order&idWebsite=$id_website&langue=$code_lang&refCommande=$id_order&montant=$order_total' />";
        }
    }

    /**
     * This code is added for having possiblities of double check
     * if not all orders are registered
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        $id_website = configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
        $secret_key = configuration::get('AV_CLESECRETE'.$this->group_name, null, null, $av_idshop);
        $code_lang = configuration::get('AV_CODE_LANG'.$this->group_name, null, null, $av_idshop);
        $code_lang = (!empty($code_lang)) ? $code_lang : 'undef';
        if (empty($id_website) || empty($secret_key)) {
            return;
        }
        $double_check_orders = Configuration::get('AV_ORDER_UPDATE', null, null, $av_idshop);
        if (isset($double_check_orders) && ($double_check_orders == "1")) {
            $o_av = new NetReviewsModel();
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                $id_order = Tools::getValue('id_order');
                // $order = new Order((int)($id_order));
            } else {
                $id_order = (int)$params['id_order'];
                if (isset($params['cart']->id_shop) && !empty($params['cart']->id_shop)) {
                    $o_av->id_shop = $params['cart']->id_shop;
                }
            }
            if (isset($id_order) && !empty($id_order)) {
                $o_av->id_order = (int)$id_order;
                $o_av->iso_lang = pSQL(Language::getIsoById($this->id_lang)); //pSQL(Language::getIsoById($o_order->id_lang));
                $o_av->saveOrderToRequest();
                return "<img height='1' hspace='0'
                        src='//www.netreviews.eu/index.php?action=act_order&idWebsite=$id_website&langue=$code_lang&refCommande=$id_order' />";
            }
        }
    }

    public function hookDisplayRightColumn()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        $av_scriptfixe_allowed = Configuration::get('AV_SCRIPTFIXE_ALLOWED'.$this->group_name, null, null, $av_idshop);
        $av_scriptfixe_position = Configuration::get('AV_SCRIPTFIXE_POSITION'.$this->group_name, null, null, $av_idshop);
        $av_scriptfixe = Configuration::get('AV_SCRIPTFIXE'.$this->group_name, null, null, $av_idshop);
        if ($av_scriptfixe_allowed != 'yes' || $av_scriptfixe_position != 'right') {
            return;
        }
        if ((strpos(Tools::strtolower($av_scriptfixe), 'null') != true || Tools::strlen($av_scriptfixe) > 10) && $av_scriptfixe_allowed  === "yes") {
            return "\n\n<div id='wigetfix_avisverifies_rightcolumn'>".Tools::stripslashes(html_entity_decode($av_scriptfixe)).
                "</div>";
        }
    }

    public function hookProductTab()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        $display_prod_reviews = Configuration::get('AV_DISPLAYPRODREVIEWS'.$this->group_name, null, null, $av_idshop);
        $o_av = new NetReviewsModel();
        $reviews = $o_av->getStatsProduct((int)Tools::getValue('id_product'), $this->group_name, $av_idshop);
        $num_reviews = !empty($reviews['nb_reviews']) ? $reviews['nb_reviews'] : 0;
        $show_tag = Configuration::get('AV_TABSHOW', null, null, $av_idshop); // 1 or null in defaut
        if ($num_reviews < 1 || $display_prod_reviews != 'yes' || $show_tag === '0') {
            return ; //Si Aucun avis, on retourne vide
        }
        $this->smartyAssign(
            array(
            'count_reviews' => $reviews['nb_reviews']
            )
        );

        $tpl = "avisverifies-tab";

        return $this->displayTemplate($tpl);
    }


    /**
     * Modifications in $reviews need to be duplicated in ajax-load.php
     * Display reviews on the product page, used 3 hooks
     * hookTabcontentNetreviews (parent hook)
     *
     * hookDisplayProductExtraContent (version > 1.7)
     * hookProductTabContent (all versions)
     */

    public function hookTabcontentNetreviews($params)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang)); //$this->context->language->iso_code
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        if (!isset($params['product']->use_tabconent[0])) {
            $new_version = false;
        } else {
            $use_tab_content = $params['product']->use_tabconent[0];
            $new_version = (!$use_tab_content)? true: false;
        }
        $display_prod_reviews = configuration::get('AV_DISPLAYPRODREVIEWS'.$this->group_name, null, null, $av_idshop);
        $url_certificat = Configuration::get('AV_URLCERTIFICAT'.$this->group_name, null, null, $av_idshop);
        $avisverifies_nb_reviews = (int)Configuration::get('AV_NBOFREVIEWS', null, null, $av_idshop);
        $local_id_website = Configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
        $local_secure_key = Configuration::get('AV_CLESECRETE'.$this->group_name, null, null, $av_idshop);
        $shop_name = Configuration::get('PS_SHOP_NAME');
        $id_product = (int)Tools::getValue('id_product');
        $o_av = new NetReviewsModel();
        $stats_product = (!isset($this->stats_product) || empty($this->stats_product)) ?
            $o_av->getStatsProduct($id_product, $this->group_name, $av_idshop)
            : $this->stats_product;
        if (! empty($stats_product['nb_reviews']) && $display_prod_reviews == 'yes') {
            $reviews_list = array(); //Create array with all reviews data
            $my_review = array(); //Create array with each reviews data
            $reviews = $o_av->getProductReviews($id_product, $this->group_name, $av_idshop, $avisverifies_nb_reviews, 1, 'horodate_DESC', 0, false);
            $reviews_count = $stats_product['nb_reviews'];
            $reviews_max_pages = floor($reviews_count/$avisverifies_nb_reviews) + ($reviews_count%$avisverifies_nb_reviews>0 ?1 :0);

            $reviews_rate_portion = array();
            $reviews_rate_portion_keys = array(1,2,3,4,5);
            $reviews_rate_portion = array_fill_keys($reviews_rate_portion_keys, 0);
            $reviews_all = $o_av->getProductReviews($id_product, $this->group_name, $av_idshop, 0, 1, 'horodate_DESC', 0, false);

            foreach ($reviews_all as $review) {
                switch ($review['rate']) {
                    case '1':
                        $reviews_rate_portion[1] += 1;
                        break;
                    case '2':
                        $reviews_rate_portion[2] += 1;
                        break;
                    case '3':
                        $reviews_rate_portion[3] += 1;
                        break;
                    case '4':
                        $reviews_rate_portion[4] += 1;
                        break;
                    case '5':
                        $reviews_rate_portion[5] += 1;
                        break;
                }
            }
            foreach ($reviews as $review) {
                //Create variable for template engine
                $my_review['ref_produit'] = $review['ref_product'];
                $my_review['id_product_av'] = $review['id_product_av'];
                $my_review['sign'] = sha1($local_id_website.$review['id_product_av'].$local_secure_key);
                if (!isset($review['helpful']) && !isset($review['helpless'])) {
                    Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'av_products_reviews`
                   ADD `helpful` int(7) DEFAULT 0,
                   ADD `helpless` int(7) DEFAULT 0');
                } else {
                    $my_review['helpful'] = $review['helpful'];
                    $my_review['helpless'] = $review['helpless'];
                }
                $my_review['rate'] = $review['rate'];
                $my_review['rate_percent'] = $review['rate']*20;
                $my_review['avis'] = html_entity_decode(urldecode($review['review']));
                // review date
                if (Tools::strlen($review['horodate'])=='10') {
                    $date = new DateTime();
                    $date->setTimestamp($review['horodate']);
                    $my_review['horodate'] = $date->format('d/m/Y') ;
                } else {
                    $my_review['horodate'] = date('d/m/Y', strtotime($review['horodate']));
                }
                // order date
                if (isset($review['horodate_order']) && !empty($review['horodate_order'])) {
                    $review['horodate_order'] = str_replace('"', '', $review['horodate_order']);
                    $my_review['horodate_order'] = date('d/m/Y', strtotime($review['horodate_order']));
                } else {
                    $my_review['horodate_order'] = $my_review['horodate'];
                }
                // in case imported reviews which have lack of this info
                if (!isset($review['horodate']) || empty($review['horodate'])) {
                    $my_review['horodate'] = $my_review['horodate_order'];
                }

                $my_review['customer_name'] = urldecode($review['customer_name']);

                $my_review['discussion'] = array();

                //renverser le nom et le prénom
                $customer_name = explode(' ', urldecode($review['customer_name']));
                $customer_name = array_values(array_filter($customer_name));
                $customer_name = array_diff($customer_name, array("."));
                $customer_name = array_reverse($customer_name);
                $customer_name = implode(' ', $customer_name);

                $my_review['customer_name'] =  $customer_name;

                $unserialized_discussion = NetReviewsModel::avJsonDecode(NetReviewsModel::acDecodeBase64($review['discussion']), true);
                $unserialized_discussion = (array)$unserialized_discussion;
                if ($unserialized_discussion) {
                    foreach ($unserialized_discussion as $k_discussion => $each_discussion) {
                        $each_discussion = (array)$each_discussion;
                        $my_review['discussion'][$k_discussion] = array();
                        if (Tools::strlen($each_discussion['horodate'])=='10') {
                            $date = new DateTime();
                            $date->setTimestamp($each_discussion['horodate']);
                            $my_review['discussion'][$k_discussion]['horodate'] = $date->format('d/m/Y') ;
                        } else {
                            $my_review['discussion'][$k_discussion]['horodate'] = date('d/m/Y', strtotime($each_discussion['horodate']));
                        }
                        $my_review['discussion'][$k_discussion]['commentaire'] = $each_discussion['commentaire'];
                        if ($each_discussion['origine'] == 'ecommercant') {
                            $my_review['discussion'][$k_discussion]['origine'] = $shop_name;
                        } elseif ($each_discussion['origine'] == 'internaute') {
                            $my_review['discussion'][$k_discussion]['origine'] = $my_review['customer_name'];
                        } else {
                            $my_review['discussion'][$k_discussion]['origine'] = $this->l('Moderator');
                        }
                    }
                }
                // Media infos
                $my_review['media_content'] = array();
                if (isset($review['media_full'])) {
                    $review_images_result =  (array) NetReviewsModel::avJsonDecode(html_entity_decode($review['media_full']), true);
                    if (isset($review_images_result) && !empty($review_images_result) && count($review_images_result) > 1) {
                        foreach ($review_images_result as $k_media => $each_media) {
                            $my_review['media_content'][$k_media] = (array) $each_media;
                        }
                    }
                }
                array_push($reviews_list, $my_review);
            }
            //rich snippets informations:
            $lang_id = (isset($this->context->language->id) && !empty($this->context->language->id))?(int)$this->context->language->id:1;
            $product = new Product((int)$id_product);
            $product_description = strip_tags($product->description_short[$lang_id]);
            $url_page = NetReviewsModel::getUrlProduct($product->id, $lang_id);
            $url_image = NetReviewsModel::getUrlImageProduct($product->id, null, $lang_id);
            $sku = $product->reference;
            $mpn = $product->supplier_reference;
            $gtin_upc = (isset($product->upc) && !empty($product->upc))?$product->upc:'';
            $gtin_ean = $product->ean13;
            $brand_name ='';
            $manufacturer = new Manufacturer($product->id_manufacturer, (int)$this->id_lang);
            if (isset($manufacturer->name)) {
                $brand_name = $manufacturer->name;
            }
            $av_sp_active = Configuration::get('AV_DISPLAYSNIPPETSITE', null, null, $av_idshop);
            $av_sp_p = Configuration::get('AV_DISPLAYSNIPPETPRODUIT', null, null, $av_idshop);
            if ($av_sp_active == '1' &&  ($av_sp_p== '4' || $av_sp_p== '5')) {
                $snippets_active = true;
            }
            if ($av_sp_p == '5') {
                $snippets_complete = 1;
            } elseif ($av_sp_p == '4') {
                $snippets_complete = 0;
            }

            $customized_star_color = (Configuration::get('AV_STARCOLOR', null, null, $av_idshop))?Configuration::get('AV_STARCOLOR', null, null, $av_idshop):"FFCD00"; //default #FFCD00
            $nrResponsive = Configuration::get('AV_NRESPONSIVE', null, null, $av_idshop) ? 1: 0; // 0 or null in defaut
            $hidehelpful = Configuration::get('AV_HELPFULHIDE', null, null, $av_idshop) ? 1: 0; // 0 or null in defaut
            $hidemedia = Configuration::get('AV_MEDIAHIDE', null, null, $av_idshop); // 0 or null in defaut
            $product_price = $product->getPrice(true, null, 2);
            $product_name = $product->name[$lang_id];
            $av_urlcertificat = Configuration::get('AV_URLCERTIFICAT'.$this->group_name, null, null, $av_idshop);
            $url_platform = explode('/', $av_urlcertificat);
            $platform = Tools::substr($url_platform[2], 4);
            $avHelpfulURL =  "https://www.".$platform."/index.php?action=act_api_product_reviews_helpful";
            $url_cgv =  "https://www.".$platform."/index.php?page=mod_conditions_utilisation";
            $average_rate_percent = array();
            $average_rate_percent['floor'] = floor($stats_product['rate']) - 1;
            $average_rate_percent['decimals'] = ($stats_product['rate'] - floor($stats_product['rate']))*20;
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                global $cookie;
                $logolang = pSQL(Language::getIsoById($cookie->id_lang));
            } else {
                $logolang = $this->context->language->iso_code;
            }
            $languagespack_av = array("de", "en", "es", "fr", "gb", "it", "pt");
            $logolang =(in_array($logolang, $languagespack_av))?$logolang:"en";

            $use_star_format_image = Configuration::get('AV_FORMAT_IMAGE', null, null, $av_idshop);
            $use_image = false;
            if (version_compare(_PS_VERSION_, '1.4', '>=') && $use_star_format_image != '1') {
                $stars_file = 'avisverifies-stars-font.tpl';
                $old_lang = false;
            } else {
                $stars_file = 'avisverifies-stars-image.tpl';
                $old_lang = true;
                $use_image = true;
            }
            $av_ajax_translation = array ();
            $av_ajax_translation['a'] = $this->l('published');
            $av_ajax_translation['b'] = $this->l('the');
            $av_ajax_translation['c'] = $this->l('following an order made on');
            $av_ajax_translation['d'] = $this->l('Comment from');
            $av_ajax_translation['e'] = $this->l('Show exchanges');
            $av_ajax_translation['f'] = $this->l('Hide exchanges');
            $av_ajax_translation['g'] = $this->l('Did you find this helpful?');
            $av_ajax_translation['h'] = $this->l('Yes');
            $av_ajax_translation['i'] = $this->l('No');
            $av_ajax_translation['j'] = $this->l('More reviews...');
            $ajax_dir = NetReviewsModel::tplFileExist('/ajax-load-tab-content.tpl');
            $stars_dir = NetReviewsModel::tplFileExist('sub/'.$stars_file);
            $this->smartyAssign(array(
                'modules_dir' => _MODULE_DIR_,
                'current_url' =>  $_SERVER['REQUEST_URI'],
                'av_idwebsite' => $local_id_website,
                'avHelpfulURL' => $avHelpfulURL,
                'url_cgv' => $url_cgv,
                'version_ps' => _PS_VERSION_,
                'ajax_dir' => $ajax_dir,
                'stars_dir' => $stars_dir,
                'use_image' => $use_image,
                'id_shop' => $av_idshop,
                'nom_group' => (!empty($this->group_name))? $this->group_name:null,
                'reviews' => $reviews_list,
                'count_reviews' => $reviews_count,
                'average_rate' => round($stats_product['rate'], 1),
                'av_rate_percent_int' =>  (float)$stats_product['rate'] * 20,
                'average_rate_percent' => $average_rate_percent,
                'is_https' => (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on' ? 1 : 0),
                'url_certificat' => $url_certificat,
                'reviews_max_pages' => ($reviews_max_pages)? (int)$reviews_max_pages : "",
                'product_id' =>  $id_product,
                'product_name' => !empty($product_name)? $product_name: 'product name',
                'product_description' => !empty($product_description)? $product_description: false,
                'product_url' =>  !empty($url_page)? $url_page: false,
                'url_image' =>  !empty($url_image)? $url_image: false,
                'product_price' => !empty($product_price)? $product_price: 0,
                'sku' =>  !empty($sku)? $sku: false,
                'mpn' =>  !empty($mpn)? $mpn: false,
                'gtin_upc' =>  !empty($gtin_upc)? $gtin_upc: false,
                'gtin_ean' =>  !empty($gtin_ean)? $gtin_ean: false,
                'brand_name' =>  !empty($brand_name)? $brand_name: false,
                'snippets_complete' =>  !empty($snippets_complete)? $snippets_complete: false,
                'snippets_active' =>  !empty($snippets_active)? $snippets_active: false,
                'reviews_rate_portion' =>  $reviews_rate_portion,
                'nrResponsive' =>  $nrResponsive,
                'hidehelpful' =>  $hidehelpful,
                'hidemedia' =>  $hidemedia,
                'current_page' =>  1,
                'av_ajax_translation' => $av_ajax_translation,
                'old_lang' => $old_lang, //old version language variable translations
                'logo_lang' => $logolang,
                'customized_star_color' => $customized_star_color
            ));
        }
       $tpl = 'avisverifies-tab-content';
        $attribute_av = array('id' => 'netreviews_tab', 'class' => 'netreviews_tab');
        //if version >= 1.7
        $array = array();
       if ($new_version) {
            if (! empty($stats_product['nb_reviews']) && $display_prod_reviews == 'yes') {
                $title_new = Configuration::get('AV_TABNEWNAME', null, null, $av_idshop); 
                $title_string= $this->l('verified reviews')."(".$stats_product['nb_reviews'].")";
                $title = (isset($title_new) && !empty($title_new))?$title_new:$title_string;
                $content=  $this->displayTemplate($tpl);
            } else {
                $title = '';
                $content = '';
            }
            $extraContent = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent()); // need to be disabled if have T_string, class missing errors
            $extraContent->setAttr($attribute_av);
            $extraContent->setTitle($title);
            $extraContent->setContent($content);
            $array[] =$extraContent;
            return $array;
        } else {
            if (! empty($stats_product['nb_reviews']) && $display_prod_reviews == 'yes') {
                return $this->displayTemplate($tpl);
            }
        }
    }

    public function hookDisplayProductExtraContent($params)
    {
        $params['product']->use_tabconent[] = false;
        return $this->hookTabcontentNetreviews($params);
    }

    public function hookProductTabContent($params)
    {
        $params['product']->use_tabconent[] = true;
        return $this->hookTabcontentNetreviews($params);
    }

    /**
     * Integration of widget product
     * hookExtraRight
     * hookExtraLeft
     * hookDisplayProductButtons
     * hookExtraNetreviews
     *
     * AV_LIGHTWIDGET :
     *  1 : simple stars
     *  2 : widget by defaut
     *  3 : widget badge
     */
    public function hookExtraNetreviews()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        } else {
            $this->group_name = "";
        }
        $snippets_complete = 0 ;
        $display_prod_reviews = configuration::get('AV_DISPLAYPRODREVIEWS'.$this->group_name, null, null, $av_idshop);
        $id_product = (int)Tools::getValue('id_product');
        $o = new NetReviewsModel();
        $reviews = $o->getStatsProduct($id_product, $this->group_name, $av_idshop);
        $num_reviews = !empty($reviews['nb_reviews']) ? $reviews['nb_reviews'] : 0;
        if ($num_reviews < 1 || $display_prod_reviews != 'yes') {
            return ;
        }
        //rich snippets informations:
        $lang_id = (isset($this->context->language->id) && !empty($this->context->language->id))?(int)$this->context->language->id:1;
        $average_rate_percent = array();
        $average_rate_percent['floor'] = floor($reviews['rate']) - 1;
        $average_rate_percent['decimals'] = ($reviews['rate'] - floor($reviews['rate']))*20;
        $product = new Product((int)$id_product, false, $lang_id);
        $product_description = strip_tags($product->description_short[$lang_id]);
        $url_page = NetReviewsModel::getUrlProduct($product->id, $lang_id);
        $url_image = NetReviewsModel::getUrlImageProduct($product->id, null, $lang_id);
        $sku = $product->reference;
        $mpn = $product->supplier_reference;
        $gtin_upc = (isset($product->upc) && !empty($product->upc))?$product->upc:'';
        $gtin_ean = $product->ean13;
        $brand_name ='';
        $manufacturer = new Manufacturer($product->id_manufacturer, (int)$this->id_lang);
        if (isset($manufacturer->name)) {
            $brand_name = $manufacturer->name;
        }

        $widgetlight = Configuration::get('AV_LIGHTWIDGET', null, null, $av_idshop);
        $av_sp_active = Configuration::get('AV_DISPLAYSNIPPETSITE', null, null, $av_idshop);
        $av_sp_p = Configuration::get('AV_DISPLAYSNIPPETPRODUIT', null, null, $av_idshop);

        if ($av_sp_active == '1' &&  ($av_sp_p== '2' || $av_sp_p== '3')) {
            $snippets_active = true;
        }
        if ($av_sp_p == '3') {
            $snippets_complete = 1;
        } elseif ($av_sp_p == '2') {
            $snippets_complete = 0;
        }
        $product_price = $product->getPrice(true, null, 2);
        $product_name = $product->name[$lang_id];
        $use_star_format_image = Configuration::get('AV_FORMAT_IMAGE', null, null, $av_idshop);
        if (version_compare(_PS_VERSION_, '1.4', '>=') && $use_star_format_image != '1') {
            $stars_file = 'avisverifies-stars-font.tpl';
        } else {
            $stars_file = 'avisverifies-stars-image.tpl';
        }
        $customized_star_color = (Configuration::get('AV_STARCOLOR', null, null, $av_idshop))?Configuration::get('AV_STARCOLOR', null, null, $av_idshop):"FFCD00"; //default #FFCD00
        $this->smartyAssign(array(
            'modules_dir' => _MODULE_DIR_,
            'base_url' => __PS_BASE_URI__ ,
            'version_ps' => _PS_VERSION_,
            'stars_dir' => _PS_ROOT_DIR_.'/modules/netreviews/views/templates/hook/sub/'.$stars_file,
            'av_nb_reviews' => $reviews['nb_reviews'],
            'av_rate' =>   round($reviews['rate'], 1),
            'average_rate_percent' => $average_rate_percent,
            'av_rate_percent_int' =>  ($reviews['rate']) ?  round($reviews['rate'] * 20) : 100,
            'average_rate' => round($reviews['rate'], 1),
            'product_id' =>  $id_product,
            'product_name' => !empty($product_name)? $product_name: 'product name',
            'product_description' => !empty($product_description)? $product_description: false,
            'product_url' =>  !empty($url_page)? $url_page: false,
            'url_image' =>  !empty($url_image)? $url_image: false,
            'product_price' =>  !empty($product_price)? $product_price: 0,
            'sku' =>  !empty($sku)? $sku: false,
            'mpn' =>  !empty($mpn)? $mpn: false,
            'gtin_upc' =>  !empty($gtin_upc)? $gtin_upc: false,
            'gtin_ean' =>  !empty($gtin_ean)? $gtin_ean: false,
            'brand_name' =>  !empty($brand_name)? $brand_name: false,
            'widgetlight' =>  !empty($widgetlight)? $widgetlight: false,
            'snippets_complete' =>  !empty($snippets_complete)? $snippets_complete: false,
            'snippets_active' =>  !empty($snippets_active)? $snippets_active: false,
            'use_star_format_image' => $use_star_format_image,
            'customized_star_color' => $customized_star_color
        ));

        $tpl = 'avisverifies-extraright';
        return $this->displayTemplate($tpl);
    }

    public function hookExtraRight()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        $av_extra_option = Configuration::get('AV_EXTRA_OPTION', null, null, $av_idshop);
        if ($av_extra_option == '0') {
            return $this->hookExtraNetreviews();
        }
    }

    public function hookExtraLeft()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        $av_extra_option = Configuration::get('AV_EXTRA_OPTION', null, null, $av_idshop);
        if ($av_extra_option == '1') {
            return $this->hookExtraNetreviews();
        }
    }

    public function hookDisplayProductButtons()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        $av_extra_option = Configuration::get('AV_EXTRA_OPTION', null, null, $av_idshop);
        if ($av_extra_option == '2') {
            return $this->hookExtraNetreviews();
        }
    }

    /**
     * Integration of widget site on the left column
     *
     * @return string|void
     */
    public function hookDisplayLeftColumn()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $av_scriptfixe_allowed = Configuration::get('AV_SCRIPTFIXE_ALLOWED', null, null, $av_idshop);
        $av_scriptfixe_position = Configuration::get('AV_SCRIPTFIXE_POSITION', null, null, $av_idshop);
        $av_scriptfixe = Configuration::get('AV_SCRIPTFIXE', null, null, $av_idshop);

        if ($av_scriptfixe_allowed != 'yes' || $av_scriptfixe_position != 'left') {
            return;
        }
        if ((strpos(Tools::strtolower($av_scriptfixe), 'null') != true || Tools::strlen($av_scriptfixe) > 10) && $av_scriptfixe_allowed  === "yes") {
            return "\n\n<div id='wigetfix_avisverifies_leftcolumn'>".Tools::stripslashes(html_entity_decode($av_scriptfixe)).
                "</div>";
        }
    }


    private function displayTemplate($tpl)
    {
        // if (version_compare(_PS_VERSION_, '1.6', '<')) {
        return  ($this->display(__FILE__, "/views/templates/hook/$tpl.tpl"));
        // } else {
        //     return  ($this->display(__FILE__, "/views/templates/hook/$tpl.tpl"));
        // }
    }

    private function smartyAssign($smarty_array)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            global  $smarty;
            return  $smarty->assign($smarty_array);
        } elseif (version_compare(_PS_VERSION_, '1.5', '>=')) {
            return  $this->context->smarty->assign($smarty_array);
        }
    }

    // private function initContext()
    // {
    //   if (class_exists('Context')) {
    //     $this->context = Context::getContext();
    //   } else {
    //     global $smarty, $cookie;
    //     $this->context = new StdClass();
    //     $this->context->smarty = $smarty;
    //     $this->context->cookie = $cookie;
    //   }
    // }

    private function addFiles($file_name, $type)
    {
        if (version_compare(_PS_VERSION_, '1.4', '>=')) {
            if ($type == 'css') {
                if (version_compare(_PS_VERSION_, '1.5', '<')) {
                    return  Tools::addCSS(($this->_path).'views/css/'.$file_name.'.css', 'all');
                } else {
                    return  $this->context->controller->addCSS($this->_path.'views/css/'.$file_name.'.css', 'all');
                }
            } elseif ($type == 'js') {
                if (version_compare(_PS_VERSION_, '1.5', '<')) {
                        return  Tools::addJS(($this->_path).'views/js/'.$file_name.'.js', 'all');
                } else {
                        return  $this->context->controller->addJS($this->_path.'views/js/'.$file_name.'.js', 'all');
                }
            }
        }
    }

    public function hookHeader($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookDisplayHeader($params);
        }
    }

    public function hookFooter($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookDisplayFooter($params);
        }
    }

    public function hookRightColumn($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookDisplayRightColumn($params);
        }
    }

    public function hookLeftColumn($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookDisplayLeftColumn($params);
        }
    }

    public function hookOrderConfirmation($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookActionValidateOrder($params);
        }
    }

    public function hookupdateOrderStatus($params)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $this->hookActionOrderStatusPostUpdate($params);
        }
    }

    private function getIdConfigurationGroup($lang_iso = null)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 && !empty($multisite)) {
            $sql = 'SELECT name FROM '._DB_PREFIX_."configuration where name like 'AV_GROUP_CONF_%' And id_shop = '"
                .$this->context->shop->getContextShopID()."'";
        } else {
            $sql = 'SELECT name FROM '._DB_PREFIX_."configuration where name like 'AV_GROUP_CONF_%'";
        }
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 && !empty($multisite)) {
                    $vconf = unserialize(Configuration::get($row['name'], null, null, $this->context->shop->getContextShopID()));
                } else {
                    $vconf = unserialize(Configuration::get($row['name']));
                }
                if ($vconf && in_array($lang_iso, $vconf)) {
                    return '_'.Tools::substr($row['name'], 14);
                }
            }
        }
    }

    private function setIdConfigurationGroup($languages = null, $idshop_conf = true, $i = 0)
    {
        if (empty($languages)) {
            return;
        }
        reset($languages);
        $id_langue_curent = key($languages);
        $lang = $languages[$id_langue_curent];
        $id_website_current = Tools::getValue('avisverifies_idwebsite_'.$lang['iso_code']);
        $cle_secrete_current = Tools::getValue('avisverifies_clesecrete_'.$lang['iso_code']);

        if (empty($id_website_current) || empty($cle_secrete_current)) {
            unset($languages[$id_langue_curent]);
            return $this->setIdConfigurationGroup($languages, $idshop_conf, $i);
        } else {
            if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 && $idshop_conf) {
                $added_condition ="And id_shop = ".$this->context->shop->getContextShopID();
            } else {
                $added_condition ="";
            }
            $sql = 'SELECT name
                FROM '._DB_PREFIX_."configuration
                WHERE value = '".pSql($id_website_current)."'
                AND name like 'AV_IDWEBSITE_%' ".$added_condition;
            if ($row = Db::getInstance()->getRow($sql)) {
                if (Configuration::get('AV_CLESECRETE_'.Tools::substr($row['name'], 13), null, null, $this->context->shop->getContextShopID()) != $cle_secrete_current) {
                    $this->context->controller->errors[] = sprintf($this->l('PARAM ERROR: please check your multilingual configuration for the id_website "%s" at language "%s"'), $id_website_current, $lang['name']);
                    unset($languages[$id_langue_curent]);
                    return $this->setIdConfigurationGroup($languages, $idshop_conf, $i);
                }
            }

            $group = array();
            array_push($group, $lang['iso_code']);
            unset($languages[$id_langue_curent]);
            foreach ($languages as $id1 => $lang1) {
                if ($id_website_current == Tools::getValue('avisverifies_idwebsite_'.$lang1['iso_code'])
                    && $cle_secrete_current == Tools::getValue('avisverifies_clesecrete_'.$lang1['iso_code'])) {
                    array_push($group, $lang1['iso_code']);
                    unset($languages[$id1]);
                }
            }
            // Create PS configuration variable
            if ($idshop_conf) {
                $idshop = $this->context->shop->getContextShopID();
            } else {
                $idshop = null;
            }
            if (!Configuration::get('AV_IDWEBSITE_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_IDWEBSITE_'.$i, Tools::getValue('avisverifies_idwebsite_'.$lang['iso_code']), false, null, $idshop);
            }

            if (!Configuration::get('AV_CLESECRETE_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_CLESECRETE_'.$i, Tools::getValue('avisverifies_clesecrete_'.$lang['iso_code']), false, null, $idshop);
            }

            if (!Configuration::get('AV_GROUP_CONF_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_GROUP_CONF_'.$i, serialize($group), false, null, $idshop);
            }

            if (!Configuration::get('AV_PROCESSINIT_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_PROCESSINIT_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_ORDERSTATESCHOOSEN_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_ORDERSTATESCHOOSEN_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_DELAY_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_DELAY_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_DELAY_PRODUIT_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_DELAY_PRODUIT_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_GETPRODREVIEWS_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_GETPRODREVIEWS_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_DISPLAYPRODREVIEWS_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_DISPLAYPRODREVIEWS_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_SCRIPTFLOAT_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_SCRIPTFLOAT_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_SCRIPTFLOAT_ALLOWED_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_SCRIPTFIXE_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_SCRIPTFIXE_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_SCRIPTFIXE_ALLOWED_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_URLCERTIFICAT_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_URLCERTIFICAT_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_FORBIDDEN_EMAIL_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_FORBIDDEN_EMAIL_'.$i, '', false, null, $idshop);
            }

            if (!Configuration::get('AV_CODE_LANG_'.$i, null, null, $idshop)) {
                Configuration::updateValue('AV_CODE_LANG_'.$i, '', false, null, $idshop);
            }

            $i++;
            return $this->setIdConfigurationGroup($languages, $idshop_conf, $i);
        }
    }
}
