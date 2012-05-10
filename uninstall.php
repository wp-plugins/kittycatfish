<?php
/*
 * Uninstall KittyCatfish
 * 
 * Deletes any created ads and data related to the plugin
 */

// exit if certain constants are not defined
if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')){
	exit();
}

global $wpdb;

$query = "select * from $wpdb->posts where post_type = 'kc_ads'";
$kc_ads = $wpdb->get_results($query, OBJECT);

if ($kc_ads){
	// loop through the ones we found
	foreach ($kc_ads as $kc_ad){
		// look for drafts and auto-saves to delete
		$wpdb->query("delete from $wpdb->posts where post_parent = $kc_ad->ID");
		
		// delete meta records
		$wpdb->query("delete from $wpdb->postmeta where post_id = $kc_ad->ID");
		
		// delete post
		$wpdb->query("delete from $wpdb->posts where ID = $kc_ad->ID");
	}
}
?>