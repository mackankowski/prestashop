<!--
* 2012-2018 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2018 NetReviews SAS
*  @version   Release: $Revision: 7.6.5
*  @license   NetReviews
*  @date      20/09/2018
*  International Registered Trademark & Property of NetReviews SAS
-->

{if $version_ps < 1.5}
<link rel="stylesheet" href="{$base_url|escape:'htmlall':'UTF-8'}modules/netreviews/views/css/avisverifies-style-back-old.css" >
<link rel="stylesheet" href="{$base_url|escape:'htmlall':'UTF-8'}modules/netreviews/views/css/avisverifies-style-admin.css" >
{/if}
{if $version_ps < 1.6}
<div class="bootstrap">
{/if}
<div class="content bootstrap av_backoffice"> 
    <div class="row netreviews_bo_wrapper well">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <a href="#"  data-toggle="tooltip" title="{l s='Module Version' mod='netreviews'} {$version|escape:'htmlall':'UTF-8'}"><img class="av_display_center" alt="" src="{$av_path|escape:'htmlall':'UTF-8'}views/img/{l s='logo_full_en.png' mod='netreviews'}"/>
        </a>
        </div>
        <hr />
        <div class="nv_contents" >
                <div class="col-lg-8 col-sm-12 col-xs-12 ">
                    <div class="av_padding20">
                    <h1>{l s='Increase your sales through customer reviews' mod='netreviews'}</h1>
                    <p> {l s='Verified-Reviews is a trusted third party specialised in the collection,' mod='netreviews'}
                    {l s='moderation and publication of post-purchase customers reviews about a brand, products and stores.' mod='netreviews'}
                    {l s='Displaying reviews on our certificate, your product pages and store locator will improve your visibility and credibility that will attract more visitors, convert them more easily and win their loyalty.' mod='netreviews'}
                    {l s='An ethical model that will increase your sales and help your e-reputation.' mod='netreviews'}</p>

                    <p>{l s='In partnership with' mod='netreviews'} :</p>
                    <img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/prestashop_partner_logo_new.png" width="200" alt="">
                    <img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/prestashop_partner_logo_shadow.png" style="float:right;width: 225px;" alt="">
                    <p><img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/NFS_Avis-en-ligne.png" width="40" alt=""> {l s='Our services are approved by AFNOR certification' mod='netreviews'}</p>
                    </div>
                </div>
                <div  class="col-lg-4 col-sm-12 col-xs-12">
                    <div class="av_padding20">
                    <ul class="av-list-stars">
                        <li>{l s='Give your customer a voice' mod='netreviews'}</li>
                        <li>{l s='Improve your SEO with Rich Snippets' mod='netreviews'}</li>
                        <li>{l s='Boost your Adwords campaign by gaining star ratings from our partner' mod='netreviews'}<img src="{$av_path|escape:'htmlall':'UTF-8'}views/img/google-adwords.png" width="100" alt=""></li>
                        <li>{l s='Control your e-reputation' mod='netreviews'}</li>
                        <li>{l s='Increase your sales up to 25%' mod='netreviews'}</li>
                        <li>{l s='Build customer loyalty' mod='netreviews'}</li>
                    </ul>
                    </div>
                </div>
                <div  class="col-lg-12 col-sm-12 col-xs-12 av_padding20" style="text-align: center;border;border-top: 2px solid #f9f9f9;">
                    <a href="{l s='url_avisverifies_track' mod='netreviews'}" class="btn btn-lg av_display_center" style="color:white;" role="button" target="_blank">{l s='Start now' mod='netreviews'}</a>
                    <i class="av_display_center av_padding20">{l s='No commitment, free trial for 15 days' mod='netreviews'}</i>
                </div>
            </div>
            
        </div>

    </div> <!-- END row module_title-->

    
    <form method="post" action="{$url_back|escape:'htmlall':'UTF-8'}" enctype="multipart/form-data" class="form-horizontal">
    <!-- START configuration -->
    <div class="panel col-lg-12">
        <div class="panel-heading"><i class="icon-cogs"></i> {l s='Configuration' mod='netreviews'}</div>
        <div class='config panel-body' >
            <p>{l s='The Module Verified Reviews allows you to show verified product reviews on your product urls, to show the Widget Verified Reviews and to collect automatically verified customer reviews via Email after each single order.' mod='netreviews'}</p>
            <p class="alert alert-info">{l s='Attention : It is obligatory to register first on' mod='netreviews'} <a href="{l s='url_avisverifies_track' mod='netreviews'}" target="_blank">{l s='www.verified-reviews.com' mod='netreviews'}</a>
             {l s='to start your free trial period' mod='netreviews'}.
            {l s='Please check your' mod='netreviews'} <a href="{l s='url_avisverifies_track' mod='netreviews'}" target="_blank">{l s='customer area on verified-reviews.com' mod='netreviews'}</a> {l s='to see your login data' mod='netreviews'}</p>
            
                <div class="col-lg-12 col-sm-12 col-xs-12">
                <!-- If not multilangual -->
                <div class="{if $current_multilingue_checked  == 'checked'} hidden {/if} configuration_labels" id="av_configuration">
                        <div class="form-group">
                            <label class="control-label col-lg-3 col-sm-2 col-xs-12"><b>{l s='Secret Key' mod='netreviews'}</b></label>
                            <div class="col-lg-9 col-sm-10 col-xs-12">
                                <div class="form-group col-lg-6 col-sm-12 col-xs-12">
                                <input type="text" name="avisverifies_clesecrete" id="avisverifies_clesecrete" value="{$current_avisverifies_clesecrete.root|escape:'htmlall':'UTF-8'}"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-3 col-sm-2 col-xs-12"><b>{l s='ID Website' mod='netreviews'}</b></label>
                            <div class="col-lg-9 col-sm-10 col-xs-12">
                                <div class="form-group col-lg-6 col-sm-12 col-xs-12">
                                <input type="text" name="avisverifies_idwebsite" id="avisverifies_idwebsite" value="{$current_avisverifies_idwebsite.root|escape:'htmlall':'UTF-8'}"/>
                                </div>
                            </div>
                        </div>
                </div>
                    <!-- If multilangual -->
                        <div class="row {if $current_multilingue_checked  != 'checked'} hidden {/if} configuration_labels" id="av_multilanguage_configuration">
                            {foreach from=$languages key=id item=lang}
                            <div class="language col-lg-6 col-sm-12 col-xs-12">

                            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                                <div class="col-lg-1 col-sm-1 col-xs-1">
                                        <img class="img-thumbnail img_flag" src="{$base_url|escape:'htmlall':'UTF-8'}img/l/{$lang.id_lang|escape:'htmlall':'UTF-8'}.jpg" alt="{$lang.name|escape:'htmlall':'UTF-8'}"/>
                                </div>
                                 <label class="control-label namelanguage  col-lg-11 col-sm-11 col-xs-11">{$lang.name|escape:'htmlall':'UTF-8'}</label>
                            </div>

                            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                                <label class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='Secret Key' mod='netreviews'}</b></label>
                                <div class="col-lg-9 col-sm-8 col-xs-12">
                                    <div class="form-group col-lg-10 col-sm-12 col-xs-12">
                                    <input type="text" name="avisverifies_clesecrete_{$lang.iso_code|escape:'htmlall':'UTF-8'}" id="avisverifies_clesecrete_{$lang.iso_code}" value="{$current_avisverifies_clesecrete[$lang.iso_code]|escape:'htmlall':'UTF-8'}"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12 col-sm-12 col-xs-12">
                                <label class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='ID Website' mod='netreviews'}</b></label>
                                <div class="col-lg-9 col-sm-8 col-xs-12">
                                    <div class="form-group col-lg-10 col-sm-12 col-xs-12">
                                    <input type="text" name="avisverifies_idwebsite_{$lang.iso_code|escape:'htmlall':'UTF-8'}" id="avisverifies_idwebsite_{$lang.iso_code}" value="{$current_avisverifies_idwebsite[$lang.iso_code]|escape:'htmlall':'UTF-8'}"/>
                                    </div>
                                </div>
                            </div>

                                </div>
                            {/foreach}
                        </div>
 
            <div class="row">
                <div class="form-group">
                <!-- multilanguage configurations START-->
                    <label class="col-lg-3 col-sm-4 col-xs-12 control-label">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='To enable this option, your review requests will be sent in the according language' mod='netreviews'}" data-original-title=""><b> {l s='Enable the multilingual configuration' mod='netreviews'}</b></span></label>
                    <div class="col-lg-9 col-sm-8 col-xs-12">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="avisverifies_multilingue" id="avisverifies_multilingue_on" value="checked"  {if ($current_multilingue_checked eq "checked" or !$current_multilingue_checked)} checked="checked"{/if}>
                            <label for="avisverifies_multilingue_on" class="radioCheck">
                                 {l s='Yes' mod='netreviews'}
                            </label>
                            <input type="radio" name="avisverifies_multilingue" id="avisverifies_multilingue_off" value="0" {if ($current_multilingue_checked eq "0")} checked="checked"{/if}>
                            <label for="avisverifies_multilingue_off" class="radioCheck">
                                 {l s='No' mod='netreviews'}
                            </label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
            </div><!-- multilanguage configurations END-->
            
            <div class="clearfix"> </div>
        </div>

            </div>
                <div class="panel-footer">
                <button type="submit" name="submit_configuration" id="submit_configuration" class="button pointer btn btn-default pull-right">
                            <i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
                </button>
                </div>
    </div>  
        <!-- END configuration -->

    <div class="panel col-lg-12 col-sm-12 col-xs-12">
        <div class="panel-heading"><i class="icon-cogs"></i> {l s='Export my orders' mod='netreviews'}</div>
        <div class='panel-body'>
            <div class="row">
                <p class="alert alert-info">{l s='Export your recently received orders to collect immediately your first customer reviews and to show your attestation Verified Reviews.' mod='netreviews'}
                </p>
                <ul>
                    <li>{l s='Without Product Reviews : Your customers will only be asked for their reviews regarding the order (obligatory)' mod='netreviews'}</li>
                    <li>{l s='With Product Reviews : Your customers will be asked for their review regarding the order (obligatory) AND regarding the purchased products as well' mod='netreviews'}</li>
                </ul>
            </div>
            
                 <div>
                        <div class="row form-group col-lg-12 col-sm-12 col-xs-12">
                            <label for="duree" class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='Since' mod='netreviews'}</b></label>
                            <div class="col-lg-9 col-sm-8 col-xs-12">
                                <select id="duree" name="duree" class="col-lg-6 col-sm-12 col-xs-12">
                                    <option value="1w">{l s='1 week' mod='netreviews'}</option>
                                    <option value="2w">{l s='2 weeks' mod='netreviews'}</option>
                                    <option value="1m">{l s='1 month' mod='netreviews'}</option>
                                    <option value="2m">{l s='2 months' mod='netreviews'}</option>
                                    <option value="3m">{l s='3 months' mod='netreviews'}</option>
                                    <option value="4m">{l s='4 months' mod='netreviews'}</option>
                                    <option value="5m">{l s='5 months' mod='netreviews'}</option>
                                    <option value="6m">{l s='6 months' mod='netreviews'}</option>
                                    <option value="7m">{l s='7 months' mod='netreviews'}</option>
                                    <option value="8m">{l s='8 months' mod='netreviews'}</option>
                                    <option value="9m">{l s='9 months' mod='netreviews'}</option>
                                    <option value="10m">{l s='10 months' mod='netreviews'}</option>
                                    <option value="11m">{l s='11 months' mod='netreviews'}</option>
                                    <option value="12m">{l s='12 months' mod='netreviews'}</option>
                                </select>
                            </div>
                     </div>
                    
                     <div class="row form-group col-lg-12 col-sm-12 col-xs-12">
                        <label class="col-lg-3 col-sm-4 col-xs-12 control-label"><b>{l s='Collect Product Reviews' mod='netreviews'}</b></label>
                        <div class="col-lg-9 col-sm-8 col-xs-12">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input type="radio" name="productreviews" id="productreviews_on" value="1">
                                <label for="productreviews_on" class="radioCheck">
                                     {l s='Yes' mod='netreviews'}
                                </label>
                                <input type="radio" name="productreviews" id="productreviews_off" value="0" checked="checked">
                                <label for="productreviews_off" class="radioCheck">
                                     {l s='No' mod='netreviews'}
                                </label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                    </div>

            {if $version_ps > 1.4}
                    <div class="row form-group col-lg-12 col-sm-12 col-xs-12">
                        <label class="col-lg-3 col-sm-4 col-xs-12 control-label"><b>{l s='Export orders with status' mod='netreviews'}</b></label>  
                          <div class="col-lg-9 col-sm-8 col-xs-12">
                                <div class="btn_av_small">
                                    <a href="javascript:cocheToute();" class="btn btn-sm">{l s='Check all' mod='netreviews'} </a> 
                                    <a href="javascript:decocheToute();" class="btn btn-sm"> {l s='Uncheck all' mod='netreviews'}</a>
                                </div>
                                {foreach from=$order_statut_list item=state}
                                    <div class="checkbox col-sm-12">
                                      <label><input class="cbOrderstates" name="orderstates[]" type="checkbox" value="{$state.id_order_state|escape:'htmlall':'UTF-8'}"/>{$state.name|escape:'htmlall':'UTF-8'}</label>
                                    </div>
                                {/foreach}
                         </div>
                    </div>
            {/if}
                    </div> <!-- end row -->
                </div>
                <div class="panel-footer">
                <button type="submit" name="submit_export" id="submit_export"  class="button pointer btn btn-default pull-right">
                            <i class="process-icon-save"></i> {l s='Export' mod='netreviews'}
                </button>
                </div>
    </div>
    
    <div class="clearfix"> </div>
    <!-- Design START -->
    {assign var="av_star_type" value="widget"}
      <div class="panel col-lg-12 col-sm-12 col-xs-12">
            <div class="panel-heading"><i class="icon-cogs"></i>  {l s='Design' mod='netreviews'}</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-lg-3 col-sm-4 col-xs-12"><b>{l s='Choose the design of the stars on product pages' mod='netreviews'}</b></label>
                    <div class="col-lg-9 col-sm-8 col-xs-12">
                        <div class="radio">
                             <label class="showexample_stars"><input type="radio" name="avisverifies_lightwidget" id="avisverifies_lightwidget_1" value="1" {if ($current_lightwidget_checked eq '1' or  !$current_lightwidget_checked)} checked="checked" {/if}>
                              <!-- {l s='simple stars' mod='netreviews'} -->
                              {include file=$stars_dir}  
                                <div id="slide">
                                    <span itemprop="reviewCount" class="reviewCount">5</span>
                                  {l s='reviews' mod='netreviews'}
                                </div>
                             </label>
                         </div>
                    <div class="radio">
                        <label class="showexample_stars"><input type="radio" name="avisverifies_lightwidget" id="avisverifies_lightwidget_2" value="2" {if ($current_lightwidget_checked eq '2')} checked="checked" {/if}> 
                            <!-- {l s='Product reviews widget (by default)' mod='netreviews'} -->
                             <div class="netreviewsProductWidgetNew">
                               <img src="{$av_path|escape:'htmlall':'UTF-8'}/views/img/{l s='Sceau_100_en.png' mod='netreviews'}" class="netreviewsProductWidgetNewLogo"/>
                                <div class="ProductWidgetNewRatingWrapper">
                                   {include file=$stars_dir}  
                                   <div class="netreviewsProductWidgetNewRate">
                                      <span class="ratingValue">5</span>/<span class="bestRating">5</span>
                                   </div>
                                   {l s='See the reviews' mod='netreviews'} 
                                   (<span>5</span>)
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="radio">
                        <label class="showexample_stars"><input type="radio" name="avisverifies_lightwidget" id="avisverifies_lightwidget_3" value="3" {if ($current_lightwidget_checked eq '3')} checked="checked" {/if}> 
                        <!-- {l s='Product reviews widget (classic design)' mod='netreviews'} -->
                            <div class="av_product_award">
                                <div id="top">
                                     {include file=$stars_dir}  
                                   <div class="ratingText">
                                        5 {l s='reviews' mod='netreviews'}
                                   </div>
                                </div>
                                <div id="bottom" {if ($stars_image != 1 && $version_ps >= 1.4 )}style="background:#{$customized_star_color}"{/if}><p id ="AV_button">{l s='See the reviews' mod='netreviews'}</p></div>
                                <img id="sceau" src="{$av_path|escape:'htmlall':'UTF-8'}views/img/{l s='Sceau_100_en.png' mod='netreviews'}" />
                            </div>
                    </label>
                    </div>
                </div>
            </div>

<!-- if display font stars -->
             <div class="form-group {if ($stars_image == 1 || $version_ps < 1.4 )}hidden{/if}">
                <label class="control-label col-lg-3 col-sm-4 col-xs-12">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="">
                    <b>{l s='Change the color of the stars' mod='netreviews'}</b>
                    </span>
                </label>
                <label class="control-label col-lg-9 col-sm-8 col-xs-12">
                    <span class="col-lg-1 col-sm-4 col-xs-4">
                    <input type="text" class="jscolor form-control"  name="avisverifies_stars_custom_color" id="avisverifies_stars_custom_color" value="{if $customized_star_color}{$customized_star_color|escape:'htmlall':'UTF-8'} {else}ffcd00{/if}" /> 
                    </span>
                 <div class="col-lg-6">
                </div>
                </label>
            </div> 

            <div class="form-group">
                <label class="control-label col-lg-3 col-sm-4 col-xs-12">
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='The reviews do not appear ? please click here to find the solution' mod='netreviews'}" data-original-title="">
                    <a href="javascript:tabcontenthook_show();"><b>{l s='Set the number of reviews product displayed' mod='netreviews'}</b></a>
                    </span>
                </label>
                <label class="control-label col-lg-9 col-sm-8 col-xs-12">
                    <span class="col-lg-1 col-sm-4 col-xs-4">
                        <input type="text" class="numbersOnly form-control"  name="avisverifies_nb_reviews" id="avisverifies_nb_reviews" value="{if $avisverifies_nb_reviews}{$avisverifies_nb_reviews|escape:'htmlall':'UTF-8'} {else}20{/if}" />
                    </span>
                 <div class="col-lg-6">
                     <p id="show_tabcontenthook"></p>
                </div>
                </label>
            </div> 

        <!-- display light version tab_conent -->
        <div class="form-group">
                <label class="col-lg-3 col-sm-4 col-xs-12 control-label"><span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='See an example' mod='netreviews'}" data-original-title=""><a href="javascript:exampleimage_show();"><b>{l s='Display product table light version' mod='netreviews'}</b></a></span></label>
                <div class="col-lg-3 col-sm-8 col-xs-12">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="avisverifies_nresponsive" id="avisverifies_nresponsive_on" value="1"{if ($current_nresponsive_checked eq "1")} checked="checked"{/if}>
                        <label for="avisverifies_nresponsive_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                        </label>
                        <input type="radio" name="avisverifies_nresponsive" id="avisverifies_nresponsive_off" value="0"{if ($current_nresponsive_checked eq "0" or !$current_nresponsive_checked)} checked="checked"{/if}>
                        <label for="avisverifies_nresponsive_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='netreviews'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
                <div class="col-lg-6">
                     <p id="show_exampleimage"><img alt="" src="{$av_path|escape:'htmlall':'UTF-8'}views/img/example_tabcontent_light.jpg"/></p>
                </div>
            </div>  

            <!-- hide helpful -->
            <div class="form-group">
                <label class="col-lg-3 col-sm-4 col-xs-12 control-label"><span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=""><b>{l s='Hide "reviews helpful"' mod='netreviews'}</b></span></label>
                <div class="col-lg-3 col-sm-8 col-xs-12">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="avisverifies_hidehelpful" id="avisverifies_hidehelpful_on" value="1"{if ($current_hidehelpful_checked eq "1")} checked="checked"{/if}>
                        <label for="avisverifies_hidehelpful_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                        </label>
                        <input type="radio" name="avisverifies_hidehelpful" id="avisverifies_hidehelpful_off" value="0"{if ($current_hidehelpful_checked eq "0" or !$current_hidehelpful_checked)} checked="checked"{/if}>
                        <label for="avisverifies_hidehelpful_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='netreviews'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
                <div class="col-lg-6">
                </div>
            </div>  

            <!-- hide media display -->
            <div class="form-group">
                <label class="col-lg-3 col-sm-4 col-xs-12 control-label"><span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=""><b>{l s='Disable media displays in review comments' mod='netreviews'}</b></span></label>
                <div class="col-lg-3 col-sm-8 col-xs-12">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="avisverifies_hidemedia" id="avisverifies_hidemedia_on" value="1"{if ($current_hidemedia_checked eq "1")} checked="checked"{/if}>
                        <label for="avisverifies_hidemedia_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                        </label>
                        <input type="radio" name="avisverifies_hidemedia" id="avisverifies_hidemedia_off" value="0"{if ($current_hidemedia_checked eq "0" or !$current_hidemedia_checked)} checked="checked"{/if}>
                        <label for="avisverifies_hidemedia_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='netreviews'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
                <div class="col-lg-6">
                </div>
            </div>  
    {if $version_ps > 1.4}
           <!-- display category stars -->
            <div class="form-group">
                <label class="col-lg-3 col-sm-4 col-xs-12 control-label"><span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='the stars do not appear ? please click here to find the solution' mod='netreviews'}" data-original-title=""><a href="javascript:productliststars_show();"><b>{l s='Show star rating on the category listing page' mod='netreviews'}</b></a></span></label>
                <div class="col-lg-3 col-sm-8 col-xs-12">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="avisverifies_star_productlist" id="avisverifies_star_productlist_on" value="1"{if ($current_starproductlist_checked eq "1")} checked="checked"{/if}>
                        <label for="avisverifies_star_productlist_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                        </label>
                        <input type="radio" name="avisverifies_star_productlist" id="avisverifies_star_productlist_off" value="0"{if ($current_starproductlist_checked eq "0" or !$current_starproductlist_checked)} checked="checked"{/if}>
                        <label for="avisverifies_star_productlist_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='netreviews'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
                <div class="col-lg-6">
                     <p id="show_howtoaddstars"></p>
                </div>
            </div>      
    {/if}
        </div>
            <div class="panel-footer">
            <button type="submit"  name="submit_advanced" id="submit_advanced_design" class="button pointer btn btn-default pull-right">
                        <i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
            </button>
            </div>
        </div>

<!-- Design END -->

 <div class="clearfix"> </div>
    <!-- Rich snippet START -->
      <div class="panel col-lg-12 col-sm-12 col-xs-12">
            <div class="panel-heading"><i class="icon-cogs"></i>  {l s='Google Rich Snippets' mod='netreviews'}</div>
            <div class="panel-body">
       
            <div class="form-group">
                            <label class="col-lg-3 col-sm-4 col-xs-12 control-label"> <b> {l s='Enable Rich Snippets' mod='netreviews'} </b></label>
                            <div class="col-lg-9 col-sm-8 col-xs-12">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" name="netreviews_snippets_site" id="current_snippets_site_checked_on" value="1"{if ($current_snippets_site_checked eq "1")} checked="checked"{/if}>
                                    <label for="current_snippets_site_checked_on" class="radioCheck">
                                        <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                                    </label>
                                    <input type="radio" name="netreviews_snippets_site" id="current_snippets_site_checked_off" value="0"{if ($current_snippets_site_checked eq "0" or !$current_snippets_site_checked)} checked="checked"{/if}>
                                    <label for="current_snippets_site_checked_off" class="radioCheck">
                                        <i class="color_danger"></i> {l s='No' mod='netreviews'}
                                    </label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>                            
            </div>  
    
            </div>
            <div class="panel-footer">
            <button type="submit"  name="submit_advanced" id="submit_advanced_rs" class="button pointer btn btn-default pull-right">
                        <i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
            </button>
            </div>
        </div>

 <div class="clearfix"> </div>

<!-- Debug START -->
    <div class="panel col-lg-12 debugelement_last">
        <div class="panel-heading"><i class="icon-cogs"></i> 
            {l s='Debug' mod='netreviews'} ({l s='Module Version' mod='netreviews'}  {$version|escape:'htmlall':'UTF-8'})
        </div>
            
            <a data-toggle="collapse" href="#collapse1" class="label-tooltip" data-toggle="tooltip" data-html="true" data-original-title="" >
                {l s='Show the advanced options' mod='netreviews'}
            </a>

        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
                <fieldset class="form-group row">
                    <legend class="col-form-legend col-sm-2 actionadvanced">{l s='Advanced actions' mod='netreviews'}</legend>
                    <div class="col-sm-10">
                    <hr />
                        <div class="form-group col-sm-12">
                        <label class="control-label col-lg-4 col-sm-6 col-xs-12"><b>{l s='Change the position of the stars on product page' mod='netreviews'}</b></label>
                        <div class="col-lg-8 col-sm-6 col-xs-12">
                        <div class="radio col-sm-12">
                        <label><input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_0" value="0" {if ($avisverifies_extra_option eq '0' or !avisverifies_extra_option)} checked="checked" {/if}> Extraright</label>
                        </div>
                        <div class="radio col-sm-12">
                        <label><input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_1" value="1" {if ($avisverifies_extra_option eq '1')} checked="checked" {/if}> Extraleft</label>
                        </div>
                        {if $version_ps > 1.4}
                        <div class="radio col-sm-12">
                        <label><input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_2" value="2" {if ($avisverifies_extra_option eq '2')} checked="checked" {/if}> DisplayProductButtons</label>
                        </div>
                        <div class="radio col-sm-12">
                        <label>
                            <input type="radio" name="avisverifies_extra_option" id="avisverifies_extra_option_3" value="3" {if ($avisverifies_extra_option eq '3')} checked="checked" {/if}> no or 
                                <a href="javascript:extrahook_show();"><b>personalized hook</b></a><p id="show_extrahook"></p>
                            </label>
                        </div>
                        {/if}
                        </div>
                        </div>

                        <!-- display tags --> 
                <div class="form-group">
                    <label class="control-label col-lg-4 col-sm-6 col-xs-12"><span><b>{l s='Hide the tag' mod='netreviews'} (x {l s='reviews' mod='netreviews'})</b></span></label>
                    <div class="col-lg-8 col-sm-6 col-xs-12">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="avisverifies_tab_show" id="avisverifies_tab_show_on" value="0" {if ($tabshow_checked == 0)} checked="checked"{/if}>
                            <label for="avisverifies_tab_show_on" class="radioCheck">
                                <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                            </label>
                            <input type="radio" name="avisverifies_tab_show" id="avisverifies_tab_show_off" value="1" {if ( $tabshow_checked == 1 || $tabshow_checked == "" )} checked="checked"{/if}>
                            <label for="avisverifies_tab_show_off" class="radioCheck">
                                <i class="color_danger"></i> {l s='No' mod='netreviews'}
                            </label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>                          
                
                <div class="form-group">
                        <label class="control-label col-lg-4 col-sm-7 col-xs-12">
                            <span>
                                <b>{l s='Rename the tag (without translation)' mod='netreviews'}</b>
                            </span>
                        </label>
                        <label class="control-label col-lg-8 col-sm-5 col-xs-12">
                            <span class="col-lg-4 col-sm-4 col-xs-4">
                            <input type="text" class="form-control"  name="avisverifies_rename_tag" id="avisverifies_rename_tag" value="{if $avisverifies_rename_tag}{$avisverifies_rename_tag|escape:'htmlall':'UTF-8'} {else}{/if}" />
                            </span>
                            
                        </label>
                </div> 

            {if $version_ps >= 1.4}
                <div class="form-group">
                    <label class="control-label col-lg-4 col-sm-6 col-xs-12"><span><b>{l s='Display stars in image format' mod='netreviews'}</b></span></label>
                    <div class="col-lg-8 col-sm-6 col-xs-12">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="avisverifies_stars_image" id="avisverifies_stars_image_on" value="1" {if ($stars_image == 1)} checked="checked"{/if}>
                            <label for="avisverifies_stars_image_on" class="radioCheck">
                                <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                            </label>
                            <input type="radio" name="avisverifies_stars_image" id="avisverifies_stars_image_off" value="0" {if ( $stars_image == 0 || $stars_image == "" )} checked="checked"{/if}>
                            <label for="avisverifies_stars_image_off" class="radioCheck">
                                <i class="color_danger"></i> {l s='No' mod='netreviews'}
                            </label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>   
            {/if} 
                        <!-- display category stars on homepage --> 
                <div class="form-group">
                    <label class="control-label col-lg-4 col-sm-6 col-xs-12"><span><b>{l s='Hide the category stars on homepage' mod='netreviews'}</b></span></label>
                    <div class="col-lg-8 col-sm-6 col-xs-12">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="avisverifies_starshome_show" id="avisverifies_starshome_show_on" value="0" {if ($starshome_checked == 0)} checked="checked"{/if}>
                            <label for="avisverifies_starshome_show_on" class="radioCheck">
                                <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                            </label>
                            <input type="radio" name="avisverifies_starshome_show" id="avisverifies_starshome_show_off" value="1" {if ( $starshome_checked == 1 || $starshome_checked == "" )} checked="checked"{/if}>
                            <label for="avisverifies_starshome_show_off" class="radioCheck">
                                <i class="color_danger"></i> {l s='No' mod='netreviews'}
                            </label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>                          
            <hr />
                        {if ($current_snippets_site_checked eq "1")}
                             <div class="form-group">
                                    <label class="control-label col-lg-4"><b>{l s='Choose the position of the rich snippet integration on product page' mod='netreviews'}</b></label>
                                    <div class="col-lg-8">
                                        <div class="radio col-sm-12">
                                             <label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_2" value="2" {if ($current_snippets_produit_checked eq '2' or !$current_snippets_produit_checked)} checked="checked" {/if}> Extraright - AggregateRating (Default) </label>
                                         </div>
                                    <div class="radio col-sm-12">
                                        <label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_3" value="3" {if ($current_snippets_produit_checked eq '3')} checked="checked" {/if}> Extraright - Product </label>
                                    </div>
                                    {if $version_ps >= 1.5}
                                        <div class="radio col-sm-12">
                                            <label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_1" value="1" {if ($current_snippets_produit_checked eq '1')} checked="checked" {/if}> Footer - Product </label>
                                        </div>
                                    {/if}
                                    <div class="radio col-sm-12">
                                        <label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_4" value="4" {if ($current_snippets_produit_checked eq '4')} checked="checked" {/if}> Tabcontent - AggregateRating </label>
                                    </div>
                                    <div class="radio col-sm-12">
                                        <label><input type="radio" name="netreviews_snippets_produit" id="current_snippets_produit_checked_5" value="5" {if ($current_snippets_produit_checked eq '5')} checked="checked" {/if}> Tabcontent - Product </label>
                                    </div>
                                </div>
                            </div>
                    
                    <!-- Global Rich Snippets/category Rich Snippets -->
                             <div class="form-group">
                                    <label class="control-label col-lg-4"><b>{l s='Choose the format of rich snippets' mod='netreviews'}</b></label>
                                    <div class="col-lg-8">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="netreviews_snippets_website_global" id="current_snippets_website_global_checked_1" value="1" {if ($current_snippets_website_global_checked eq '1')} checked="checked" {/if}> 
                                            Microdata 
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="netreviews_snippets_website_global" id="current_snippets_website_global_checked_2" value="2" {if ($current_snippets_website_global_checked eq '2')} checked="checked" {/if}> JSON-LD
                                        </label>
                                    </div>
                                <!--     <div class="radio">
                                        <label>
                                            <input type="radio" name="netreviews_snippets_website_global" id="current_snippets_website_global_checked_3" value="3" {if (!$current_snippets_website_global_checked || $current_snippets_website_global_checked eq '3')} checked="checked" {/if}> Microdata - Review-aggregate
                                        </label>
                                    </div> -->
                                </div>
                            </div>
                            <hr />
                        {/if}

                        <!-- Google shopping use parent information for google shopping for the case of product combinations -->
                             <div class="form-group">
                                    <label class="control-label col-lg-4"><b>{l s='Use unique EAN UPC MPN SKU for product combinations' mod='netreviews'}</b></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" name="avisverifies_productuniqueginfo" id="avisverifies_productuniqueginfo_on" value="1" {if ($productuniqueginfo_checked == 1)} checked="checked"{/if}>
                                    <label for="avisverifies_productuniqueginfo_on" class="radioCheck">
                                        <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                                    </label>
                                    <input type="radio" name="avisverifies_productuniqueginfo" id="avisverifies_productuniqueginfo_off" value="0" {if ( $productuniqueginfo_checked == 0 || $productuniqueginfo_checked == "" )} checked="checked"{/if}>
                                    <label for="avisverifies_productuniqueginfo_off" class="radioCheck">
                                        <i class="color_danger"></i> {l s='No' mod='netreviews'}
                                    </label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="control-label col-lg-4 col-sm-7 col-xs-12">
                            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="">
                                <b>{l s='Set the number of products in one review request' mod='netreviews'}</b>
                            </span>
                        </label>
                        <label class="control-label col-lg-8 col-sm-5 col-xs-12">
                            <span class="col-lg-1 col-sm-4 col-xs-4">
                            <input type="text" class="form-control numbersOnly"  name="avisverifies_nb_products" id="avisverifies_nb_products" value="{if $avisverifies_nb_products}{$avisverifies_nb_products|escape:'htmlall':'UTF-8'} {else}{/if}" />
                            </span>
                        </label>
                        </div> 

                        <div class="form-group">
                        <label class="control-label col-lg-4 col-sm-7 col-xs-12">
                            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{l s='If you do not want to send review requests to free products, please mark 0' mod='netreviews'}" data-original-title="">
                                <b>{l s='Set minimum price for review request' mod='netreviews'}</b>
                            </span>
                        </label>
                        <label class="control-label col-lg-8 col-sm-5 col-xs-12">
                            <span class="col-lg-1 col-sm-4 col-xs-4">
                            <input type="text" class="form-control numbersOnly"  name="avisverifies_amount_min_products" id="avisverifies_amount_min_products" value="{if $avisverifies_amount_min_products}{$avisverifies_amount_min_products|escape:'htmlall':'UTF-8'} {else}{/if}" />
                            </span>
                            
                        </label>
                        </div> 

                        <div class="form-group">
                            <label class="col-lg-4 col-sm-7 col-xs-12 control-label">
                                <b>{l s='Use order doublecheck' mod='netreviews'} {if $version_ps > 1.4} (ActionOrderStatusPostUpdate) {/if} </b>
                                </label>
                            <div class="col-lg-8 col-sm-5 col-xs-12">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" name="avisverifies_orders_doublecheck" id="avisverifies_orders_doublecheck_on" value="1"{if ($avisverifies_orders_doublecheck eq "1")} checked="checked"{/if}>
                                    <label for="avisverifies_orders_doublecheck_on" class="radioCheck">
                                        <i class="color_success"></i> {l s='Yes' mod='netreviews'}
                                    </label>
                                    <input type="radio" name="avisverifies_orders_doublecheck" id="avisverifies_orders_doublecheck_off" value="0"{if ($avisverifies_orders_doublecheck eq "0" or !$avisverifies_orders_doublecheck)} checked="checked"{/if}>
                                    <label for="avisverifies_orders_doublecheck_off" class="radioCheck">
                                        <i class="color_danger"></i> {l s='No' mod='netreviews'}
                                    </label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>      

                        <div class="form-group">
                            <label class="col-lg-4 col-sm-7 col-xs-12 control-label">
                                <b>{l s='Purge all orders for this shop' mod='netreviews'}  ({$shop_name|escape:'htmlall':'UTF-8'})</b>
                            </label>
                            <div class="col-lg-8 col-sm-5 col-xs-12">
                                <input type="submit"  name="submit_purge" id="submit_purge" value="{l s='Purged' mod='netreviews'}" class="btn btn-danger">
                            </div>
                        </div>

                        <ul class="list-group col-lg-3 col-sm-6 col-xs-12 pull-right">
                            <li class="list-group-item">Reviews : {$debug_nb_reviews|escape:'htmlall':'UTF-8'}</li>
                            <li class="list-group-item">Average reviews : {$debug_nb_reviews_average|escape:'htmlall':'UTF-8'}</li>
                            <li class="list-group-item">Orders pending : {$debug_nb_orders_not_flagged|escape:'htmlall':'UTF-8'}</li>
                            <li class="list-group-item">Orders getted : {$debug_nb_orders_flagged|escape:'htmlall':'UTF-8'}</li>
                            <li class="list-group-item">Orders all : {$debug_nb_orders_all|escape:'htmlall':'UTF-8'}</li>
                        </ul>
                    </div> 
                </fieldset>
            </div>     <!-- pannel body end -->

            
            <div class="panel-footer">
                <button type="submit"  name="submit_advanced" id="submit_advanced_debug" class="button pointer btn btn-default pull-right">
                <i class="process-icon-save"></i> {l s='Save' mod='netreviews'}
                </button>
            </div>
        </div> <!-- collapse END -->
    </div> <!-- Debug END -->

    <div class="clearfix"> </div>
    
</form>

</div> <!-- END avisverifies_module content -->

{if $version_ps < 1.6}
</div> 
{literal}
    <script language=javascript>
        $("a[href='#collapse1']").click(function(){ 
            $("#collapse1").show();
            $(this).hide();
         });
    </script>
{/literal}
{/if}

{literal}
    <script language=javascript>

        $(".switch").change(function(){
            if($("#avisverifies_multilingue_on").attr("checked")){
                console.log("T");
                $(".configuration_labels").removeClass("hidden");
                $("#av_configuration").addClass("hidden");
            }else{
                $(".configuration_labels").removeClass("hidden");
                $("#av_multilanguage_configuration").addClass("hidden");
            }
        })

        function cocheToute(){
            $('.cbOrderstates').each(function () {
                $(this).attr('checked', true);
            });
        }
       function decocheToute(){
            $('.cbOrderstates').each(function () {
                $(this).attr('checked', false);
            });
        }   

        function productliststars_show(){
            $('#show_howtoaddstars').html("Please add <b>{hook h='displayProductListReviews' product=$product}</b> in your <u><b>product-list.tpl</b></u> file");
            $('#show_exampleimage').hide();
        }       

        function exampleimage_show(){
            $('#show_howtoaddstars').html("");
            $('#show_exampleimage').show();
            $('#show_tabcontenthook').hide();
        }

        function extrahook_show(){
            $('#show_extrahook').html("Please add <b>{hook h='ExtraNetreviews'}</b> in your <u><b>product.tpl</b></u> file");
        }

        function tabcontenthook_show(){
            $('#show_tabcontenthook').html("Please add <b>{hook h='TabcontentNetreviews'}</b> in your <u><b>product.tpl</b></u> file");
            $('#show_exampleimage').hide();
        }

        function categoryrs_show(){
            $('#show_categoryrs').html("Please add <b>{hook h='Category_rs_netreviews'}</b> in your <u><b>category.tpl</b></u> file");
        }

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });

        $('.numbersOnly').keyup(function () { 
            this.value = this.value.replace(/[^0-9\.]/g,'');
            if( this.value >300){
                  this.value =Math.trunc(this.value/10);
            }
        });
        /**
 * jscolor - JavaScript Color Picker
 *
 * @link    http://jscolor.com
 * @license For open source use: GPLv3
 *          For commercial use: JSColor Commercial License
 * @author  Jan Odvarko
 * @version 2.0.5
 *
 * See usage examples at http://jscolor.com/examples/
 */


"use strict";


if (!window.jscolor) { window.jscolor = (function () {


var jsc = {


    register : function () {
        jsc.attachDOMReadyEvent(jsc.init);
        jsc.attachEvent(document, 'mousedown', jsc.onDocumentMouseDown);
        jsc.attachEvent(document, 'touchstart', jsc.onDocumentTouchStart);
        jsc.attachEvent(window, 'resize', jsc.onWindowResize);
    },


    init : function () {
        if (jsc.jscolor.lookupClass) {
            jsc.jscolor.installByClassName(jsc.jscolor.lookupClass);
        }
    },


    tryInstallOnElements : function (elms, className) {
        var matchClass = new RegExp('(^|\\s)(' + className + ')(\\s*(\\{[^}]*\\})|\\s|$)', 'i');

        for (var i = 0; i < elms.length; i += 1) {
            if (elms[i].type !== undefined && elms[i].type.toLowerCase() == 'color') {
                if (jsc.isColorAttrSupported) {
                    // skip inputs of type 'color' if supported by the browser
                    continue;
                }
            }
            var m;
            if (!elms[i].jscolor && elms[i].className && (m = elms[i].className.match(matchClass))) {
                var targetElm = elms[i];
                var optsStr = null;

                var dataOptions = jsc.getDataAttr(targetElm, 'jscolor');
                if (dataOptions !== null) {
                    optsStr = dataOptions;
                } else if (m[4]) {
                    optsStr = m[4];
                }

                var opts = {};
                if (optsStr) {
                    try {
                        opts = (new Function ('return (' + optsStr + ')'))();
                    } catch(eParseError) {
                        jsc.warn('Error parsing jscolor options: ' + eParseError + ':\n' + optsStr);
                    }
                }
                targetElm.jscolor = new jsc.jscolor(targetElm, opts);
            }
        }
    },


    isColorAttrSupported : (function () {
        var elm = document.createElement('input');
        if (elm.setAttribute) {
            elm.setAttribute('type', 'color');
            if (elm.type.toLowerCase() == 'color') {
                return true;
            }
        }
        return false;
    })(),


    isCanvasSupported : (function () {
        var elm = document.createElement('canvas');
        return !!(elm.getContext && elm.getContext('2d'));
    })(),


    fetchElement : function (mixed) {
        return typeof mixed === 'string' ? document.getElementById(mixed) : mixed;
    },


    isElementType : function (elm, type) {
        return elm.nodeName.toLowerCase() === type.toLowerCase();
    },


    getDataAttr : function (el, name) {
        var attrName = 'data-' + name;
        var attrValue = el.getAttribute(attrName);
        if (attrValue !== null) {
            return attrValue;
        }
        return null;
    },


    attachEvent : function (el, evnt, func) {
        if (el.addEventListener) {
            el.addEventListener(evnt, func, false);
        } else if (el.attachEvent) {
            el.attachEvent('on' + evnt, func);
        }
    },


    detachEvent : function (el, evnt, func) {
        if (el.removeEventListener) {
            el.removeEventListener(evnt, func, false);
        } else if (el.detachEvent) {
            el.detachEvent('on' + evnt, func);
        }
    },


    _attachedGroupEvents : {},


    attachGroupEvent : function (groupName, el, evnt, func) {
        if (!jsc._attachedGroupEvents.hasOwnProperty(groupName)) {
            jsc._attachedGroupEvents[groupName] = [];
        }
        jsc._attachedGroupEvents[groupName].push([el, evnt, func]);
        jsc.attachEvent(el, evnt, func);
    },


    detachGroupEvents : function (groupName) {
        if (jsc._attachedGroupEvents.hasOwnProperty(groupName)) {
            for (var i = 0; i < jsc._attachedGroupEvents[groupName].length; i += 1) {
                var evt = jsc._attachedGroupEvents[groupName][i];
                jsc.detachEvent(evt[0], evt[1], evt[2]);
            }
            delete jsc._attachedGroupEvents[groupName];
        }
    },


    attachDOMReadyEvent : function (func) {
        var fired = false;
        var fireOnce = function () {
            if (!fired) {
                fired = true;
                func();
            }
        };

        if (document.readyState === 'complete') {
            setTimeout(fireOnce, 1); // async
            return;
        }

        if (document.addEventListener) {
            document.addEventListener('DOMContentLoaded', fireOnce, false);

            // Fallback
            window.addEventListener('load', fireOnce, false);

        } else if (document.attachEvent) {
            // IE
            document.attachEvent('onreadystatechange', function () {
                if (document.readyState === 'complete') {
                    document.detachEvent('onreadystatechange', arguments.callee);
                    fireOnce();
                }
            })

            // Fallback
            window.attachEvent('onload', fireOnce);

            // IE7/8
            if (document.documentElement.doScroll && window == window.top) {
                var tryScroll = function () {
                    if (!document.body) { return; }
                    try {
                        document.documentElement.doScroll('left');
                        fireOnce();
                    } catch (e) {
                        setTimeout(tryScroll, 1);
                    }
                };
                tryScroll();
            }
        }
    },


    warn : function (msg) {
        if (window.console && window.console.warn) {
            window.console.warn(msg);
        }
    },


    preventDefault : function (e) {
        if (e.preventDefault) { e.preventDefault(); }
        e.returnValue = false;
    },


    captureTarget : function (target) {
        // IE
        if (target.setCapture) {
            jsc._capturedTarget = target;
            jsc._capturedTarget.setCapture();
        }
    },


    releaseTarget : function () {
        // IE
        if (jsc._capturedTarget) {
            jsc._capturedTarget.releaseCapture();
            jsc._capturedTarget = null;
        }
    },


    fireEvent : function (el, evnt) {
        if (!el) {
            return;
        }
        if (document.createEvent) {
            var ev = document.createEvent('HTMLEvents');
            ev.initEvent(evnt, true, true);
            el.dispatchEvent(ev);
        } else if (document.createEventObject) {
            var ev = document.createEventObject();
            el.fireEvent('on' + evnt, ev);
        } else if (el['on' + evnt]) { // alternatively use the traditional event model
            el['on' + evnt]();
        }
    },


    classNameToList : function (className) {
        return className.replace(/^\s+|\s+$/g, '').split(/\s+/);
    },


    // The className parameter (str) can only contain a single class name
    hasClass : function (elm, className) {
        if (!className) {
            return false;
        }
        return -1 != (' ' + elm.className.replace(/\s+/g, ' ') + ' ').indexOf(' ' + className + ' ');
    },


    // The className parameter (str) can contain multiple class names separated by whitespace
    setClass : function (elm, className) {
        var classList = jsc.classNameToList(className);
        for (var i = 0; i < classList.length; i += 1) {
            if (!jsc.hasClass(elm, classList[i])) {
                elm.className += (elm.className ? ' ' : '') + classList[i];
            }
        }
    },


    // The className parameter (str) can contain multiple class names separated by whitespace
    unsetClass : function (elm, className) {
        var classList = jsc.classNameToList(className);
        for (var i = 0; i < classList.length; i += 1) {
            var repl = new RegExp(
                '^\\s*' + classList[i] + '\\s*|' +
                '\\s*' + classList[i] + '\\s*$|' +
                '\\s+' + classList[i] + '(\\s+)',
                'g'
            );
            elm.className = elm.className.replace(repl, '$1');
        }
    },


    getStyle : function (elm) {
        return window.getComputedStyle ? window.getComputedStyle(elm) : elm.currentStyle;
    },


    setStyle : (function () {
        var helper = document.createElement('div');
        var getSupportedProp = function (names) {
            for (var i = 0; i < names.length; i += 1) {
                if (names[i] in helper.style) {
                    return names[i];
                }
            }
        };
        var props = {
            borderRadius: getSupportedProp(['borderRadius', 'MozBorderRadius', 'webkitBorderRadius']),
            boxShadow: getSupportedProp(['boxShadow', 'MozBoxShadow', 'webkitBoxShadow'])
        };
        return function (elm, prop, value) {
            switch (prop.toLowerCase()) {
            case 'opacity':
                var alphaOpacity = Math.round(parseFloat(value) * 100);
                elm.style.opacity = value;
                elm.style.filter = 'alpha(opacity=' + alphaOpacity + ')';
                break;
            default:
                elm.style[props[prop]] = value;
                break;
            }
        };
    })(),


    setBorderRadius : function (elm, value) {
        jsc.setStyle(elm, 'borderRadius', value || '0');
    },


    setBoxShadow : function (elm, value) {
        jsc.setStyle(elm, 'boxShadow', value || 'none');
    },


    getElementPos : function (e, relativeToViewport) {
        var x=0, y=0;
        var rect = e.getBoundingClientRect();
        x = rect.left;
        y = rect.top;
        if (!relativeToViewport) {
            var viewPos = jsc.getViewPos();
            x += viewPos[0];
            y += viewPos[1];
        }
        return [x, y];
    },


    getElementSize : function (e) {
        return [e.offsetWidth, e.offsetHeight];
    },


    // get pointer's X/Y coordinates relative to viewport
    getAbsPointerPos : function (e) {
        if (!e) { e = window.event; }
        var x = 0, y = 0;
        if (typeof e.changedTouches !== 'undefined' && e.changedTouches.length) {
            // touch devices
            x = e.changedTouches[0].clientX;
            y = e.changedTouches[0].clientY;
        } else if (typeof e.clientX === 'number') {
            x = e.clientX;
            y = e.clientY;
        }
        return { x: x, y: y };
    },


    // get pointer's X/Y coordinates relative to target element
    getRelPointerPos : function (e) {
        if (!e) { e = window.event; }
        var target = e.target || e.srcElement;
        var targetRect = target.getBoundingClientRect();

        var x = 0, y = 0;

        var clientX = 0, clientY = 0;
        if (typeof e.changedTouches !== 'undefined' && e.changedTouches.length) {
            // touch devices
            clientX = e.changedTouches[0].clientX;
            clientY = e.changedTouches[0].clientY;
        } else if (typeof e.clientX === 'number') {
            clientX = e.clientX;
            clientY = e.clientY;
        }

        x = clientX - targetRect.left;
        y = clientY - targetRect.top;
        return { x: x, y: y };
    },


    getViewPos : function () {
        var doc = document.documentElement;
        return [
            (window.pageXOffset || doc.scrollLeft) - (doc.clientLeft || 0),
            (window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0)
        ];
    },


    getViewSize : function () {
        var doc = document.documentElement;
        return [
            (window.innerWidth || doc.clientWidth),
            (window.innerHeight || doc.clientHeight),
        ];
    },


    redrawPosition : function () {

        if (jsc.picker && jsc.picker.owner) {
            var thisObj = jsc.picker.owner;

            var tp, vp;

            if (thisObj.fixed) {
                // Fixed elements are positioned relative to viewport,
                // therefore we can ignore the scroll offset
                tp = jsc.getElementPos(thisObj.targetElement, true); // target pos
                vp = [0, 0]; // view pos
            } else {
                tp = jsc.getElementPos(thisObj.targetElement); // target pos
                vp = jsc.getViewPos(); // view pos
            }

            var ts = jsc.getElementSize(thisObj.targetElement); // target size
            var vs = jsc.getViewSize(); // view size
            var ps = jsc.getPickerOuterDims(thisObj); // picker size
            var a, b, c;
            switch (thisObj.position.toLowerCase()) {
                case 'left': a=1; b=0; c=-1; break;
                case 'right':a=1; b=0; c=1; break;
                case 'top':  a=0; b=1; c=-1; break;
                default:     a=0; b=1; c=1; break;
            }
            var l = (ts[b]+ps[b])/2;

            // compute picker position
            if (!thisObj.smartPosition) {
                var pp = [
                    tp[a],
                    tp[b]+ts[b]-l+l*c
                ];
            } else {
                var pp = [
                    -vp[a]+tp[a]+ps[a] > vs[a] ?
                        (-vp[a]+tp[a]+ts[a]/2 > vs[a]/2 && tp[a]+ts[a]-ps[a] >= 0 ? tp[a]+ts[a]-ps[a] : tp[a]) :
                        tp[a],
                    -vp[b]+tp[b]+ts[b]+ps[b]-l+l*c > vs[b] ?
                        (-vp[b]+tp[b]+ts[b]/2 > vs[b]/2 && tp[b]+ts[b]-l-l*c >= 0 ? tp[b]+ts[b]-l-l*c : tp[b]+ts[b]-l+l*c) :
                        (tp[b]+ts[b]-l+l*c >= 0 ? tp[b]+ts[b]-l+l*c : tp[b]+ts[b]-l-l*c)
                ];
            }

            var x = pp[a];
            var y = pp[b];
            var positionValue = thisObj.fixed ? 'fixed' : 'absolute';
            var contractShadow =
                (pp[0] + ps[0] > tp[0] || pp[0] < tp[0] + ts[0]) &&
                (pp[1] + ps[1] < tp[1] + ts[1]);

            jsc._drawPosition(thisObj, x, y, positionValue, contractShadow);
        }
    },


    _drawPosition : function (thisObj, x, y, positionValue, contractShadow) {
        var vShadow = contractShadow ? 0 : thisObj.shadowBlur; // px

        jsc.picker.wrap.style.position = positionValue;
        jsc.picker.wrap.style.left = x + 'px';
        jsc.picker.wrap.style.top = y + 'px';

        jsc.setBoxShadow(
            jsc.picker.boxS,
            thisObj.shadow ?
                new jsc.BoxShadow(0, vShadow, thisObj.shadowBlur, 0, thisObj.shadowColor) :
                null);
    },


    getPickerDims : function (thisObj) {
        var displaySlider = !!jsc.getSliderComponent(thisObj);
        var dims = [
            2 * thisObj.insetWidth + 2 * thisObj.padding + thisObj.width +
                (displaySlider ? 2 * thisObj.insetWidth + jsc.getPadToSliderPadding(thisObj) + thisObj.sliderSize : 0),
            2 * thisObj.insetWidth + 2 * thisObj.padding + thisObj.height +
                (thisObj.closable ? 2 * thisObj.insetWidth + thisObj.padding + thisObj.buttonHeight : 0)
        ];
        return dims;
    },


    getPickerOuterDims : function (thisObj) {
        var dims = jsc.getPickerDims(thisObj);
        return [
            dims[0] + 2 * thisObj.borderWidth,
            dims[1] + 2 * thisObj.borderWidth
        ];
    },


    getPadToSliderPadding : function (thisObj) {
        return Math.max(thisObj.padding, 1.5 * (2 * thisObj.pointerBorderWidth + thisObj.pointerThickness));
    },


    getPadYComponent : function (thisObj) {
        switch (thisObj.mode.charAt(1).toLowerCase()) {
            case 'v': return 'v'; break;
        }
        return 's';
    },


    getSliderComponent : function (thisObj) {
        if (thisObj.mode.length > 2) {
            switch (thisObj.mode.charAt(2).toLowerCase()) {
                case 's': return 's'; break;
                case 'v': return 'v'; break;
            }
        }
        return null;
    },


    onDocumentMouseDown : function (e) {
        if (!e) { e = window.event; }
        var target = e.target || e.srcElement;

        if (target._jscLinkedInstance) {
            if (target._jscLinkedInstance.showOnClick) {
                target._jscLinkedInstance.show();
            }
        } else if (target._jscControlName) {
            jsc.onControlPointerStart(e, target, target._jscControlName, 'mouse');
        } else {
            // Mouse is outside the picker controls -> hide the color picker!
            if (jsc.picker && jsc.picker.owner) {
                jsc.picker.owner.hide();
            }
        }
    },


    onDocumentTouchStart : function (e) {
        if (!e) { e = window.event; }
        var target = e.target || e.srcElement;

        if (target._jscLinkedInstance) {
            if (target._jscLinkedInstance.showOnClick) {
                target._jscLinkedInstance.show();
            }
        } else if (target._jscControlName) {
            jsc.onControlPointerStart(e, target, target._jscControlName, 'touch');
        } else {
            if (jsc.picker && jsc.picker.owner) {
                jsc.picker.owner.hide();
            }
        }
    },


    onWindowResize : function (e) {
        jsc.redrawPosition();
    },


    onParentScroll : function (e) {
        // hide the picker when one of the parent elements is scrolled
        if (jsc.picker && jsc.picker.owner) {
            jsc.picker.owner.hide();
        }
    },


    _pointerMoveEvent : {
        mouse: 'mousemove',
        touch: 'touchmove'
    },
    _pointerEndEvent : {
        mouse: 'mouseup',
        touch: 'touchend'
    },


    _pointerOrigin : null,
    _capturedTarget : null,


    onControlPointerStart : function (e, target, controlName, pointerType) {
        var thisObj = target._jscInstance;

        jsc.preventDefault(e);
        jsc.captureTarget(target);

        var registerDragEvents = function (doc, offset) {
            jsc.attachGroupEvent('drag', doc, jsc._pointerMoveEvent[pointerType],
                jsc.onDocumentPointerMove(e, target, controlName, pointerType, offset));
            jsc.attachGroupEvent('drag', doc, jsc._pointerEndEvent[pointerType],
                jsc.onDocumentPointerEnd(e, target, controlName, pointerType));
        };

        registerDragEvents(document, [0, 0]);

        if (window.parent && window.frameElement) {
            var rect = window.frameElement.getBoundingClientRect();
            var ofs = [-rect.left, -rect.top];
            registerDragEvents(window.parent.window.document, ofs);
        }

        var abs = jsc.getAbsPointerPos(e);
        var rel = jsc.getRelPointerPos(e);
        jsc._pointerOrigin = {
            x: abs.x - rel.x,
            y: abs.y - rel.y
        };

        switch (controlName) {
        case 'pad':
            // if the slider is at the bottom, move it up
            switch (jsc.getSliderComponent(thisObj)) {
            case 's': if (thisObj.hsv[1] === 0) { thisObj.fromHSV(null, 100, null); }; break;
            case 'v': if (thisObj.hsv[2] === 0) { thisObj.fromHSV(null, null, 100); }; break;
            }
            jsc.setPad(thisObj, e, 0, 0);
            break;

        case 'sld':
            jsc.setSld(thisObj, e, 0);
            break;
        }

        jsc.dispatchFineChange(thisObj);
    },


    onDocumentPointerMove : function (e, target, controlName, pointerType, offset) {
        return function (e) {
            var thisObj = target._jscInstance;
            switch (controlName) {
            case 'pad':
                if (!e) { e = window.event; }
                jsc.setPad(thisObj, e, offset[0], offset[1]);
                jsc.dispatchFineChange(thisObj);
                break;

            case 'sld':
                if (!e) { e = window.event; }
                jsc.setSld(thisObj, e, offset[1]);
                jsc.dispatchFineChange(thisObj);
                break;
            }
        }
    },


    onDocumentPointerEnd : function (e, target, controlName, pointerType) {
        return function (e) {
            var thisObj = target._jscInstance;
            jsc.detachGroupEvents('drag');
            jsc.releaseTarget();
            // Always dispatch changes after detaching outstanding mouse handlers,
            // in case some user interaction will occur in user's onchange callback
            // that would intrude with current mouse events
            jsc.dispatchChange(thisObj);
        };
    },


    dispatchChange : function (thisObj) {
        if (thisObj.valueElement) {
            if (jsc.isElementType(thisObj.valueElement, 'input')) {
                jsc.fireEvent(thisObj.valueElement, 'change');
            }
        }
    },


    dispatchFineChange : function (thisObj) {
        if (thisObj.onFineChange) {
            var callback;
            if (typeof thisObj.onFineChange === 'string') {
                callback = new Function (thisObj.onFineChange);
            } else {
                callback = thisObj.onFineChange;
            }
            callback.call(thisObj);
        }
    },


    setPad : function (thisObj, e, ofsX, ofsY) {
        var pointerAbs = jsc.getAbsPointerPos(e);
        var x = ofsX + pointerAbs.x - jsc._pointerOrigin.x - thisObj.padding - thisObj.insetWidth;
        var y = ofsY + pointerAbs.y - jsc._pointerOrigin.y - thisObj.padding - thisObj.insetWidth;

        var xVal = x * (360 / (thisObj.width - 1));
        var yVal = 100 - (y * (100 / (thisObj.height - 1)));

        switch (jsc.getPadYComponent(thisObj)) {
        case 's': thisObj.fromHSV(xVal, yVal, null, jsc.leaveSld); break;
        case 'v': thisObj.fromHSV(xVal, null, yVal, jsc.leaveSld); break;
        }
    },


    setSld : function (thisObj, e, ofsY) {
        var pointerAbs = jsc.getAbsPointerPos(e);
        var y = ofsY + pointerAbs.y - jsc._pointerOrigin.y - thisObj.padding - thisObj.insetWidth;

        var yVal = 100 - (y * (100 / (thisObj.height - 1)));

        switch (jsc.getSliderComponent(thisObj)) {
        case 's': thisObj.fromHSV(null, yVal, null, jsc.leavePad); break;
        case 'v': thisObj.fromHSV(null, null, yVal, jsc.leavePad); break;
        }
    },


    _vmlNS : 'jsc_vml_',
    _vmlCSS : 'jsc_vml_css_',
    _vmlReady : false,


    initVML : function () {
        if (!jsc._vmlReady) {
            // init VML namespace
            var doc = document;
            if (!doc.namespaces[jsc._vmlNS]) {
                doc.namespaces.add(jsc._vmlNS, 'urn:schemas-microsoft-com:vml');
            }
            if (!doc.styleSheets[jsc._vmlCSS]) {
                var tags = ['shape', 'shapetype', 'group', 'background', 'path', 'formulas', 'handles', 'fill', 'stroke', 'shadow', 'textbox', 'textpath', 'imagedata', 'line', 'polyline', 'curve', 'rect', 'roundrect', 'oval', 'arc', 'image'];
                var ss = doc.createStyleSheet();
                ss.owningElement.id = jsc._vmlCSS;
                for (var i = 0; i < tags.length; i += 1) {
                    ss.addRule(jsc._vmlNS + '\\:' + tags[i], 'behavior:url(#default#VML);');
                }
            }
            jsc._vmlReady = true;
        }
    },


    createPalette : function () {

        var paletteObj = {
            elm: null,
            draw: null
        };

        if (jsc.isCanvasSupported) {
            // Canvas implementation for modern browsers

            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            var drawFunc = function (width, height, type) {
                canvas.width = width;
                canvas.height = height;

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                var hGrad = ctx.createLinearGradient(0, 0, canvas.width, 0);
                hGrad.addColorStop(0 / 6, '#F00');
                hGrad.addColorStop(1 / 6, '#FF0');
                hGrad.addColorStop(2 / 6, '#0F0');
                hGrad.addColorStop(3 / 6, '#0FF');
                hGrad.addColorStop(4 / 6, '#00F');
                hGrad.addColorStop(5 / 6, '#F0F');
                hGrad.addColorStop(6 / 6, '#F00');

                ctx.fillStyle = hGrad;
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                var vGrad = ctx.createLinearGradient(0, 0, 0, canvas.height);
                switch (type.toLowerCase()) {
                case 's':
                    vGrad.addColorStop(0, 'rgba(255,255,255,0)');
                    vGrad.addColorStop(1, 'rgba(255,255,255,1)');
                    break;
                case 'v':
                    vGrad.addColorStop(0, 'rgba(0,0,0,0)');
                    vGrad.addColorStop(1, 'rgba(0,0,0,1)');
                    break;
                }
                ctx.fillStyle = vGrad;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            };

            paletteObj.elm = canvas;
            paletteObj.draw = drawFunc;

        } else {
            // VML fallback for IE 7 and 8

            jsc.initVML();

            var vmlContainer = document.createElement('div');
            vmlContainer.style.position = 'relative';
            vmlContainer.style.overflow = 'hidden';

            var hGrad = document.createElement(jsc._vmlNS + ':fill');
            hGrad.type = 'gradient';
            hGrad.method = 'linear';
            hGrad.angle = '90';
            hGrad.colors = '16.67% #F0F, 33.33% #00F, 50% #0FF, 66.67% #0F0, 83.33% #FF0'

            var hRect = document.createElement(jsc._vmlNS + ':rect');
            hRect.style.position = 'absolute';
            hRect.style.left = -1 + 'px';
            hRect.style.top = -1 + 'px';
            hRect.stroked = false;
            hRect.appendChild(hGrad);
            vmlContainer.appendChild(hRect);

            var vGrad = document.createElement(jsc._vmlNS + ':fill');
            vGrad.type = 'gradient';
            vGrad.method = 'linear';
            vGrad.angle = '180';
            vGrad.opacity = '0';

            var vRect = document.createElement(jsc._vmlNS + ':rect');
            vRect.style.position = 'absolute';
            vRect.style.left = -1 + 'px';
            vRect.style.top = -1 + 'px';
            vRect.stroked = false;
            vRect.appendChild(vGrad);
            vmlContainer.appendChild(vRect);

            var drawFunc = function (width, height, type) {
                vmlContainer.style.width = width + 'px';
                vmlContainer.style.height = height + 'px';

                hRect.style.width =
                vRect.style.width =
                    (width + 1) + 'px';
                hRect.style.height =
                vRect.style.height =
                    (height + 1) + 'px';

                // Colors must be specified during every redraw, otherwise IE won't display
                // a full gradient during a subsequential redraw
                hGrad.color = '#F00';
                hGrad.color2 = '#F00';

                switch (type.toLowerCase()) {
                case 's':
                    vGrad.color = vGrad.color2 = '#FFF';
                    break;
                case 'v':
                    vGrad.color = vGrad.color2 = '#000';
                    break;
                }
            };
            
            paletteObj.elm = vmlContainer;
            paletteObj.draw = drawFunc;
        }

        return paletteObj;
    },


    createSliderGradient : function () {

        var sliderObj = {
            elm: null,
            draw: null
        };

        if (jsc.isCanvasSupported) {
            // Canvas implementation for modern browsers

            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            var drawFunc = function (width, height, color1, color2) {
                canvas.width = width;
                canvas.height = height;

                ctx.clearRect(0, 0, canvas.width, canvas.height);

                var grad = ctx.createLinearGradient(0, 0, 0, canvas.height);
                grad.addColorStop(0, color1);
                grad.addColorStop(1, color2);

                ctx.fillStyle = grad;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            };

            sliderObj.elm = canvas;
            sliderObj.draw = drawFunc;

        } else {
            // VML fallback for IE 7 and 8

            jsc.initVML();

            var vmlContainer = document.createElement('div');
            vmlContainer.style.position = 'relative';
            vmlContainer.style.overflow = 'hidden';

            var grad = document.createElement(jsc._vmlNS + ':fill');
            grad.type = 'gradient';
            grad.method = 'linear';
            grad.angle = '180';

            var rect = document.createElement(jsc._vmlNS + ':rect');
            rect.style.position = 'absolute';
            rect.style.left = -1 + 'px';
            rect.style.top = -1 + 'px';
            rect.stroked = false;
            rect.appendChild(grad);
            vmlContainer.appendChild(rect);

            var drawFunc = function (width, height, color1, color2) {
                vmlContainer.style.width = width + 'px';
                vmlContainer.style.height = height + 'px';

                rect.style.width = (width + 1) + 'px';
                rect.style.height = (height + 1) + 'px';

                grad.color = color1;
                grad.color2 = color2;
            };
            
            sliderObj.elm = vmlContainer;
            sliderObj.draw = drawFunc;
        }

        return sliderObj;
    },


    leaveValue : 1<<0,
    leaveStyle : 1<<1,
    leavePad : 1<<2,
    leaveSld : 1<<3,


    BoxShadow : (function () {
        var BoxShadow = function (hShadow, vShadow, blur, spread, color, inset) {
            this.hShadow = hShadow;
            this.vShadow = vShadow;
            this.blur = blur;
            this.spread = spread;
            this.color = color;
            this.inset = !!inset;
        };

        BoxShadow.prototype.toString = function () {
            var vals = [
                Math.round(this.hShadow) + 'px',
                Math.round(this.vShadow) + 'px',
                Math.round(this.blur) + 'px',
                Math.round(this.spread) + 'px',
                this.color
            ];
            if (this.inset) {
                vals.push('inset');
            }
            return vals.join(' ');
        };

        return BoxShadow;
    })(),


    //
    // Usage:
    // var myColor = new jscolor(<targetElement> [, <options>])
    //

    jscolor : function (targetElement, options) {

        // General options
        //
        this.value = null; // initial HEX color. To change it later, use methods fromString(), fromHSV() and fromRGB()
        this.valueElement = targetElement; // element that will be used to display and input the color code
        this.styleElement = targetElement; // element that will preview the picked color using CSS backgroundColor
        this.required = true; // whether the associated text <input> can be left empty
        this.refine = true; // whether to refine the entered color code (e.g. uppercase it and remove whitespace)
        this.hash = false; // whether to prefix the HEX color code with # symbol
        this.uppercase = true; // whether to show the color code in upper case
        this.onFineChange = null; // called instantly every time the color changes (value can be either a function or a string with javascript code)
        this.activeClass = 'jscolor-active'; // class to be set to the target element when a picker window is open on it
        this.overwriteImportant = false; // whether to overwrite colors of styleElement using !important
        this.minS = 0; // min allowed saturation (0 - 100)
        this.maxS = 100; // max allowed saturation (0 - 100)
        this.minV = 0; // min allowed value (brightness) (0 - 100)
        this.maxV = 100; // max allowed value (brightness) (0 - 100)

        // Accessing the picked color
        //
        this.hsv = [0, 0, 100]; // read-only  [0-360, 0-100, 0-100]
        this.rgb = [255, 255, 255]; // read-only  [0-255, 0-255, 0-255]

        // Color Picker options
        //
        this.width = 181; // width of color palette (in px)
        this.height = 101; // height of color palette (in px)
        this.showOnClick = true; // whether to display the color picker when user clicks on its target element
        this.mode = 'HSV'; // HSV | HVS | HS | HV - layout of the color picker controls
        this.position = 'bottom'; // left | right | top | bottom - position relative to the target element
        this.smartPosition = true; // automatically change picker position when there is not enough space for it
        this.sliderSize = 16; // px
        this.crossSize = 8; // px
        this.closable = false; // whether to display the Close button
        this.closeText = 'Close';
        this.buttonColor = '#000000'; // CSS color
        this.buttonHeight = 18; // px
        this.padding = 12; // px
        this.backgroundColor = '#FFFFFF'; // CSS color
        this.borderWidth = 1; // px
        this.borderColor = '#BBBBBB'; // CSS color
        this.borderRadius = 8; // px
        this.insetWidth = 1; // px
        this.insetColor = '#BBBBBB'; // CSS color
        this.shadow = true; // whether to display shadow
        this.shadowBlur = 15; // px
        this.shadowColor = 'rgba(0,0,0,0.2)'; // CSS color
        this.pointerColor = '#4C4C4C'; // px
        this.pointerBorderColor = '#FFFFFF'; // px
        this.pointerBorderWidth = 1; // px
        this.pointerThickness = 2; // px
        this.zIndex = 1000;
        this.container = null; // where to append the color picker (BODY element by default)


        for (var opt in options) {
            if (options.hasOwnProperty(opt)) {
                this[opt] = options[opt];
            }
        }


        this.hide = function () {
            if (isPickerOwner()) {
                detachPicker();
            }
        };


        this.show = function () {
            drawPicker();
        };


        this.redraw = function () {
            if (isPickerOwner()) {
                drawPicker();
            }
        };


        this.importColor = function () {
            if (!this.valueElement) {
                this.exportColor();
            } else {
                if (jsc.isElementType(this.valueElement, 'input')) {
                    if (!this.refine) {
                        if (!this.fromString(this.valueElement.value, jsc.leaveValue)) {
                            if (this.styleElement) {
                                this.styleElement.style.backgroundImage = this.styleElement._jscOrigStyle.backgroundImage;
                                this.styleElement.style.backgroundColor = this.styleElement._jscOrigStyle.backgroundColor;
                                this.styleElement.style.color = this.styleElement._jscOrigStyle.color;
                            }
                            this.exportColor(jsc.leaveValue | jsc.leaveStyle);
                        }
                    } else if (!this.required && /^\s*$/.test(this.valueElement.value)) {
                        this.valueElement.value = '';
                        if (this.styleElement) {
                            this.styleElement.style.backgroundImage = this.styleElement._jscOrigStyle.backgroundImage;
                            this.styleElement.style.backgroundColor = this.styleElement._jscOrigStyle.backgroundColor;
                            this.styleElement.style.color = this.styleElement._jscOrigStyle.color;
                        }
                        this.exportColor(jsc.leaveValue | jsc.leaveStyle);

                    } else if (this.fromString(this.valueElement.value)) {
                        // managed to import color successfully from the value -> OK, don't do anything
                    } else {
                        this.exportColor();
                    }
                } else {
                    // not an input element -> doesn't have any value
                    this.exportColor();
                }
            }
        };


        this.exportColor = function (flags) {
            if (!(flags & jsc.leaveValue) && this.valueElement) {
                var value = this.toString();
                if (this.uppercase) { value = value.toUpperCase(); }
                if (this.hash) { value = '#' + value; }

                if (jsc.isElementType(this.valueElement, 'input')) {
                    this.valueElement.value = value;
                } else {
                    this.valueElement.innerHTML = value;
                }
            }
            if (!(flags & jsc.leaveStyle)) {
                if (this.styleElement) {
                    var bgColor = '#' + this.toString();
                    var fgColor = this.isLight() ? '#000' : '#FFF';

                    this.styleElement.style.backgroundImage = 'none';
                    this.styleElement.style.backgroundColor = bgColor;
                    this.styleElement.style.color = fgColor;

                    if (this.overwriteImportant) {
                        this.styleElement.setAttribute('style',
                            'background: ' + bgColor + ' !important; ' +
                            'color: ' + fgColor + ' !important;'
                        );
                    }
                }
            }
            if (!(flags & jsc.leavePad) && isPickerOwner()) {
                redrawPad();
            }
            if (!(flags & jsc.leaveSld) && isPickerOwner()) {
                redrawSld();
            }
        };


        // h: 0-360
        // s: 0-100
        // v: 0-100
        //
        this.fromHSV = function (h, s, v, flags) { // null = don't change
            if (h !== null) {
                if (isNaN(h)) { return false; }
                h = Math.max(0, Math.min(360, h));
            }
            if (s !== null) {
                if (isNaN(s)) { return false; }
                s = Math.max(0, Math.min(100, this.maxS, s), this.minS);
            }
            if (v !== null) {
                if (isNaN(v)) { return false; }
                v = Math.max(0, Math.min(100, this.maxV, v), this.minV);
            }

            this.rgb = HSV_RGB(
                h===null ? this.hsv[0] : (this.hsv[0]=h),
                s===null ? this.hsv[1] : (this.hsv[1]=s),
                v===null ? this.hsv[2] : (this.hsv[2]=v)
            );

            this.exportColor(flags);
        };


        // r: 0-255
        // g: 0-255
        // b: 0-255
        //
        this.fromRGB = function (r, g, b, flags) { // null = don't change
            if (r !== null) {
                if (isNaN(r)) { return false; }
                r = Math.max(0, Math.min(255, r));
            }
            if (g !== null) {
                if (isNaN(g)) { return false; }
                g = Math.max(0, Math.min(255, g));
            }
            if (b !== null) {
                if (isNaN(b)) { return false; }
                b = Math.max(0, Math.min(255, b));
            }

            var hsv = RGB_HSV(
                r===null ? this.rgb[0] : r,
                g===null ? this.rgb[1] : g,
                b===null ? this.rgb[2] : b
            );
            if (hsv[0] !== null) {
                this.hsv[0] = Math.max(0, Math.min(360, hsv[0]));
            }
            if (hsv[2] !== 0) {
                this.hsv[1] = hsv[1]===null ? null : Math.max(0, this.minS, Math.min(100, this.maxS, hsv[1]));
            }
            this.hsv[2] = hsv[2]===null ? null : Math.max(0, this.minV, Math.min(100, this.maxV, hsv[2]));

            // update RGB according to final HSV, as some values might be trimmed
            var rgb = HSV_RGB(this.hsv[0], this.hsv[1], this.hsv[2]);
            this.rgb[0] = rgb[0];
            this.rgb[1] = rgb[1];
            this.rgb[2] = rgb[2];

            this.exportColor(flags);
        };


        this.fromString = function (str, flags) {
            var m;
            if (m = str.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i)) {
                // HEX notation
                //

                if (m[1].length === 6) {
                    // 6-char notation
                    this.fromRGB(
                        parseInt(m[1].substr(0,2),16),
                        parseInt(m[1].substr(2,2),16),
                        parseInt(m[1].substr(4,2),16),
                        flags
                    );
                } else {
                    // 3-char notation
                    this.fromRGB(
                        parseInt(m[1].charAt(0) + m[1].charAt(0),16),
                        parseInt(m[1].charAt(1) + m[1].charAt(1),16),
                        parseInt(m[1].charAt(2) + m[1].charAt(2),16),
                        flags
                    );
                }
                return true;

            } else if (m = str.match(/^\W*rgba?\(([^)]*)\)\W*$/i)) {
                var params = m[1].split(',');
                var re = /^\s*(\d*)(\.\d+)?\s*$/;
                var mR, mG, mB;
                if (
                    params.length >= 3 &&
                    (mR = params[0].match(re)) &&
                    (mG = params[1].match(re)) &&
                    (mB = params[2].match(re))
                ) {
                    var r = parseFloat((mR[1] || '0') + (mR[2] || ''));
                    var g = parseFloat((mG[1] || '0') + (mG[2] || ''));
                    var b = parseFloat((mB[1] || '0') + (mB[2] || ''));
                    this.fromRGB(r, g, b, flags);
                    return true;
                }
            }
            return false;
        };


        this.toString = function () {
            return (
                (0x100 | Math.round(this.rgb[0])).toString(16).substr(1) +
                (0x100 | Math.round(this.rgb[1])).toString(16).substr(1) +
                (0x100 | Math.round(this.rgb[2])).toString(16).substr(1)
            );
        };


        this.toHEXString = function () {
            return '#' + this.toString().toUpperCase();
        };


        this.toRGBString = function () {
            return ('rgb(' +
                Math.round(this.rgb[0]) + ',' +
                Math.round(this.rgb[1]) + ',' +
                Math.round(this.rgb[2]) + ')'
            );
        };


        this.isLight = function () {
            return (
                0.213 * this.rgb[0] +
                0.715 * this.rgb[1] +
                0.072 * this.rgb[2] >
                255 / 2
            );
        };


        this._processParentElementsInDOM = function () {
            if (this._linkedElementsProcessed) { return; }
            this._linkedElementsProcessed = true;

            var elm = this.targetElement;
            do {
                // If the target element or one of its parent nodes has fixed position,
                // then use fixed positioning instead
                //
                // Note: In Firefox, getComputedStyle returns null in a hidden iframe,
                // that's why we need to check if the returned style object is non-empty
                var currStyle = jsc.getStyle(elm);
                if (currStyle && currStyle.position.toLowerCase() === 'fixed') {
                    this.fixed = true;
                }

                if (elm !== this.targetElement) {
                    // Ensure to attach onParentScroll only once to each parent element
                    // (multiple targetElements can share the same parent nodes)
                    //
                    // Note: It's not just offsetParents that can be scrollable,
                    // that's why we loop through all parent nodes
                    if (!elm._jscEventsAttached) {
                        jsc.attachEvent(elm, 'scroll', jsc.onParentScroll);
                        elm._jscEventsAttached = true;
                    }
                }
            } while ((elm = elm.parentNode) && !jsc.isElementType(elm, 'body'));
        };


        // r: 0-255
        // g: 0-255
        // b: 0-255
        //
        // returns: [ 0-360, 0-100, 0-100 ]
        //
        function RGB_HSV (r, g, b) {
            r /= 255;
            g /= 255;
            b /= 255;
            var n = Math.min(Math.min(r,g),b);
            var v = Math.max(Math.max(r,g),b);
            var m = v - n;
            if (m === 0) { return [ null, 0, 100 * v ]; }
            var h = r===n ? 3+(b-g)/m : (g===n ? 5+(r-b)/m : 1+(g-r)/m);
            return [
                60 * (h===6?0:h),
                100 * (m/v),
                100 * v
            ];
        }


        // h: 0-360
        // s: 0-100
        // v: 0-100
        //
        // returns: [ 0-255, 0-255, 0-255 ]
        //
        function HSV_RGB (h, s, v) {
            var u = 255 * (v / 100);

            if (h === null) {
                return [ u, u, u ];
            }

            h /= 60;
            s /= 100;

            var i = Math.floor(h);
            var f = i%2 ? h-i : 1-(h-i);
            var m = u * (1 - s);
            var n = u * (1 - s * f);
            switch (i) {
                case 6:
                case 0: return [u,n,m];
                case 1: return [n,u,m];
                case 2: return [m,u,n];
                case 3: return [m,n,u];
                case 4: return [n,m,u];
                case 5: return [u,m,n];
            }
        }


        function detachPicker () {
            jsc.unsetClass(THIS.targetElement, THIS.activeClass);
            jsc.picker.wrap.parentNode.removeChild(jsc.picker.wrap);
            delete jsc.picker.owner;
        }


        function drawPicker () {

            // At this point, when drawing the picker, we know what the parent elements are
            // and we can do all related DOM operations, such as registering events on them
            // or checking their positioning
            THIS._processParentElementsInDOM();

            if (!jsc.picker) {
                jsc.picker = {
                    owner: null,
                    wrap : document.createElement('div'),
                    box : document.createElement('div'),
                    boxS : document.createElement('div'), // shadow area
                    boxB : document.createElement('div'), // border
                    pad : document.createElement('div'),
                    padB : document.createElement('div'), // border
                    padM : document.createElement('div'), // mouse/touch area
                    padPal : jsc.createPalette(),
                    cross : document.createElement('div'),
                    crossBY : document.createElement('div'), // border Y
                    crossBX : document.createElement('div'), // border X
                    crossLY : document.createElement('div'), // line Y
                    crossLX : document.createElement('div'), // line X
                    sld : document.createElement('div'),
                    sldB : document.createElement('div'), // border
                    sldM : document.createElement('div'), // mouse/touch area
                    sldGrad : jsc.createSliderGradient(),
                    sldPtrS : document.createElement('div'), // slider pointer spacer
                    sldPtrIB : document.createElement('div'), // slider pointer inner border
                    sldPtrMB : document.createElement('div'), // slider pointer middle border
                    sldPtrOB : document.createElement('div'), // slider pointer outer border
                    btn : document.createElement('div'),
                    btnT : document.createElement('span') // text
                };

                jsc.picker.pad.appendChild(jsc.picker.padPal.elm);
                jsc.picker.padB.appendChild(jsc.picker.pad);
                jsc.picker.cross.appendChild(jsc.picker.crossBY);
                jsc.picker.cross.appendChild(jsc.picker.crossBX);
                jsc.picker.cross.appendChild(jsc.picker.crossLY);
                jsc.picker.cross.appendChild(jsc.picker.crossLX);
                jsc.picker.padB.appendChild(jsc.picker.cross);
                jsc.picker.box.appendChild(jsc.picker.padB);
                jsc.picker.box.appendChild(jsc.picker.padM);

                jsc.picker.sld.appendChild(jsc.picker.sldGrad.elm);
                jsc.picker.sldB.appendChild(jsc.picker.sld);
                jsc.picker.sldB.appendChild(jsc.picker.sldPtrOB);
                jsc.picker.sldPtrOB.appendChild(jsc.picker.sldPtrMB);
                jsc.picker.sldPtrMB.appendChild(jsc.picker.sldPtrIB);
                jsc.picker.sldPtrIB.appendChild(jsc.picker.sldPtrS);
                jsc.picker.box.appendChild(jsc.picker.sldB);
                jsc.picker.box.appendChild(jsc.picker.sldM);

                jsc.picker.btn.appendChild(jsc.picker.btnT);
                jsc.picker.box.appendChild(jsc.picker.btn);

                jsc.picker.boxB.appendChild(jsc.picker.box);
                jsc.picker.wrap.appendChild(jsc.picker.boxS);
                jsc.picker.wrap.appendChild(jsc.picker.boxB);
            }

            var p = jsc.picker;

            var displaySlider = !!jsc.getSliderComponent(THIS);
            var dims = jsc.getPickerDims(THIS);
            var crossOuterSize = (2 * THIS.pointerBorderWidth + THIS.pointerThickness + 2 * THIS.crossSize);
            var padToSliderPadding = jsc.getPadToSliderPadding(THIS);
            var borderRadius = Math.min(
                THIS.borderRadius,
                Math.round(THIS.padding * Math.PI)); // px
            var padCursor = 'crosshair';

            // wrap
            p.wrap.style.clear = 'both';
            p.wrap.style.width = (dims[0] + 2 * THIS.borderWidth) + 'px';
            p.wrap.style.height = (dims[1] + 2 * THIS.borderWidth) + 'px';
            p.wrap.style.zIndex = THIS.zIndex;

            // picker
            p.box.style.width = dims[0] + 'px';
            p.box.style.height = dims[1] + 'px';

            p.boxS.style.position = 'absolute';
            p.boxS.style.left = '0';
            p.boxS.style.top = '0';
            p.boxS.style.width = '100%';
            p.boxS.style.height = '100%';
            jsc.setBorderRadius(p.boxS, borderRadius + 'px');

            // picker border
            p.boxB.style.position = 'relative';
            p.boxB.style.border = THIS.borderWidth + 'px solid';
            p.boxB.style.borderColor = THIS.borderColor;
            p.boxB.style.background = THIS.backgroundColor;
            jsc.setBorderRadius(p.boxB, borderRadius + 'px');

            // IE hack:
            // If the element is transparent, IE will trigger the event on the elements under it,
            // e.g. on Canvas or on elements with border
            p.padM.style.background =
            p.sldM.style.background =
                '#FFF';
            jsc.setStyle(p.padM, 'opacity', '0');
            jsc.setStyle(p.sldM, 'opacity', '0');

            // pad
            p.pad.style.position = 'relative';
            p.pad.style.width = THIS.width + 'px';
            p.pad.style.height = THIS.height + 'px';

            // pad palettes (HSV and HVS)
            p.padPal.draw(THIS.width, THIS.height, jsc.getPadYComponent(THIS));

            // pad border
            p.padB.style.position = 'absolute';
            p.padB.style.left = THIS.padding + 'px';
            p.padB.style.top = THIS.padding + 'px';
            p.padB.style.border = THIS.insetWidth + 'px solid';
            p.padB.style.borderColor = THIS.insetColor;

            // pad mouse area
            p.padM._jscInstance = THIS;
            p.padM._jscControlName = 'pad';
            p.padM.style.position = 'absolute';
            p.padM.style.left = '0';
            p.padM.style.top = '0';
            p.padM.style.width = (THIS.padding + 2 * THIS.insetWidth + THIS.width + padToSliderPadding / 2) + 'px';
            p.padM.style.height = dims[1] + 'px';
            p.padM.style.cursor = padCursor;

            // pad cross
            p.cross.style.position = 'absolute';
            p.cross.style.left =
            p.cross.style.top =
                '0';
            p.cross.style.width =
            p.cross.style.height =
                crossOuterSize + 'px';

            // pad cross border Y and X
            p.crossBY.style.position =
            p.crossBX.style.position =
                'absolute';
            p.crossBY.style.background =
            p.crossBX.style.background =
                THIS.pointerBorderColor;
            p.crossBY.style.width =
            p.crossBX.style.height =
                (2 * THIS.pointerBorderWidth + THIS.pointerThickness) + 'px';
            p.crossBY.style.height =
            p.crossBX.style.width =
                crossOuterSize + 'px';
            p.crossBY.style.left =
            p.crossBX.style.top =
                (Math.floor(crossOuterSize / 2) - Math.floor(THIS.pointerThickness / 2) - THIS.pointerBorderWidth) + 'px';
            p.crossBY.style.top =
            p.crossBX.style.left =
                '0';

            // pad cross line Y and X
            p.crossLY.style.position =
            p.crossLX.style.position =
                'absolute';
            p.crossLY.style.background =
            p.crossLX.style.background =
                THIS.pointerColor;
            p.crossLY.style.height =
            p.crossLX.style.width =
                (crossOuterSize - 2 * THIS.pointerBorderWidth) + 'px';
            p.crossLY.style.width =
            p.crossLX.style.height =
                THIS.pointerThickness + 'px';
            p.crossLY.style.left =
            p.crossLX.style.top =
                (Math.floor(crossOuterSize / 2) - Math.floor(THIS.pointerThickness / 2)) + 'px';
            p.crossLY.style.top =
            p.crossLX.style.left =
                THIS.pointerBorderWidth + 'px';

            // slider
            p.sld.style.overflow = 'hidden';
            p.sld.style.width = THIS.sliderSize + 'px';
            p.sld.style.height = THIS.height + 'px';

            // slider gradient
            p.sldGrad.draw(THIS.sliderSize, THIS.height, '#000', '#000');

            // slider border
            p.sldB.style.display = displaySlider ? 'block' : 'none';
            p.sldB.style.position = 'absolute';
            p.sldB.style.right = THIS.padding + 'px';
            p.sldB.style.top = THIS.padding + 'px';
            p.sldB.style.border = THIS.insetWidth + 'px solid';
            p.sldB.style.borderColor = THIS.insetColor;

            // slider mouse area
            p.sldM._jscInstance = THIS;
            p.sldM._jscControlName = 'sld';
            p.sldM.style.display = displaySlider ? 'block' : 'none';
            p.sldM.style.position = 'absolute';
            p.sldM.style.right = '0';
            p.sldM.style.top = '0';
            p.sldM.style.width = (THIS.sliderSize + padToSliderPadding / 2 + THIS.padding + 2 * THIS.insetWidth) + 'px';
            p.sldM.style.height = dims[1] + 'px';
            p.sldM.style.cursor = 'default';

            // slider pointer inner and outer border
            p.sldPtrIB.style.border =
            p.sldPtrOB.style.border =
                THIS.pointerBorderWidth + 'px solid ' + THIS.pointerBorderColor;

            // slider pointer outer border
            p.sldPtrOB.style.position = 'absolute';
            p.sldPtrOB.style.left = -(2 * THIS.pointerBorderWidth + THIS.pointerThickness) + 'px';
            p.sldPtrOB.style.top = '0';

            // slider pointer middle border
            p.sldPtrMB.style.border = THIS.pointerThickness + 'px solid ' + THIS.pointerColor;

            // slider pointer spacer
            p.sldPtrS.style.width = THIS.sliderSize + 'px';
            p.sldPtrS.style.height = sliderPtrSpace + 'px';

            // the Close button
            function setBtnBorder () {
                var insetColors = THIS.insetColor.split(/\s+/);
                var outsetColor = insetColors.length < 2 ? insetColors[0] : insetColors[1] + ' ' + insetColors[0] + ' ' + insetColors[0] + ' ' + insetColors[1];
                p.btn.style.borderColor = outsetColor;
            }
            p.btn.style.display = THIS.closable ? 'block' : 'none';
            p.btn.style.position = 'absolute';
            p.btn.style.left = THIS.padding + 'px';
            p.btn.style.bottom = THIS.padding + 'px';
            p.btn.style.padding = '0 15px';
            p.btn.style.height = THIS.buttonHeight + 'px';
            p.btn.style.border = THIS.insetWidth + 'px solid';
            setBtnBorder();
            p.btn.style.color = THIS.buttonColor;
            p.btn.style.font = '12px sans-serif';
            p.btn.style.textAlign = 'center';
            try {
                p.btn.style.cursor = 'pointer';
            } catch(eOldIE) {
                p.btn.style.cursor = 'hand';
            }
            p.btn.onmousedown = function () {
                THIS.hide();
            };
            p.btnT.style.lineHeight = THIS.buttonHeight + 'px';
            p.btnT.innerHTML = '';
            p.btnT.appendChild(document.createTextNode(THIS.closeText));

            // place pointers
            redrawPad();
            redrawSld();

            // If we are changing the owner without first closing the picker,
            // make sure to first deal with the old owner
            if (jsc.picker.owner && jsc.picker.owner !== THIS) {
                jsc.unsetClass(jsc.picker.owner.targetElement, THIS.activeClass);
            }

            // Set the new picker owner
            jsc.picker.owner = THIS;

            // The redrawPosition() method needs picker.owner to be set, that's why we call it here,
            // after setting the owner
            if (jsc.isElementType(container, 'body')) {
                jsc.redrawPosition();
            } else {
                jsc._drawPosition(THIS, 0, 0, 'relative', false);
            }

            if (p.wrap.parentNode != container) {
                container.appendChild(p.wrap);
            }

            jsc.setClass(THIS.targetElement, THIS.activeClass);
        }


        function redrawPad () {
            // redraw the pad pointer
            switch (jsc.getPadYComponent(THIS)) {
            case 's': var yComponent = 1; break;
            case 'v': var yComponent = 2; break;
            }
            var x = Math.round((THIS.hsv[0] / 360) * (THIS.width - 1));
            var y = Math.round((1 - THIS.hsv[yComponent] / 100) * (THIS.height - 1));
            var crossOuterSize = (2 * THIS.pointerBorderWidth + THIS.pointerThickness + 2 * THIS.crossSize);
            var ofs = -Math.floor(crossOuterSize / 2);
            jsc.picker.cross.style.left = (x + ofs) + 'px';
            jsc.picker.cross.style.top = (y + ofs) + 'px';

            // redraw the slider
            switch (jsc.getSliderComponent(THIS)) {
            case 's':
                var rgb1 = HSV_RGB(THIS.hsv[0], 100, THIS.hsv[2]);
                var rgb2 = HSV_RGB(THIS.hsv[0], 0, THIS.hsv[2]);
                var color1 = 'rgb(' +
                    Math.round(rgb1[0]) + ',' +
                    Math.round(rgb1[1]) + ',' +
                    Math.round(rgb1[2]) + ')';
                var color2 = 'rgb(' +
                    Math.round(rgb2[0]) + ',' +
                    Math.round(rgb2[1]) + ',' +
                    Math.round(rgb2[2]) + ')';
                jsc.picker.sldGrad.draw(THIS.sliderSize, THIS.height, color1, color2);
                break;
            case 'v':
                var rgb = HSV_RGB(THIS.hsv[0], THIS.hsv[1], 100);
                var color1 = 'rgb(' +
                    Math.round(rgb[0]) + ',' +
                    Math.round(rgb[1]) + ',' +
                    Math.round(rgb[2]) + ')';
                var color2 = '#000';
                jsc.picker.sldGrad.draw(THIS.sliderSize, THIS.height, color1, color2);
                break;
            }
        }


        function redrawSld () {
            var sldComponent = jsc.getSliderComponent(THIS);
            if (sldComponent) {
                // redraw the slider pointer
                switch (sldComponent) {
                case 's': var yComponent = 1; break;
                case 'v': var yComponent = 2; break;
                }
                var y = Math.round((1 - THIS.hsv[yComponent] / 100) * (THIS.height - 1));
                jsc.picker.sldPtrOB.style.top = (y - (2 * THIS.pointerBorderWidth + THIS.pointerThickness) - Math.floor(sliderPtrSpace / 2)) + 'px';
            }
        }


        function isPickerOwner () {
            return jsc.picker && jsc.picker.owner === THIS;
        }


        function blurValue () {
            THIS.importColor();
        }


        // Find the target element
        if (typeof targetElement === 'string') {
            var id = targetElement;
            var elm = document.getElementById(id);
            if (elm) {
                this.targetElement = elm;
            } else {
                jsc.warn('Could not find target element with ID \'' + id + '\'');
            }
        } else if (targetElement) {
            this.targetElement = targetElement;
        } else {
            jsc.warn('Invalid target element: \'' + targetElement + '\'');
        }

        if (this.targetElement._jscLinkedInstance) {
            jsc.warn('Cannot link jscolor twice to the same element. Skipping.');
            return;
        }
        this.targetElement._jscLinkedInstance = this;

        // Find the value element
        this.valueElement = jsc.fetchElement(this.valueElement);
        // Find the style element
        this.styleElement = jsc.fetchElement(this.styleElement);

        var THIS = this;
        var container =
            this.container ?
            jsc.fetchElement(this.container) :
            document.getElementsByTagName('body')[0];
        var sliderPtrSpace = 3; // px

        // For BUTTON elements it's important to stop them from sending the form when clicked
        // (e.g. in Safari)
        if (jsc.isElementType(this.targetElement, 'button')) {
            if (this.targetElement.onclick) {
                var origCallback = this.targetElement.onclick;
                this.targetElement.onclick = function (evt) {
                    origCallback.call(this, evt);
                    return false;
                };
            } else {
                this.targetElement.onclick = function () { return false; };
            }
        }
        // valueElement
        if (this.valueElement) {
            if (jsc.isElementType(this.valueElement, 'input')) {
                var updateField = function () {
                    THIS.fromString(THIS.valueElement.value, jsc.leaveValue);
                    jsc.dispatchFineChange(THIS);
                };
                jsc.attachEvent(this.valueElement, 'keyup', updateField);
                jsc.attachEvent(this.valueElement, 'input', updateField);
                jsc.attachEvent(this.valueElement, 'blur', blurValue);
                this.valueElement.setAttribute('autocomplete', 'off');
            }
        }

        // styleElement
        if (this.styleElement) {
            this.styleElement._jscOrigStyle = {
                backgroundImage : this.styleElement.style.backgroundImage,
                backgroundColor : this.styleElement.style.backgroundColor,
                color : this.styleElement.style.color
            };
        }

        if (this.value) {
            // Try to set the color from the .value option and if unsuccessful,
            // export the current color
            this.fromString(this.value) || this.exportColor();
        } else {
            this.importColor();
        }
    }

};


//================================
// Public properties and methods
//================================


// By default, search for all elements with class="jscolor" and install a color picker on them.
//
// You can change what class name will be looked for by setting the property jscolor.lookupClass
// anywhere in your HTML document. To completely disable the automatic lookup, set it to null.
//
jsc.jscolor.lookupClass = 'jscolor';


jsc.jscolor.installByClassName = function (className) {
    var inputElms = document.getElementsByTagName('input');
    var buttonElms = document.getElementsByTagName('button');

    jsc.tryInstallOnElements(inputElms, className);
    jsc.tryInstallOnElements(buttonElms, className);
};


jsc.register();


return jsc.jscolor;


})(); }

    </script>
{/literal}