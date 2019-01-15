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

{if isset($av_rate) && !empty($av_rate)}
     <div class="av_category_stars">
          <a href="{$link_product|escape:'htmlall':'UTF-8'}" title="{$av_nb_reviews|escape:'htmlall':'UTF-8'} {if $av_nb_reviews > 1}{l s='reviews' mod='netreviews'}{else}{l s='review' mod='netreviews'}{/if}"> 
          {if $use_star_format_image}
                  <div class="netreviews_stars netreviews_stars_md">
                    <span style="width:{$av_rate_percent_int|escape:'htmlall':'UTF-8'}%"></span>
                  </div>
          {else}  
              <div class="netreviews_review_rate_and_stars">
                  <div class="netreviews_font_stars">
                     <div>
                        {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
                     </div>
                     <div style="color:#{$customized_star_color}">
                        {for $av_star=0 to $average_rate_percent.floor}<span class="nr-icon nr-star"></span>{/for}{if $average_rate_percent.decimals}<span class="nr-icon nr-star" style="width:{$average_rate_percent.decimals|escape:'htmlall':'UTF-8'}%;"></span>{/if}
                     </div>  
                  </div> 
              </div>
          {/if}
          <div class="ratingText">
              {$av_nb_reviews|escape:'htmlall':'UTF-8'}
                {if $av_nb_reviews > 1}
                    {l s='reviews' mod='netreviews'}
                {else}
                    {l s='review' mod='netreviews'}
              {/if}
          </div>
          </a>
        </div>
{/if}
