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
 {if ($rs_choice eq "2")}
    <script type="application/ld+json">
          {
          "@context": "http://schema.org/",
          "@type": "Product",
          "name": "{$product_name|escape:'htmlall':'UTF-8'}",
          "description": "{$product_description|strip_tags|escape:'htmlall':'UTF-8'}",
           "offers":
      [
          {
              "@type": "Offer",
              "priceCurrency": "EUR",
              "price": "{$product_price|escape:'htmlall':'UTF-8'}",
              "availability": "http://schema.org/InStock",
              "name": "{$product_name|escape:'htmlall':'UTF-8'}"
          }
      ],
          "aggregateRating": { 
          "@type": "AggregateRating", 
          "ratingValue": "{$average_rate|escape:'htmlall':'UTF-8'}", 
          "reviewCount": "{$count_reviews|escape:'htmlall':'UTF-8'}",
          "worstRating": "1", 
          "bestRating": "5"
          } 
          }
      </script>
 {else}
<div itemscope itemtype="http://schema.org/Product" id="av_snippets_block">
   <div>
      <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
         <meta itemprop="priceCurrency" content="EUR" />
         <meta itemprop="price" content="{$product_price|escape:'htmlall':'UTF-8'}" />
         <link itemprop="availability" href="http://schema.org/InStock" />
      </span>
   <meta itemprop="name" content="{$product_name|escape:'htmlall':'UTF-8'}" /> 
   <meta itemprop="description" content="{$product_description|strip_tags|escape:'htmlall':'UTF-8'}" />
   <meta itemprop="image" content="{$url_image|escape:'htmlall':'UTF-8'}" />
   {if $brand_name} 
   <meta itemprop="brand" content="{$brand_name|escape:'htmlall':'UTF-8'}" />
   {/if}         
   {if $product_url} 
        <meta itemprop="url" content="{$product_url|escape:'htmlall':'UTF-8'}" />
   {/if}         
   {if $product_id} 
         <meta itemprop="productID" content="{$product_id|escape:'htmlall':'UTF-8'}" />
   {/if}    
   {if $sku} 
        <meta itemprop="sku" content="{$sku|escape:'htmlall':'UTF-8'}" />
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
{if $count_reviews && $average_rate} 
      <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="{$average_rate|escape:'htmlall':'UTF-8'}" />
        <meta itemprop="bestRating" content="5" />
        <meta itemprop="worstRating" content="1" />
        <meta itemprop="reviewCount" content="{$count_reviews|escape:'htmlall':'UTF-8'}" />
      </div>
{/if}
   </div>
</div>
{/if}