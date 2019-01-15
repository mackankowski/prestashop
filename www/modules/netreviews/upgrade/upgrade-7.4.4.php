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
 *  @version   Release: $Revision: 7.4.4
 *  @date      26/01/2018
 *  International Registered Trademark & Property of NetReviews SAS
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 7.4.4,
 */

function upgrade_module_7_4_4($object)
{
    return upgradeHook_7_4_4($object) //Upgrade hook from previous versions to the version 7.4.4
        && upgradePsConfiguration_7_4_4($object) //Upgrade PS configuration from previous versions to the version 7.4.4
        && upgradeDatabase_7_4_4($object); //Upgrade database from previous versions to the version 7.4.4
}

/**
 * Function used to update your PS configuration from previous versions to the version 7.4.4,
 */
function upgradePsConfiguration_7_4_4()
{
    return true;
}

/**
 * Function used to update your hook from previous versions to the version 7.4.4,
 */
function upgradeHook_7_4_4()
{
    return true;
}

/**
 * Function used to update your database from previous versions to the version 7.4.4,
 */
function upgradeDatabase_7_4_4($module)
{
    $error           = false;
    $orderdate_added = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'av_products_reviews');
    if (is_array($orderdate_added) && !array_key_exists('horodate_order', $orderdate_added)) {
        $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'av_products_reviews` 
                ADD `horodate_order` TEXT NOT NULL AFTER `horodate`';
        if (!Db::getInstance()->Execute($sql)) {
            Context::getContext()->controller->errors[] = sprintf($module->l('SQL ERROR : %s | Query can\'t be
             executed. Maybe, check SQL user permissions.'), $sql);
            $error                                      = true;
        }
    }

    if (empty($error)) {
        return true;
    }
}
