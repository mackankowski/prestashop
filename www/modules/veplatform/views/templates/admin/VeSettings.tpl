 {*
 * NOTICE OF LICENSE
 *
 * @author    Ve Interactive <info@veinteractive.com>
 * @copyright 2017 Ve Interactive
 * @license   MIT License
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *}

  <script type="text/javascript">
       $(document).ready(function () {
           //get input params
           var jsonParams = JSON.parse({$params|json_encode});

           $.ajax({
               type: "POST",
               url: '{$veApi|escape:'htmlall':'UTF-8'}api/veconnect/install',
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
                    url: '{$baseDir|escape:'htmlall':'UTF-8'}modules/veplatform/classes/ajax-response.php',
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
     {if isset($veApi)}
     <img src="{$veApi|escape:'htmlall':'UTF-8'}Areas/Veframe/Content/images/loading.gif" />
     {/if}
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
