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
 *  @version   Release: $Revision: 7.6.0
 *  @date      20/04/2018
 *  International Registered Trademark & Property of NetReviews SAS
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
 * Function used to update your module from previous versions to the version 7.6.0,
 */

function upgrade_module_7_6_0()
{
    return true;
}

/**
 * Function used to update your PS configuration from previous versions to the version 7.6.0,
 */
function upgradePsConfiguration_7_6_0()
{
     return true;
}

/**
 * Function used to update your hook from previous versions to the version 7.6.0,
 */
function upgradeHook_7_6_0()
{
      return true;
}

/**
 * Function used to update your database from previous versions to the version 7.6.0,
 */
function upgradeDatabase_7_6_0()
{
    return true;
}
