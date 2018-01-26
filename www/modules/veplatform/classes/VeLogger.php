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

class VeLogger
{
    /**
     * @var \ApplicationInsights\Telemetry_Client $telemetryClient
     */
    private $telemetryClient;

    /**
     * @var string $moduleVersion
     */
    public $moduleVersion;

    /**
     * @var FileLoggerCore $psLogger
     */
    private $psLogger;

    /**
     * @var string $logFile
     */
    private $logFile;

    public function __construct()
    {
        try {
            $this->moduleVersion = VePlatform::VE_MODULE_VERSION;
            if (class_exists('\ApplicationInsights\Telemetry_Client')) {
                $this->telemetryClient = new \ApplicationInsights\Telemetry_Client();
                $this->telemetryClient->getContext()->setInstrumentationKey('35a3d3fa-b65d-440f-be92-4330255f523d');
            } else {
                $this->logFile = _PS_ROOT_DIR_ . "/log/" . date('Ymd') . "_veplatform.log";
                if (is_writable($this->logFile) || (!file_exists($this->logFile) && is_writable(dirname(__FILE__)))) {
                    $this->psLogger = new FileLogger(0);
                    $this->psLogger->setFilename($this->logFile);
                }
            }
        } catch (Exception $ex) {
            $this->logFile = _PS_ROOT_DIR_ . "/log/" . date('Ymd') . "_veplatform.log";
            if (is_writable($this->logFile) || (!file_exists($this->logFile) && is_writable(dirname(__FILE__)))) {
                $this->psLogger = new FileLogger(0);
                $this->psLogger->setFilename($this->logFile);
                $this->psLogger->logDebug('Cannot send logs to AppInsights: ' . print_r($ex, true));
            }
        }
    }

    /**
     * Log message in Application Insights
     *
     * @param string $message
     * @param string $level
     * @param bool   $forceFlush
     *
     * @return void
     */
    public function logMessage($message, $level = 'INFO', $forceFlush = true)
    {
        try {
            if (isset($message) && isset($this->telemetryClient)) {
                $this->telemetryClient->trackMessage($message, $this->getContainerInformation($level));
                if ($forceFlush) {
                    $this->telemetryClient->flush();
                }
            }
        } catch (Exception $ex) {
            if (isset($this->psLogger)) {
                $this->psLogger->logDebug(print_r($ex, true));
            }
        }
    }

    /**
     * Log exception in Application Insights
     *
     * @param Exception $exception
     * @param bool      $forceFlush
     *
     * @return void
     */
    public function logException(\Exception $exception, $forceFlush = true)
    {
        try {
            if (isset($exception) && isset($this->telemetryClient)) {
                $this->telemetryClient->trackException($exception, $this->getContainerInformation('ERROR'));
                if ($forceFlush) {
                    $this->telemetryClient->flush();
                }
            }
        } catch (Exception $ex) {
            if (isset($this->psLogger)) {
                $this->psLogger->logDebug(print_r($ex, true));
            }
        }
    }

    /**
     * Log metric in Application Insights
     *
     * @param string $name
     * @param double $value
     * @param bool   $forceFlush
     *
     * @return void
     */
    public function trackMetric($name, $value, $forceFlush = true)
    {
        try {
            if (isset($name) && isset($value) && isset($this->telemetryClient)) {
                $this->telemetryClient->trackMetric(
                    $name,
                    $value,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $this->getContainerInformation('INFO')
                );

                if ($forceFlush) {
                    $this->telemetryClient->flush();
                }
            }
        } catch (Exception $ex) {
            if (isset($this->psLogger)) {
                $this->psLogger->logDebug(print_r($ex, true));
            }
        }
    }

    /**
     * Send metrics for get product count and duration from DB
     *
     * @param string $syncType
     * @param int    $productsCount
     * @param int    $time
     */
    public function sendModuleDbMetrics($syncType, $productsCount, $time)
    {
        $this->trackMetric($syncType . '.ModuleDb.ProdCount', $productsCount);
        $this->trackMetric($syncType . '.ModuleDb.Duration', $time);
    }

    /**
     * Get environment information
     *
     * @param string $level
     *
     * @return array
     */
    private function getContainerInformation($level)
    {
        return array('Level' => $level,
            'Shop' => 'PrestaShop',
            'Version' => _PS_VERSION_,
            'URL' => isset($_SERVER['REQUEST_URI']) ? Context::getContext()->shop->getBaseURL(true) .
                $_SERVER['REQUEST_URI'] : Context::getContext()->shop->getBaseURL(true),
            'PHPVersion' => phpversion(),
            'ModuleVersion' => $this->moduleVersion);
    }
}
