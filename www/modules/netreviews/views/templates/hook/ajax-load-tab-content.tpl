<!--
* 2012-2018 NetReviews
*
*  @author    NetReviews SAS <contact@avis-verifies.com>
*  @copyright 2018 NetReviews SAS
*  @version   Release: $Revision: 7.6.1
*  @license   NetReviews
*  @date      18/05/2018
*  International Registered Trademark & Property of NetReviews SAS
-->
{assign var="av_star_type" value="small"}
<div class="netreviews_reviews_section">
   <div class="loader_av"></div>
      {foreach from=$reviews key=k_review item=review}
         <div class="netreviews_review_part{if $k_review == '0'} first-child{/if}" >
            <p class="netreviews_customer_name">
               {$review.customer_name|escape:'htmlall':'UTF-8'} 
               <span>{if $old_lang}{$av_ajax_translation.a} {$av_ajax_translation.b}{else}{l s='published' mod='netreviews'} {l s='the' mod='netreviews'}{/if} {$review.horodate|escape:'htmlall':'UTF-8'}</span> 
               <span class="order_date">{if $old_lang}{$av_ajax_translation.c}{else}{l s='following an order made on' mod='netreviews'}{/if} {$review.horodate_order|escape:'htmlall':'UTF-8'}</span>
            </p>

         <div class="netreviews_review_rate_and_stars">
               {include file=$stars_dir}    
             <div class="netreviews_reviews_rate">
                  {$review.rate|escape:'htmlall':'UTF-8'}/5
               </div>
            </div>

            <p class="netreviews_customer_review">
               {$review.avis|escape:'htmlall':'UTF-8'}                                
            </p>
            
            {if $review.discussion}
            {foreach from=$review.discussion key=k_discussion item=discussion}
            <div class="netreviews_website_answer" {if $k_discussion > 0} review_number={$review.id_product_av|escape:'htmlall':'UTF-8'} style= "display: none" {/if}>
               <p>
                  <span class="netreviews_answer_title">
                  {if $old_lang}{$av_ajax_translation.d}{else}{l s='Comment from' mod='netreviews'}{/if} {$discussion.origine|escape:'htmlall':'UTF-8'} {if $old_lang}{$av_ajax_translation.b}{else}{l s='the' mod='netreviews'}{/if} {$discussion.horodate|escape:'htmlall':'UTF-8'}
                  </span>
                  <br>
                  {$discussion.commentaire|escape:'htmlall':'UTF-8'}
               </p>
            </div>
         {if $k_discussion > 0}
            <a class="netreviews_button_comment active" href="javascript:switchCommentsVisibility('{$review.id_product_av|escape:'htmlall':'UTF-8'}',1)" id="display{$review.id_product_av|escape:'htmlall':'UTF-8'}" review_number={$review.id_product_av|escape:'htmlall':'UTF-8'}>
            <img class="netreviews_more_comment" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/conversation.png">
            <span>{if $old_lang}{$av_ajax_translation.e}{else}{l s='Show exchanges' mod='netreviews'}{/if}</span>
            </a>  

            <a class="netreviews_button_comment" href="javascript:switchCommentsVisibility('{$review.id_product_av|escape:'htmlall':'UTF-8'}',0)" id="hide{$review.id_product_av|escape:'htmlall':'UTF-8'}" review_number={$review.id_product_av|escape:'htmlall':'UTF-8'} >
            <img class="netreviews_more_comment" src="{$modules_dir|escape:'htmlall':'UTF-8'}netreviews/views/img/conversation.png">
            <span>{if $old_lang}{$av_ajax_translation.f}{else}{l s='Hide exchanges' mod='netreviews'}{/if}</span>
            </a>  
         {/if}
      {/foreach}
      {/if}

            {if (!$hidehelpful)} 
            <!-- helpful START -->
            <p class="netreviews_helpful_block">
                        {if $old_lang}{$av_ajax_translation.g}{else}{l s='Did you find this helpful?' mod='netreviews'}{/if}
                        <a href="javascript:" onclick="javascript:avHelpfulClick('{$review.id_product_av|escape:'htmlall':'UTF-8'}','1','{$review.sign|escape:'htmlall':'UTF-8'}')" class="netreviewsVote" data-review-id="{$review.id_product_av|escape:'htmlall':'UTF-8'}" id="{$review.id_product_av|escape:'htmlall':'UTF-8'}_1">{if $old_lang}{$av_ajax_translation.h}{else}{l s='Yes' mod='netreviews'}{/if} <span>{$review.helpful|escape:'htmlall':'UTF-8'}</span></a>
                        <a href="javascript:" onclick="javascript:avHelpfulClick('{$review.id_product_av|escape:'htmlall':'UTF-8'}','0','{$review.sign|escape:'htmlall':'UTF-8'}')" class="netreviewsVote" data-review-id="{$review.id_product_av|escape:'htmlall':'UTF-8'}" id="{$review.id_product_av|escape:'htmlall':'UTF-8'}_0">{if $old_lang}{$av_ajax_translation.i}{else}{l s='No' mod='netreviews'}{/if} <span>{$review.helpless|escape:'htmlall':'UTF-8'}</span></a>
                    </p>
                    <p class="netreviews_helpfulmsg" id="{$review.id_product_av|escape:'htmlall':'UTF-8'}_msg"></p>
            <!-- helpful END-->
            {/if}

        {if ($hidemedia != '1')} 
        <!-- Media part -->
         {if $review.media_content}
        <ul class="netreviews_media_part">
              {foreach from=$review.media_content key=k_media_content item=media}
                <li>
                    <a data-type="{$media.type}"
                       data-src="{$media.large}"
                       class="netreviews_image_thumb" href="javascript:"
                       style="background-image:url('{$media.small}');">&nbsp;</a>
                </li>               
              {/foreach}
        </ul>
        <div class="netreviews_clear">&nbsp;</div>
         {/if}
            <!-- End media part -->
        {/if}

</div>
{/foreach}

  {if $reviews_max_pages > 1 && $reviews_max_pages > $current_page}
   <div id="netreviews_button_more_reviews">
       <button onclick="javascript:netreviewsFilter('more');" class="netreviews_button">{if $old_lang}{$av_ajax_translation.j}{else}{l s='More reviews...' mod='netreviews'}{/if}</button>
    </div>
   {/if}

</div> 