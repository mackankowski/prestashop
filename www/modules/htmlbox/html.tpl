{if Configuration::get('htmlbox_logged')==1 || Configuration::get('htmlbox_unlogged')==1}
    {if Configuration::get('htmlbox_logged')==1}
        {if $logged==true}
            {assign var='hidefreeblock' value='0'}
        {else}
            {assign var='hidefreeblock' value='1'}
        {/if}
    {elseif Configuration::get('htmlbox_unlogged')==1}
        {if $logged==true}
            {assign var='hidefreeblock' value='1'}
        {else}
            {assign var='hidefreeblock' value='0'}
        {/if}
    {/if}
{else}
    {assign var='hidefreeblock' value='0'}
{/if}



{if $hidefreeblock==0}
    {if $htmlbox_ssl==1}
        {if $is_https_htmlbox==1}
            {if $page_name!='index'}
                {if $htmlbox_home==1}
                    {* disable *}
                {else}
                    {$htmlboxbody}
                {/if}
            {else}
                {$htmlboxbody}
            {/if}
        {/if}
    {else}
        {if $page_name!='index'}
            {if $htmlbox_home==1}
                {* disable *}
            {else}
                {$htmlboxbody nofilter}
            {/if}
        {else}
            {$htmlboxbody nofilter}
        {/if}
    {/if}
{/if}