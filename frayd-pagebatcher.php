<?php

/*
Plugin Name: PageBatcher
Description: Batch create a hierarchy of pages and an accompanying menu. Find it in Pages &gt; Add New Batch.
Version: 1.0.20150724
Author: Kevin Freitas, Frayd Media
Author URI: http://frayd.us/
*/

define('FRAYD_PAGEBATCHER_DIR', plugin_dir_path(__FILE__)); // USAGE: FRAYD_PAGEBATCHER_DIR.'assets/img/image.jpg'
define('FRAYD_PAGEBATCHER_URL', plugin_dir_url(__FILE__));


// Load files
function frayd_pagebatcher_load() {
	if( is_admin() ) { // load admin files only in admin
		require_once(FRAYD_PAGEBATCHER_DIR.'includes/admin.php');
	}

	require_once(FRAYD_PAGEBATCHER_DIR.'includes/core.php');
}
frayd_pagebatcher_load();
// END Load files

?>