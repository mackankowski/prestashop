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

class NetReviewsModel extends ObjectModel
{
    protected $table = 'av_products_reviews';
    protected $identifier = 'id_product_av';
    public $reviews_by_page;
    public $id_order;
    public $id_shop = null;
    public $iso_lang = null;
    public function __construct()
    {
    }

    public function getProductReviews($id_product, $group_name = null, $id_shop = null, $reviews_per_page = 20, $current_page = 1, $review_filter_orderby = 'horodate_DESC', $review_filter_note = 0, $getreviews = false)
    {
        $filter = "" ;
        $multishop_condition = "" ;
        $limit = "" ;
        $start = 0; // $start = ($current_page > 1)? ($current_page-1) * $reviews_per_page : 0;
        $end = $current_page * $reviews_per_page ;
        $helfulrating ="" ;
        $note_range = array(1,2,3,4,5);
        if (in_array($review_filter_note, $note_range)) {
            $filter .= " and rate = '".$review_filter_note."'";
        }
        $a_sorting = explode("_", $review_filter_orderby);
        if ($a_sorting[0] == "horodate" || $a_sorting[0] == "rate") {
             $filter .=' ORDER BY '.$a_sorting[0].' '.$a_sorting[1];
        } elseif ($a_sorting[0] == "helpfulrating") {
            $helfulrating =", helpful-helpless as helpfulrating" ;
            $filter .=' ORDER BY '.$a_sorting[0].' '.$a_sorting[1];
        }
        if ($reviews_per_page != '0') {
            $limit .=' LIMIT '.$start.', '.$end;
        }
        $sql = 'SELECT *'.$helfulrating.' FROM '._DB_PREFIX_.'av_products_reviews WHERE ref_product = '.(int)$id_product;

        if (!empty($group_name)) {
            if (!empty($id_shop) && Shop::isFeatureActive()) {
                $av_group_conf = unserialize(Configuration::get('AV_GROUP_CONF'.$group_name, null, null, $id_shop));
            } else {
                $av_group_conf = unserialize(Configuration::get('AV_GROUP_CONF'.$group_name));
            }
            $sql .= ' and iso_lang in ("'.implode('","', $av_group_conf).'")';
        } else {
            $sql .= " and iso_lang = '0'";
        }
        if (!empty($id_shop) && Shop::isFeatureActive()) {
            $multishop_condition .= ' and (id_shop = '.$id_shop.')';
        } else {
            $multishop_condition .= ' and id_shop IN(0,1)';
        }

        if ($getreviews == true) {
            $sql = 'SELECT COUNT(ref_product) as nbreviews FROM '._DB_PREFIX_
                .'av_products_reviews WHERE ref_product = '.(int)$id_product.$multishop_condition.$filter;
        } else {
            $sql .= $multishop_condition.$filter.$limit;
        }
       // echo $sql.'<br>';
        return Db::getInstance()->ExecuteS($sql);
    }
    public function getStatsProduct($id_product, $group_name = null, $id_shop = null)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'av_products_average WHERE ref_product = '.(int)$id_product;
        if (!empty($group_name)) {
            if (!empty($id_shop) && Shop::isFeatureActive()) {
                $av_group_conf = unserialize(Configuration::get('AV_GROUP_CONF'.$group_name, null, null, $id_shop));
            } else {
                $av_group_conf = unserialize(Configuration::get('AV_GROUP_CONF'.$group_name));
            }
            $sql .= ' and iso_lang in ("'.implode('","', $av_group_conf).'")';
        } else {
            $sql .= " and iso_lang = '0'";
        }
        if (!empty($id_shop) && Shop::isFeatureActive()) {
            $sql .= ' and id_shop = '.$id_shop;
        } else {
            $sql .= ' and id_shop IN(0,1)';
        }
        return Db::getInstance()->getRow($sql);
    }

    public function export($header_colums, $id_shop = null)
    {
        $o_netreviews = new NetReviews;
        $duree = Tools::getValue('duree');
        $global_marketplaces=array(
            // Enter here the payment module name for which we will not pick the orders
            // '1' => 'priceminister' //as example
        );
        $order_statut_list =  (Tools::getValue('orderstates'))?array_map('intval', Tools::getValue('orderstates')):'';
        $order_statut_list = (!empty($order_statut_list)) ? implode(',', $order_statut_list) : null;
        if (! empty($id_shop)) {
            $file_name = Configuration::get('AV_CSVFILENAME', null, null, $id_shop);
            $delay = (Configuration::get('AV_DELAY', null, null, $id_shop)) ? Configuration::get('AV_DELAY', null, null, $id_shop) : 0;
        } else {
            $file_name = Configuration::get('AV_CSVFILENAME');
            $delay = (Configuration::get('AV_DELAY')) ? Configuration::get('AV_DELAY') : 0;
        }
        $avis_produit = Tools::getValue('productreviews');
        if (!empty($file_name)) {
            $file_path = _PS_MODULE_DIR_.'netreviews/Export_NetReviews_'.
                str_replace('/', '', Tools::stripslashes($file_name));
            if (file_exists($file_path)) {
                if (is_writable($file_path)) {
                    unlink($file_path);
                } else {
                    throw new Exception($o_netreviews->l('Writing on our server is not allowed.
                     Please assign write permissions to the folder netreviews'));
                }
            } else {
                foreach (glob(_PS_MODULE_DIR_.'netreviews/Export_NetReviews_*') as $filename_to_delete) {
                    if (is_writable($filename_to_delete)) {
                        unlink($filename_to_delete);
                    }
                }
            }
        }
        $file_name = date('d-m-Y').'-'.Tools::substr(md5(rand(0, 10000)), 1, 10).'.csv';
        $file_path = _PS_MODULE_DIR_.'netreviews/Export_NetReviews_'.$file_name;
        $duree_sql = '';
        switch ($duree) {
            case '1w':
                $duree_sql = 'INTERVAL 1 WEEK';
                break;
            case '2w':
                $duree_sql = 'INTERVAL 2 WEEK';
                break;
            case '1m':
                $duree_sql = 'INTERVAL 1 MONTH';
                break;
            case '2m':
                $duree_sql = 'INTERVAL 2 MONTH';
                break;
            case '3m':
                $duree_sql = 'INTERVAL 3 MONTH';
                break;
            case '4m':
                $duree_sql = 'INTERVAL 4 MONTH';
                break;
            case '5m':
                $duree_sql = 'INTERVAL 5 MONTH';
                break;
            case '6m':
                $duree_sql = 'INTERVAL 6 MONTH';
                break;
            case '7m':
                $duree_sql = 'INTERVAL 7 MONTH';
                break;
            case '8m':
                $duree_sql = 'INTERVAL 8 MONTH';
                break;
            case '9m':
                $duree_sql = 'INTERVAL 9 MONTH';
                break;
            case '10m':
                $duree_sql = 'INTERVAL 10 MONTH';
                break;
            case '11m':
                $duree_sql = 'INTERVAL 11 MONTH';
                break;
            case '12m':
                $duree_sql = 'INTERVAL 12 MONTH';
                break;
            default:
                $duree_sql = 'INTERVAL 1 WEEK';
                break;
        }
        $all_orders = array();
        // Get orders with choosen date interval
        $where_id_shop = (! empty($id_shop)) ?  'AND o.id_shop = '.(int)$id_shop  : '';
        $select_id_shop = (! empty($id_shop)) ?  ', o.id_shop' : '';

        if (version_compare(_PS_VERSION_, '1.5', '>=')) {
            $where_id_state = (! empty($order_statut_list)) ?  ' AND o.current_state IN ('.$order_statut_list.')'  : '';
            $select_id_state = (! empty($order_statut_list)) ?  ', o.current_state' : '';

            $qry_sql = '    SELECT o.module, lg.iso_code, o.id_order, o.total_paid, o.id_customer, o.date_add,'.
                ' c.firstname, c.lastname, c.email, o.id_lang '.$select_id_shop.$select_id_state.'
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'customer c ON o.id_customer = c.id_customer
                            LEFT JOIN '._DB_PREFIX_.'lang lg ON o.id_lang = lg.id_lang
                            WHERE (TO_DAYS(DATE_ADD(o.date_add,'.$duree_sql.')) - TO_DAYS(NOW())) >= 0
                            '.$where_id_shop.$where_id_state;
        } else {
            $qry_sql = ' SELECT o.module, lg.iso_code, o.id_order, o.total_paid, o.id_customer, o.date_add,c.firstname, c.lastname, c.email, o.id_lang, (SELECT `id_order_state` FROM '._DB_PREFIX_.'order_history WHERE `id_order`=o.id_order GROUP BY `id_order_state` asc ORDER BY `'._DB_PREFIX_.'order_history`.`date_add` DESC limit 0,1) AS current_state '.$select_id_shop.'
                            FROM '._DB_PREFIX_.'orders o
                            LEFT JOIN '._DB_PREFIX_.'customer c ON o.id_customer = c.id_customer
                            LEFT JOIN '._DB_PREFIX_.'lang lg ON o.id_lang = lg.id_lang
                            WHERE (TO_DAYS(DATE_ADD(o.date_add,'.$duree_sql.')) - TO_DAYS(NOW())) >= 0
                            '.$where_id_shop;
        }
        $item_list = Db::getInstance()->ExecuteS($qry_sql);
        foreach ($item_list as $item) {
            $marketplaceKey = array_search($item['module'], $global_marketplaces);
            if (!empty($marketplaceKey)) {
                $marketplace = $global_marketplaces[$marketplaceKey];
            } else {
                $marketplace = "non";
            }
            $current_state_item = (isset($item['current_state']) && !empty($item['current_state']))?$item['current_state']:"";
            $all_orders[$item['id_order']] = array(
                'TYPE_PAIEMENT' => $marketplace,
                'ID_ORDER'     => $item['id_order'],
                'MONTANT_COMMANDE'     => $item['total_paid'],
                'DATE_ORDER'   => date('d/m/Y', strtotime($item['date_add'])),
                'ID_CUSTOMER'  => array(
                'ID_CUSTOMER'  => $item['id_customer'],
                'FIRST_NAME'   => $item['firstname'],
                'LAST_NAME'    => $item['lastname'],
                'EMAIL'        => $item['email']
            ),
                'EMAIL_CLIENT' => '',
                'NOM_CLIENT'   => '',
                'ORDER_STATE'  => $current_state_item,
                'ISO_LANG'  => $item['iso_code'],
                'PRODUCTS'     => array()
            );
            // $qry_sql = 'SELECT id_order, product_id FROM '._DB_PREFIX_.
            //     'order_detail WHERE id_order = '.(int)$item['id_order'];
            // $product_list = Db::getInstance()->ExecuteS($qry_sql);
            $shop_name = Configuration::get('PS_SHOP_NAME');
            $o_order = new Order($item['id_order']);
            $products_in_order = $o_order->getProducts();
            foreach ($products_in_order as $product_element) {
                // $o_product = new Product($product['product_id'], false, (int)$item['id_lang']);
                if(isset($product_element['id_manufacturer']) && !empty($product_element['id_manufacturer'])){
                $o_manufacturer = new Manufacturer($product_element['id_manufacturer']);
                $brand_name =  $o_manufacturer->name;
                } 
                $upc =  (isset($product_element['upc']) && !empty($product_element['upc']))?$product_element['upc']:'';
                $ean13 =  (isset($product_element['ean13']) && !empty($product_element['ean13']))?$product_element['ean13']:'';
                $sku = (isset($product_element['reference']) && !empty($product_element['reference']))?$product_element['reference']:"";
                $mpn = (isset($product_element['supplier_reference']) && !empty($product_element['supplier_reference']))?$product_element['supplier_reference']:"";
                $uniquegoogleshoppinginfo = Configuration::get('AV_PRODUCTUNIGINFO', null, null, $id_shop);
                if($uniquegoogleshoppinginfo == 1){
                    $product_upc = $upc;
                    $product_ean13 = $ean13;
                    $product_sku = $sku;
                    $product_mpn = $mpn;
                }else{
                    $product_upc = (isset($product_element['product_upc']) && !empty($product_element['product_upc']))?$product_element['product_upc']:$upc;
                    $product_ean13 = (isset($product_element['product_ean13']) && !empty($product_element['product_ean13']))?$product_element['product_ean13']:$ean13;
                    $product_sku = (isset($product_element['product_reference']) && !empty($product_element['product_reference']))?$product_element['product_reference']:$sku;
                    $product_mpn = (isset($product_element['product_supplier_reference']) && !empty($product_element['product_supplier_reference']))?$product_element['product_supplier_reference']:$mpn;
                }
                $product_title_image_id = (isset($product_element['image']) && !empty($product_element['image']))?$product_element['image']->id_image:"";
                $product_category = "";
                if(isset($product_element['id_category_default']) && !empty($product_element['id_category_default'])){
                    $product_category_create = new Category($product_element['id_category_default'], (int)$item['id_lang']);
                    $product_category = $product_category_create->name;
                }
                $all_orders[$product_element['id_order']]['PRODUCTS'][] = array(
                    'ID_PRODUCT' => $product_element['product_id'],
                    'NOM_PRODUCT' => $product_element['product_name'],
                    'SKU_PRODUCT' => $product_sku,
                    'EAN13_PRODUCT' => $product_ean13,
                    'UPC_PRODUCT' =>  $product_upc,
                    'MPN_PRODUCT' => $product_mpn,
                    'BRAND_NAME_PRODUCT' => (isset($brand_name) && !empty($brand_name))? $brand_name:$shop_name,
                    'PRICE_PRODUCT_UNITY' => $product_element['product_price'],
                    'URL_PRODUCT' => self::getUrlProduct($product_element['product_id'],(int)$item['id_lang']),
                    'URL_IMAGE_PRODUCT' => self::getUrlImageProduct($product_element['product_id'],$product_title_image_id,(int)$item['id_lang']),
                    'CAT_PRODUCT' => $product_category,
                );
            }
        }
        $order_statut_list = OrderState::getOrderStates((int)Configuration::get('PS_LANG_DEFAULT'));
        $order_statut_indice = array();
        foreach ((array)$order_statut_list as $value) {
            $order_statut_indice[$value['id_order_state']] = utf8_decode($value['name']);
        }

        if (count($all_orders) > 0) {
            if ($csv = @fopen($file_path, 'w')) {
                fwrite($csv, $header_colums);
                foreach ($all_orders as $order) {
                    $o_order = new Order($order['ID_ORDER']);
                    $o_carrier = new Carrier((int)$o_order->id_carrier);
                    $count_products = count($order['PRODUCTS']);
                    $order_reference = (isset($o_order->reference) && !empty($o_order->reference))?$o_order->reference:"";
                    $current_state = (isset($o_order->current_state) && !empty($o_order->current_state))?$o_order->current_state:"";
                    if ($avis_produit == 1 && $count_products > 0) {
                        for ($i = 0; $i < $count_products; $i++) {
                            // $o_product = new Product($order['PRODUCTS'][$i]['ID_PRODUCT'], false, (int)Configuration::get('PS_LANG_DEFAULT'));
                            $line   = array();//reset the line
                            $line[] = $order['ID_ORDER'];
                            $line[] = $order_reference;
                            $line[] = $order['MONTANT_COMMANDE'];
                            $line[] = $order['ID_CUSTOMER']['EMAIL'];
                            $line[] = utf8_decode($order['ID_CUSTOMER']['FIRST_NAME']);
                            $line[] = utf8_decode($order['ID_CUSTOMER']['LAST_NAME']);
                            $line[] = $order['DATE_ORDER'];
                            $line[] = utf8_decode($o_order->payment);//  Le type de paiement
                            $line[] = utf8_decode($o_carrier->name);
                            $line[] = $delay;
                            $line[] = $order['PRODUCTS'][$i]['ID_PRODUCT'];
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['CAT_PRODUCT']);
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['NOM_PRODUCT']);
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['EAN13_PRODUCT']);
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['UPC_PRODUCT']);
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['MPN_PRODUCT']);
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['BRAND_NAME_PRODUCT']);
                            //Url fiche product
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['URL_PRODUCT']);
                            //Url image fiche product
                            $line[] = utf8_decode($order['PRODUCTS'][$i]['URL_IMAGE_PRODUCT']);
                            $num_state = $current_state; // $order['ORDER_STATE'];
                            $line[] = $num_state; //Order current state ID
                            $line[] = (isset($num_state) && !empty($num_state))?$order_statut_indice[$num_state]:""; //Etat de la commande
                            $line[] = $order['ISO_LANG']; //Order lang
                            if (!empty($id_shop)) {
                                $line[] = $id_shop;
                            }
                            fwrite($csv, self::generateCsvLine($line));
                        }
                    } else {
                        $line   = array();//reset the line
                        $line[] = $order['ID_ORDER'];
                        $line[] = $order_reference;
                        $line[] = $order['MONTANT_COMMANDE'];
                        $line[] = $order['ID_CUSTOMER']['EMAIL'];
                        $line[] = utf8_decode($order['ID_CUSTOMER']['FIRST_NAME']);
                        $line[] = utf8_decode($order['ID_CUSTOMER']['LAST_NAME']);
                        $line[] = $order['DATE_ORDER'];
                        $line[] = utf8_decode($o_order->payment);//  Le type de paiement
                        $line[] = utf8_decode($o_carrier->name);
                        $line[] = $delay;
                        $line[] = ''; //id product
                        $line[] = ''; // Product category
                        $line[] = ''; // NOM_PRODUCT
                        $line[] = ''; // EAN13_PRODUCT
                        $line[] = ''; // UPC_PRODUCT
                        $line[] = ''; // MPN_PRODUCT
                        $line[] = ''; // BRAND_NAME_PRODUCT
                        $line[] = ''; // URL_PRODUCT
                        $line[] = ''; //URL_IMAGE_PRODUCT
                        $num_state = $current_state; // $order['ORDER_STATE'];
                        $line[] = $num_state; //Order state ID
                        $line[] = (isset($num_state) && !empty($num_state))?$order_statut_indice[$num_state]:""; //Etat de la commande
                        $line[] = $order['ISO_LANG']; //Order lang
                        if (! empty($id_shop)) {
                            $line[] = $id_shop;
                        }
                        fwrite($csv, self::generateCsvLine($line));
                    }
                }
                fclose($csv);
                if (file_exists($file_path)) {
                    Configuration::updateValue('AV_CSVFILENAME', $file_name);
                    return array($file_name, count($all_orders), $file_path);
                } else {
                    throw new Exception($o_netreviews->l('Unable to read/write export file'));
                }
            } else {
                throw new Exception($o_netreviews->l('Unable to read/write export file'));
            }
        } else {
            throw new Exception($o_netreviews->l('No order to export'));
        }
    }


    public function exportApi($duree, $statut)
    {
        $o_netreviews = new NetReviews;
        $duree = Tools::getValue('duree');
        $global_marketplaces=array(
            // Enter here the payment module name for which we will not pick the orders
            // '1' => 'priceminister' //as example
        );
        $order_statut_list = array_map('intval', $statut);
        $order_statut_list = (!empty($order_statut_list)) ? implode(',', $order_statut_list) : null;
        $file_name = Configuration::get('AV_CSVFILENAME');
        if (!empty($file_name)) {
            $file_path = _PS_MODULE_DIR_.'netreviews/Export_NetReviews_'.str_replace('/', '', Tools::stripslashes($file_name));
            if (file_exists($file_path)) {
                if (is_writable($file_path)) {
                    unlink($file_path);
                } else {
                    throw new Exception($o_netreviews->l('Writing on our server is not allowed.
                     Please assign write permissions to the folder netreviews'));
                }
            } else {
                foreach (glob(_PS_MODULE_DIR_.'netreviews/Export_NetReviews_*') as $filename_to_delete) {
                    if (is_writable($filename_to_delete)) {
                        unlink($filename_to_delete);
                    }
                }
            }
        }
        $file_name = date('d-m-Y').'-'.Tools::substr(md5(rand(0, 10000)), 1, 10).'.csv';
        $file_path = _PS_MODULE_DIR_.'netreviews/Export_NetReviews_'.$file_name;
        $duree_sql = '';
        switch ($duree) {
            case '1w':
                $duree_sql = 'INTERVAL 1 WEEK';
                break;
            case '2w':
                $duree_sql = 'INTERVAL 2 WEEK';
                break;
            case '1m':
                $duree_sql = 'INTERVAL 1 MONTH';
                break;
            case '2m':
                $duree_sql = 'INTERVAL 2 MONTH';
                break;
            case '3m':
                $duree_sql = 'INTERVAL 3 MONTH';
                break;
            case '4m':
                $duree_sql = 'INTERVAL 4 MONTH';
                break;
            case '5m':
                $duree_sql = 'INTERVAL 5 MONTH';
                break;
            case '6m':
                $duree_sql = 'INTERVAL 6 MONTH';
                break;
            case '7m':
                $duree_sql = 'INTERVAL 7 MONTH';
                break;
            case '8m':
                $duree_sql = 'INTERVAL 8 MONTH';
                break;
            case '9m':
                $duree_sql = 'INTERVAL 9 MONTH';
                break;
            case '10m':
                $duree_sql = 'INTERVAL 10 MONTH';
                break;
            case '11m':
                $duree_sql = 'INTERVAL 11 MONTH';
                break;
            case '12m':
                $duree_sql = 'INTERVAL 12 MONTH';
                break;
            default:
                $duree_sql = 'INTERVAL 1 WEEK';
                break;
        }
        $all_orders = array();
        // Get orders with choosen date interval
        $where_id_state = (! empty($order_statut_list)) ?  ' AND o.current_state IN ('.$order_statut_list.')'  : '';
        $select_id_state = (! empty($order_statut_list)) ?  ', o.current_state' : '';
        $qry_sql = '    SELECT o.module, lg.iso_code, o.id_order, o.total_paid, o.id_customer, o.date_add, c.firstname, c.lastname, c.email,  o.id_lang '
            .$select_id_state.'
                        FROM '._DB_PREFIX_.'orders o
                        LEFT JOIN '._DB_PREFIX_.'customer c ON o.id_customer = c.id_customer
                        LEFT JOIN '._DB_PREFIX_.'lang lg ON o.id_lang = lg.id_lang
                        WHERE (TO_DAYS(DATE_ADD(o.date_add,'.$duree_sql.')) - TO_DAYS(NOW())) >= 0
                        '.$where_id_state;
        $item_list = Db::getInstance()->ExecuteS($qry_sql);
        foreach ($item_list as $item) {
            $marketplaceKey = array_search($item['module'], $global_marketplaces);
            if (!empty($marketplaceKey)) {
                $marketplace = $global_marketplaces[$marketplaceKey];
            } else {
                $marketplace = "non";
            }
            $all_orders[$item['id_order']] = array(
                'TYPE_PAIEMENT' => $marketplace,
                'ID_ORDER'     => $item['id_order'],
                'MONTANT_COMMANDE'     => $item['total_paid'],
                'DATE_ORDER'   => date('d/m/Y', strtotime($item['date_add'])),
                'ID_CUSTOMER'  => array(
                'ID_CUSTOMER'  => $item['id_customer'],
                'FIRST_NAME'   => $item['firstname'],
                'LAST_NAME'    => $item['lastname'],
                'EMAIL'        => $item['email']
            ),
                'EMAIL_CLIENT' => '',
                'NOM_CLIENT'   => '',
                'ORDER_STATE'  => $item['current_state'],
                'ISO_LANG'  => $item['iso_code'],
                'PRODUCTS'     => array()
            );
            $qry_sql = 'SELECT id_order, product_id FROM '._DB_PREFIX_.
                'order_detail WHERE id_order = '.(int)$item['id_order'];
            $product_list = Db::getInstance()->ExecuteS($qry_sql);
            foreach ($product_list as $product) {
                $o_product = new Product($product['product_id'], false, (int)$item['id_lang']);
                $o_manufacturer = new Manufacturer($o_product->id_manufacturer);
                $array_url = self::getUrlsProduct($o_product->id, $item['id_lang']);
                $all_orders[$product['id_order']]['PRODUCTS'][] = array(
                    'ID_PRODUCT' => $o_product->id,
                    'NOM_PRODUCT' => $o_product->name,
                    'EAN13_PRODUCT' => $o_product->ean13,
                    'UPC_PRODUCT' => $o_product->upc,
                    'MPN_PRODUCT' => $o_product->supplier_reference,
                    'BRAND_NAME_PRODUCT' => $o_manufacturer->name,
                    'URL_PRODUCT' => $array_url['url_product'],
                    'URL_IMAGE_PRODUCT' => $array_url['url_image_product'],
                );
            }
        }
        if (count($all_orders) > 0) {
            return $all_orders;
        }
    }

    public function getReviewImages($id_avis)
    {
        $query = 'SELECT id_eav, id_avis, value, CONCAT(id_eav, "-", id_avis) As id_media FROM ' . _DB_PREFIX_ . 'av_eav'
               . ' WHERE id_avis = "' . $id_avis . '"';
        return Db::getInstance() -> executeS($query);
    }

    public function saveOrderToRequest()
    {
        $qry_order = 'SELECT id_order FROM '._DB_PREFIX_.'av_orders WHERE id_order = '.$this->id_order;
        $this->id_shop = (!empty($this->id_shop)) ? $this->id_shop : 0;
        $this->iso_lang = (!empty($this->iso_lang)) ? $this->iso_lang : '0';
        if (!Db::getInstance()->getRow($qry_order, false)) {
            //Save order only if not exist in table
            Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'av_orders
                                                    (id_order, id_shop, iso_lang)
                                                    VALUES ('.$this->id_order.',
                                                        '.$this->id_shop.',
                                                        "'.$this->iso_lang.'"
                                                    )');
        }
    }
    public function getTotalReviews()
    {
        return Db::getInstance()->getRow('SELECT count(*) as nb_reviews FROM '._DB_PREFIX_.'av_products_reviews');
    }
    public function getTotalReviewsAverage()
    {
        return Db::getInstance()->getRow('SELECT count(*) as nb_reviews_average FROM '._DB_PREFIX_.'av_products_average');
    }
    public function getTotalOrders()
    {
        $results = array();
        $results['all'] = Db::getInstance()->getRow('SELECT count(*) as nb FROM '._DB_PREFIX_.'av_orders');
        $results['flagged'] = Db::getInstance()->getRow('SELECT count(*) as nb FROM '._DB_PREFIX_.'av_orders WHERE flag_get IS NULL');
        $results['not_flagged'] = Db::getInstance()->getRow('SELECT count(*) as nb FROM '._DB_PREFIX_.'av_orders WHERE flag_get IS NOT NULL');
        return $results;
    }
    public static function getUrlProduct($product_id, $lang_id)
    {
        $product_exist = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'product WHERE id_product ='.(int)$product_id);
        if ($product_exist) {
            $o_product = new Product($product_id, false, $lang_id);
            $protocol_link = (Configuration::get('PS_SSL_ENABLED') || self::avUsingSecureMode()) ? 'https://' : 'http://';
            $use_ssl = (Configuration::get('PS_SSL_ENABLED') || self::avUsingSecureMode()) ? true : false;
            $protocol_content = ($use_ssl) ? 'https://' : 'http://';
            $link = new Link($protocol_link, $protocol_content);
            $url_product = $link->getProductLink($o_product, null, null, null, $lang_id);
            return $url_product;
        }
    }

        public static function getUrlImageProduct($product_id, $id_image, $lang_id)
    {
        $product_exist = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'product WHERE id_product ='.(int)$product_id);
        if ($product_exist) {
            $o_product = new Product($product_id, false, $lang_id);
            $protocol_link = (Configuration::get('PS_SSL_ENABLED') || self::avUsingSecureMode()) ? 'https://' : 'http://';
            $use_ssl = (Configuration::get('PS_SSL_ENABLED') || self::avUsingSecureMode()) ? true : false;
            $protocol_content = ($use_ssl) ? 'https://' : 'http://';
            $link = new Link($protocol_link, $protocol_content);
            $getidimage = Image::getCover($product_id);
            $id_cover_image = (isset($id_image) && !empty($id_image))?$id_image:$getidimage['id_image'];
            if ((version_compare(_PS_VERSION_, '1.5', '>='))) {
                $img_type_chosen = (version_compare(_PS_VERSION_, '1.7', '>='))? 'large':'thickbox';
                $img_type = (version_compare(_PS_VERSION_, '1.7', '>='))? ImageType::getFormattedName($img_type_chosen):ImageType::getFormatedName($img_type_chosen);
                $image_path = $link->getImageLink($o_product->link_rewrite, $id_cover_image, $img_type);
            } elseif ((version_compare(_PS_VERSION_, '1.4', '>='))) {
                $image_path = $link->getImageLink($o_product->link_rewrite, $product_id.'-'.$id_cover_image, 'large');//thickbox
            } else { //1.3
                $image_path = _PS_BASE_URL_.$link->getImageLink($o_product->link_rewrite, $product_id.'-'.$id_cover_image, 'large');//thickbox
            }
            return $image_path;
        }
    }
    private static function generateCsvLine($list)
    {
        foreach ($list as &$l) {
            $l = ''.addslashes($l).'';
        }
        return implode(';', $list)."\r\n";
    }
    public static function acEncodeBase64($s_data)
    {
        $s_base64 = base64_encode($s_data);
        return strtr($s_base64, '+/', '-_');
    }
    public static function acDecodeBase64($s_data)
    {
        $s_base64 = strtr($s_data, '-_', '+/');
        return base64_decode($s_base64);
    }

    public static function acDecodeBase64SetP($s_data)
    {
        $s_base64 = strtr($s_data, '-_', '+/');
        $s_base64 = urldecode($s_base64);
        return base64_decode($s_base64);
    }

    public static function avJsonEncode($codes)
    {
        if (version_compare(_PS_VERSION_, '1.4', '<')) {
            return json_encode($codes);
        } else {
            return Tools::jsonEncode($codes);
        }
    }

    public static function avJsonDecode($codes)
    {
        if (version_compare(_PS_VERSION_, '1.4', '<')) {
            return json_decode($codes);
        } else {
            return Tools::jsonDecode($codes);
        }
    }

    public static function tplFileExist($file_name)
    {
        $tpl_file = _PS_THEME_DIR_.'modules/netreviews/views/templates/hook/'.$file_name;
        $override_theme_file = file_exists($tpl_file);
        if ($override_theme_file) {
            return $tpl_file;
        } else {
            return _PS_ROOT_DIR_.'/modules/netreviews/views/templates/hook/'.$file_name;
        }
    }
    
    public static function toUTF8($text)
    {
        /**
       * Function \ForceUTF8\Encoding::toUTF8
       *
       * This function leaves UTF8 characters alone, while converting almost all non-UTF8 to UTF8.
       *
       * It assumes that the encoding of the original string is either Windows-1252 or ISO 8859-1.
       *
       * It may fail to convert characters to UTF-8 if they fall into one of these scenarios:
       *
       * 1) when any of these characters:   ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞß
       *    are followed by any of these:  ("group B")
       *                                    ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶•¸¹º»¼½¾¿
       * For example:   %ABREPRESENT%C9%BB. «REPRESENTÉ»
       * The "«" (%AB) character will be converted, but the "É" followed by "»" (%C9%BB)
       * is also a valid unicode character, and will be left unchanged.
       *
       * 2) when any of these: àáâãäåæçèéêëìíîï  are followed by TWO chars from group B,
       * 3) when any of these: ðñòó  are followed by THREE chars from group B.
       *
       * @name toUTF8
       * @param string $text  Any string.
       * @return string  The same string, UTF8 encoded
       *
       */
        if (is_array($text)) {
            foreach ($text as $k => $v) {
                $text[$k] = self::toUTF8($v);
            }
            return $text;
        }

        if (!is_string($text)) {
            return $text;
        }

        $max = self::strlen($text);

        $buf = "";
        for ($i = 0; $i < $max; $i++) {
            $c1 = $text{$i};
            if ($c1>="\xc0") { //Should be converted to UTF8, if it's not UTF8 already
                $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
                $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
                $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
                if ($c1 >= "\xc0" & $c1 <= "\xdf") { //looks like 2 bytes UTF8
                    if ($c2 >= "\x80" && $c2 <= "\xbf") { //yeah, almost sure it's UTF8 already
                        $buf .= $c1 . $c2;
                        $i++;
                    } else { //not valid UTF8.  Convert it.
                        $cc1 = (chr(ord($c1) / 64) | "\xc0");
                        $cc2 = ($c1 & "\x3f") | "\x80";
                        $buf .= $cc1 . $cc2;
                    }
                } elseif ($c1 >= "\xe0" & $c1 <= "\xef") { //looks like 3 bytes UTF8
                    if ($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf") { //yeah, almost sure it's UTF8 already
                        $buf .= $c1 . $c2 . $c3;
                        $i = $i + 2;
                    } else { //not valid UTF8.  Convert it.
                        $cc1 = (chr(ord($c1) / 64) | "\xc0");
                        $cc2 = ($c1 & "\x3f") | "\x80";
                        $buf .= $cc1 . $cc2;
                    }
                } elseif ($c1 >= "\xf0" & $c1 <= "\xf7") { //looks like 4 bytes UTF8
                    if ($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf") { //yeah, almost sure it's UTF8 already
                        $buf .= $c1 . $c2 . $c3 . $c4;
                        $i = $i + 3;
                    } else { //not valid UTF8.  Convert it.
                        $cc1 = (chr(ord($c1) / 64) | "\xc0");
                        $cc2 = ($c1 & "\x3f") | "\x80";
                        $buf .= $cc1 . $cc2;
                    }
                } else { //doesn't look like UTF8, but should be converted
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = (($c1 & "\x3f") | "\x80");
                    $buf .= $cc1 . $cc2;
                }
            } elseif (($c1 & "\xc0") == "\x80") { // needs conversion
                if (isset(self::$win1252ToUtf8[ord($c1)])) { //found in Windows-1252 special cases
                    $buf .= self::$win1252ToUtf8[ord($c1)];
                } else {
                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
                    $cc2 = (($c1 & "\x3f") | "\x80");
                    $buf .= $cc1 . $cc2;
                }
            } else { // it doesn't need conversion
                $buf .= $c1;
            }
        }
        return $buf;
    }
    public static function toWin1252($text, $option = self::WITHOUT_ICONV)
    {
        if (is_array($text)) {
            foreach ($text as $k => $v) {
                $text[$k] = self::toWin1252($v, $option);
            }
            return $text;
        } elseif (is_string($text)) {
            return self::utf8_decode($text, $option);
        } else {
            return $text;
        }
    }
    public static function toISO8859($text)
    {
        return self::toWin1252($text);
    }
    public static function toLatin1($text)
    {
        return self::toWin1252($text);
    }
    public static function fixUTF8($text, $option = self::WITHOUT_ICONV)
    {
        if (is_array($text)) {
            foreach ($text as $k => $v) {
                $text[$k] = self::fixUTF8($v, $option);
            }
            return $text;
        }
        $last = '';
        while ($last <> $text) {
            $last = $text;
            $text = self::toUTF8(self::utf8_decode($text, $option));
        }
        $text = self::toUTF8(self::utf8_decode($text, $option));
        return $text;
    }
    public static function UTF8FixWin1252Chars($text)
    {
        // If you received an UTF-8 string that was converted from Windows-1252 as it was ISO8859-1
        // (ignoring Windows-1252 chars from 80 to 9F) use this function to fix it.
        // See: http://en.wikipedia.org/wiki/Windows-1252
        return str_replace(array_keys(self::$brokenUtf8ToUtf8), array_values(self::$brokenUtf8ToUtf8), $text);
    }
    public static function removeBOM($str = "")
    {
        if (Tools::substr($str, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
            $str=Tools::substr($str, 3);
        }
        return $str;
    }
    public static function strlen($text)
    {
        return (function_exists('mb_strlen') && ((int) ini_get('mbstring.func_overload')) & 2) ?
            mb_strlen($text, '8bit') : Tools::strlen($text);
    }
    public static function normalizeEncoding($encodingLabel)
    {
        $encoding = Tools::strtoupper($encodingLabel);
        $encoding = preg_replace('/[^a-zA-Z0-9\s]/', '', $encoding);
        $equivalences = array(
            'ISO88591' => 'ISO-8859-1',
            'ISO8859'  => 'ISO-8859-1',
            'ISO'      => 'ISO-8859-1',
            'LATIN1'   => 'ISO-8859-1',
            'LATIN'    => 'ISO-8859-1',
            'UTF8'     => 'UTF-8',
            'UTF'      => 'UTF-8',
            'WIN1252'  => 'ISO-8859-1',
            'WINDOWS1252' => 'ISO-8859-1'
        );
        if (empty($equivalences[$encoding])) {
            return 'UTF-8';
        }
        return $equivalences[$encoding];
    }
    public static function encode($encodingLabel, $text)
    {
        $encodingLabel = self::normalizeEncoding($encodingLabel);
        if ($encodingLabel == 'ISO-8859-1') {
            return self::toLatin1($text);
        }
        return self::toUTF8($text);
    }

    public static function l($string)
    {
        return Translate::getModuleTranslation('netreviews', $string, 'ajax-load-tab-content');
    }

    protected static function utf8_decode($text, $option)
    {
        if ($option == self::WITHOUT_ICONV || !function_exists('iconv')) {
            $o = utf8_decode(
                str_replace(array_keys(self::$utf8ToWin1252), array_values(self::$utf8ToWin1252), self::toUTF8($text))
            );
        } else {
            $o = iconv("UTF-8", "Windows-1252" . ($option == self::ICONV_TRANSLIT ? '//TRANSLIT' : ($option == self::ICONV_IGNORE ? '//IGNORE' : '')), $text);
        }
        return $o;
    }

    public static function avUsingSecureMode()
    {
        if (version_compare(_PS_VERSION_, '1.4', '<')) {
            if (isset($_SERVER['HTTPS'])) {
                return ($_SERVER['HTTPS'] == 1 || Tools::strtolower($_SERVER['HTTPS']) == 'on');
            }
            // $_SERVER['SSL'] exists only in some specific configuration
            if (isset($_SERVER['SSL'])) {
                return ($_SERVER['SSL'] == 1 || Tools::strtolower($_SERVER['SSL']) == 'on');
            }
            return false;
        } else {
            return Tools::usingSecureMode();
        }
    }

    public static function avFileGetContents($url_link)
    {
        if (version_compare(_PS_VERSION_, '1.4', '<')) {
            return file_get_contents($url_link);
        } else {
            return Tools::file_get_contents($url_link);
        }
    }
}
