<?php /* Smarty version Smarty-3.1.8, created on 2012-10-21 17:58:32
         compiled from "application\themes\raxezwow\template.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15632508437c820d339-57969478%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46937fee1250058642a9b6b2750ccf19adc0a14c' => 
    array (
      0 => 'application\\themes\\raxezwow\\template.tpl',
      1 => 1350467561,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15632508437c820d339-57969478',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'head' => 0,
    'modals' => 0,
    'header_url' => 0,
    'menu_top' => 0,
    'menu_1' => 0,
    'menu_side' => 0,
    'menu_2' => 0,
    'sideboxes' => 0,
    'sidebox' => 0,
    'show_slider' => 0,
    'slider' => 0,
    'image' => 0,
    'page' => 0,
    'serverName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_508437c82ba214_93469657',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_508437c82ba214_93469657')) {function content_508437c82ba214_93469657($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['head']->value;?>

	<body>
		<?php echo $_smarty_tpl->tpl_vars['modals']->value;?>

		<section id="wrapper">
			<header <?php echo $_smarty_tpl->tpl_vars['header_url']->value;?>
></header>
			<div id="top"></div>
			
			<nav>
				<ul>
					<?php  $_smarty_tpl->tpl_vars['menu_1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu_1']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu_top']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu_1']->key => $_smarty_tpl->tpl_vars['menu_1']->value){
$_smarty_tpl->tpl_vars['menu_1']->_loop = true;
?>
						<li><a <?php echo $_smarty_tpl->tpl_vars['menu_1']->value['link'];?>
><?php echo $_smarty_tpl->tpl_vars['menu_1']->value['name'];?>
</a></li>
					<?php } ?>
				</ul>
			</nav>
			
			<!-- body start -->
			<section id="body">
            	<div id="space"></div>
				<aside>
					<section class="side_box">
						<div class="side_box_top">Navigation</b></div>
						<ul>
							<?php  $_smarty_tpl->tpl_vars['menu_2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu_2']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu_side']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu_2']->key => $_smarty_tpl->tpl_vars['menu_2']->value){
$_smarty_tpl->tpl_vars['menu_2']->_loop = true;
?>
								<li><a <?php echo $_smarty_tpl->tpl_vars['menu_2']->value['link'];?>
><?php echo $_smarty_tpl->tpl_vars['menu_2']->value['name'];?>
</a></li>
							<?php } ?>
                        </ul>
					</section>
					
					<?php  $_smarty_tpl->tpl_vars['sidebox'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sidebox']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sideboxes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sidebox']->key => $_smarty_tpl->tpl_vars['sidebox']->value){
$_smarty_tpl->tpl_vars['sidebox']->_loop = true;
?>
						<section class="side_box">
							<div class="side_box_top"><?php echo $_smarty_tpl->tpl_vars['sidebox']->value['name'];?>
</div>
							<div style="padding:5px;">
								<?php echo $_smarty_tpl->tpl_vars['sidebox']->value['data'];?>

							</div>
						</section>
					<?php } ?>
                    
				</aside>
				
				<section id="main">
					<div id="slider_wrapper" <?php if (!$_smarty_tpl->tpl_vars['show_slider']->value){?>style="display:none;"<?php }?>>
						<div id="slider">
							<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['slider']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
?>
								<a href="<?php echo $_smarty_tpl->tpl_vars['image']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['image']->value['image'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['image']->value['text'];?>
"/></a>
							<?php } ?>
						</div>
					</div>

					<?php echo $_smarty_tpl->tpl_vars['page']->value;?>

				</section>
                
                <div class="clear"></div>
			</section>
			<!-- body end -->
			
			<footer>
				&copy; Copyright 2012 <?php echo $_smarty_tpl->tpl_vars['serverName']->value;?>


				<div id="cms">
                	<a href="http://raxezdev.com/fusioncms" target="_blank"></a>
                </div>
            </footer>
		</section>
	</body>
</html>
<?php }} ?>