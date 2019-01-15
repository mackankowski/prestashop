{*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* You must not modify, adapt or create derivative works of this source code
*
*  @author    eKomi
*  @copyright 2017 eKomi
*  @license   LICENSE.txt
*}
<div id="widget-container" class="data-ekomi-emp ekomi-widget-container ekomi-widget-{$widgetToken|escape:'htmlall':'UTF-8'}"></div>
<div id="ekomi-product-widget-identifier" class="prod-data-emp"  style="visibility: hidden">{$productIdentifier|escape:'htmlall':'UTF-8'}</div>
<script type="text/javascript">
    (function (w) {
        w['_ekomiWidgetsServerUrl'] = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//widgets.ekomi.com';
        w['_customerId'] = {$customerId|escape:'htmlall':'UTF-8'};
        w['_ekomiDraftMode'] = true;
        w['_language'] = 'en';

        if(typeof(w['_ekomiWidgetTokens']) !== 'undefined'){
            w['_ekomiWidgetTokens'][w['_ekomiWidgetTokens'].length] = '{$widgetToken|escape:'htmlall':'UTF-8'}';
        } else {
            w['_ekomiWidgetTokens'] = new Array('{$widgetToken|escape:'htmlall':'UTF-8'}');
        }

        if(typeof(ekomiWidgetJs) == 'undefined') {
            ekomiWidgetJs = true;

            var scr = document.createElement('script');scr.src = 'https://sw-assets.ekomiapps.de/static_resources/widget.js';
            var head = document.getElementsByTagName('head')[0];head.appendChild(scr);
        }
    })(window);
</script>