<!--
* 2012-2018 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2017 NetReviews SAS
*  @version   Release: $Revision: 7.6.3
*  @license   NetReviews
*  @date      25/07/2018
*  International Registered Trademark & Property of NetReviews SAS
-->

<div id="netreviews_category_review">
  <img width="100" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/logo_full_{$logo_lang|escape:'htmlall':'UTF-8'}.png">
  <div>
    <b>{$nom_category|escape:'htmlall':'UTF-8'}</b>
    <div class="netreviews_review_rate_and_stars">
      <div class="netreviews_font_stars">
         <div>
            {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
         </div>
         <div>
            {for $av_star=0 to $av_rate_percent.floor}<span class="nr-icon nr-star gold"></span>{/for}{if $av_rate_percent.decimals}<span class="nr-icon nr-star gold" style="width:{$av_rate_percent.decimals|escape:'htmlall':'UTF-8'}%;"></span>{/if}
         </div>  
      </div> 
    </div>
    {$average_rate|escape:'htmlall':'UTF-8'} /5
  </div>
</div>


