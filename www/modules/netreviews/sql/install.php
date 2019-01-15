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
 *  @version   Release: $Revision: 7.6.3
 *  @date      25/07/2018
 *  International Registered Trademark & Property of NetReviews SAS
 */

$sql = array();
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'av_products_reviews;';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'av_products_average;';
$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'av_orders;';
$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'av_products_reviews (
              `id_product_av` varchar(36) NOT NULL,
              `ref_product` varchar(20) NOT NULL,
              `rate` varchar(5) NOT NULL,
              `review` text NOT NULL,
              `customer_name` varchar(30) NOT NULL,
              `horodate` text NOT NULL,
              `horodate_order` text NOT NULL,
              `discussion` text NULL,
              `helpful` int(7) DEFAULT 0,
              `helpless` int(7) DEFAULT 0,
              `media_full` text NULL,
              `iso_lang` varchar(5) DEFAULT "0",
              `id_shop` int(2) DEFAULT 0,
              PRIMARY KEY (`id_product_av`,`iso_lang`,`id_shop`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'av_products_average (
              `id_product_av` varchar(36) NOT NULL,
              `ref_product` varchar(20) NOT NULL,
              `rate` varchar(5) NOT NULL,
              `nb_reviews` int(10) NOT NULL,
              `horodate_update` text NOT NULL,
              `iso_lang` varchar(5) DEFAULT "0",
              `id_shop` int(2) DEFAULT 0,
              PRIMARY KEY (`ref_product`,`iso_lang`,`id_shop`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'av_orders (
              `id_order` int(11) NOT NULL,
              `id_shop` int(2) DEFAULT 0,
              `flag_get` int(2) DEFAULT NULL,
              `horodate_get` varchar(25) DEFAULT NULL,
              `id_order_state` int(5) DEFAULT NULL,
              `iso_lang` varchar(5) DEFAULT "0",
              `horodate_now` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id_order`,`iso_lang`,`id_shop`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return $query;
    }
}
