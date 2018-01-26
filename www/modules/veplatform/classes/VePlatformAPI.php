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

class VePlatformAPI extends AbstractVePlatformAPI
{
    /**
     * @var string $requestEcommerce
     */
    protected $requestEcommerce = 'PrestaShop';

    /**
     * @var Context $context
     */
    protected $context;

    /**
     * @var array $configurations
     */
    private $configurations = array(
        'veplatform_tag_url',
        'veplatform_pixel_url',
        'veplatform_token',
        'veplatform_journeyCode',
        'veplatform_is_installed',
        'veplatform_veData_active',
        'veplatform_productSync_active',
        'veplatform_basketRebuild_active'
    );

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }

    /**
     * Load config array with tag, pixel, token, veDataActive, productSyncActive, basketRebuildActive values
     * @return void
     */
    protected function loadConfig()
    {
        try {
            $config = $this->dataAccess->getMultipleConfig($this->configurations);
            $this->config['tag'] = $config['veplatform_tag_url'] !== false ?
                $config['veplatform_tag_url'] : $this->config['tag'];
            $this->config['journeyCode'] = $config['veplatform_journeyCode'] !== false ?
                $config['veplatform_journeyCode'] : $this->config['journeyCode'];
            $this->config['pixel'] = $config['veplatform_pixel_url'] !== false ?
                $config['veplatform_pixel_url'] : $this->config['pixel'];
            $this->config['token'] = $config['veplatform_token'] !== false ?
                $config['veplatform_token'] : $this->config['token'];
            $this->config['veDataActive'] = $config['veplatform_veData_active'];
            $this->config['productSyncActive'] = $config['veplatform_productSync_active'];
            $this->config['basketRebuildActive'] = $config['veplatform_basketRebuild_active'];
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            $this->config = array();
        }
    }

    /**
     * Save tag, token, pixel, journeyCode, veDataActive, productSyncActive, basketRebuildActive
     *
     * @param mixed $journey
     *
     * @return bool
     */
    protected function saveJourney($journey)
    {
        try {
            if (!$this->dataAccess->updateConfigValue('veplatform_tag_url', $journey->URLTag)
                || !$this->dataAccess->updateConfigValue('veplatform_pixel_url', $journey->URLPixel)
                || !$this->dataAccess->updateConfigValue('veplatform_token', $journey->Token)
                || !$this->dataAccess->updateConfigValue('veplatform_journeyCode', $journey->JourneyCode)
                || !$this->dataAccess->updateConfigValue('veplatform_veData_active', $journey->VeDataActive)
                || !$this->dataAccess->updateConfigValue('veplatform_productSync_active', $journey->ProductSyncActive)
                || !$this->dataAccess->updateConfigValue('veplatform_basketRebuild_active', $journey->BasketRebuildActive)
            ) {
                return false;
            }

            $this->config['tag'] = $journey->URLTag;
            $this->config['pixel'] = $journey->URLPixel;
            $this->config['token'] = $journey->Token;
            $this->config['journeyCode'] = $journey->JourneyCode;
            $this->config['veDataActive'] = $journey->VeDataActive;
            $this->config['productSyncActive'] = $journey->ProductSyncActive;
            $this->config['basketRebuildActive'] = $journey->ProductSyncActive;

            $this->logger->logMessage('Module configurations' . print_r($this->config, true));

            return true;
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return false;
        }
    }

    /**
     * Delete all config values
     * @return void
     */
    protected function deleteConfig()
    {
        try {
            foreach ($this->configurations as $config) {
                $this->dataAccess->deleteConfigByKey($config);
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }
    }

    /**
     * Get all installed modules names
     * @return array
     */
    protected function getInstalledModules()
    {
        $installedModulesNames = array();

        try {
            $installedModules = Module::getModulesInstalled();

            foreach ($installedModules as $module) {
                $installedModulesNames[] = $module['name'];
            }
        } catch (Exception $ex) {
            $this->logger->logException($ex);
        }
        return $installedModulesNames;
    }

    /**
     * Get module version from database
     *
     * @param string $moduleName
     *
     * @return string
     */
    protected function getOlderModuleVersion($moduleName)
    {
        $databaseVersion = 'versionNotFound';
        if (!isset($moduleName)) {
            return $databaseVersion;
        }

        $installedModules = Module::getModulesInstalled();
        foreach ($installedModules as $installedModule) {
            if ($installedModule['name'] === $moduleName) {
                $databaseVersion = $installedModule['version'];
                return $databaseVersion;
            }
        }

        return $databaseVersion;
    }

    /**
     * Get config option saved in PrestaShop DB by name
     *
     * @param string  $option
     * @param boolean $reload (default: false)
     *
     * @return string
     */
    public function getConfigOption($option, $reload = false)
    {
        try {
            if ($reload === true) {
                $this->loadConfig();
            }
            $value = array_key_exists($option, $this->config) ? $this->config[$option] : null;
            return $value;
        } catch (Exception $ex) {
            $this->logger->logException($ex);
            return "";
        }
    }

    /**
     * Update config options value in PrestaShop DB
     *
     * @param stdClass $values
     *
     * @return array
     */
    public function updateFeatureToggleConfigs($values)
    {
        $featuresStatus = array();
        if (!isset($values)) {
            return $featuresStatus;
        }

        if ($this->dataAccess->updateConfigValue('veplatform_veData_active', $values->VeDataActive) &&
            $this->dataAccess->updateConfigValue('veplatform_productSync_active', $values->ProductSyncActive) &&
            $this->dataAccess->updateConfigValue('veplatform_basketRebuild_active', $values->BasketRebuildActive)
        ) {
            $this->loadConfig();
            $featuresStatus['veDataActive'] = $values->VeDataActive;
            $featuresStatus['productSyncActive'] = $values->ProductSyncActive;
            $featuresStatus['basketRebuildActive'] = $values->BasketRebuildActive;

            $this->logger->logMessage('Update module configurations ' . print_r($featuresStatus, true));
        }

        return $featuresStatus;
    }
}
