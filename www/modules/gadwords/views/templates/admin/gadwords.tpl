{*
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @version  Release: $Revision: 14985 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<div class="row gadwords-header">
		<div class="col-xs-6 text-center">
			<img id="adwords_logo" src="{$module_dir|escape:'html':'UTF-8'}/views/img/header_logo.jpg" alt="{l s='Google AdWords' mod='gadwords'}" />
		</div>
		<div class="col-xs-6 text-center">
			<span class="items-video-promotion"><object type="text/html" data="{l s='//www.youtube.com/embed/jQWrmxsQIM0?rel=0&amp;controls=0&amp;showinfo=0' mod='gadwords'}" width="400" height="225"></object></span>
		</div>
	</div>
	<hr />
	<div class="gadwords-content">
		<div class="row">
			<div class="col-xs-12">
				<p>
					<b>
						{l s='Show your ad to people at the very moment they are searching for what your offer!' mod='gadwords'}
					</b>
				</p>

				<ul>
					<li>{l s='Drive more traffic to your website by advertising on Google to the target audience you choose, whether it\'s local or global, to people who are looking for a business like yours.' mod='gadwords'}</li>
					<li>{l s='Google and PrestaShop boost your advertising investment by offering you credit to spend on advertising, after you initial spend.' mod='gadwords'}</li>
				</ul>
				<br/>
				<div class="col-xs-12 text-center">
						<h4>{l s='Your Google AdWords promotional code for your shop is' mod='gadwords'}:</h4>
						<pre id="adwords_voucher">{$code|escape:htmlall}</pre>
						<p style="width:70%;margin:auto;">{l s='Add your promotional code from PrestaShop after entering billing details, and we will automatically credit your account when you spend a minimum credit*.' mod='gadwords'}</p>
                        <br>
						<p><a style="font-size:15px;font-weight:bold;" href="{$landing_page|escape:htmlall}" target="_blank" title="Google AdWords">{l s='Start your campaign now' mod='gadwords'}</a></p>
				</div>
                <div style="margin-top:250px;">
                    <span class="small">
                        * {l s='The promotion is active for new Google Adwords accounts in the following countries :' mod='gadwords'}
                    </span>
                    <table id="gadwords_promotion">
                        <tr>
                            <td>{l s='Austria, Belgium, Estonia, France, Germany, Greece, Ireland, Italy, Latvia, Lithuania, Netherlands, Portugal, Slovakia, Spain' mod='gadwords'}</td>
                            <td>{l s='Receive 75 EUR when you spend 25 EUR' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='BulGaria' mod='gadwords'}</td>
                            <td>{l s='Receive 90 BGN when you spend 30 BGN' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Czech Republic' mod='gadwords'}</td>
                            <td>{l s='Receive 1000 CZK when you spend 250 CZK' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Denmark' mod='gadwords'}</td>
                            <td>{l s='Receive 600 DKK when you spend 200 DKK' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Hungary' mod='gadwords'}</td>
                            <td>{l s='Receive 15000 HUF when you spend 5000 HUF' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Poland' mod='gadwords'}</td>
                            <td>{l s='Receive 250 PLN when you spend 100 PLN' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Romania' mod='gadwords'}</td>
                            <td>{l s='Receive 200 RON when you spend 50 RON' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Sweden' mod='gadwords'}</td>
                            <td>{l s='Receive 750 SEK when you spend 250 SEK' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Switzerland' mod='gadwords'}</td>
                            <td>{l s='Receive 100 CHF when you spend 25 CHF' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='Turkey' mod='gadwords'}</td>
                            <td>{l s='Receive 100 TRL when you spend 25 TRL' mod='gadwords'}</td>
                        </tr>
                        <tr>
                            <td>{l s='United Kingdom' mod='gadwords'}</td>
                            <td>{l s='Receive 75 GBP when you spend 25 GBP' mod='gadwords'}</td>
                        </tr>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>
