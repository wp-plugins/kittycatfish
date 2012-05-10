<?php
/*
Plugin Name: KittyCatfish
Plugin URI: http://www.missilesilo.com/kittycatfish
Description: KittyCatfish allows you to display and configure ads that appear from the bottom, top, or sides of your page.
Version: 2.1
Author: Missilesilo
Author URI: http://www.missilesilo.com
*/

//----------------------------------------------
// KittyCatfish
//----------------------------------------------

// add admin menu
function kittycatfish_add_admin_menu(){
	// top-level item
	add_menu_page('KittyCatfish', 'KittyCatfish', 'manage_options', 'kittycatfish', '', plugin_dir_url(__FILE__).'/images/kittycatfish-icon-16.png');
}


// create custom post type (ads)
function kittycatfish_custom_post_type(){
	register_post_type('kc_ads', array('labels'=>array('name'=>'KittyCatfish Ads', 'singular_name'=>'KittyCatfish Ad', 'add_new_item'=>'Add New Ad', 'edit_item'=>'Edit Ad', 'new_item'=>'New Ad', 'view_item'=>'View Ad', 'search_items'=>'Search Ads', 'not_found'=>'No ads found.', 'not_found_in_trash'=>'No ads found in trash.'), 'public'=>true, 'show_in_menu'=>'kittycatfish'));
}


// create custom write panels for the ads
function kittycatfish_custom_write_panels(){
	// add the new write panels
	add_meta_box('kittycatfish_ad_css_box', 'Ad CSS', 'kittycatfish_ad_css_html', 'kc_ads', 'advanced', 'high');
	add_meta_box('kittycatfish_ad_config_box', 'Ad Configuration', 'kittycatfish_ad_config_html', 'kc_ads', 'advanced', 'high');
	
	// add the promo side box
	add_meta_box('kittycatfish_ad_side_box', 'Custom Services', 'kittycatfish_side_box_html', 'kc_ads', 'side', 'default');
	
	// add CSS for custom write panel
	wp_enqueue_style('kc_panel_css', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'admin.css');
	
	// include jquery ui scripts
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-tabs');
}


// create the inputs and HTML for the ad css panel
function kittycatfish_ad_css_html(){
	global $post;
	
	// our variables for the ad css
	$kc_ad_css = get_post_meta($post->ID, 'kc_ad_css', true);
	
	// call the write panel's HTML
	include(plugin_dir_path(__FILE__).'/ad-meta-css.php');
	
	// Use nonce for verification
	wp_nonce_field(plugin_basename(__FILE__), 'kc_ad_css_nonce');
}


// create the inputs and HTML for the ad config panel
function kittycatfish_ad_config_html(){
	global $post;
	
	// our variables for the ad settings
	$kc_ad_scope = get_post_meta($post->ID, 'kc_ad_scope', true);
	$kc_ad_selected_list = get_post_meta($post->ID, 'kc_ad_selected_list', true);
	$kc_ad_screen_location = get_post_meta($post->ID, 'kc_ad_screen_location', true);
	$kc_ad_lrvertpos_val = get_post_meta($post->ID, 'kc_ad_lrvertpos_val', true);
	$kc_ad_lrvertpos_type = get_post_meta($post->ID, 'kc_ad_lrvertpos_type', true);
	$kc_ad_lrvertpos_ref = get_post_meta($post->ID, 'kc_ad_lrvertpos_ref', true);
	$kc_ad_tbhorizpos_val = get_post_meta($post->ID, 'kc_ad_tbhorizpos_val', true);
	$kc_ad_tbhorizpos_type = get_post_meta($post->ID, 'kc_ad_tbhorizpos_type', true);
	$kc_ad_tbhorizpos_ref = get_post_meta($post->ID, 'kc_ad_tbhorizpos_ref', true);
	$kc_ad_appear_type = get_post_meta($post->ID, 'kc_ad_appear_type', true);
	$kc_ad_appear_delay = get_post_meta($post->ID, 'kc_ad_appear_delay', true);
	$kc_ad_appear_position = get_post_meta($post->ID, 'kc_ad_appear_position', true);
	$kc_ad_user_close = get_post_meta($post->ID, 'kc_ad_user_close', true);
	$kc_ad_display_count = get_post_meta($post->ID, 'kc_ad_display_count', true);
	$kc_ad_include_spacer = get_post_meta($post->ID, 'kc_ad_include_spacer', true);
	
	// call the write panel's HTML
	include(plugin_dir_path(__FILE__).'/ad-meta-config.php');
	
	// Use nonce for verification
	wp_nonce_field(plugin_basename(__FILE__), 'kc_ad_config_nonce');
}

// create a side box
function kittycatfish_side_box_html(){
	include(plugin_dir_path(__FILE__).'/ad-side-box.php');
}


// save meta fields
function kittycatfish_save_postdata($post_id){
	// check if this is an autosave
	if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE){
		return;
	}
	
	// check nonces
	if (!wp_verify_nonce($_POST['kc_ad_css_nonce'], plugin_basename(__FILE__)) || !wp_verify_nonce($_POST['kc_ad_config_nonce'], plugin_basename(__FILE__))){
		return;
	}
	
	// check user permissions
	if (!current_user_can('edit_post', $post_id)){
		return;
	}
	
	// get data from POST
	$kc_ad_css = $_POST['kc_ad_css'];
	$kc_ad_scope = $_POST['kc_ad_scope'];
	$kc_ad_selected_list = $_POST['kc_ad_selected_list'];
	$kc_ad_screen_location = $_POST['kc_ad_screen_location'];
	$kc_ad_tbhorizpos_val = $_POST['kc_ad_tbhorizpos_val'];
	$kc_ad_tbhorizpos_type = $_POST['kc_ad_tbhorizpos_type'];
	$kc_ad_tbhorizpos_ref = $_POST['kc_ad_tbhorizpos_ref'];
	$kc_ad_lrvertpos_val = $_POST['kc_ad_lrvertpos_val'];
	$kc_ad_lrvertpos_type = $_POST['kc_ad_lrvertpos_type'];
	$kc_ad_lrvertpos_ref = $_POST['kc_ad_lrvertpos_ref'];
	$kc_ad_appear_type = $_POST['kc_ad_appear_type'];
	$kc_ad_appear_delay = $_POST['kc_ad_appear_delay'];
	$kc_ad_appear_position = $_POST['kc_ad_appear_position'];
	$kc_ad_user_close = $_POST['kc_ad_user_close'];
	$kc_ad_display_count = $_POST['kc_ad_display_count'];
	$kc_ad_include_spacer = $_POST['kc_ad_include_spacer'];
	
	// save data
	update_post_meta($post_id, 'kc_ad_css', $kc_ad_css);
	update_post_meta($post_id, 'kc_ad_scope', $kc_ad_scope);
	update_post_meta($post_id, 'kc_ad_selected_list', $kc_ad_selected_list);
	update_post_meta($post_id, 'kc_ad_screen_location', $kc_ad_screen_location);
	update_post_meta($post_id, 'kc_ad_tbhorizpos_val', $kc_ad_tbhorizpos_val);
	update_post_meta($post_id, 'kc_ad_tbhorizpos_type', $kc_ad_tbhorizpos_type);
	update_post_meta($post_id, 'kc_ad_tbhorizpos_ref', $kc_ad_tbhorizpos_ref);
	update_post_meta($post_id, 'kc_ad_lrvertpos_val', $kc_ad_lrvertpos_val);
	update_post_meta($post_id, 'kc_ad_lrvertpos_type', $kc_ad_lrvertpos_type);
	update_post_meta($post_id, 'kc_ad_lrvertpos_ref', $kc_ad_lrvertpos_ref);
	update_post_meta($post_id, 'kc_ad_appear_type', $kc_ad_appear_type);
	update_post_meta($post_id, 'kc_ad_appear_delay', $kc_ad_appear_delay);
	update_post_meta($post_id, 'kc_ad_appear_position', $kc_ad_appear_position);
	update_post_meta($post_id, 'kc_ad_user_close', $kc_ad_user_close);
	update_post_meta($post_id, 'kc_ad_display_count', $kc_ad_display_count);
	update_post_meta($post_id, 'kc_ad_include_spacer', $kc_ad_include_spacer);
}

// check what kind of post/page is being displayed
function kittycatfish_test_post(){
	// set globals
	global $kc_ad, $kc_ad_meta, $wpdb, $wp_query;
	
	// get post ID
	$current_post_id = $wp_query->post->ID;
	
	// set global as default
	$post_type = 'global';
	
	// set up potential overrides
	if (is_single($current_post_id)){
		$post_type = 'single';
	}
	if (is_page($current_post_id)){
		$post_type = 'page';
	}
	if (is_home()){
		$post_type = 'home';
	}
	if (is_front_page()){
		$post_type = 'front';
	}
	
	// set values to default
	$kc_ad = false;
	$kc_ad_meta = array();
	$check_for_global = false;
	
	// determine what to show based on location
	switch ($post_type) {
		case 'single':
		case 'page':
			// see if there are any ads that are specified to appear on this post
			$query = "select * from $wpdb->postmeta where meta_key = 'kc_ad_selected_list' and meta_value like '%$current_post_id%'";
			$kc_ads_on_this_post = $wpdb->get_results($query, OBJECT);
			
			if ($kc_ads_on_this_post){
				// found at least one ad, let's make sure the meta value contains the exact post id. if so, add to array
				$posts_to_select = array();
				foreach ($kc_ads_on_this_post as $kc_ad_postmeta){
					$selected_ids = array();
					$selected_ids = explode(',', $kc_ad_postmeta->meta_value);
					
					if (in_array($current_post_id, $selected_ids)){
						// post id is definitely selected, add to list
						if (kittycatfish_check_cookies($kc_ad_postmeta->post_id) !== false){
							$posts_to_select[] = $kc_ad_postmeta->post_id;
						}
					}
				}
			}
			
			if (!empty($posts_to_select)){
				// still have at least one ad, let's select them and get the most recent one
				$post_id_str = '';
				foreach ($posts_to_select as $post_id){
					if ($post_id_str != ''){
						$post_id_str .= ' or ';
					}
					
					$post_id_str .= "(ID = ".$post_id." and post_type = 'kc_ads' and post_status = 'publish')";
				}
				
				// get the most recent ad assigned to this post
				$query = "select * from $wpdb->posts where $post_id_str order by post_date desc limit 1";
				$kc_ad = $wpdb->get_row($query, OBJECT);
				
			}else{
				// no ads to show on this post specifically; see if there are any ads set to show on all posts or all pages
				$query = "select * from $wpdb->postmeta where meta_key = 'kc_ad_scope' and meta_value = '";
				
				if ($post_type == 'single'){
					$query .= "all_posts";
				}else if ($post_type == 'page'){
					$query .= "all_pages";
				}
				
				$query .= "'";
				
				$kc_ads_on_this = $wpdb->get_results($query, OBJECT);
				
				if ($kc_ads_on_this){
					// found at least one ad, let's select them and get the most recent one
					$posts_to_select = array();
					foreach ($kc_ads_on_this as $kc_ad_postmeta){
						// build a list of IDs to select
						if (kittycatfish_check_cookies($kc_ad_postmeta->post_id) !== false){
							$posts_to_select[] = $kc_ad_postmeta->post_id;
						}
					}
					
					$post_id_str = '';
					foreach ($posts_to_select as $post_id){
						if ($post_id_str != ''){
							$post_id_str .= ' or ';
						}
						
						$post_id_str .= "(ID = ".$post_id." and post_type = 'kc_ads' and post_status = 'publish')";
					}
					
					// get the most recent ad
					$query = "select * from $wpdb->posts where $post_id_str order by post_date desc limit 1";
					$kc_ad = $wpdb->get_row($query, OBJECT);
					
				}else{
					// no ads set to show on all posts; see if there are any global ads
					$check_for_global = true;
				}
			}
			break;
			
		case 'home':
			// see if there are any ads set to show on the home page
			$query = "select * from $wpdb->postmeta where meta_key = 'kc_ad_scope' and meta_value = 'home_page'";
			$kc_ads_on_this = $wpdb->get_results($query, OBJECT);
			
			if ($kc_ads_on_this){
				// found at least one ad, let's select them and get the most recent one
				$posts_to_select = array();
				foreach ($kc_ads_on_this as $kc_ad_postmeta){
					// build a list of IDs to select
					if (kittycatfish_check_cookies($kc_ad_postmeta->post_id) !== false){
						$posts_to_select[] = $kc_ad_postmeta->post_id;
					}
				}
				
				$post_id_str = '';
				foreach ($posts_to_select as $post_id){
					if ($post_id_str != ''){
						$post_id_str .= ' or ';
					}
					
					$post_id_str .= "(ID = ".$post_id." and post_type = 'kc_ads' and post_status = 'publish')";
				}
				
				// get the most recent ad assigned to the home page
				$query = "select * from $wpdb->posts where $post_id_str order by post_date desc limit 1";
				$kc_ad = $wpdb->get_row($query, OBJECT);
				
			}else{
				// no ads set to show on home page; see if there are any global ads
				$check_for_global = true;
			}
			break;
			
		case 'front':
			// see if there are any ads set to show on the front page
			$query = "select * from $wpdb->postmeta where meta_key = 'kc_ad_scope' and meta_value = 'front_page'";
			$kc_ads_on_this = $wpdb->get_results($query, OBJECT);
			
			if ($kc_ads_on_this){
				// found at least one ad, let's select them and get the most recent one
				$posts_to_select = array();
				foreach ($kc_ads_on_this as $kc_ad_postmeta){
					// build a list of IDs to select
					if (kittycatfish_check_cookies($kc_ad_postmeta->post_id) !== false){
						$posts_to_select[] = $kc_ad_postmeta->post_id;
					}
				}
				
				$post_id_str = '';
				foreach ($posts_to_select as $post_id){
					if ($post_id_str != ''){
						$post_id_str .= ' or ';
					}
					
					$post_id_str .= "(ID = ".$post_id." and post_type = 'kc_ads' and post_status = 'publish')";
				}
				
				// get the most recent ad assigned to the front page
				$query = "select * from $wpdb->posts where $post_id_str order by post_date desc limit 1";
				$kc_ad = $wpdb->get_row($query, OBJECT);
				
			}else{
				// no ads set to show on front page; see if there are any global ads
				$check_for_global = true;
			}
			break;
		
		case 'global':
		default:
			$check_for_global = true;
			break;
	}
	
	if ($check_for_global === true){
		// see if there are any global ads
		$query = "select * from $wpdb->postmeta where meta_key = 'kc_ad_scope' and meta_value = 'global'";
		$kc_ads_on_this = $wpdb->get_results($query, OBJECT);
		
		if ($kc_ads_on_this){
			// found at least one ad, let's select them and get the most recent one
			$posts_to_select = array();
			foreach ($kc_ads_on_this as $kc_ad_postmeta){
				if (kittycatfish_check_cookies($kc_ad_postmeta->post_id) !== false){
					// build a list of posts to select
					$posts_to_select[] = $kc_ad_postmeta->post_id;
				}
			}
			
			$post_id_str = '';
			foreach ($posts_to_select as $post_id){
				if ($post_id_str != ''){
					$post_id_str .= ' or ';
				}
				
				$post_id_str .= "(ID = ".$post_id." and post_type = 'kc_ads' and post_status = 'publish')";
			}
			
			// get the most recent ad assigned to all pages
			$query = "select * from $wpdb->posts where $post_id_str order by post_date desc limit 1";
			$kc_ad = $wpdb->get_row($query, OBJECT);
		}else{
			// no ads set to show globally. do nothing.
		}
	}
	
	// by this point, $kc_ad should be set to an object (the ad we want to display) or false.
	if ($kc_ad !== false && is_object($kc_ad)){
		// load meta data
		$kc_ad_meta = kittycatfish_ad_get_meta($kc_ad->ID);
	}
}

// show the ad
function kittycatfish_include_ad(){
	global $kc_ad, $kc_ad_meta, $wpdb;
	
	if ($kc_ad !== false && is_object($kc_ad)){
		if (kittycatfish_check_cookies($kc_ad_postmeta->post_id) !== false){
			// we can show the ad //
			
			echo "\n\n".'<div id="kittycatfish">'."\n";
			
			echo '<div id="kittycatfish_ad_content">';
			echo '<div id="close">Close</div>'."\n";
			echo $kc_ad->post_content.'</div>';
			
			echo "\n</div>\n\n";
		}
	}else{
		// we didn't have an ad loaded. do nothing.
	}
}

// process meta data to get a format that we like
function kittycatfish_ad_get_meta($post_id){
	global $wpdb;
	
	$query = "select meta_key, meta_value from $wpdb->postmeta where post_id = $post_id";
	$result = $wpdb->get_results($query, OBJECT);
	
	// check for our values only
	$kc_ad_meta = array();
	foreach ($result as $row){
		if (stristr($row->meta_key, 'kc_ad_')){
			// it's one of ours; add to array
			$key = $row->meta_key;
			$value = $row->meta_value;
			$kc_ad_meta[$key] = $value;
		}
	}
	
	return $kc_ad_meta;
}

// check the cookies to see if a given ad can be loaded/displayed
function kittycatfish_check_cookies($post_id){
	$kc_ad_meta = kittycatfish_ad_get_meta($post_id);
				
	// check display settings to see if the number of displays has been exceeded, or if the user closed it and we shouldn't show it again
	
	// load cookie values
	$count = $_COOKIE['kittycatfish_count'];
	$closed = $_COOKIE['kittycatfish_closed'];
	
	// extract the counter for this ad
	$this_ad_count = 0;
	$ad_counters = explode(',', $count);
	foreach ($ad_counters as $ad_counter){
		$count_val = explode(':', $ad_counter);
		if ($count_val[0] == $post_id){
			$this_ad_count = $count_val[1];
		}
	}
	
	// split the string of closed ad ids
	$closed_ads = explode(',', $closed);
	if ($kc_ad_meta['kc_ad_user_close'] == 'true' && in_array($post_id, $closed_ads)){
		// user has closed it and it's set to not show after that; don't show ad
		return false;
	}else if ($kc_ad_meta['kc_ad_display_count'] != 0 && $this_ad_count >= $kc_ad_meta['kc_ad_display_count']){
		// we've exceeded the number of displays allowed; don't show ad
		return false;
	}else{
		// we can show the ad //
		return $post_id;
	}
}

// script inclusion
function kittycatfish_include_scripts(){
	global $kc_ad, $kc_ad_meta, $wpdb;
	
	if ($kc_ad !== false && is_object($kc_ad)){
		// check display settings to see if the number of displays has been exceeded, or if the user closed it and we shouldn't show it again
		
		// load cookie values
		$count = $_COOKIE['kittycatfish_count'];
		$closed = $_COOKIE['kittycatfish_closed'];
		
		// extract the counter for this ad
		$this_ad_count = 0;
		$ad_counters = explode(',', $count);
		foreach ($ad_counters as $ad_counter){
			$count_val = explode(':', $ad_counter);
			if ($count_val[0] == $kc_ad->ID){
				$this_ad_count = $count_val[1];
			}
		}
		
		// split the string of closed ad ids
		$closed_ads = explode(',', $closed);
		
		if ($kc_ad_meta['kc_ad_user_close'] == 'true' && in_array($kc_ad->ID, $closed_ads)){
			// user has closed it and it's set to not show after that; don't show ad
		}else if ($kc_ad_meta['kc_ad_display_count'] != 0 && $this_ad_count >= $kc_ad_meta['kc_ad_display_count']){
			// we've exceeded the number of displays allowed; don't show ad
		}else{
			// we can show the ad //
			
			// Load JavaScripts //
			wp_enqueue_script('jquery'); // normal jQuery
			
			wp_deregister_script('jquery-cookie');
			wp_register_script('jquery-cookie', plugins_url('/jquery.cookie.js', __FILE__), array('jquery'), '1.0');
			wp_enqueue_script('jquery-cookie'); // jQuery Cookie interface
			
			wp_deregister_script('kittycatfish');
			wp_register_script('kittycatfish', plugins_url('/kittycatfish.js.php?kc_ad='.$kc_ad->ID, __FILE__), array('jquery'), '2.0');
			wp_enqueue_script('kittycatfish'); // KittyCatfish functions
			
			// Load CSS //
			wp_register_style('kittycatfish-base', plugins_url('/base.css.php?kc_ad='.$kc_ad->ID, __FILE__), array(), '2.0');
			wp_enqueue_style('kittycatfish-base'); // base styles
		}
	}else{
		// we didn't have an ad loaded. do nothing.
	}
}

//----------------------------------------------
// Hooks
//----------------------------------------------

// admin menu creation
add_action('admin_menu', 'kittycatfish_add_admin_menu');

// custom write panel creation
add_action('admin_init', 'kittycatfish_custom_write_panels');

// post type creation
add_action('init', 'kittycatfish_custom_post_type');

// save meta data with post
add_action('save_post', 'kittycatfish_save_postdata');

// test what kind of post is displayed
if (!is_admin()){
	add_action('get_header', 'kittycatfish_test_post');
}

// include necessary scripts for kittycatfish
add_action('wp_enqueue_scripts', 'kittycatfish_include_scripts');

// ad inclusion
if (!is_admin()){
	add_action('wp_footer', 'kittycatfish_include_ad');
}
?>