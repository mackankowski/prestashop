<?php /* Smarty version Smarty-3.1.19, created on 2018-01-26 16:11:30
         compiled from "/var/www/html/admin252vbzm39/themes/default/template/controllers/modules/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21311104475a6b4522e21c87-73992053%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '901e2e58a02d3305687ea441a3cc49d951783e93' => 
    array (
      0 => '/var/www/html/admin252vbzm39/themes/default/template/controllers/modules/content.tpl',
      1 => 1516653578,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21311104475a6b4522e21c87-73992053',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'module_content' => 0,
    'context_mode' => 0,
    'logged_on_addons' => 0,
    'iso_code' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a6b4522e48582_91992076',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a6b4522e48582_91992076')) {function content_5a6b4522e48582_91992076($_smarty_tpl) {?>

<?php if (isset($_smarty_tpl->tpl_vars['module_content']->value)) {?>
	<?php echo $_smarty_tpl->tpl_vars['module_content']->value;?>

<?php } else { ?>

	<?php if (isset($_GET['addnewmodule'])&&($_smarty_tpl->tpl_vars['context_mode']->value==Context::MODE_HOST)) {?>

		<div class="defaultForm form-horizontal">

			<?php if ($_smarty_tpl->tpl_vars['logged_on_addons']->value) {?>

				<div class="panel">
					<div class="row">
						<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
							<img class="img-responsive" alt="PrestaShop Addons" src="themes/default/img/prestashop-addons-logo.png">
						</div>
						<div class="col-lg-4 col-lg-offset-1 col-md-4 col-sm-7 col-xs-12 addons-style-search-bar">
							<form id="addons-search-form" method="get" action="http://addons.prestashop.com/<?php echo $_smarty_tpl->tpl_vars['iso_code']->value;?>
/search" class="float">
							<label><?php echo smartyTranslate(array('s'=>'Search on PrestaShop Marketplace:'),$_smarty_tpl);?>
</label>
							<div class="input-group">
								<input id="addons-search-box" class="form-control" type="text" autocomplete="off" name="query" value="" placeholder="Search on PrestaShop Marketplace">
								<div id="addons-search-btn" class="btn btn-primary input-group-addon">
									<i class="icon-search"></i>
								</div>
							</div>
							</form>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-12 addons-see-all-themes">
							<?php echo smartyTranslate(array('s'=>'Or'),$_smarty_tpl);?>
<a href="http://addons.prestashop.com/<?php echo $_smarty_tpl->tpl_vars['iso_code']->value;?>
/2-modules-prestashop" class="btn btn-primary" onclick="return !window.open(this.href)"><?php echo smartyTranslate(array('s'=>'See all modules'),$_smarty_tpl);?>
</a>
						</div>
					</div>
				</div>

			<?php } else { ?>

				<div class="panel" id="">
					<div class="panel-heading">
						<i class="icon-picture"></i> <?php echo smartyTranslate(array('s'=>'Add a new module'),$_smarty_tpl);?>

					</div>

					<div class="form-wrapper">
						<div class="form-group">
							<p><?php echo smartyTranslate(array('s'=>'To add a new module, simply connect to your PrestaShop Addons account and all your purchases will be automatically imported.'),$_smarty_tpl);?>
</p>
						</div>
					</div><!-- /.form-wrapper -->

					<div class="panel-footer">
						<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminModules',true), ENT_QUOTES, 'UTF-8', true);?>
" class="btn btn-default">
							<i class="process-icon-cancel"></i> <?php echo smartyTranslate(array('s'=>'Cancel'),$_smarty_tpl);?>

						</a>
						<a href="#" data-toggle="modal" data-target="#modal_addons_connect" class="btn btn-default pull-right">
							<i class="process-icon-next"></i> <?php echo smartyTranslate(array('s'=>'Next'),$_smarty_tpl);?>

						</a>
					</div>
				</div>

			<?php }?>

				<div class="alert alert-info">
					<h4><?php echo smartyTranslate(array('s'=>'Can I add my own modules?'),$_smarty_tpl);?>
</h4>
					<p><?php echo smartyTranslate(array('s'=>'Please note that for security reasons, you can only add modules that are being distributed on PrestaShop Addons, the official marketplace.'),$_smarty_tpl);?>
</p>
				</div>

		</div>

	<?php } elseif (!isset($_GET['configure'])) {?>
		<?php echo $_smarty_tpl->getSubTemplate ('controllers/modules/js.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ('controllers/modules/page.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<?php }?>
<?php }?>
<?php }} ?>
