<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    Ve Interactive <info@veinteractive.com>
 * @copyright 2017 Ve Interactive
 * @license   MIT License
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class VePlatform extends Module
{
    const VIEW_FRONT_TAG_PATH = 'views/templates/front/VeTag.tpl';
    const VIEW_FRONT_PIXEL_PATH = 'views/templates/front/VePixel.tpl';
    const VIEW_ADMIN_SETTINGS_PATH = 'views/templates/admin/VeSettings.tpl';
    const VIEW_ADMIN_CSS_PATH = 'views/css/VePlatform.css';
    const VIEW_MODULE_JS_PATH = '/views/js/front/veData.js';
    const IMG_PATH = '../modules/veplatform/views/img/';
    const OLDER_ADMIN_VE_PLATFORM_TAB_CONTROLLER = 'VePlatformTab';
    const ADMIN_VE_PLATFORM_TAB_CONTROLLER = 'AdminVePlatformTab';
    const ADMIN_VE_PLATFORM_CONFIGURE_TAB_CONTROLLER = 'AdminVePlatformConfigureTab';
    const VE_MODULE_VERSION = '17.1.4';

    /**
     * @var VePlatformAPI $api
     */
    public $api = null;

    /**
     * @var VeLogger $logger
     */
    public $logger;

    /**
     * @var DataAccess $dataAccess
     */
    private $dataAccess;

    /**
     * @var RecursiveIteratorIterator $iterator
     */
    public $iterator;

    /**
     * @var RecursiveDirectoryIterator $directoryIterator
     */
    public $directoryIterator;

    /**
     * @var FileSystemHelper $fileSystemHelper
     */
    public $fileSystemHelper;

    /**
     * @var UpgradeHandler $upgradeHandler
     */
    public $upgradeHandler;

    public function __construct()
    {
        $this->author = 'Ve Interactive';
        $this->displayName = 'Ve for PrestaShop';
        $this->name = 'veplatform';
        $this->version = '17.1.4';
        $this->module_key = '8dd5aeff61bc9289af2b7e2bc3b9ed18';
        $this->tab = 'advertising_marketing';
        $this->module_admintab_names = array('Ve', 'Configure');
        if (_PS_VERSION_ != null) {
            $this->ps_versions_compliancy = array('min' => '1.5.0.1', 'max' => _PS_VERSION_ . '.1');
        } else {
            $this->ps_versions_compliancy = array('min' => '1.5.0.1', 'max' => '1.6');
        }

        if (!isset($this->context)) {
            $this->context = Context::getContext();
        }

        parent::__construct();

        //turn notices and warnings of for our productSync endpoint
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

        /* Not to Move */
        $this->description = $this->l('The only automated marketing solution to solve your abandonment &
        conversion problems at every stage in the customer\'s journey.');

        if (self::isPHPVersionCompatible()) {
            include_once 'includes.php';
            if (class_exists('VeLogger')) {
                $this->logger = new VeLogger();
            }

            if (class_exists('VePlatformAPI')) {
                $this->api = new VePlatformAPI($this->context);
            }

            if (class_exists('DataAccess')) {
                $this->dataAccess = new DataAccess($this->logger, $this->context);
            }

            $folder = dirname(__FILE__);
            $this->directoryIterator = new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS);

            $this->iterator = new RecursiveIteratorIterator(
                $this->directoryIterator,
                RecursiveIteratorIterator::SELF_FIRST,
                RecursiveIteratorIterator::CATCH_GET_CHILD
            );

            $this->fileSystemHelper = new FileSystemHelper($this->iterator, $this->logger);
            $this->upgradeHandler = new UpgradeHandler();

            return;
        }

        $pathLogger = dirname(__FILE__) . '/classes/VeLogger.php';
        if (file_exists($pathLogger)) {
            include_once $pathLogger;
        }

        if (class_exists('VeLogger')) {
            $this->logger = new VeLogger();
        }
    }

    /**
     * Check module is enabled
     *
     * @param $name
     *
     * @return bool
     */
    public function isModuleEnabled($name)
    {
        if (!isset($name)) {
            return false;
        }

        if (version_compare(_PS_VERSION_, '1.5', '>=')) {
            return Module::isEnabled($name);
        }

        $module = Module::getInstanceByName($name);
        return ($module && $module->active === true);
    }

    /**
     * Install Ve module
     * @return bool
     */
    public function install()
    {
        $installed = false;

        try {
            /* PS >= 1.6 already check the compliancy */
            $errorMsg = $this->checkRequirements();

            if (!$errorMsg && isset($this->api)) {
                if ($this->api->installModule() === true) {
                    $installed = (parent::install() && $this->registerDisplayHooks()
                            && $this->installTab($this->module_admintab_names))
                        && $this->registerHook('moduleRoutes');
                    if ($installed) {
                        $this->activateProductSync();
                        $this->logger->logMessage('Module has been properly installed in PrestaShop');
                        $this->clearCache();
                    } else {
                        $this->logger->logMessage('Module not installed in PrestaShop -
                        parentInstall, installTab or registerHooks', 'ERROR');
                    }
                } else {
                    $errorMsg = $this->l('There was a problem connecting to Ve Interactive server, try again please.');
                }
            } else {
                $this->logger->logMessage('VePlatformAPI.php was not found or module requirement weren\'t met.', 'ERROR');
                $this->l('There was a problem installing the module.
                Please download the module from marketplace and try again.');
            }

            if ($errorMsg) {
                $this->logger->logMessage($errorMsg, 'ERROR');
                $this->_errors[] = $errorMsg;
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }

        return $installed;
    }

    /**
     * Upgrade Ve module to a higher version
     * @return bool
     */
    public function upgrade($moduleName, $moduleVersion)
    {
        $upgraded = false;
        if (!isset($moduleName) || !isset($moduleVersion)) {
            return $upgraded;
        }

        /* PS >= 1.6 already check the compliancy */
        $errorMsg = $this->checkRequirements();

        if (!$errorMsg && isset($this->api)) {
            $initialProductSyncStatus = $this->api->getConfigOption('productSyncActive');
            $featuresStatus = $this->api->upgradeModule($moduleName, $moduleVersion);
            if (!empty($featuresStatus)) {
                $upgraded = true;
                $updatedProductSyncStatus = $featuresStatus['productSyncActive'];
                if ($initialProductSyncStatus != $updatedProductSyncStatus) {
                    $upgraded = $this->productSyncHandler($initialProductSyncStatus, $updatedProductSyncStatus);
                }
            }

            $tabs = array(self::ADMIN_VE_PLATFORM_TAB_CONTROLLER, self::ADMIN_VE_PLATFORM_CONFIGURE_TAB_CONTROLLER);
            $tabIds = $this->getVeTabIds($tabs);

            $tabs = array(self::OLDER_ADMIN_VE_PLATFORM_TAB_CONTROLLER);
            $olderTabIds = $this->getVeTabIds($tabs);

            //if no tabs were added during install or there is and older tab installed
            if (empty($tabIds) || !empty($olderTabIds)) {
                //remove older tab
                $this->uninstallTab();
                $this->installTab($this->module_admintab_names);
                $this->clearCache();
            }
        } else {
            $this->logger->logMessage('VePlatformAPI.php was not found or module requirements weren\'t met.', 'ERROR');
        }

        if ($errorMsg) {
            $this->logger->logMessage($errorMsg, 'ERROR');
            $this->_errors[] = $errorMsg;
        }

        return $upgraded;
    }

    /**
     * Hook module routes - add autoload file of Composer
     */
    public function hookModuleRoutes()
    {
        try {
            $pathVendorAutoload = dirname(__FILE__) . '/vendor/autoload.php';
            if (file_exists($pathVendorAutoload)) {
                // Add the autoload here to make our Composer classes available everywhere!
                require_once $pathVendorAutoload;
            } else {
                $this->logger->logMessage('Couldn\'t find autoload file to include to use AppInsight');
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }
    }

    /**
     * Add hook for Settings/Thanks Display page on Admin
     */
    public function getContent()
    {
        try {
            /* This hook only exist in PS >= 1.6 */
            $this->hookActionAdminControllerSetMedia();

            $this->context->smarty->assign('veApi', $this->api->requestDomain);
            $this->context->smarty->assign('params', Tools::jsonEncode($this->api->getParams()));

            $domain = Tools::usingSecureMode() ? _PS_BASE_URL_SSL_ . __PS_BASE_URI__ : _PS_BASE_URL_ . __PS_BASE_URI__;
            $this->context->smarty->assign('baseDir', $domain);

            $template_path = self::VIEW_ADMIN_SETTINGS_PATH;
            return $this->display(__FILE__, $template_path);
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return '';
        }
    }

    /**
     * Call WS to uninstall merchant
     * @return bool WS response
     */
    public function uninstall()
    {
        try {
            $this->api->uninstallModule();
            $this->uninstallTab();
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }

        // Calling uninstall automatically unregisters all the hooks
        return parent::uninstall();
    }

    /**
     * Enable Ve module
     * @return bool
     */
    public function enable($force_all = false)
    {
        $response = false;

        try {
            $response = parent::enable($force_all);
            if ($response) {
                $this->logger->logMessage('Module has been enabled');
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }

        return $response;
    }

    /**
     * Enable Ve module on a specific device
     * @return bool
     */
    public function enableDevice($device)
    {
        $response = false;
        if (!isset($device) || !$device) {
            return $response;
        }

        try {
            $response = parent::enableDevice($device);
            $deviceType = $this->getDeviceType($device);
            $this->logger->logMessage('Module has been enabled on a ' . $deviceType . ' device ');
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }

        return $response;
    }

    private function getDeviceType($device)
    {
        if ($device == Context::DEVICE_MOBILE) {
            $device = 'mobile';
        } elseif ($device == Context::DEVICE_TABLET) {
            $device = 'tablet';
        } else {
            $device = 'computer';
        }

        return $device;
    }

    /**
     * Disable Ve module
     * @return bool
     */
    public function disable($force_all = false)
    {
        $response = false;

        try {
            $response = parent::disable($force_all);
            if ($response) {
                $this->logger->logMessage('Module has been disabled');
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }

        return $response;
    }

    /**
     * Disable Ve module on a specific device
     * @return bool
     */
    public function disableDevice($device)
    {
        $response = false;
        if (!isset($device) || !$device) {
            return $response;
        }

        try {
            $response = parent::disableDevice($device);
            $deviceType = $this->getDeviceType($device);
            if ($response) {
                $this->logger->logMessage('Module has been disabled on a ' . $deviceType . ' device ');
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }

        return $response;
    }

    /**
     * Add hook to display vePixel on order confirmation (thank you page)
     * @return string
     */
    public function hookDisplayOrderConfirmation($params)
    {
        $output = '';
        try {
            $pixel = $this->api->getConfigOption('pixel');
            if ($pixel && !empty($pixel)) {
                $this->context->smarty->assign('url_pixel', $pixel);
                $output = $this->display(__FILE__, self::VIEW_FRONT_PIXEL_PATH);
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }
        return $output;
    }

    /**
     * Add hook to display in footer the veTag and veData on all pages
     * @return string
     */
    public function hookFooter($params)
    {
        return $this->addTagVeData($params);
    }

    public function hookDisplayAfterBodyOpeningTag($params)
    {
        return $this->addTagVeData($params);
    }

    private function addTagVeData($params)
    {
        $output = '';
        try {
            $tag = $this->api->getConfigOption('tag');
            $veDataActive = $this->api->getConfigOption('veDataActive');
            if (!empty($tag)) {
                $this->context->smarty->assign('url_tag', $tag);
            }
            if ($veDataActive) {
                $veData = $this->getVeData();
                if (count($veData) > 0) {
                    $this->context->smarty->assign('masterData', Tools::jsonEncode($veData));
                }
            }

            $output = $this->display(__FILE__, self::VIEW_FRONT_TAG_PATH);
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }
        return $output;
    }

    /**
     * Retrieve veData
     * @return array
     */
    public function getVeData()
    {
        $data = array();
        try {
            $veData = new VeData($this->logger, $this->api);
            $data['currency'] = $veData->getCurrency();
            $data['language'] = $veData->getLanguage();
            $data['culture'] = $veData->getCulture();
            $data['user'] = $veData->getUser();
            $data['currentPage'] = $veData->getCurrentPage();
            $data['cart'] = $veData->getCart();
            $data['history'] = $veData->getHistory();

            $path = $this->_path . self::VIEW_MODULE_JS_PATH;
            if (!$this->isPrestashopVersion('1.7')) {
                $this->context->controller->addJS($path);
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }

        return $data;
    }

    /**
     * Add hook to add our CSS and JS files for the Admin section
     * @return void
     */
    public function hookActionAdminControllerSetMedia()
    {
        try {
            $this->context->controller->addCSS($this->_path . self::VIEW_ADMIN_CSS_PATH, 'all');
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }
    }

    /**
     * Add hook to add our CSS and JS files for the front store
     * @return void
     */
    public function hookActionFrontControllerSetMedia()
    {
        try {
            $veDataActive = $this->api->getConfigOption('veDataActive');
            $path = 'modules/' . $this->name . self::VIEW_MODULE_JS_PATH;
            $baseDir = _PS_BASE_URL_ . __PS_BASE_URI__;
            if ($veDataActive) {
                $this->context->controller->registerJavascript(
                    'modules-veplatform',
                    $path,
                    array('position' => 'bottom', 'priority' => 150)
                );
                Media::addJsDef(array('baseDir' => $baseDir));
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }
    }

    /**
     * Register needed hooks for veTag/vePixel/veData/basketRebuild
     * Register hook to add our CSS and JS files
     * @return bool
     */
    private function registerDisplayHooks()
    {
        try {
            if ($this->isPrestashopVersion('1.7')) {
                return (
                    $this->registerHook('displayAfterBodyOpeningTag') &&
                    $this->registerHook('displayOrderConfirmation') &&
                    $this->registerHook('actionAdminControllerSetMedia') &&
                    $this->registerHook('actionFrontControllerSetMedia')
                );
            }
            return (
                $this->registerHook('footer') &&
                $this->registerHook('displayOrderConfirmation') &&
                $this->registerHook('actionAdminControllerSetMedia')
            );
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return false;
        }
    }

    /**
     * Activate productSync feature - register hooks and create db table
     * @return bool
     */
    public function activateProductSync()
    {
        $productSyncActive = $this->api->getConfigOption('productSyncActive');
        if ($productSyncActive) {
            $hookResponse = $this->registerProductHooks();

            if ($hookResponse) {
                $this->dataAccess->createProductSyncTable();
                $this->logger->logMessage('productSync feature successfully activated');
                return true;
            }
        }

        $this->logger->logMessage('productSync feature failed to be activated', 'ERROR');
        return false;
    }

    /**
     * Deactivate productSync feature - unregister hooks and delete db table
     * @return bool
     */
    public function deactivateProductSync()
    {
        $productSyncActive = $this->api->getConfigOption('productSyncActive');
        if (!$productSyncActive) {
            $hookResponse = $this->unregisterProductHooks();

            if (!$hookResponse) {
                $this->logger->logMessage('Failed to unregister hooks for productSync feature', 'ERROR');
            }

            $this->dataAccess->deleteProductSyncTable();
            $this->logger->logMessage('productSync feature was successfully deactivated');
            return true;
        }

        $this->logger->logMessage('productSync feature failed to be activated', 'ERROR');
        return false;
    }

    /**
     * Handler for the productSync feature
     *
     * @param bool $initialProductSyncStatus
     * @param bool $updatedProductSyncStatus
     *
     * @return bool
     */
    public function productSyncHandler($initialProductSyncStatus, $updatedProductSyncStatus)
    {
        $result = false;

        if (!isset($initialProductSyncStatus) || !isset($updatedProductSyncStatus)) {
            return $result;
        }

        if ($initialProductSyncStatus == false && $updatedProductSyncStatus == true) {
            $result = $this->activateProductSync();
        } elseif ($initialProductSyncStatus == true && $updatedProductSyncStatus == false) {
            $result = $this->deactivateProductSync();
        }

        return $result;
    }

    /**
     * Register hooks needed for continuous veProductDataSync
     * @return bool
     */
    private function registerProductHooks()
    {
        try {
            return ($this->registerHook('addProduct') && $this->registerHook('updateProduct') &&
                $this->registerHook('deleteProduct') && $this->registerHook('actionUpdateQuantity'));
        } catch (Exception $exception) {
            $this->logger->logException($exception);
            return false;
        }
    }

    /**
     * Unregister hooks needed for continuous veProductDataSync
     * @return bool
     */
    private function unregisterProductHooks()
    {
        try {
            return ($this->unregisterHook('addProduct') && $this->unregisterHook('updateProduct')
                && $this->unregisterHook('deleteProduct') && $this->unregisterHook('actionUpdateQuantity'));
        } catch (Exception $exception) {
            $this->logger->logException($exception);
            return false;
        }
    }

    /**
     * Add product to be synced when the hook for update quantity is triggered
     *
     * @param array $params
     *
     * @return void
     */
    public function hookUpdateQuantity($params)
    {
        $productId = isset($params['id_product']) ? $params['id_product'] : $params['product']->id;
        $this->addProduct($productId);
    }

    /**
     * Add product to be synced when the hook for add product is triggered
     *
     * @param array $params
     *
     * @return void
     */
    public function hookAddProduct($params)
    {
        $productId = isset($params['id_product']) ? $params['id_product'] : $params['product']->id;
        $this->addProduct($productId);
    }

    /**
     * Add product to be synced when the hook for update product is triggered
     *
     * @param array $params
     *
     * @return void
     */
    public function hookUpdateProduct($params)
    {
        $productId = isset($params['id_product']) ? $params['id_product'] : $params['product']->id;
        $this->addProduct($productId);
    }

    /**
     * Add product to be synced when the hook for delete product is triggered
     *
     * @param array $params
     *
     * @return bool
     */
    public function hookDeleteProduct($params)
    {
        try {
            $productId = isset($params['id_product']) ? $params['id_product'] : $params['product']->id;
            $product = $params['product'];

            //a product has multiple names based on language
            $productNames = $product->name;
            //get base name of the product
            $productName = $productNames[1];
            $productUrl = $product->getLink();

            $this->addProduct($productId, $productUrl, $productName);
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
    }

    /**
     * Add product to be synced
     *
     * @param int    $idProduct
     * @param string $productUrl
     * @param string $productName
     *
     * @return bool
     */
    private function addProduct($idProduct, $productUrl = null, $productName = null)
    {
        try {
            if (isset($idProduct)) {
                $this->dataAccess->addProductToSync($idProduct, $productUrl, $productName);
            }
        } catch (Exception $exception) {
            $this->logger->logException($exception);
        }
    }

    /**
     * Add tab for Ve module
     *
     * @param array $tabNames
     *
     * @return bool
     */
    private function installTab($tabNames)
    {
        $mainTabClassName = self::ADMIN_VE_PLATFORM_TAB_CONTROLLER;
        $mainTabId = (int)Tab::getIdFromClassName($mainTabClassName);
        $mainTabAdded = $this->createTab($mainTabId, $mainTabClassName, $tabNames[0]);

        // For PS 1.6 it is enough to have a main menu, for PS 1.5 and 1.7 we need to add a sub-menu.
        if ($mainTabAdded && !$this->isPrestashopVersion('1.6')) {
            $subTabClassName = self::ADMIN_VE_PLATFORM_CONFIGURE_TAB_CONTROLLER;
            $subTabId = (int)Tab::getIdFromClassName($subTabClassName);
            $parentId = (int)Tab::getIdFromClassName($mainTabClassName);
            $subTabAdded = $this->createTab($subTabId, $subTabClassName, $tabNames[1], $parentId);
        } else {
            $subTabAdded = true;
        }

        return (bool)($mainTabAdded && $subTabAdded);
    }

    /**
     * Create a new tab in the PrestaShop menu
     *
     * @param string $className
     * @param string $tabName
     * @param int    $parentId
     *
     * @return bool
     */
    private function createTab($id_tab, $className, $tabName, $parentId = 0)
    {
        if ($id_tab) {
            $tabAdded = new Tab($id_tab);
        } else {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $className;
            $tab->name[(int)Configuration::get('PS_LANG_DEFAULT')] = $tabName;
            $tab->id_parent = $parentId;
            $tab->module = $this->name;
            $tabAdded = $tab->add();
        }
        return $tabAdded;
    }

    /**
     * Check all requirements are met before installing Ve module
     * @return bool
     */
    private function checkRequirements()
    {
        $message = 'Ve for Prestashop is incompatible with your store, ';
        try {
            $errorMsg = false;

            if (!$errorMsg && !self::isPHPVersionCompatible()) {
                $message = $message . 'PHP 5.5 or newer is required.';
                $this->logger->logMessage($message, 'ERROR');
                $errorMsg = $this->l($message);
            } elseif (!$errorMsg && !self::checkCurlIsInstalled()) {
                $message = $message . 'CURL extension is required.';
                $this->logger->logMessage($message, 'ERROR');
                $errorMsg = $this->l($message);
            } elseif (!$errorMsg && !self::checkGDIsInstalled()) {
                $message = $message . 'PHPGD extension is required.';
                $errorMsg = $this->l($message);
            } else {
                if (method_exists($this, 'checkCompliancy')) {
                    $check_compliancy = $this->checkCompliancy();
                } else {
                    $check_compliancy = $this->checkCompliancyVersion();
                }
                if ($check_compliancy === false) {
                    $errorMsg = $this->l('The version of your module is not compliant with your PrestaShop version.');
                }
            }

            return $errorMsg;
        } catch (Exception $ex) {
            return $this->l($message);
        }
    }

    /**
     * Check PHP Version is equal or greater than 5.5.0
     * @return mixed
     */
    private static function isPHPVersionCompatible()
    {
        return (version_compare(phpversion(), '5.5.0', '>='));
    }

    /**
     * Check PrestaShop compliancy version
     * @return bool
     */
    private function checkCompliancyVersion()
    {
        try {
            $min_version = version_compare(_PS_VERSION_, $this->ps_versions_compliancy['min'], '<');
            $max_version = version_compare(_PS_VERSION_, $this->ps_versions_compliancy['max'], '>');
            if ($min_version || $max_version) {
                return false;
            }
            return true;
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return false;
        }
    }

    /**
     * Check CURL is installed
     * @return bool
     */
    private static function checkCurlIsInstalled()
    {
        return function_exists('curl_init') && function_exists('curl_reset');
    }

    /**
     * Check GD is installed
     * @return bool
     */
    private static function checkGDIsInstalled()
    {
        return (extension_loaded('gd') && function_exists('gd_info'));
    }

    /**
     * Get Ve tab ids by tab names
     *
     * @param array $tabs
     *
     * @return array
     */
    private function getVeTabIds($tabs)
    {
        $tabIds = array();

        foreach ($tabs as $tabName) {
            $id = Tab::getIdFromClassName($tabName);
            if ($id) {
                $tabIds[] = $id;
            }
        }

        return $tabIds;
    }

    /**
     * Remove Ve module tabs
     * @return bool
     */
    private function uninstallTab()
    {
        try {
            $tabs = array(self::ADMIN_VE_PLATFORM_TAB_CONTROLLER, self::ADMIN_VE_PLATFORM_CONFIGURE_TAB_CONTROLLER,
                self::OLDER_ADMIN_VE_PLATFORM_TAB_CONTROLLER);
            $tabIds = $this->getVeTabIds($tabs);

            foreach ($tabIds as $tabId) {
                $tab = new Tab($tabId);
                $tab->delete();
            }

            return true;
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return false;
        }
    }

    /**
     * Clear PrestaShop cache
     *
     * @return void
     */
    private function clearCache()
    {
        Tools::clearSmartyCache();
        Media::clearCache();
    }

    /**
     * Check PrestaShop version
     *
     * @param string $version
     *
     * @return bool
     */
    public static function isPrestashopVersion($version)
    {
        //true if is PrestaShop 1.7.x
        return (strpos(_PS_VERSION_, $version) === 0);
    }
}
