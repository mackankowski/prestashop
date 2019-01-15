<?php
/**
* 2012-2016 NetReviews
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
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2016 NetReviews SAS
*  @license   NetReviews
*  @version   Release: $Revision: 7.2.2
*  @date      26/10/2016
*  International Registered Trademark & Property of NetReviews SAS
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 7.2.2,
 * Don't forget to create one file per version.
 */
function upgrade_module_7_2_0($module)
{
    return upgradePsConfiguration_7_2_0($module) //Upgrade PS configuration from previous versions to the version 7.2.2
        && upgradeHook_7_2_0() //Upgrade hook from previous versions to the version 7.2.2
        && upgradeDatabase_7_2_0(); //Upgrade database from previous versions to the version 7.2.2
}

/**
 * Function used to update your PS configuration from previous versions to the version 7.2.2,
 */
function upgradePsConfiguration_7_2_0()
{
    return Configuration::updateValue('AV_DELAY_PRODUIT', '')
        && Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUIT', '0')
            && Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUITLI', '0')
                && Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETSITE', '0')
                    && Configuration::updateValue('AV_NBOFREVIEWS', '20')
                        && Configuration::updateValue('AV_DISPLAYSTARPLIST', '')
                            && Configuration::updateValue('AV_HOOKPRODUCTTAB_STYLEH3', '')
                                && Configuration::updateValue('AV_HOOKPRODUCTTAB_STYLEHEADERAV', '')
                                    && Configuration::updateValue('AV_HOOKPRODUCTTAB_STYLEA', '');
}

/**
 * Function used to update your hook from previous versions to the version 7.2.2,
 */
function upgradeHook_7_2_0()
{
    return true;
}

/**
 * Function used to update your database from previous versions to the version 7.2.2,
 */
function upgradeDatabase_7_2_0()
{
    return true;
}
