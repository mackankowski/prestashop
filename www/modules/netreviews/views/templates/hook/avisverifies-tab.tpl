<!--
* 2012-2018 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2018 NetReviews SAS
*  @version   Release: $Revision: 7.5.1
*  @license   NetReviews
*  @date      04/04/2018
*  International Registered Trademark & Property of NetReviews SAS
-->
<li>
    <a href="#netreviews_reviews_tab" class="avisverifies_tab" data-toggle="tab" id="tab_avisverifies" >
        {$count_reviews|escape:'htmlall':'UTF-8'}
        {if $count_reviews > 1}
            {l s='reviews' mod='netreviews'}
        {else}
            {l s='review' mod='netreviews'}
        {/if}
    </a>
</li>