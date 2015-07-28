<?php

global $submenu;

// access page settings 
$page_data = array();
foreach($submenu['edit.php?post_type=page'] as $i => $menu_item) {
	if($submenu['edit.php?post_type=page'][$i][2] == 'frayd_pagebatcher')
		$page_data = $submenu['edit.php?post_type=page'][$i];
}

?>
<div class="wrap">
	<?php screen_icon();?>
	<h2><?php echo $page_data[3];?></h2>

	<?php
	if( isset($_REQUEST['success']) && is_numeric($_REQUEST['success']) ) {
		$msg_type = 'updated';
		switch( $_REQUEST['success'] ) {
			case 1: $msg = $_REQUEST['num_pages'] . ' page' . ( $_REQUEST['num_pages'] == 1 ? '' : 's' ) . ' created successfully!'; break;
		}
	}

	if( isset($_REQUEST['error']) && is_numeric($_REQUEST['error']) ) {
		$msg_type = 'error';
		switch( $_REQUEST['error'] ) {
			case 1: $msg = 'Please list out the page(s) you\'d like created below.'; break;
		}
	}
	?>

	<?php if( isset($msg) ): ?>
		<div id="message" class="<?php echo $msg_type ?>"><p><?php echo $msg ?></p></div>
	<?php endif; ?>

	<form id="frayd_pagebatcher_form" action="admin.php?action=frayd_pagebatcher_form_action" method="post">
		<div id="poststuff">
			<div id="post-body" class="columns-2">
				<div id="postbox-container-2">
					<p>
						<label for="frayd_pagebatcher_text_list_field"><b>Pages List</b> <span class="description">(One page per line. Use dashes to create a page hierarchy. Blank lines are ignored.)</span></label><br>
						<textarea id="frayd_pagebatcher_text_list_field" class="large-text code" name="frayd_pagebatcher_text_list" style="height: 20em; width: 30em;" placeholder="First Parent Page"></textarea>
					</p>

					<p>
						<input id="frayd_pagebatcher_publish_status_field" type="checkbox" name="frayd_pagebatcher_publish_status" value="1">
						<label for="frayd_pagebatcher_publish_status_field"><b>Publish Pages</b></label>
						<span class="description">(Otherwise, page will have "Draft" status)</span>
					</p>

					<p>
						<label for="frayd_pagebatcher_menu_name_field"><b>Menu Name</b></label> <span class="description">(If left blank no menu will be created)</span><br>
						<input id="frayd_pagebatcher_menu_name_field" class="regular-text" type="text" name="frayd_pagebatcher_menu_name" style="width: 30em;">
					</p>

					<?php submit_button(); ?>
				</div><!-- END #postbox-container-2 -->

				<div id="postbox-container-1"></div><!-- END #postbox-container-1 -->
			</div>
		</div>
	</form>
</div>