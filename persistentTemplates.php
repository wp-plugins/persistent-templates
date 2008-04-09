<?php
/*
Plugin Name: Persistent Templates
Plugin URI: http://www.stemwinderproductions.com/
Description: Persistent Templates makes your custom templates "persistent", in that all child subpages inherit their parent's template setting.
Author: Joshua Smith
Version: 1.0
Author URI: http://www.stemwinderproductions.com/
*/


function inherit(){
	
	global $wpdb;
	
	$sql = "SELECT * FROM $wpdb->posts WHERE post_parent > 0";
	$children = $wpdb->get_results($sql);
	
	foreach($children as $child){
		$parentTemplate = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $child->post_parent AND meta_key = '_wp_page_template'");
		if($parentTemplate){
			
			$isQUERY = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = $child->ID AND meta_key = '_wp_page_template'");
			$isExist = $wpdb->num_rows;
			
			if($isExist > 0){
				$iuSQL = "UPDATE $wpdb->postmeta
						  SET meta_value = '$parentTemplate'
						  WHERE post_id = $child->ID
						  AND meta_key = '_wp_page_template'";
			} else {
				$iuSQL = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value)
						  VALUES ('$child->ID', '_wp_page_template', '$parentTemplate')";
			}
			
			$wpdb->query($iuSQL);
		}
	}
	
}

add_action('save_post', 'inherit');

?>