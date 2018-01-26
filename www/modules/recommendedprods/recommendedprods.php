<?php
/*
* 2007-2012 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 7048 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA

12/05/2012 Gary D. Pierre: Modifications to the original Featured Products module by Prestashop

This customizations where made to create a custom isting for recommended products in the shopping cart page for up-selling purposes.


*/

if (!defined('_PS_VERSION_'))
	exit;

class RecommendedProds extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'recommendedprods';
		$this->tab = 'front_office_features';
		$this->version = '0.9';
		$this->author = 'PrestaShop';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Recommend Products');
		$this->description = $this->l('Displays recommended products in the shopping cart page.');
	}

	function install()
	{
		if (!Configuration::updateValue('RECOMMEND_NBR', 8) || !parent::install() || !$this->registerHook('displayShoppingCartFooter') || !$this->registerHook('header'))
			return false;
		return true;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitRecommendedProds'))
		{
			$nbr = (int)(Tools::getValue('nbr'));
			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('Invalid number of products');
			else
				Configuration::updateValue('RECOMMEND_NBR', (int)($nbr));
			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
				$output .= $this->displayConfirmation($this->l('Settings updated'));
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<p>'.$this->l('In order to add products to your cart page, just add them to the designated category you wish. Then change the category id# in line 112 of the module recommendedprods.php file.').'</p><br />
				<label>'.$this->l('Number of products displayed').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="nbr" value="'.Tools::safeOutput(Tools::getValue('nbr', (int)(Configuration::get('RECOMMEND_NBR')))).'" />
					<p class="clear">'.$this->l('The number of products displayed on the shopping cart page (default: 8).').'</p>

				</div>
				<center><input type="submit" name="submitRecommendedProds" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
		return $output;
	}

	public function hookDisplayHeader($params)
	{
		$this->hookHeader($params);
	}

	public function hookHeader($params)
	{
		$this->context->controller->addCss($this->_path.'recommendedprods.css', 'all');
	}
	
	public function hookDisplayShoppingCartFooter($params)
	{
		
		$category = new Category(15, (int)Context::getContext()->language->id);
		$nb = (int)(Configuration::get('RECOMMEND_NBR'));
		$products = $category->getProducts((int)Context::getContext()->language->id, 1, ($nb ? $nb : 10));

		$this->smarty->assign(array(
			'products' => $products,
			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
			'homeSize' => Image::getSize('home_default'),
		));

		return $this->display(__FILE__, 'recommendedprods.tpl');
		
	}

}
