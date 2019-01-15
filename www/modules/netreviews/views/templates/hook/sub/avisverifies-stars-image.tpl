<!--
* 2012-2018 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2018 NetReviews SAS
*  @version   Release: $Revision: 7.6.3
*  @license   NetReviews
*  @date      25/07/2018
*  International Registered Trademark & Property of NetReviews SAS
-->
{if $av_star_type == 'big'}  
 <div class="netreviews_stars netreviews_stars_bg">
    <span style="width:{$av_rate_percent_int|escape:'htmlall':'UTF-8'}%;"></span>
 </div>  
{elseif $av_star_type == 'small'} 
    <div class="netreviews_stars netreviews_stars_md">
        <span style="width:{$review.rate_percent|escape:'htmlall':'UTF-8'}%"></span>
    </div>
{elseif $av_star_type == 'widget'} 
    <div class="netreviews_stars netreviews_stars_md">
        <span style="width:{$av_rate_percent_int|escape:'htmlall':'UTF-8'}%"></span>
    </div>
 {/if}
