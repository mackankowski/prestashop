<?php /* Smarty version Smarty-3.1.19, created on 2018-01-26 16:23:08
         compiled from "/var/www/html/admin252vbzm39/themes/default/template/controllers/modules/warning_module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19892033925a6b47dc93e959-43640868%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e24eb1d44a8c54c8dfafd9e5a88bc05112ec4a5' => 
    array (
      0 => '/var/www/html/admin252vbzm39/themes/default/template/controllers/modules/warning_module.tpl',
      1 => 1516653578,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19892033925a6b47dc93e959-43640868',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'module_link' => 0,
    'text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a6b47dc952388_90745722',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a6b47dc952388_90745722')) {function content_5a6b47dc952388_90745722($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_link']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</a><?php }} ?>
