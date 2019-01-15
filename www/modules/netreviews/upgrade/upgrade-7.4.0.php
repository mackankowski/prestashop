<?php
/**
 * 2012-2017 NetReviews
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
 *  @copyright 2017 NetReviews SAS
 *  @license   NetReviews
 *  @version   Release: $Revision: 7.4.0
 *  @date      01/09/2017
 *  International Registered Trademark & Property of NetReviews SAS
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 7.4.0,
 * Don't forget to create one file per version.
 */
function upgrade_module_7_4_0($module)
{
    return upgradeHook_7_4_0($module) //Upgrade hook from previous versions to the version 7.4.0
        && upgradePsConfiguration_7_4_0() //Upgrade PS configuration from previous versions to the version 7.4.0
        && upgradeDatabase_7_4_0(); //Upgrade database from previous versions to the version 7.4.0
}

/**
 * Function used to update your hook from previous versions to the version 7.4.0,
 */
function upgradeHook_7_4_0($module)
{
    return $module->registerHook('displayFooterProduct')
        && $module->registerHook('Extra_netreviews')
        && $module->registerHook('actionOrderStatusPostUpdate');
}

/**
 * Function used to update your database from previous versions to the version 7.4.0,
 */
function upgradeDatabase_7_4_0()
{
    return true;
}

/**
 * Function used to update your PS configuration from previous versions to the version 7.4.0,
 */
function upgradePsConfiguration_7_4_0()
{
    if (version_compare(_PS_VERSION_, '1.7', '>')) {
        return Configuration::updateValue('AV_LIGHTWIDGET', '1')
            && Configuration::updateValue('AV_MULTISITE', '')
                && Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUIT', '2')
                    && Configuration::updateValue('AV_NBOFREVIEWS', '10')
                        && Configuration::updateValue('AV_NBOPRODUCTS', '')
                            && Configuration::updateValue('AV_ORDER_UPDATE', '')
                                && Configuration::updateValue('AV_DISPLAYSTARPLIST', '0')
                                    && Configuration::updateValue('AV_EXTRA_OPTION', '2'); //hookDisplayProductButtons
    } else {
        return Configuration::updateValue('AV_LIGHTWIDGET', '1')
            && Configuration::updateValue('AV_MULTISITE', '')
                && Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUIT', '2')
                    && Configuration::updateValue('AV_NBOFREVIEWS', '10')
                        && Configuration::updateValue('AV_NBOPRODUCTS', '')
                            && Configuration::updateValue('AV_ORDER_UPDATE', '')
                                && Configuration::updateValue('AV_DISPLAYSTARPLIST', '0')
                                    && Configuration::updateValue('AV_EXTRA_OPTION', '0');  //hookExtraright
    }
}
