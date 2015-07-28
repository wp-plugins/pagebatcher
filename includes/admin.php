<?php

function frayd_pagebatcher_admin_load_script() {
	wp_register_style( 'frayd_pagebatcher_admin_style', FRAYD_PAGEBATCHER_URL.'assets/css/frayd-pagebatcher-admin.css', false, '1.0' );
	wp_enqueue_style( 'frayd_pagebatcher_admin_style' );
}
add_action( 'admin_enqueue_scripts', 'frayd_pagebatcher_admin_load_script', 10 );


/* register menu item */
function frayd_pagebatcher_admin_menu_setup() {
	// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	add_submenu_page(
		'edit.php?post_type=page',
		'Batch Add New Pages',
		'Add New Batch',
		'publish_pages',
		'frayd_pagebatcher',
		'frayd_pagebatcher_form'
	);
}
add_action('admin_menu', 'frayd_pagebatcher_admin_menu_setup'); //menu setup

function frayd_pagebatcher_form() {
	require_once( FRAYD_PAGEBATCHER_DIR.'includes/frayd-pagebatcher-form.php' );
}

add_action( 'admin_action_frayd_pagebatcher_form_action', 'frayd_pagebatcher_form_action');
function frayd_pagebatcher_form_action() {
	// check for page/menu list
	if( !empty($_REQUEST["frayd_pagebatcher_text_list"]) ) {
		$nav_arr_text = str_replace(array("\r\n\r\n", "\n\n"), "\n", $_REQUEST["frayd_pagebatcher_text_list"]); // remove blank lines
		$nav_arr = frayd_pagebatcher_parse_page_arr( $nav_arr_text );

		$opts["publish_page"] = $_REQUEST["frayd_pagebatcher_publish_status"] ? 1 : NULL;

		// check for menu name
		if( !empty($_REQUEST["frayd_pagebatcher_menu_name"]) ) {
			// check if menu exists
			$menu = wp_get_nav_menu_object( $_REQUEST["frayd_pagebatcher_menu_name"] );
			if( !$menu ) {
				$opts["menu_id"] = wp_create_nav_menu( $_REQUEST["frayd_pagebatcher_menu_name"] );
			} else {
				$opts["menu_id"] = $menu->ID;
			}
		}

		// echo "<pre>"; print_r($nav_arr); echo "</pre>"; exit;

		$num_pages = frayd_pagebatcher_create_pages( $nav_arr, NULL, $opts );

		$message["success"] = 1;
		$message["num_pages"] = $num_pages;
		$goto = add_query_arg($message, remove_query_arg('error', $_SERVER["HTTP_REFERER"]));
	} else {
		$message["error"] = 1;
		$goto = add_query_arg($message, $_SERVER["HTTP_REFERER"]);
	}

	header("Location: $goto");
}

// parse text page/menu hierarchy
function frayd_pagebatcher_parse_page_arr( $page_text_list, $row=0, $level=0 ) {
	$page_arr_raw = explode("\n", $page_text_list); // delimit list by newline
	$page_arr = array();

	$count = 0;
	for( $i=$row; $i<count($page_arr_raw); $i++ ) {
		$page = $page_arr_raw[$i];
		$page_level = frayd_pagebatcher_parse_level($page);
		$page = trim($page);
		$next_page = isset($page_arr_raw[$i+1]) ? $page_arr_raw[$i+1] : NULL;

		if( !empty($page) ) {
			if( $page_level == $level ) {
				$page_arr[$count]["title"] = trim(ltrim($page, "\t-"));

				if( $next_page ) {
					$next_level = frayd_pagebatcher_parse_level($next_page);
					if( $next_level > $page_level ) {
						$page_arr[$count]["kids"] = frayd_pagebatcher_parse_page_arr( $page_text_list, $i+1, $next_level );
					} elseif( $next_level < $page_level ) {
						break;
					}
				}

				$count++;
			}
		}
	}

	return $page_arr;
}

// parse level of page/menu item given number of leading tabs or dashes
function frayd_pagebatcher_parse_level( $text ) {
	$level = strspn($text, "\t-"); // count number of tabs at beginning of string

	return $level;
}

function frayd_pagebatcher_create_pages( $nav_arr, $parent_id=NULL, $opts=array() ) {
	global $wpdb;
	global $blog_id;
	$count = 1;
	$num_pages = 0;

	foreach( $nav_arr AS $nav ) {
		$page_title = $nav["title"];

		$args = array(
			"menu_order" => $count,
			"post_author" => get_current_user_id(),
			"post_parent" => "$parent_id",
			"post_status" => $opts["publish_page"] == 0 ? "draft" : "publish",
			"post_title" => "$page_title",
			"post_type" => "page",
		);

		$new_page_id = wp_insert_post( $args );
		update_post_meta( $new_page_id, '_wp_page_template', 'default' );
		$num_pages++;

		// check for menu name
		if( isset($opts["menu_id"]) ) {
			$menu_data = array(
				"menu-item-db-id" => $blog_id,
				"menu-item-object-id" => $new_page_id,
				"menu-item-object" => "page",
				"menu-item-parent-id" => ( isset($opts["menu_item_parent_id"]) ? $opts["menu_item_parent_id"] : NULL ),
				"menu-item-position" => $count,
				"menu-item-type" => "post_type",
				"menu-item-url" => get_permalink( $new_page_id ),
				"menu-item-status" => $opts["publish_page"] == 0 ? "draft" : "publish",
				"menu-item-target" => ""
			);

			// create menu item
			$menu_item_id = wp_update_nav_menu_item( $opts["menu_id"], 0, $menu_data );
			$wpdb->insert($wpdb->term_relationships, array("object_id" => $menu_item_id, "term_taxonomy_id" => $opts["menu_id"]), array("%d", "%d"));
		}

		// check if page has kids
		if( isset($nav["kids"]) ) {
			$kids_opts = $opts;
			$kids_opts["menu_item_parent_id"] = $menu_item_id ? $menu_item_id : NULL;

			$num_pages += frayd_pagebatcher_create_pages( $nav["kids"], $new_page_id, $kids_opts );
		}

		$count++;
	}

	return $num_pages;
}

?>