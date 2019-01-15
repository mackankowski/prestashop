<?php

class htmlbox extends Module
{
    function __construct()
    {
        $this->name = 'htmlbox';
        $this->tab = 'front_office_features';
        $this->author = 'MyPresta.eu';
        $this->mypresta_link = 'https://mypresta.eu/modules/front-office-features/html-box.html';
        $this->module_key = '44873542187effe440a9606087efd6e7';
        $this->version = '1.7.0';
        $this->bootstrap = true;
        $this->dir = '/modules/htmlbox/';
        parent::__construct();
        $this->displayName = $this->l('HTMLbox');
        $this->description = $this->l('With this module you can put the HTML/JavaScript/CSS code anywhere you want');
        $this->checkforupdates();

        $this->modulehooks['header']['ps15'] = 1;
        $this->modulehooks['header']['ps16'] = 1;
        $this->modulehooks['header']['ps17'] = 1;
        $this->modulehooks['top']['ps15'] = 1;
        $this->modulehooks['top']['ps16'] = 1;
        $this->modulehooks['top']['ps17'] = 1;
        $this->modulehooks['topcolumn']['ps15'] = 0;
        $this->modulehooks['topcolumn']['ps16'] = 1;
        $this->modulehooks['topcolumn']['ps17'] = 0;
        $this->modulehooks['displaynav']['ps15'] = 0;
        $this->modulehooks['displaynav']['ps16'] = 1;
        $this->modulehooks['displaynav']['ps17'] = 1;
        $this->modulehooks['leftcolumn']['ps15'] = 1;
        $this->modulehooks['leftcolumn']['ps16'] = 1;
        $this->modulehooks['leftcolumn']['ps17'] = 1;
        $this->modulehooks['rightcolumn']['ps15'] = 1;
        $this->modulehooks['rightcolumn']['ps16'] = 1;
        $this->modulehooks['rightcolumn']['ps17'] = 1;
        $this->modulehooks['footer']['ps15'] = 1;
        $this->modulehooks['footer']['ps16'] = 1;
        $this->modulehooks['footer']['ps17'] = 1;
        $this->modulehooks['home']['ps15'] = 1;
        $this->modulehooks['home']['ps16'] = 1;
        $this->modulehooks['home']['ps17'] = 1;
    }

    public function inconsistency($return)
    {
        return true;
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 12 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php'))
        {
            @require_once('../modules/' . $this->name . '/key.php');
        }
        else
        {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php'))
            {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            }
            else
            {
                if (@file_exists('modules/' . $this->name . '/key.php'))
                {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        if ($form == 1)
        {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? ($this->inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        }
        else
        {
            if (defined('_PS_ADMIN_DIR_'))
            {
                if (Tools::isSubmit('submit_settings_updates'))
                {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') == false)
                {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200))
                    {
                        $actual_version = htmlboxUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (htmlboxUpdate::version($this->version) < htmlboxUpdate::version(Configuration::get('updatev_' . $this->name)))
                    {
                        $this->warning = $this->l('New version available, check http://MyPresta.eu for more informations');
                    }
                }
                if ($display_msg == 1)
                {
                    if (htmlboxUpdate::version($this->version) < htmlboxUpdate::version(htmlboxUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version)))
                    {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    }
                    else
                    {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }


    static function remove_doublewhitespace($s = null)
    {
        return $ret = preg_replace('/([\s])\1+/', ' ', $s);
    }

    static function remove_whitespace($s = null)
    {
        $ret = preg_replace('/[\s]+/', '', $s);
        $ret = mysql_escape_string($ret);
        return $ret;
    }

    static function remove_whitespace_feed($s = null)
    {
        //$ret = preg_replace('/[\t\n\r\0\x0B]/', ' ', $s);
        $ret = str_replace("// <![CDATA[", "", $s);
        $ret = str_replace("// ]]>", "", $ret);
        return $ret;
    }

    static function smart_clean($s = null)
    {
        return $ret = trim(self::remove_doublewhitespace(self::remove_whitespace_feed($s)));
    }

    public function installModuleHooks()
    {
        foreach ($this->modulehooks AS $modulehook => $value)
        {
            if (($this->psversion() == 4 && $value['ps15'] == 1) || ($this->psversion() == 5 && $value['ps15'] == 1) || ($this->psversion() == 6 && $value['ps16'] == 1) || ($this->psversion() == 7 && $value['ps17'] == 1))
            {
                if ($this->registerHook($modulehook) == false)
                {
                    return false;
                }
            }
        }
        return true;
    }

    public function install()
    {
        if (parent::install() == false OR $this->installModuleHooks() == false OR Configuration::updateValue('update_' . $this->name, '0') == false OR Configuration::updateValue('htmlbox_header', '0') == false OR Configuration::updateValue('htmlbox_top', '0') == false OR Configuration::updateValue('htmlbox_leftcol', '0') == false OR Configuration::updateValue('htmlbox_rightcol', '1') == false OR Configuration::updateValue('htmlbox_footercol', '0') == false OR Configuration::updateValue('htmlbox_homecol', '0') == false OR Configuration::updateValue('htmlbox_body', 'enter the code here') == false OR Configuration::updateValue('htmlbox_ssl', '0') == false)
        {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $output = "";
        if (Tools::isSubmit('submitSettingsHtmlbox'))
        {
            $v = trim($_POST['htmlbox_body']);
            $v = self::smart_clean($v);
            $body = $v;
            Configuration::updateValue('htmlbox_origtiny', Tools::getValue('htmlbox_origtiny'));
            Configuration::updateValue('htmlbox_body', $body, true);
            Configuration::updateValue('htmlbox_ssl', Tools::getValue('htmlbox_ssl'));
            Configuration::updateValue('htmlbox_home', Tools::getValue('htmlbox_home'));
            Configuration::updateValue('htmlbox_hook', Tools::getValue('htmlbox_hook'));
            Configuration::updateValue('htmlbox_logged', Tools::getValue('htmlbox_logged'));
            Configuration::updateValue('htmlbox_unlogged', Tools::getValue('htmlbox_unlogged'));
            $output .= '<div class="alert alert-success">' . $this->l('Settings updated') . '</div>';
        }
        $output .= "";
        return $output . $this->displayForm();
    }

    public function psversion()
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        return $exp[1];
    }

    public function displayForm()
    {
        global $cookie;
        $iso = Language::getIsoById((int)($cookie->id_lang));
        $isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en');
        $ad = dirname($_SERVER["PHP_SELF"]);
        $form = '';

        if ($this->psversion() == 6 || $this->psversion() == 7)
        {
            if (Configuration::get('htmlbox_origtiny') == 1)
            {
                $form .= '<script type="text/javascript" src="../js/admin/tinymce.inc.js"></script>';
            }
            else
            {
                $form .= '<script type="text/javascript" src="../modules/htmlbox/tinymce16.inc.js"></script>';
            }
            $form.= ' <script>
            	        function addClass(id){
            				tinyMCE.execCommand("mceToggleEditor", false, id);
            			}
            			
            			function removeClass(id){
            				tinyMCE.execCommand("mceToggleEditor", false, id);
            			}	
                    </script>';
        }

        if ($this->psversion() == 5 || $this->psversion() == 6 || $this->psversion() == 7)
        {
            $form .= '
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
    			var iso = \'' . $isoTinyMCE . '\' ;
    			var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
    			var ad = \'' . $ad . '\' ;
			</script>';
            if (Configuration::get('htmlbox_origtiny') == 1)
            {
                $form .= '<script>$(document).ready(function(){tinySetup();});</script>';
            }
        }

        if ($this->psversion() == 5)
        {
            $form .= '<script>
                	$(document).ready(function(){
            			tinySetup({
            				editor_selector :"rte",
                    		theme_advanced_buttons1 : "code, save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,fontselect,fontsizeselect",
                    		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,codemagic,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                    		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                    		theme_advanced_buttons4 : "styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
                    		theme_advanced_toolbar_location : "top",
                    		theme_advanced_toolbar_align : "left",
                    		theme_advanced_statusbar_location : "bottom",
                    		theme_advanced_resizing : false,
                            extended_valid_elements: \'pre[*],script[*],style[*]\',
                            valid_children: "+body[meta|style|script],pre[script|div|p|br|span|img|style|h1|h2|h3|h4|h5],*[*],+body[style],+body[script]",
                            custom_elements: "*[*],script[charset|defer|language|src|type],style",
                            valid_elements : \'*[*]\',
                            force_p_newlines : false,
                            cleanup: false,
                            forced_root_block : false,
                            force_br_newlines : true,
                            verify_html : false,
                            media_strict: false
            				});
            			});
                        </script>';
            $form .= '<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tinymce.inc.js"></script>';
        }

        if ($this->psversion() == 5 || $this->psversion() == 4 || $this->psversion() == 3)
        {
            $form .= '
              <script>  
              function addClass(id){                    
				tinyMCE.execCommand(\'mceAddControl\', true, \'htmlbox_body\');
              }
			
			  function removeClass(id){    
				tinyMCE.execCommand(\'mceRemoveControl\', false, \'htmlbox_body\');
			  }	
              </script>
            ';
        }

        if ($this->psversion() == 4)
        {
            $form = '
    			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tiny_mce/tiny_mce.js"></script>
    			<script type="text/javascript">
    			var iso = \'' . $isoTinyMCE . '\' ;
    			var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
    			var ad = \'' . $ad . '\' ;
			
                function addClass(id){                    
    				tinyMCE.execCommand(\'mceAddControl\', true, \'htmlbox_body\');
    			}
    			
    			function removeClass(id){
    				tinyMCE.execCommand(\'mceRemoveControl\', false, \'htmlbox_body\');
    			}
			</script>
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tinymce.inc.js"></script>
			';
        }


        if ($this->psversion() == 3)
        {
            $form = '
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
		    <script type="text/javascript">
                function addClass(id){                    
    				tinyMCE.execCommand(\'mceAddControl\', true, \'htmlbox_body\');
    			}
    			
    			function removeClass(id){
    				tinyMCE.execCommand(\'mceRemoveControl\', false, \'htmlbox_body\');
    			}
                $(document).ready(function(){
                    tinyMCE.init({
						mode : "textareas",
						theme : "advanced",
						plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",
						// Theme options
						theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
						theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
						theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
						theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_statusbar_location : "bottom",
						theme_advanced_resizing : false,
						width: "600",
						height: "400",
						font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
						// Drop lists for link/image/media/template dialogs
						template_external_list_url : "lists/template_list.js",
						external_link_list_url : "lists/link_list.js",
						external_image_list_url : "lists/image_list.js",
						media_external_list_url : "lists/media_list.js",
						entity_encoding: "raw",
						convert_urls : false,
						language : "en"
					});
                });
		    </script>';
        }

        $select_options = '';
        foreach ($this->modulehooks AS $modulehook => $value)
        {
            if (($this->psversion() == 4 && $value['ps15'] == 1) || ($this->psversion() == 5 && $value['ps15'] == 1) || ($this->psversion() == 6 && $value['ps16'] == 1) || ($this->psversion() == 7 && $value['ps17'] == 1))
            {
                $select_options .= "<option value=\"$modulehook\" " . (Configuration::get('htmlbox_hook') == $modulehook ? 'selected="yes"' : '') . ">" . $modulehook . "</option>";
            }
        }


        return $form . '
        <div class="panel">
        
            <div style="diplay:block; clear:both; margin-bottom:20px;">
              <iframe src="//apps.facepages.eu/somestuff/onlyexample.html" width="100%" height="150" border="0" style="border:none;"></iframe>
            </div>
		</div>
		<div class="panel nobootstrap">
		<h3>' . $this->l('Appearance of module') . '</h3>
		<form name="htmlboxform" id="htmlboxform" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <div style="display:block; margin:auto; vertical-align:top;">
                    <div style="clear:both; display:block; vertical-align:top;">
						<fieldset style="margin-bottom:20px; display:block; vertical-align:top; clear:both;">
                            <label>' . $this->l('Use original editor (default tinymce)') . '</label>
            			    <div class="margin-form">
                                <input type="checkbox" onchange="htmlboxform.submit();" name="htmlbox_origtiny" value="1" ' . (Configuration::get('htmlbox_origtiny') == 1 ? 'checked="checked"' : '') . '/>
                                <p class="small">'.$this->l('Use this option if you want to create own contents with default rich text editor.').' '.$this->l('Turn it on also if you use some module to extend rich text editor like: ').' <a href="https://mypresta.eu/modules/administration-tools/tinymce-pro-extended-rich-text-editor.html">TinyMCE pro</a>.'.$this->l('Module will use this extended editor then').'</p>
                            </div>
                            <label>' . $this->l('Where you want to display content box?') . '</label>
            			    <div class="margin-form">
    			                <select name="htmlbox_hook">
                                    ' . $select_options . '
                                <select>
                            </div>
                            <br/>
                            <label>' . $this->l('Only on SSL page') . '</label>
            			    <div class="margin-form">
                                <input type="checkbox" name="htmlbox_ssl" value="1" ' . (Configuration::get('htmlbox_ssl') == 1 ? 'checked="checked"' : '') . '/>
                            </div>
                            <label>' . $this->l('Only on homepage') . '</label>
            			    <div class="margin-form">
                                <input type="checkbox" name="htmlbox_home" value="1" ' . (Configuration::get('htmlbox_home') == 1 ? 'checked="checked"' : '') . '/>
                            </div>
                            <label>' . $this->l('Only for logged customers') . '</label>
            			    <div class="margin-form">
                                <input type="checkbox" name="htmlbox_logged" value="1" ' . (Configuration::get('htmlbox_logged') == 1 ? 'checked="checked"' : '') . '/>
                            </div>
                            <label>' . $this->l('Only for unlogged customers') . '</label>
            			    <div class="margin-form">
                                <input type="checkbox" name="htmlbox_unlogged" value="1" ' . (Configuration::get('htmlbox_unlogged') == 1 ? 'checked="checked"' : '') . '/>
                            </div>
                            <label>' . $this->l('More appearance options') . '</label>
            			    <div class="margin-form">
                                <a href="https://mypresta.eu/modules/front-office-features/html-box-pro.html" target="_blank">html box pro</a>
                            </div>
						</fieldset>             
						
                        <fieldset style="margin-bottom:20px; display:block; vertical-align:top; clear:both;">
                            <div class="bootstrap">
                        		<div class="alert alert-warning">
                        			<button type="button" class="close" data-dismiss="alert"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    <h4>' . $this->l('JavaScrpt usage') . '</h4>
                                    ' . $this->l('if you want to use <script></script> tags remember to disable html purifier') . '<br/>
                                    <a href="https://mypresta.eu/en/art/prestashop-16/disable-html-purifier.html" target="_blank">' . $this->l('How to disable HTML purifier') . '</a>
                        		</div>
                        	</div>
                            ' . (1 == 1 ? '<span onclick="$(\'#htmlbox_body\').addClass(\'rte\'); addClass(\'htmlbox_body\');" style="" class="button clearfix btn btn-default">' . $this->l('Switch editor') . '</span>' : '') . '
       						<textarea class="rte rtepro" type="text" style="margin-bottom:10px; width:99%; height:300px;" id="htmlbox_body" name="htmlbox_body">' . Configuration::get('htmlbox_body') . '</textarea>
                            <input type="hidden" name="submitSettingsHtmlbox" val="1" />
                        </fieldset>                    
                    </div>
                    <div class="panel-footer"><button type="submit" name="submit_settings" class="button btn btn-default pull-right" onclick="addClass(\'htmlbox_body\');"/><i class="process-icon-save"></i>' . $this->l('Save') . '</button></div>
            </div>
		</form>
		</div>
        ' . $this->checkforupdates(0, 1) . '<div style="diplay:block; clear:both; margin-bottom:10px;">
		</div>' . $this->l('like us on Facebook') . '</br><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Fmypresta&amp;send=false&amp;layout=button_count&amp;width=120&amp;show_faces=true&amp;font=verdana&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=276212249177933" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:120px; height:21px; margin-top:10px;" allowtransparency="true"></iframe>
        ' . '<div style="float:right; text-align:right; display:inline-block; margin-top:10px; font-size:10px;">
        ' . $this->l('Proudly developed by') . ' <a href="http://mypresta.eu" style="font-weight:bold; color:#B73737">MyPresta<font style="color:black;">.eu</font></a>
        </div>' . "<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement(\"iframe\");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src=\"javascript:false\",r.title=\"\",r.role=\"presentation\",(r.frameElement||r).style.cssText=\"display: none\",d=document.getElementsByTagName(\"script\"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain=\"'+n+'\";void(0);',o=s}o.open()._l=function(){var o=this.createElement(\"script\");n&&(this.domain=n),o.id=\"js-iframe-async\",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload=\"document._l();\">'),o.close()}(\"//assets.zendesk.com/embeddable_framework/main.js\",\"prestasupport.zendesk.com\");/*]]>*/</script>";
    }

    public function prepareBody($body)
    {
        $body = str_replace(array(
            "\rn",
            "\r",
            "\n"
        ), array(
            ' ',
            ' ',
            ' '
        ), $body);
        $body = str_replace("// <![CDATA[", "", $body);
        $body = str_replace("// ]]>", "", $body);
        return $body;
    }

    public function prepareDatas()
    {
        global $smarty;
        $smarty->assign(array('htmlboxbody' => nl2br(stripslashes($this->prepareBody(Configuration::get('htmlbox_body'))))));
        $smarty->assign(array('is_https_htmlbox' => (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == "on" ? 1 : 0)));
        $smarty->assign(array('htmlbox_ssl' => Configuration::get('htmlbox_ssl')));
        $smarty->assign(array('htmlbox_home' => Configuration::get('htmlbox_home')));
    }

    function hookrightColumn($params)
    {
        if (Configuration::get('htmlbox_hook') == "rightcolumn")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }

    function hookleftColumn($params)
    {
        if (Configuration::get('htmlbox_hook') == "leftcolumn")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }

    function hookhome($params)
    {
        if (Configuration::get('htmlbox_hook') == "home")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }

    function hookfooter($params)
    {
        if (Configuration::get('htmlbox_hook') == "footer")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }

    function hookheader($params)
    {
        if (Configuration::get('htmlbox_hook') == "header")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }

    function hooktop($params)
    {
        if (Configuration::get('htmlbox_hook') == "top")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }

    public function hookdisplayTopColumn($params)
    {
        if (Configuration::get('htmlbox_hook') == "topcolumn")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }

    public function hookdisplayNav($params)
    {
        if (Configuration::get('htmlbox_hook') == "displaynav")
        {
            $this->prepareDatas();
            return $this->display(__FILE__, 'html.tpl');
        }
    }
}

class htmlboxUpdate extends htmlbox
{
    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3)
        {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2)
        {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1)
        {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0)
        {
            $version = (int)$version . "0000";
        }
        return (int)$version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen"))
        {
            if (function_exists("file_get_contents"))
            {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}

?>