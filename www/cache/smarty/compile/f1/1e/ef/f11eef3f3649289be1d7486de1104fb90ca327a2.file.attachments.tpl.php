<?php /* Smarty version Smarty-3.1.19, created on 2018-01-26 16:33:55
         compiled from "/var/www/html/admin252vbzm39/themes/default/template/controllers/products/attachments.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5965012925a6b4a63ead757-92153840%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f11eef3f3649289be1d7486de1104fb90ca327a2' => 
    array (
      0 => '/var/www/html/admin252vbzm39/themes/default/template/controllers/products/attachments.tpl',
      1 => 1516653578,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5965012925a6b4a63ead757-92153840',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'obj' => 0,
    'id_lang' => 0,
    'languages' => 0,
    'attachment_name' => 0,
    'attachment_description' => 0,
    'attachment_uploader' => 0,
    'attach2' => 0,
    'attach' => 0,
    'attach1' => 0,
    'link' => 0,
    'iso_tiny_mce' => 0,
    'ad' => 0,
    'default_form_language' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a6b4a6401ba85_12966620',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a6b4a6401ba85_12966620')) {function content_5a6b4a6401ba85_12966620($_smarty_tpl) {?>

<?php if (isset($_smarty_tpl->tpl_vars['obj']->value->id)) {?>
<div id="product-attachements" class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" value="Attachments" />
	<h3><?php echo smartyTranslate(array('s'=>'Attachment'),$_smarty_tpl);?>
</h3>

	<div class="form-group">
		<label class="control-label col-lg-3 required" for="attachment_name_<?php echo $_smarty_tpl->tpl_vars['id_lang']->value;?>
">
			<span class="label-tooltip" data-toggle="tooltip"
			title="<?php echo smartyTranslate(array('s'=>'Maximum 32 characters.'),$_smarty_tpl);?>
">
			<?php echo smartyTranslate(array('s'=>'Filename'),$_smarty_tpl);?>

			</span>
		</label>
		<div class="col-lg-9">
			<?php echo $_smarty_tpl->getSubTemplate ("controllers/products/input_text_lang.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('languages'=>$_smarty_tpl->tpl_vars['languages']->value,'input_value'=>$_smarty_tpl->tpl_vars['attachment_name']->value,'input_name'=>"attachment_name"), 0);?>

		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-3" for="attachment_description_<?php echo $_smarty_tpl->tpl_vars['id_lang']->value;?>
">
			<?php echo smartyTranslate(array('s'=>'Description'),$_smarty_tpl);?>

		</label>
		<div class="col-lg-9">
			<?php echo $_smarty_tpl->getSubTemplate ("controllers/products/textarea_lang.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('languages'=>$_smarty_tpl->tpl_vars['languages']->value,'input_name'=>"attachment_description",'input_value'=>$_smarty_tpl->tpl_vars['attachment_description']->value), 0);?>

		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-3" for="attachement_filename">
			<span class="label-tooltip" data-toggle="tooltip"
				title="<?php echo smartyTranslate(array('s'=>'Upload a file from your computer'),$_smarty_tpl);?>
 (<?php echo sprintf('%.2f',Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'));?>
 <?php echo smartyTranslate(array('s'=>'MB max.'),$_smarty_tpl);?>
)">
				<?php echo smartyTranslate(array('s'=>'File'),$_smarty_tpl);?>

			</span>
		</label>
		<?php echo $_smarty_tpl->tpl_vars['attachment_uploader']->value;?>

		<div class="col-lg-3">
		&nbsp;
		</div>
		<div class="col-lg-8">
			<p class="help-block"><?php echo smartyTranslate(array('s'=>'Upload a file from your computer'),$_smarty_tpl);?>
 (<?php echo sprintf('%.2f',Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'));?>
 <?php echo smartyTranslate(array('s'=>'MB max.'),$_smarty_tpl);?>
)</p>
		</div>
	</div>

	<hr/>

	<div class="form-group">
		<div class="col-lg-9 col-lg-offset-3">
			<div class="row">
				<div class="col-lg-6">
					<p><?php echo smartyTranslate(array('s'=>'Available attachments:'),$_smarty_tpl);?>
</p>
					<select multiple id="selectAttachment2">
						<?php  $_smarty_tpl->tpl_vars['attach'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attach']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attach2']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attach']->key => $_smarty_tpl->tpl_vars['attach']->value) {
$_smarty_tpl->tpl_vars['attach']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['attach']->value['id_attachment'];?>
"><?php echo $_smarty_tpl->tpl_vars['attach']->value['name'];?>
</option>
						<?php } ?>
					</select>
					<a href="#" id="addAttachment" class="btn btn-default btn-block">
						<?php echo smartyTranslate(array('s'=>'Add'),$_smarty_tpl);?>

						<i class="icon-arrow-right"></i>
					</a>
				</div>
				<div class="col-lg-6">
					<p><?php echo smartyTranslate(array('s'=>'Attachments for this product:'),$_smarty_tpl);?>
</p>
					<select multiple id="selectAttachment1" name="attachments[]">
						<?php  $_smarty_tpl->tpl_vars['attach'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attach']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attach1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attach']->key => $_smarty_tpl->tpl_vars['attach']->value) {
$_smarty_tpl->tpl_vars['attach']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['attach']->value['id_attachment'];?>
"><?php echo $_smarty_tpl->tpl_vars['attach']->value['name'];?>
</option>
						<?php } ?>
					</select>
					<a href="#" id="removeAttachment" class="btn btn-default btn-block">
						<i class="icon-arrow-left"></i>
						<?php echo smartyTranslate(array('s'=>'Remove'),$_smarty_tpl);?>

					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminProducts'), ENT_QUOTES, 'UTF-8', true);?>
<?php if (isset($_REQUEST['page'])&&$_REQUEST['page']>1) {?>&amp;submitFilterproduct=<?php echo intval($_REQUEST['page']);?>
<?php }?>" class="btn btn-default"><i class="process-icon-cancel"></i> <?php echo smartyTranslate(array('s'=>'Cancel'),$_smarty_tpl);?>
</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> <?php echo smartyTranslate(array('s'=>'Save'),$_smarty_tpl);?>
</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> <?php echo smartyTranslate(array('s'=>'Save and stay'),$_smarty_tpl);?>
</button>
	</div>

	<input type="hidden" name="arrayAttachments" id="arrayAttachments" value="<?php  $_smarty_tpl->tpl_vars['attach'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attach']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attach1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attach']->key => $_smarty_tpl->tpl_vars['attach']->value) {
$_smarty_tpl->tpl_vars['attach']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['attach']->value['id_attachment'];?>
,<?php } ?>" />

	<script type="text/javascript">
		var iso = '<?php echo $_smarty_tpl->tpl_vars['iso_tiny_mce']->value;?>
';
		var pathCSS = '<?php echo @constant('_THEME_CSS_DIR_');?>
';
		var ad = '<?php echo $_smarty_tpl->tpl_vars['ad']->value;?>
';

		if (tabs_manager.allow_hide_other_languages)
			hideOtherLanguage(<?php echo $_smarty_tpl->tpl_vars['default_form_language']->value;?>
);
	</script>
</div>
<?php }?>
<?php }} ?>
