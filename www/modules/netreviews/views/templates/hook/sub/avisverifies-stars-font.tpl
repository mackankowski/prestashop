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
{if $av_star_type == 'big'} 
<div class="netreviews_bg_stars_big">
   <div>
      {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
   </div>
   <div style="color:#{$customized_star_color}">
      {for $av_star=0 to $average_rate_percent.floor}<span class="nr-icon nr-star"></span>{/for}{if $average_rate_percent.decimals}<span class="nr-icon nr-star" style="width:{$average_rate_percent.decimals|escape:'htmlall':'UTF-8'}%;"></span>{/if}
   </div>  
</div> 

{elseif $av_star_type == 'small'} 
<div class="netreviews_font_stars">
    <div>
      {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
   </div>
   <div style="color:#{$customized_star_color}">
      {for $av_star=1 to $review.rate}<span class="nr-icon nr-star"></span>{/for}
   </div> 
</div>

{elseif $av_star_type == 'widget'} 
<div class="netreviews_font_stars">
    <div>
      {for $av_star=1 to 5}<span class="nr-icon nr-star grey"></span>{/for}
   </div>
   <div style="color:#{$customized_star_color}">
      {for $av_star=0 to $average_rate_percent.floor}<span class="nr-icon nr-star"></span>{/for}{if $average_rate_percent.decimals}<span class="nr-icon nr-star" style="width:{$average_rate_percent.decimals|escape:'htmlall':'UTF-8'}%;"></span>{/if}
   </div> 
</div>
 {/if}
