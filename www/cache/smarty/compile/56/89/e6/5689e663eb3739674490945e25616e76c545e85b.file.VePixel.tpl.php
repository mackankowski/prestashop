<?php /* Smarty version Smarty-3.1.19, created on 2018-01-26 16:25:13
         compiled from "/var/www/html/modules/veplatform/views/templates/front/VePixel.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12702130005a6b48592e9224-88999696%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5689e663eb3739674490945e25616e76c545e85b' => 
    array (
      0 => '/var/www/html/modules/veplatform/views/templates/front/VePixel.tpl',
      1 => 1516979136,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12702130005a6b48592e9224-88999696',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'url_pixel' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a6b48592f7750_11780215',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a6b48592f7750_11780215')) {function content_5a6b48592f7750_11780215($_smarty_tpl) {?>
<?php if (isset($_smarty_tpl->tpl_vars['url_pixel']->value)) {?>
 <img height="1" width="1" style="position:absolute;top:0;left:0;" src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['url_pixel']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
<?php }?><?php }} ?>
