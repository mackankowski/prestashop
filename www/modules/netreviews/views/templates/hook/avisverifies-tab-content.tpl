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

<section class="{if $version_ps < 1.7} tab-pane tab_media{/if} {if $nrResponsive == '1'}nrResponsive{/if}"  id="netreviews_reviews_tab">
{if ($snippets_complete == "1")}
<div itemscope itemtype="http://schema.org/Product">
   <meta itemprop="description" content="{$product_description|strip_tags|escape:'htmlall':'UTF-8'}">
   <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
      <meta itemprop="priceCurrency" content="EUR">
      <meta itemprop="price" content="{$product_price|escape:'htmlall':'UTF-8'}">
      <link itemprop="availability" href="http://schema.org/InStock" />
   </span>
   <meta itemprop="name" content="{$product_name|escape:'htmlall':'UTF-8'}" />
   <meta itemprop="image" content="{$url_image|escape:'htmlall':'UTF-8'}" />
   {if $product_url} 
   <meta itemprop="url" content="{$product_url|escape:'htmlall':'UTF-8'}" />
   {/if}         
   {if $product_id} 
   <meta itemprop="productID" content="{$product_id|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $sku} 
   <meta itemprop="sku" content="{$sku|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $brand_name} 
   <meta itemprop="brand" content="{$brand_name|escape:'htmlall':'UTF-8'}" />
   {/if}       
   {if $mpn} 
   <meta itemprop="mpn" content="{$mpn|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $gtin_ean} 
   <meta itemprop="gtin8" content="{$gtin_ean|escape:'htmlall':'UTF-8'}" />
   {/if}   
   {if $gtin_upc} 
   <meta itemprop="gtin12" content="{$gtin_upc|escape:'htmlall':'UTF-8'}" />
   {/if}  
   {/if}
   <div id="netreviews_rating_section" data-group-name="{$nom_group|escape:'htmlall':'UTF-8'}"  data-url-ajax="{$modules_dir|escape:'htmlall':'UTF-8'}" data-idshop="{$id_shop|escape:'htmlall':'UTF-8'}" data-productid="{$product_id|escape:'htmlall':'UTF-8'}" data-current-page="1" data-current-option="horodate_DESC"  data-sortbynote="0" data-max-page="{$reviews_max_pages|escape:'htmlall':'UTF-8'}">
      {if $snippets_active} 
       <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
           <meta itemprop= "ratingValue" content= "{$average_rate|floatval}">
           <meta itemprop= "bestRating" content= "5">
           <meta itemprop= "worstRating" content= "1">
      {/if}
      <input type="hidden" value="{$av_idwebsite|escape:'htmlall':'UTF-8'}" id="av_idwebsite"/>
      <input type="hidden" value="{$avHelpfulURL|escape:'htmlall':'UTF-8'}" id="avHelpfulURL"/>
      <input type="hidden" value="{l s='Thank you, your vote will be published soon.' mod='netreviews'}" id="avHelpfulSuccessMessage"/>
      <input type="hidden" value="{l s='An error has occurred.' mod='netreviews'}" id="avHelpfulErrorMessage"/>
      <div class="netreviews_rating_header">
         <img class="netreviews_logo" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/logo_full_{$logo_lang|escape:'htmlall':'UTF-8'}.png">
         <div class="av-certificate">
            <a class="netreviews_certification" target="_blank" href="{$url_certificat|escape:'htmlall':'UTF-8'}">{l s='View the trust certificate' mod='netreviews'}</a><br>
            <label id="netreviews_informations_label">
             <div class="av-labeltext">{l s='Reviews subject to control' mod='netreviews'}</div>
             {if $use_image}<img src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/icone_information.png" height="15px" width="15px" alt="" />{else}<div class="nr-icon nr-info netreviews_icone_info"></div>{/if}
            </label>
            <span id="netreviews_informations">
               {if $use_image}<img src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/exit_information.png" height="15px" width="15px" alt="" />{else}<div class="nr-icon nr-exit netreviews_exit_info"></div>{/if}
               <ul>
                  <li> {l s='For further information on the nature of the review controls, as well as the possibility of contacting the author of the review please consult our' mod='netreviews'} <a href="{$url_cgv|escape:'htmlall':'UTF-8'}" target="_blank">CGU</a>.</li>
                  <li> {l s='No inducements have been provided for these reviews' mod='netreviews'}</li>
                  <li> {l s='Reviews are published and kept for a period of five years' mod='netreviews'}</li>
                  <li> {l s='Reviews can not be modified: If a customer wishes to modify their review then they can do so by contacting Verified Reviews directly to remove the existing review and publish an amended one' mod='netreviews'}</li>
                  <li> {l s='The reasons for deletion of reviews are available' mod='netreviews'} <a href="{$url_cgv|escape:'htmlall':'UTF-8'}#Rejet_de_lavis_de_consommateur" target="_blank">{l s='here' mod='netreviews'}</a>.
                  </li>
               </ul>
            </span>
         </div>
      </div>
      <div class="netreviews_rating_content">
         <div class="netreviews_global_rating">
            <p class="netreviews_note_generale">
               {$average_rate|floatval}<span> /5</span>
            </p>
               {assign var="av_star_type" value="big"}
               {include file=$stars_dir}             
            <p class="netreviews_subtitle">
                {l s='Based on' mod='netreviews'} <span id="reviewCount" {if $snippets_active} itemprop="reviewCount" {/if}>{$count_reviews|escape:'htmlall':'UTF-8'}</span> {l s='customer reviews' mod='netreviews'}                            
            </p>
         </div>

         <div class="netreviews_global_rating_details">
            <ul class="netreviews_rates_list">
               {foreach from=$reviews_rate_portion key=reviews_rate_portion_key item=review_portion}
               <li class="netreviews_rate_list_item {if $review_portion > 0}cursorp{/if}" {if $review_portion > 0} onclick="javascript:netreviewsFilter({$reviews_rate_portion_key|escape:'htmlall':'UTF-8'});" {/if} >
                  <span class="av_star_index" {if (!$use_image)}style="color:#{$customized_star_color}"{/if}>{$reviews_rate_portion_key|escape:'htmlall':'UTF-8'}</span>
                  {if $use_image}<img src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/star-gold-16.png" height="15px" width="15px" alt="" />{else}<div class="nr-icon nr-star" style="color:#{$customized_star_color}"></div>{/if}
                  <div class="netreviews_rate_graph">
                     <span class="netreviews_rate_percent" style="{if (!$use_image)}background:#{$customized_star_color};{/if}height:{if $review_portion > 0}{math equation='a/b*100' a=$review_portion b=$count_reviews}{else}1{/if}%">
                     <span class="netreviews_rate_total{$reviews_rate_portion_key|escape:'htmlall':'UTF-8'}"> {$review_portion|escape:'htmlall':'UTF-8'} </span>
                     </span>
                  </div>
               </li>
               {/foreach}
            </ul>
         </div>
      </div>
      {if $snippets_active} 
      </div>
      {/if}
      <!-- filtering section -->
      <div class="netreviews_filtering_section">
         <span class="netreviews_filtering_section_title">{l s='Sort reviews by' mod='netreviews'} :</span>
         <select id="netreviews_reviews_filter" name="netreviews_reviews_filter" onchange="javascript:netreviewsFilter(this.value);">
            <option value="horodate_DESC" selected="selected">---</option>
            <option value="horodate_DESC">{l s='Most recent' mod='netreviews'}</option>
            <option value="horodate_ASC">{l s='Oldest' mod='netreviews'}</option>
            <option value="rate_DESC">{l s='Best rating' mod='netreviews'}</option>
            <option value="rate_ASC">{l s='Worst rating' mod='netreviews'}</option>
             {if (!$hidehelpful)} <option value="helpfulrating_DESC">{l s='Most useful' mod='netreviews'}</option>{/if}
         </select>
      </div>
   </div>

{include file=$ajax_dir}

{if ($snippets_complete == "1")}
</div> <!-- End product --> 
{/if}
  <!-- The Modal -->
    <div id="netreviews_media_modal">
        <div id="netreviews_media_content"></div>
        <!-- The Close Button -->
        <a id="netreviews_media_close">×</a>
        <!-- Modal Content (The Image) -->
    </div>
</section>