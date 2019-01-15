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
<div id="rnr_interactive_screen_bottom" class="signup-wrapper container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="login-wrapper">
                <div class="sign-in">
                    <h2>{l s='Sign in' mod='ekomiratingsandreviews'}</h2>
                    <h4>
                        {l s='You already heve an eKomi account?' mod='ekomiratingsandreviews'}<br/>
                        {l s='You will need your Interface ID and Interface Password.' mod='ekomiratingsandreviews'}
                    </h4>
                    <a href="#" id="rnr_signin" class="btn btn-success">{l s='NEXT' mod='ekomiratingsandreviews'}</a>
                    <div class="link-q">
                        <a target="_blank" href="{$rnr_module_path|escape:'htmlall':'UTF-8'}views/docs/wheredoIfindmyShopIDandPassword.pdf" class="link">
                            {l s='Where do I find my Interface ID and Interface Password?' mod='ekomiratingsandreviews'}
                        </a>
                    </div>
                </div>
                <div class="spacer-or">

                </div>
                <hr>
                <div class="sing-up">
                    <h2>{l s='Sign Up' mod='ekomiratingsandreviews'}</h2>
                    <h4>
                        {l s='Don\'t yet have an eKomi account?' mod='ekomiratingsandreviews'} <br/>
                        {l s='Click here and we will sign you up.' mod='ekomiratingsandreviews'}
                    </h4>
                    <a target="_blank" href="{$rnr_booking_url|escape:'htmlall':'UTF-8'}" class="btn btn-success">{l s='Book your appointment' mod='ekomiratingsandreviews'}</a>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="content-right">
                <div class="ekomi-logo">
                    <img src="{$rnr_module_path|escape:'htmlall':'UTF-8'}views/img/logo.png" alt="logo">
                </div>
            </div>
        </div>
    </div>
</div>