<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class RijndaelCore
{
    protected $_key;
    protected $_iv;

    public function __construct($key, $iv)
    {
        $this->_key = $key;
        $this->_iv = base64_decode($iv);
    }

    /**
     * Base64 is not required, but it is be more compact than urlencode
     *
     * @param string $plaintext
     * @return bool|string
     */
    public function encrypt($plaintext)
    {
        $length = (ini_get('mbstring.func_overload') & 2) ? mb_strlen($plaintext, ini_get('default_charset')) : strlen($plaintext);

        if ($length >= 1048576) {
            return false;
        }
        $ciphertext = null;
        if (function_exists('openssl_encrypt') && version_compare(phpversion(), '5.3.3', '>=')) {
            $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $this->_key, OPENSSL_RAW_DATA, $this->_iv);
        } elseif (function_exists('mcrypt_encrypt')) {
            $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_key, $plaintext, MCRYPT_MODE_CBC, $this->_iv);
        } else {
            throw new RuntimeException('Either Mcrypt or OpenSSL extension is required to run Prestashop');
        }
        return base64_encode($ciphertext) . sprintf('%06d', $length);
    }

    public function decrypt($ciphertext)
    {
        $output = null;
        if (ini_get('mbstring.func_overload') & 2) {
            $length = intval(mb_substr($ciphertext, -6, 6, ini_get('default_charset')));
            $ciphertext = mb_substr($ciphertext, 0, -6, ini_get('default_charset'));
            if (function_exists('openssl_decrypt') && version_compare(phpversion(), '5.3.3', '>=')) {
                $output = openssl_decrypt(base64_decode($ciphertext), 'AES-128-CBC', $this->_key, OPENSSL_RAW_DATA, $this->_iv);
            } elseif (function_exists('mcrypt_decrypt')) {
                $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_key, base64_decode($ciphertext), MCRYPT_MODE_CBC, $this->_iv);
            } else {
                throw new RuntimeException('Either Mcrypt or OpenSSL extension is required to run Prestashop');
            }
            return mb_substr(
                $output,
                0,
                $length,
                ini_get('default_charset')
            );
        } else {
            $length = intval(substr($ciphertext, -6));
            $ciphertext = substr($ciphertext, 0, -6);
            if (function_exists('openssl_decrypt') && version_compare(phpversion(), '5.3.3', '>=')) {
                $output = openssl_decrypt(base64_decode($ciphertext), 'AES-128-CBC', $this->_key, OPENSSL_RAW_DATA, $this->_iv);
            } elseif (function_exists('mcrypt_decrypt')) {
                $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_key, base64_decode($ciphertext), MCRYPT_MODE_CBC, $this->_iv);
            } else {
                throw new RuntimeException('Either Mcrypt or OpenSSL extension is required to run Prestashop');
            }
            return substr($output, 0, $length);
        }
    }
}
