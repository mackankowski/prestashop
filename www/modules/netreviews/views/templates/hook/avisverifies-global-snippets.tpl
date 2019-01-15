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
    {if $av_site_rating_avis != 0}

       {if ($rs_choice eq "3")}
           <div id="netreviews_global_website_review"  itemscope="" itemtype="http://data-vocabulary.org/Review-aggregate">
                <meta content="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="itemreviewed" >
                <meta content="{$av_site_rating_avis|escape:'htmlall':'UTF-8'}" itemprop="votes" >
                <span itemprop="rating" itemscope="" itemtype="http://data-vocabulary.org/Rating">
                    <meta content="{$av_site_rating_rate|escape:'htmlall':'UTF-8'}" itemprop="average" >
                    <meta content="5" itemprop="best" >
                    <meta content="1" itemprop="worst" >
                </span>  
            </div>

         {elseif ($rs_choice eq "2")}
         {literal}<script type="application/ld+json">
                {
                "@context": "http://schema.org/",
                "@type": "Website",
                "name": "{/literal}{$shop_name|escape:'htmlall':'UTF-8'}{literal}",
                "url": "{/literal}{$url|escape:'htmlall':'UTF-8'}{literal}",
                    "aggregateRating": { 
                    "@type": "AggregateRating", 
                    "ratingValue": "{/literal}{$av_site_rating_rate|escape:'htmlall':'UTF-8'}{literal}", 
                    "ratingCount": "{/literal}{$av_site_rating_avis|escape:'htmlall':'UTF-8'}{literal}",
                    "worstRating": "1", 
                    "bestRating": "5"
                    } 
                }
        </script>{/literal} 

        {elseif ($rs_choice eq "1")}
                <div id="netreviews_global_website_review" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Website" >
                 <span  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <meta content="{$shop_name|escape:'htmlall':'UTF-8'}" itemprop="name" />
                    <meta content="{$url|escape:'htmlall':'UTF-8'}" itemprop="url" />
                <span class="bandeauServiceClientAvisNoteGros">
                    <meta content="{$av_site_rating_rate|escape:'htmlall':'UTF-8'}" itemprop="ratingValue"/>
                    <meta content="5" itemprop="bestRating" />
                    <meta content="1" itemprop="worstRating" >
                </span>
                    <meta content="{$av_site_rating_avis|escape:'htmlall':'UTF-8'}" itemprop="reviewCount" >
                </span>
                </div>
        {/if}

    {/if}





