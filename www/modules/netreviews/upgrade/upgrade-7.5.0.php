<?php
/**
 * 2012-2018 NetReviews
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
 *  @copyright 2012-2018 NetReviews SAS
 *  @license   NetReviews
 *  @version   Release: $Revision: 7.5.0
 *  @date      16/03/2018
 *  International Registered Trademark & Property of NetReviews SAS
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
 * Function used to update your module from previous versions to the version 7.5.0,
 */

function upgrade_module_7_5_0($object)
{
    return upgradeHook_7_5_0($object) //Upgrade hook from previous versions to the version 7.5.0
        && upgradePsConfiguration_7_5_0($object) //Upgrade PS configuration from previous versions to the version 7.5.0
        && upgradeDatabase_7_5_0($object); //Upgrade database from previous versions to the version 7.5.0
}

/**
 * Function used to update your PS configuration from previous versions to the version 7.5.0,
 */
function upgradePsConfiguration_7_5_0()
{
     return Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETSITE', '1')
        && Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETSITEGLOBAL', '3')
        && Configuration::updateValue('AV_NRESPONSIVE', '0')
        && Configuration::updateValue('AV_TABSHOW', '1');
}

/**
 * Function used to update your hook from previous versions to the version 7.5.0,
 */
function upgradeHook_7_5_0()
{
      return true;
      // return $module->registerHook('displayProductButtons')
      //   && $module->registerHook('displayProductPriceBlock')
      //   && $module->registerHook('displayRightColumnProduct')
      //   && $module->registerHook('displayLeftColumnProduct')
      //   && $module->registerHook('Extra_netreviews')
      //   && $module->registerHook('Tabcontent_netreviews')
      //   && $module->registerHook('displayProductListReviews')
      //   && $module->registerHook('displayFooterProduct')
      //   && $module->registerHook('Categorystars_netreviews');
}

/**
 * Function used to update your database from previous versions to the version 7.5.0,
 */
function upgradeDatabase_7_5_0()
{
    return true;
}
