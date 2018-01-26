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

$pathConfig = dirname(__FILE__) . '/../../../config/config.inc.php';
$pathInit = dirname(__FILE__) . '/../../../init.php';
$pathIncludes = dirname(dirname(__FILE__)) . '/includes.php';

if (file_exists($pathConfig)) {
    include_once($pathConfig);
}

if (file_exists($pathInit)) {
    include_once($pathInit);
}

if (file_exists($pathIncludes)) {
    include_once($pathIncludes);
}

$logger = new VeLogger();

try {
    $ajaxMethod = Tools::getValue('method');
} catch (Exception $ex) {
    $ajaxMethod = isset($_REQUEST['method']) ? $_REQUEST['method'] : '';
    $logger->logException($ex);
}

switch ($ajaxMethod) {
    case 'updateCart':
        try {
            $context = Context::getContext();
            $veApi = new VePlatformAPI($context);
            $veData = new VeData($logger, $veApi);
            sleep(1);
            $cart = $veData->getCart();
            if (!isset($cart['dateUpd'])) {
                $cart['dateUpd'] = date('D M d Y H:i:s O');
            }
            die(Tools::jsonEncode($cart));
        } catch (Exception $ex) {
            $logger->logException($ex);
            die(VeData::getDefaultCart());
        }
        break;
    case 'logMessage':
        try {
            $logger->logMessage(Tools::getValue('message'), Tools::getValue('level'));
        } catch (Exception $ex) {
            $logger->logException($ex);
        }
        break;
    default:
        die('empty method');
}
exit;
