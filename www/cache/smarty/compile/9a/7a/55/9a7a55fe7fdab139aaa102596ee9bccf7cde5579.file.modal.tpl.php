<?php /* Smarty version Smarty-3.1.19, created on 2019-01-13 12:34:56
         compiled from "/var/www/html/admin252vbzm39/themes/default/template/helpers/modules_list/modal.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3177750785c3b2260257461-59782809%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9a7a55fe7fdab139aaa102596ee9bccf7cde5579' => 
    array (
      0 => '/var/www/html/admin252vbzm39/themes/default/template/helpers/modules_list/modal.tpl',
      1 => 1541839712,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3177750785c3b2260257461-59782809',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c3b226025b0d7_54936840',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c3b226025b0d7_54936840')) {function content_5c3b226025b0d7_54936840($_smarty_tpl) {?><div class="modal fade" id="modules_list_container">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title"><?php echo smartyTranslate(array('s'=>'Recommended Modules and Services'),$_smarty_tpl);?>
</h3>
			</div>
			<div class="modal-body">
				<div id="modules_list_container_tab_modal" style="display:none;"></div>
				<div id="modules_list_loader"><i class="icon-refresh icon-spin"></i></div>
			</div>
		</div>
	</div>
</div>
<?php }} ?>
