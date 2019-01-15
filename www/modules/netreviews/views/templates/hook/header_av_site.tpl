<!--
* 2012-2018 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2018 NetReviews SAS
*  @version   Release: $Revision: 7.6.1
*  @license   NetReviews
*  @date      28/05/2018
*  International Registered Trademark & Property of NetReviews SAS
*  For personalized use only
-->
{assign var="av_star_type" value="widget"}
<div class="col-xs-12 col-sm-12 col-lg-12 avis_header_stars">
    <div class="image_wrap">
        <img src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/Sceau_100_fr.png" width="45"/>
    </div>
    <div class="av_text_wrap" >
       <span class="av_text_wrap_inner">Avis de nos clients :</span>
         <div class="netreviews_review_rate_and_stars">
            {include file=$stars_dir}  
          </div>
    </div>
    <div class="av_rate_values">
         <span>{$av_site_rating_rate|escape:'htmlall':'UTF-8'}</span>/<span>5</span>
    </div>

    <div  class="av_author"> par Avis vérifies</div>
</div>




