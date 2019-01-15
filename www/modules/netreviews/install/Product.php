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
 *  @version   Release: $Revision: 7.6.5
 *  @date      20/09/2018
 *  International Registered Trademark & Property of NetReviews SAS
 */

class Product extends ProductCore
{
    public static function getProductProperties($id_lang, $row, Context $context = null)
    {
        if (empty($context) || !isset($context)) {
            $context = Context::getContext();
        }
        $product_av = parent::getProductProperties($id_lang, $row, $context);
        $av_model = _PS_MODULE_DIR_.'netreviews/NetReviewsModel.php';
        if (!class_exists('NetReviewsModel') && file_exists($av_model)) {
            require_once($av_model);
        }
        $multisite = Configuration::get('AV_MULTISITE');
        $id_shop = (!empty($multisite))? (int)$context->shop->id :null;
        $NetReviewsModel = new NetReviewsModel;
        $product_av['l']['reviews'] = $NetReviewsModel::l("reviews");
        $product_av['l']['review'] = $NetReviewsModel::l("review");
        $product_av['av_stats'] = $NetReviewsModel->getStatsProduct($product_av['id_product'], null, $id_shop);
        $product_av['av_rate'] = "";
        $product_av['av_nb_reviews'] = "";
        if (!empty($product_av['av_stats']['rate'])) {
            $product_av['av_rate'] = $product_av['av_stats']['rate'];
        }
        $product_av['av_rate_percent'] = array();
        $product_av['av_rate_percent']['floor'] = floor($product_av['av_rate']) - 1;
        $product_av['av_rate_percent']['decimals'] = ($product_av['av_rate'] - floor($product_av['av_rate']))*20;
        $num_reviews = !empty($product_av['av_stats']['nb_reviews']) ? $product_av['av_stats']['nb_reviews'] : 0;
        $product_av['av_nb_reviews'] = $num_reviews;
        $product_av['customized_star_color'] = (Configuration::get('AV_STARCOLOR', null, null, $id_shop))?Configuration::get('AV_STARCOLOR', null, null, $id_shop):"FFCD00"; //default #FFCD00
        return $product_av;
    }
}
