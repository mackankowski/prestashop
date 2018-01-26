<?php /* Smarty version Smarty-3.1.19, created on 2018-01-26 16:11:30
         compiled from "/var/www/html/modules/veplatform/views/templates/admin/VeSettings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14093029695a6b4522c7c5d9-19792761%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '876b549a5a9dd6f18969454dcd71efe28c4b9134' => 
    array (
      0 => '/var/www/html/modules/veplatform/views/templates/admin/VeSettings.tpl',
      1 => 1516979136,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14093029695a6b4522c7c5d9-19792761',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'params' => 0,
    'veApi' => 0,
    'baseDir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a6b4522c90ab2_89744899',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a6b4522c90ab2_89744899')) {function content_5a6b4522c90ab2_89744899($_smarty_tpl) {?> 

  <script type="text/javascript">
       $(document).ready(function () {
           //get input params
           var jsonParams = JSON.parse(<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['params']->value);?>
);

           $.ajax({
               type: "POST",
               url: '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['veApi']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
api/veconnect/install',
               data: JSON.stringify(jsonParams),
               contentType: "application/json",
               dataType: "json",
               success: OnSuccess,
               error: OnError
           });

           function OnSuccess(response) {
                $("#veResult").html(response.HtmlView);
                logMessage("Call webservice for configuration","INFO");
                if(response.IsNewFlow) {
                    logMessage("Display iframe from webservice","INFO");
                    $('.veplatform-iframe').load(function() {
                        $("#loadingGif").hide();
                        $("#veResult").show();
                    });
                }
                else {
                    logMessage("Display complete page from webservice","INFO");
                    $("#loadingGif").hide();
                    $("#veResult").show();
                }
           }

           function OnError(response) {
                $("#loadingGif").hide();
                if(typeof response.responseText != undefined && response.responseText != null && response.responseText.length > 0)
                {
                    var json = JSON.parse(response.responseText);
                    if(json.HtmlView == null || json.HtmlView == 'undefined') {
                        logMessage("Display error page from module","ERROR");
                        $("#veplatform").hide();
                        $("#error-page").show();
                    }
                    else {
                        logMessage("Display error page from webservice","ERROR");
                        $("#veResult").html(json.HtmlView);
                        $("#veResult").show();
                    }
                }
                else {
                    logMessage("Display error page from module","ERROR");
                    $("#veplatform").hide();
                    $("#error-page").show();
                }
           }

           function logMessage(message,level) {
                var a = $.ajax({
                    type: 'POST',
                    url: '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['baseDir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
modules/veplatform/classes/ajax-response.php',
                    data: 'method=logMessage&message='+message+'&level='+level,
                    dataType: 'json',
                    complete: function(){

                    }
                });
            }

        });
   </script>

<div id="veplatform">
 <div id="loadingGif">
     <?php if (isset($_smarty_tpl->tpl_vars['veApi']->value)) {?>
     <img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['veApi']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
Areas/Veframe/Content/images/loading.gif" />
     <?php }?>
 </div>

<div id="veResult"></div>
</div>

  <div id="error-page" class="resultView error-page">
    <div id="errorView">
      <div id="veinteractive_main">
            <div class="ve_main">
                <div class="company_info content_grid">
                    <div class="faint-line">
                        <div class="main_messages content_grid">
                            <div class="conf-msg">
                                Oops!
                            </div>
                        </div>
                    </div>
                </div>

                <div class="thanks_info content_grid">
                    <div class="info_text">
                        <p>
                            Ve for Prestashop could not be activated. Please try again later.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
<?php }} ?>
