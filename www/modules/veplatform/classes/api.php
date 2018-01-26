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

try {
    include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config/config.inc.php');
    include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config/settings.inc.php');
    include_once(dirname(dirname(__FILE__)) . '/includes.php');

    $timePre = microtime(true);
    $logger = new VeLogger();
    $context = Context::getContext();
    $veApi = new VePlatformAPI($context);
    $productSyncActive = $veApi->getConfigOption('productSyncActive');

    if ($_SERVER['REQUEST_METHOD'] != 'GET' || !$productSyncActive || !Tools::getValue('method') ||
        !Tools::getValue('batchSize')) {
        $logger->logMessage('Invalid request', 'ERROR');

        header("HTTP/1.1 " . 500 . " Internal Server Error");
        die();
    }

    $method = Tools::getValue('method');
    $startingProductIndex = Tools::getValue('startingProductIndex');
    $batchSize = Tools::getValue('batchSize');
    $products = $veApi->$method($batchSize, $startingProductIndex);

    //flag to disable escaping slashes
    $products = json_encode($products, JSON_UNESCAPED_SLASHES);

    $timePost = microtime(true);
    $execSync = $timePost - $timePre;
    $logger->trackMetric(Tools::strtoupper($method{0}) . Tools::substr($method, 1) . '.Module.Duration', $execSync);
    die($products);
} catch (Exception $ex) {
    $logger = new VeLogger();
    $logger->logException($ex);
    header("HTTP/1.1 " . 500 . " Internal Server Error");
    die();
}
