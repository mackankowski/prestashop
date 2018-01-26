<?php /* Smarty version Smarty-3.1.19, created on 2018-01-26 16:28:26
         compiled from "/var/www/html/admin252vbzm39/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17900949785a6b491a611935-51029716%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '337afca465bc6a3182e9342d5440ff5d09dbb50f' => 
    array (
      0 => '/var/www/html/admin252vbzm39/themes/default/template/content.tpl',
      1 => 1516653578,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17900949785a6b491a611935-51029716',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a6b491a6176c3_20964735',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a6b491a6176c3_20964735')) {function content_5a6b491a6176c3_20964735($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div><?php }} ?>
