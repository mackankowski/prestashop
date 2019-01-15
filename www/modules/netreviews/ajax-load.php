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

if ($_POST) {
    require(dirname(__FILE__) . '/../../config/config.inc.php');
    include(dirname(__FILE__) . '/../../init.php');
    require_once(dirname(__FILE__) . '/NetReviewsModel.php');
    $Module_attr = Module::getInstanceByName('netreviews');
    $o_av        = new NetReviewsModel();
    $id_product              = Tools::getValue('id_product');
    if (Tools::getValue('nom_group')) {
        if (strpos(Tools::getValue('nom_group'), "_") !== false) { //if find "_"
            $nom_group = Tools::getValue('nom_group');
        } else {
            $nom_group =  "_".Tools::getValue('nom_group');
        }
    } else {
         $nom_group =null;
    }
    $id_shop                 = (Tools::getValue('id_shop')) ? (int) Tools::getValue('id_shop') : null;
    $shop_name               = Configuration::get('PS_SHOP_NAME');
    $avisverifies_nb_reviews = (int) Configuration::get('AV_NBOFREVIEWS', null, null, $id_shop);
    $filter_option           = Tools::getValue('filter_option');
    $current_page            =  (int) Tools::getValue('current_page');
    $current_option_filter   = Tools::getValue('current_option_filter');
    $reviews_max_pages       =  (int) Tools::getValue('reviews_max_pages');
    $sortbynote              = (int)Tools::getValue('sortbynote');
    $local_id_website = Configuration::get('AV_IDWEBSITE'.$nom_group, null, null, $id_shop);
    $local_secure_key = Configuration::get('AV_CLESECRETE'.$nom_group, null, null, $id_shop);
    $hidehelpful = Configuration::get('AV_HELPFULHIDE', null, null, $id_shop) ? 1: 0; // 0 or null in defaut
    $hidemedia = Configuration::get('AV_MEDIAHIDE', null, null, $id_shop) ? 1: 0; // 0 or null in defaut
    $get_max_reviews = array();
    $reviews = array();
    $reviews_list = array(); //Create array with all reviews data
    $my_review    = array();
    $note_range = array(1,2,3,4,5);
    if (in_array($sortbynote, $note_range)) {
         $get_max_reviews = $o_av->getProductReviews($id_product, $nom_group, $id_shop, $avisverifies_nb_reviews, $current_page, $current_option_filter, $sortbynote, true);
         $max_reviews = $get_max_reviews[0]['nbreviews'];
         $reviews_max_pages = floor($max_reviews/$avisverifies_nb_reviews) + ($max_reviews%$avisverifies_nb_reviews>0 ?1 :0);
    }
    $reviews = $o_av->getProductReviews($id_product, $nom_group, $id_shop, $avisverifies_nb_reviews, $current_page, $current_option_filter, $sortbynote, false);
    foreach ($reviews as $review) {
        //Create variable for template engine
        $my_review['ref_produit'] = $review['ref_product'];
        $my_review['id_product_av'] = $review['id_product_av'];
        $my_review['sign'] = sha1($local_id_website.$review['id_product_av'].$local_secure_key);
        $my_review['helpful'] = $review['helpful'];
        $my_review['helpless'] = $review['helpless'];
        $my_review['rate'] = $review['rate'];
        $my_review['rate_percent'] = $review['rate']*20;
        $my_review['avis'] = html_entity_decode(urldecode($review['review']));
        // review date
        if (Tools::strlen($review['horodate']) == '10') {
            $date = new DateTime();
            $date->setTimestamp($review['horodate']);
            $my_review['horodate'] = $date->format('d/m/Y');
        } else {
            $my_review['horodate'] = date('d/m/Y', strtotime($review['horodate']));
        }
        // order date
        if (isset($review['horodate_order']) && !empty($review['horodate_order'])) {
            $review['horodate_order']    = str_replace('"', '', $review['horodate_order']);
            $my_review['horodate_order'] = date('d/m/Y', strtotime($review['horodate_order']));
        } else {
            $my_review['horodate_order'] = $my_review['horodate'];
        }
        // in case imported reviews which have lack of this info
        if (!isset($review['horodate']) || empty($review['horodate'])) {
            $my_review['horodate'] = $my_review['horodate_order'];
        }
        
        $my_review['discussion']    = array();
        //renverser le nom et le prénom
        $customer_name = explode(' ', urldecode($review['customer_name']));
        $customer_name = array_values(array_filter($customer_name));
        $customer_name = array_diff($customer_name, array("."));
        $customer_name = array_reverse($customer_name);
        $customer_name = implode(' ', $customer_name);

        $my_review['customer_name'] =  $customer_name;
        $unserialized_discussion = NetReviewsModel::avJsonDecode(NetReviewsModel::acDecodeBase64($review['discussion']), true);
        if ($unserialized_discussion) {
            foreach ($unserialized_discussion as $k_discussion => $each_discussion) {
                $each_discussion = (array)$each_discussion;
                $my_review['discussion'][$k_discussion] = array();
                if (Tools::strlen($each_discussion['horodate']) == '10') {
                    $date = new DateTime();
                    $date->setTimestamp($each_discussion['horodate']);
                    $my_review['discussion'][$k_discussion]['horodate'] = $date->format('d/m/Y');
                } else {
                    $my_review['discussion'][$k_discussion]['horodate'] = date('d/m/Y', strtotime($each_discussion['horodate']));
                }
                $my_review['discussion'][$k_discussion]['commentaire'] = $each_discussion['commentaire'];
                if ($each_discussion['origine'] == 'ecommercant') {
                    $my_review['discussion'][$k_discussion]['origine'] = $shop_name;
                } elseif ($each_discussion['origine'] == 'internaute') {
                    $my_review['discussion'][$k_discussion]['origine'] = $my_review['customer_name'];
                } else {
                    $my_review['discussion'][$k_discussion]['origine'] = $Module_attr->l('Moderator');
                }
            }
        }
        // Media infos
        $my_review['media_content'] = array();
        if (isset($review['media_full'])) {
            $review_images_result =  (array) NetReviewsModel::avJsonDecode(html_entity_decode($review['media_full']), true);
            if (isset($review_images_result) && !empty($review_images_result) && count($review_images_result) > 1) {
                foreach ($review_images_result as $k_media => $each_media) {
                    $my_review['media_content'][$k_media] = (array) $each_media;
                }
            }
        }
        array_push($reviews_list, $my_review);
    }

        $av_ajax_translation = array ();
        $ajax_tpl = 'ajax-load-tab-content.tpl';
        $use_star_format_image = Configuration::get('AV_FORMAT_IMAGE', null, null, $id_shop);
    if (version_compare(_PS_VERSION_, '1.4', '>=') && $use_star_format_image != '1') {
        $stars_file = 'avisverifies-stars-font.tpl';
        $old_lang = false;
    } else {
        $stars_file = 'avisverifies-stars-image.tpl';
        $old_lang = true;
    }
    $av_ajax_translation['a'] = $Module_attr->l('published');
    $av_ajax_translation['b'] = $Module_attr->l('the');
    $av_ajax_translation['c'] = $Module_attr->l('following an order made on');
    $av_ajax_translation['d'] = $Module_attr->l('Comment from');
    $av_ajax_translation['e'] = $Module_attr->l('Show exchanges');
    $av_ajax_translation['f'] = $Module_attr->l('Hide exchanges');
    $av_ajax_translation['g'] = $Module_attr->l('Did you find this helpful?');
    $av_ajax_translation['h'] = $Module_attr->l('Yes');
    $av_ajax_translation['i'] = $Module_attr->l('No');
    $av_ajax_translation['j'] = $Module_attr->l('More reviews...');
    $stars_dir = NetReviewsModel::tplFileExist('sub/'.$stars_file);
    $ajax_dir = NetReviewsModel::tplFileExist($ajax_tpl);
    $customized_star_color = (Configuration::get('AV_STARCOLOR', null, null, $id_shop))?Configuration::get('AV_STARCOLOR', null, null, $id_shop):"FFCD00"; //default #FFCD00
        $smarty->assign(array(
        'modules_dir' => _MODULE_DIR_,
        'stars_dir' => $stars_dir,
        'hidehelpful' =>  $hidehelpful,
        'hidemedia' =>  $hidemedia,
        'reviews' => $reviews_list,
        'current_page' => $current_page,
        'reviews_max_pages' => $reviews_max_pages,
        'old_lang' => $old_lang, //old version language variable translations
        'customized_star_color' => $customized_star_color,
        'av_ajax_translation' => $av_ajax_translation
        ));
        echo $smarty->fetch($ajax_dir);
} else {
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    Tools::redirect('Location: ../');
    exit;
}
