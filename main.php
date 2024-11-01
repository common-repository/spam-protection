<?php
/*
Plugin Name: Spam Protection
Plugin URI: http://themexpand.com
Description: Reduce the spam on your WordPress Blog by up to 90% with this simple but effective plugin.
Version: 1.18
Author: Shameem Reza
Author URI: http://shameemreza.com
*/

//options initialization
add_option('dc_sp_database_version', "0");//database version
add_option('dc_sp_time_delay', "15" );//Time delay
add_option('dc_sp_time_max', "1200" );//Time delay
add_option('dc_sp_custom_message',"Please wait few seconds or refresh the page.");//Custom message for blocked user

//embedding classes
require_once('class/pagination.php');//control the pagination in the back end

//embedding external files
require_once('includes/activation.php');//create or update plugin tables
require_once('includes/save_log.php');//save user_ip post_id and time
require_once('includes/parse_comment.php');//this works with the pre_comment_on_post hook
require_once('includes/menu_spam.php');//spam menu
require_once('includes/menu_options.php');//spam menu

//create the Spam Protection menu
add_action( 'admin_menu', 'dc_sp_admin_menu' );
function dc_sp_admin_menu() {
	
	$form_name='Spam Protection';
	
	//main menu
	add_menu_page($form_name, $form_name, 'manage_options', 'menu_spam','dc_sp_menu_spam');
	
	//SPAM - Child of menu_spam - Visible
	add_submenu_page('menu_spam', $form_name.' - Spam', 'Spam', 'manage_options', 'menu_spam', 'dc_sp_menu_spam');
	
	//OPTIONS - Child of MENU_SPAM - Visible
	add_submenu_page('menu_spam', $form_name.' - Options', 'Options', 'manage_options', 'dc_sp_menu_options', 'dc_sp_menu_options');	
	
}

//delete options when the plugin is uninstalled
register_uninstall_hook( __FILE__, 'dc_sp_uninstall' );
function dc_sp_uninstall(){
	
	//deleting tables
	global $wpdb;
	
	$table_name=$wpdb->prefix . "dc_sp_log";
	$sql = "DROP TABLE $table_name";
	mysql_query($sql);
	
	$table_name=$wpdb->prefix . "dc_sp_spam";
	$sql = "DROP TABLE $table_name";
	mysql_query($sql);
	
	//deleting options
	delete_option('dc_sp_database_version');
	delete_option('dc_sp_time_delay');
	delete_option('dc_sp_custom_message');
	delete_option('dc_sp_time_max');
	
}

?>