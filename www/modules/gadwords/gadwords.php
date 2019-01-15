<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @version	Release: $Revision: 17142 $
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_'))
	exit;

class GAdwords extends Module
{

	public function __construct()
	{
		$this->name = 'gadwords';
		$this->tab = 'advertising_marketing';
		$this->version = '1.3.10';
		$this->author = 'PrestaShop';
		$this->need_instance = 1;

        $this->module_key = 'd538ea5fbbb9d4351b6678f60d7367a1';

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Google AdWords');
		$this->description = $this->l('You want to be more visible on Google and attract new clients ? Use our 75€ promo code on Google Adwords !');

		if (_PS_VERSION_ < '1.5')
			require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');

		if (!isset($this->_path))
			$this->_path = _PS_MODULE_DIR_.$this->name;
	}

	public function install()
	{
		return parent::install() && $this->registerHook('backOfficeHeader');
	}

	public function hookBackOfficeHeader()
	{
		if (strcmp(Tools::getValue('configure'), $this->name) === 0)
		{
			if (version_compare(_PS_VERSION_, '1.5', '>') == true)
			{
				$this->context->controller->addCSS($this->_path.'views/css/gadwords.css');
				if (version_compare(_PS_VERSION_, '1.6', '<') == true)
					$this->context->controller->addCSS($this->_path.'views/css/gadwords-nobootstrap.css');
			}
			else
			{
				echo '<link rel="stylesheet" href="'.$this->_path.'views/css/gadwords.css" type="text/css" />';
				echo '<link rel="stylesheet" href="'.$this->_path.'views/css/gadwords-nobootstrap.css" type="text/css" />';
			}
		}
	}

	public function getContent()
	{
		$landing_page = 'https://adwords.google.com/um/Welcome/Home';
		
		$is_local = preg_match('/^172\.16\.|^192\.168\.|^10\.|^127\.|^localhost|\.local$/', Configuration::get('PS_SHOP_DOMAIN'));

		//Prepare data for voucher code
		$data = array(
			'campaign' => $this->name,
			'iso_country' => $this->context->country->iso_code,
			'iso_lang' => $this->context->language->iso_code,
			'ps_version' => _PS_VERSION_,
			'host' => Configuration::get('PS_SHOP_DOMAIN'),
			'is_local' => $is_local,
			'email' => $is_local ? Configuration::get('PS_SHOP_EMAIL') : ''
		);

		$code = '----';

		// Call to get voucher code
		$content = Tools::jsonDecode(Tools::file_get_contents('https://gamification.prestashop.com/get_campaign.php?'.http_build_query($data)));
		if ($content)
		{
			if (isset($content->error) && isset($content->code))
			{
				if ($content->error === false)
					$code = $content->code;
				else
					Logger::addLog('Module Google AdWords: Error returned by the Gamification ('.$content->code.').', 3);
			}
			else
				Logger::addLog('Module Google AdWords: Missing required fields.', 3);
		}
		else
			Logger::addLog('Module Google AdWords: Unexpected data returned from the Gamification.', 3);

		$this->context->smarty->assign(array(
			'module_dir' => $this->_path,
			'code' => $code,
			'landing_page' => $landing_page,
			'is_local' => $is_local,
		));
		return $this->display(__FILE__, 'views/templates/admin/gadwords.tpl');
	}

}
