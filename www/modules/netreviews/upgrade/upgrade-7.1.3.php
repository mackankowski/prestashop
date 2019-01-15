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
*  @version   Release: $Revision: 7.1.3
*  @date      26/10/2016
*  International Registered Trademark & Property of NetReviews SAS
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 7.1.3,
 * Don't forget to create one file per version.
 */
function upgrade_module_7_1_3($module)
{
    return upgradePsConfiguration_7_1_3($module) //Upgrade PS configuration from previous versions to the version 7.1.3
        && upgradeHook_7_1_3($module) //Upgrade hook from previous versions to the version 7.1.3
        && upgradeDatabase_7_1_3($module); //Upgrade database from previous versions to the version 7.1.3
}

/**
 * Function used to update your PS configuration from previous versions to the version 7.1.3,
 */
function upgradePsConfiguration_7_1_3()
{
    return ((Configuration::get('AVISVERIFIES_IDWEBSITE', '')) ?
            Configuration::updateValue('AV_IDWEBSITE', Configuration::get('AVISVERIFIES_IDWEBSITE', '')) :
            Configuration::updateValue('AV_IDWEBSITE', ''))
        && ((Configuration::get('AVISVERIFIES_CLESECRETE', '')) ?
            Configuration::updateValue('AV_CLESECRETE', Configuration::get('AVISVERIFIES_CLESECRETE', '')) :
            Configuration::updateValue('AV_CLESECRETE', ''))
        && ((Configuration::get('AVISVERIFIES_PROCESSINIT', '')) ?
            Configuration::updateValue('AV_PROCESSINIT', Configuration::get('AVISVERIFIES_PROCESSINIT', '')) :
            Configuration::updateValue('AV_PROCESSINIT', ''))
        && ((Configuration::get('AVISVERIFIES_ORDERSTATESCHOOSEN', '')) ?
            Configuration::updateValue('AV_ORDERSTATESCHOOSEN', Configuration::get('AVISVERIFIES_ORDERSTATESCHOOSEN', '')) :
            Configuration::updateValue('AV_ORDERSTATESCHOOSEN', ''))
        && ((Configuration::get('AVISVERIFIES_DELAY', '')) ?
            Configuration::updateValue('AV_DELAY', Configuration::get('AVISVERIFIES_DELAY', '')) :
            Configuration::updateValue('AV_DELAY', ''))
        && ((Configuration::get('AVISVERIFIES_GETPRODREVIEWS', '')) ?
            Configuration::updateValue('AV_GETPRODREVIEWS', Configuration::get('AVISVERIFIES_GETPRODREVIEWS', '')) :
            Configuration::updateValue('AV_GETPRODREVIEWS', ''))
        && ((Configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS', '')) ?
            Configuration::updateValue('AV_DISPLAYPRODREVIEWS', Configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS', '')) :
            Configuration::updateValue('AV_DISPLAYPRODREVIEWS', ''))
        && ((Configuration::get('AVISVERIFIES_CSVFILENAME', '')) ?
            Configuration::updateValue('AV_CSVFILENAME', Configuration::get('AVISVERIFIES_CSVFILENAME', '')) :
            Configuration::updateValue('AV_CSVFILENAME', 'Export_NetReviews_01-01-1970-default.csv'))
        && ((Configuration::get('AVISVERIFIES_SCRIPTFLOAT_ALLOWED', '')) ?
            Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED', Configuration::get('AVISVERIFIES_SCRIPTFLOAT_ALLOWED', '')) :
            Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED', ''))
        && ((Configuration::get('AVISVERIFIES_SCRIPTFLOAT', '')) ?
            Configuration::updateValue('AV_SCRIPTFLOAT', Configuration::get('AVISVERIFIES_SCRIPTFLOAT', '')) :
            Configuration::updateValue('AV_SCRIPTFLOAT', ''))
        && ((Configuration::get('AVISVERIFIES_SCRIPTFIXE', '')) ?
            Configuration::updateValue('AV_SCRIPTFIXE', Configuration::get('AVISVERIFIES_SCRIPTFIXE', '')) :
            Configuration::updateValue('AV_SCRIPTFIXE', ''))
        && ((Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED', '')) ?
            Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED', Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED', '')) :
            Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED', ''))
        && ((Configuration::get('AVISVERIFIES_URLCERTIFICAT', '')) ?
            Configuration::updateValue('AV_URLCERTIFICAT', Configuration::get('AVISVERIFIES_URLCERTIFICAT', '')) :
            Configuration::updateValue('AV_URLCERTIFICAT', ''))
        && ((Configuration::get('AVISVERIFIES_FORBIDDEN_EMAIL', '')) ?
            Configuration::updateValue('AV_FORBIDDEN_EMAIL', Configuration::get('AVISVERIFIES_FORBIDDEN_EMAIL', '')) :
            Configuration::updateValue('AV_FORBIDDEN_EMAIL', ''))
        && ((Configuration::get('AVISVERIFIES_CODE_LANG', '')) ?
            Configuration::updateValue('AV_CODE_LANG', Configuration::get('AVISVERIFIES_CODE_LANG', '')) :
            Configuration::updateValue('AV_CODE_LANG', ''))
        && ((Configuration::get('AVISVERIFIES_LIGHTWIDGET', '')) ?
            Configuration::updateValue('AV_LIGHTWIDGET', Configuration::get('AVISVERIFIES_LIGHTWIDGET', '')) :
            Configuration::updateValue('AV_LIGHTWIDGET', '0'))
        && ((Configuration::get('AVISVERIFIES_URLAPI', '')) ?
            Configuration::updateValue('AV_URLAPI', Configuration::get('AVISVERIFIES_URLAPI', '')) :
            Configuration::updateValue('AV_URLAPI', ''))
        && Configuration::updateValue('AV_MULTILINGUE', '0')
            && Configuration::deleteByName('AVISVERIFIES_IDWEBSITE')
                && Configuration::deleteByName('AVISVERIFIES_CLESECRETE')
                    && Configuration::deleteByName('AVISVERIFIES_PROCESSINIT')
                        && Configuration::deleteByName('AVISVERIFIES_ORDERSTATESCHOOSEN')
                            && Configuration::deleteByName('AVISVERIFIES_DELAY')
                                && Configuration::deleteByName('AVISVERIFIES_GETPRODREVIEWS')
                                    && Configuration::deleteByName('AVISVERIFIES_DISPLAYPRODREVIEWS')
                                        && Configuration::deleteByName('AVISVERIFIES_CSVFILENAME')
                                            && Configuration::deleteByName('AVISVERIFIES_SCRIPTFLOAT')
                                                && Configuration::deleteByName('AVISVERIFIES_SCRIPTFLOAT_ALLOWED')
                                                    && Configuration::deleteByName('AVISVERIFIES_SCRIPTFIXE')
                                                        && Configuration::deleteByName('AVISVERIFIES_SCRIPTFIXE_POSITION')
                                                            && Configuration::deleteByName('AVISVERIFIES_SCRIPTFIXE_ALLOWED')
                                                                && Configuration::deleteByName('AVISVERIFIES_URLCERTIFICAT')
                                                                    && Configuration::deleteByName('AVISVERIFIES_FORBIDDEN_EMAIL')
                                                                        && Configuration::deleteByName('AVISVERIFIES_LIGHTWIDGET')
                                                                            && Configuration::deleteByName('AVISVERIFIES_URLAPI')
                                                                                && Configuration::deleteByName('AVISVERIFIES_CODE_LANG');
}

/**
 * Function used to update your hook from previous versions to the version 7.1.3,
 */
function upgradeHook_7_1_3($module)
{
    if (version_compare(_PS_VERSION_, '1.5', '<')) {
        return $module->unregisterHook('orderConfirmation')
            && $module->registerHook('newOrder')
            && $module->registerHook('footer');
    } else {
        return $module->unregisterHook('displayOrderConfirmation')
            && $module->registerHook('actionValidateOrder')
            && $module->registerHook('displayFooter');
    }
}

/**
 * Function used to update your database from previous versions to the version 7.1.3,
 */
function upgradeDatabase_7_1_3($module)
{
    $query = array();

    // av_products_reviews
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_products_reviews
                CHANGE `lang` `iso_lang` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT "0";';
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_products_reviews
                ADD `id_shop` INT( 2 ) NULL DEFAULT 0;';

    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_products_reviews
                DROP PRIMARY KEY ,
                ADD PRIMARY KEY ( `id_product_av` , `iso_lang` , `id_shop` );';

    $query[] = 'UPDATE '._DB_PREFIX_.'av_products_reviews SET `iso_lang` = "0" WHERE `iso_lang` = "" ;';

    // av_products_average
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_products_average
                CHANGE `id_lang` `iso_lang` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT "0";';
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_products_average ADD `id_shop` INT( 2 ) NULL DEFAULT 0;';
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_products_average DROP PRIMARY KEY ,
                ADD PRIMARY KEY ( `ref_product`,`iso_lang`,`id_shop` );';
    $query[] = 'UPDATE '._DB_PREFIX_.'av_products_average SET `iso_lang` = "0" WHERE `iso_lang` = "" ;';


    // av_orders
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_orders
                CHANGE `id_lang_order` `iso_lang` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci
                 NULL DEFAULT "0";';
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_orders CHANGE id_shop `id_shop` INT( 2 ) NULL DEFAULT 0';
    $query[] = 'ALTER TABLE '._DB_PREFIX_.'av_orders DROP PRIMARY KEY ,
                ADD PRIMARY KEY (`id_order`,`iso_lang`,`id_shop`);';
    $query[] = 'UPDATE '._DB_PREFIX_.'av_orders SET `iso_lang` = "0" WHERE `iso_lang` = "" ;';


    foreach ($query as $sql) {
        $error = false;
        if (!Db::getInstance()->Execute($sql)) {
            Context::getContext()->controller->errors[] = sprintf($module->l('SQL ERROR : %s | Query can\'t be
             executed. Maybe, check SQL user permissions.'), $sql);
            $error = true;
        }
    }
    if (empty($error)) {
        return true;
    }
}
