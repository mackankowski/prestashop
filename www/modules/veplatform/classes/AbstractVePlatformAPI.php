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

abstract class AbstractVePlatformAPI
{
    public $requestDomain = 'https://veconnect.veinteractive.com/';
    protected $requestEcommerce = 'EcommerceIdentifier';
    protected $requestInstall = 'Install';
    protected $requestUninstall = 'Uninstall';
    protected $requestUpgrade = 'Upgrade';
    protected $requestGetSettings = 'GetModuleSettings';
    protected $requestTimeout = 15;
    protected $requestParams = array();
    protected $config = array(
        'tag' => null,
        'pixel' => null,
        'token' => null,
        'journeyCode' => null,
        'veDataActive' => null,
        'productSyncActive' => null,
        'basketRebuildActive' => null
    );

    /**
     * @var ProductCore $productCore
     */
    protected $productCore;

    /**
     * @var DataAccess $dataAccess
     */
    protected $dataAccess;

    /**
     * @var ProductPrestashopService $productPrestashopService
     */
    protected $productPrestashopService;

    /**
     * @var ProductMapper $productMapper
     */
    protected $productMapper;

    /**
     * @var ProductModel $productModel
     */
    protected $productModel;

    /**
     * @var ProductDecorator $productDecorator
     */
    protected $productDecorator;

    /**
     * @var ProductService $productService
     */
    protected $productService;

    /**
     * @var PrestashopProductFactory $prestashopProductFactory
     */
    public $prestashopProductFactory;
    /**
     * @var PrestashopCategoryFactory $prestashopCategoryFactory
     */
    public $prestashopCategoryFactory;

    /**
     * @var VeLogger $logger
     */
    protected $logger;

    /**
     * @var Context $context
     */
    protected $context;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->logger = new VeLogger();
        $this->productCore = new Product();
        $this->dataAccess = new DataAccess($this->logger, $this->context, $this->productCore);
        $this->prestashopProductFactory = new PrestashopProductFactory();
        $this->prestashopCategoryFactory = new PrestashopCategoryFactory();
        $this->productPrestashopService = new ProductPrestashopService(
            $this->logger,
            $this->prestashopProductFactory,
            $this->prestashopCategoryFactory
        );
        $this->productMapper = new ProductMapper($this->productCore);
        $this->productModel = new ProductModel();
        $this->productDecorator = new ProductDecorator($this->productPrestashopService);
        $this->productService = new ProductService(
            $this->dataAccess,
            $this->productPrestashopService,
            $this->productMapper,
            $this->productModel,
            $this->productDecorator,
            $this->logger
        );

        $this->loadConfig();
    }

    abstract protected function loadConfig();

    abstract protected function deleteConfig();

    abstract protected function saveJourney($journey);

    abstract protected function getInstalledModules();

    abstract protected function getOlderModuleVersion($moduleName);

    abstract public function getConfigOption($option, $reload = false);

    abstract public function updateFeatureToggleConfigs($values);

    /**
     * Get needed params for the install request
     * @return array
     */
    public function getParams()
    {
        try {
            $params = array(
                'domain' => $_SERVER['HTTP_HOST'],
                'language' => Tools::strtolower($this->context->language->iso_code),
                'email' => $this->context->cookie->email,
                'phone' => null,
                'merchant' => $this->context->shop->name,
                'country' => Tools::strtoupper($this->context->country->iso_code),
                'currency' => Tools::strtoupper($this->context->currency->iso_code),
                'version' => _PS_VERSION_,
                'ecommerce' => $this->requestEcommerce,
                'isInstallFlow' => 'false',
                'journeyCode' => isset($this->config['journeyCode']) ? $this->config['journeyCode'] : $this->getJourneyCodeFromTag($this->config['tag']),
                'moduleVersion' => $this->logger->moduleVersion
            );
            return $params;
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return array(
                'domain' => null,
                'language' => null,
                'email' => null,
                'phone' => null,
                'merchant' => null,
                'country' => null,
                'currency' => null,
                'version' => null,
                'ecommerce' => null,
                'isInstallFlow' => null,
                'journeyCode' => null,
                'moduleVersion' => null
            );
        }
    }

    /**
     * Get JourneyCode from tag
     * @return string
     */
    private function getJourneyCodeFromTag($tag)
    {
        //ex of tag: //config-ci.veinteractive.net/tags/E242C763/FBE7/4CB2/9A4D/B0953BA53EEC/tag.js
        //we search the 'tags/' string in the tag and we move 5 positions to the right to sit at the beginning of the JourneyCode
        //$formattedTag will be E242C763/FBE7/4CB2/9A4D/B0953BA53EEC/tag.js so we remove the last 7 characters to get only the JourneyCode
        $formattedTag = Tools::substr($tag, strpos($tag, 'tags/') + 5);
        return str_replace('/', '-', Tools::substr($formattedTag, 0, Tools::strlen($formattedTag) - 7));
    }

    /**
     * Install Ve module
     * @return boolean
     */
    public function installModule()
    {
        $installedModules = $this->getInstalledModules();
        $this->logger->logMessage('Modules installed: ' . print_r($installedModules, true));
        $params = $this->getParams();
        $params['isInstallFlow'] = 'true';
        $response = $this->getRequest($this->requestInstall, $params);
        if ($response) {
            $journey = Tools::jsonDecode($response);
            if (isset($journey->URLPixel) && isset($journey->URLTag) && isset($journey->Token)) {
                $journey->URLPixel = $this->cleanUrl($journey->URLPixel);
                $journey->URLTag = $this->cleanUrl($journey->URLTag);

                return $this->saveJourney($journey);
            }
            $this->logger->logMessage('Module not installed in PrestaShop - token, tag or pixel were null.', 'ERROR');
        }

        return false;
    }

    /**
     * Upgrade Ve module
     *
     * @param string $moduleName
     * @param string $moduleVersion
     *
     * @return array
     */
    public function upgradeModule($moduleName, $moduleVersion)
    {
        $featuresStatus = array();
        if (!isset($moduleName) || !isset($moduleVersion)) {
            return $featuresStatus;
        }

        $installedModules = $this->getInstalledModules();
        $this->logger->logMessage('Modules installed: ' . print_r($installedModules, true));

        $params = $this->getParams();
        unset($params['moduleVersion']);
        $params['fromVersion'] = $this->getOlderModuleVersion($moduleName);
        $params['toVersion'] = $moduleVersion;

        $this->logger->logMessage('Start - module upgrade from version ' . $params['fromVersion'] . ' to ' .
            $params['toVersion']);

        $response = $this->getRequest($this->requestUpgrade, $params);
        if ($response) {
            $featureToggles = Tools::jsonDecode($response);
            if (isset($featureToggles->ProductSyncActive) &&
                isset($featureToggles->VeDataActive) &&
                isset($featureToggles->BasketRebuildActive)
            ) {
                return $this->updateFeatureToggleConfigs($featureToggles);
            }
        }
        $this->logger->logMessage('Unexpected response or no feature statuses received from webservice call.', 'ERROR');

        return $featuresStatus;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function cleanUrl($url)
    {
        $clean_url = preg_replace('(^https?:)', '', $url);
        return $clean_url;
    }

    /**
     * Uninstall Ve module
     * @return boolean
     */
    public function uninstallModule()
    {
        $params = $this->getParams();
        $params['token'] = $this->getConfigOption('token');

        $this->deleteConfig();
        $this->dataAccess->deleteProductSyncTable();
        $response = $this->getRequest($this->requestUninstall, $params);
        if ($response) {
            return Tools::jsonDecode($response);
        }

        return false;
    }

    /**
     * @param string $requestAction
     * @param array  $params
     *
     * @return mixed
     */
    protected function getRequest($requestAction, $params)
    {
        try {
            $params = Tools::jsonEncode($params);
            $endpoint = $this->requestDomain . 'API/veconnect/' . $requestAction;

            $this->logger->logMessage('Start - Call WS endpoint ' . $endpoint);

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . Tools::strlen($params)
            ));

            $response = curl_exec($ch);
            $requestCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($response === false) {
                //log curl error
                $this->logger->logMessage(curl_error($ch), 'ERROR');
            }

            if ($requestCode != 200) {
                $this->logger->logMessage('End - Call to WS failed', 'ERROR');
                return false;
            }

            $this->logger->logMessage('End - Call to WS was successful');
            return $response;
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return false;
        }
    }

    /**
     * Get all products for initialProductSync
     *
     * @param int $batchSize
     * @param int $startingProductIndex
     *
     * @return array
     */
    public function initialProductSync($batchSize, $startingProductIndex)
    {
        $products = $this->productService->getProducts($startingProductIndex, $batchSize);

        return $products;
    }

    /**
     * Get all products for continuousSync
     *
     * @param int $batchSize
     *
     * @return array
     */
    public function continuousProductSync($batchSize)
    {
        $products = $this->productService->getUpdatedProducts($batchSize);
        return $products;
    }
}
