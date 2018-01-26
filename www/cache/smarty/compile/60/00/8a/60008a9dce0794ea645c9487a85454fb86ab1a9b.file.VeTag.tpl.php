<?php /* Smarty version Smarty-3.1.19, created on 2018-01-26 16:24:23
         compiled from "/var/www/html/modules/veplatform/views/templates/front/VeTag.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9424206405a6b48276f0423-17142236%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '60008a9dce0794ea645c9487a85454fb86ab1a9b' => 
    array (
      0 => '/var/www/html/modules/veplatform/views/templates/front/VeTag.tpl',
      1 => 1516979136,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9424206405a6b48276f0423-17142236',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'masterData' => 0,
    'url_tag' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a6b48277003e3_03549148',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a6b48277003e3_03549148')) {function content_5a6b48277003e3_03549148($_smarty_tpl) {?>
<?php if (isset($_smarty_tpl->tpl_vars['masterData']->value)) {?>
	<script id="veplatform-masterdata" type="text/javascript">
		var veData = JSON.parse(<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['masterData']->value);?>
);
	</script>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['url_tag']->value)) {?>
	<script type="text/javascript">
   		(function() {
	 	    var ve = document.createElement('script');
	    	ve.type = 'text/javascript';
	    	ve.async = true;
	  	  	ve.src = '<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['url_tag']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
';
	    	var s = document.getElementsByTagName('body')[0];
	    	s.appendChild(ve, s);
		}
    	)();
	</script>
<?php }?>

<?php }} ?>
