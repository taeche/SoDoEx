<?php
/*
Plugin Name: DO NOT UPDATE!!Ninja Announcements
Plugin URI: http://wpninjas.net/
Description: (DO NOT UPDATE!! This plugin is customized by YongUn)A plugin that displays annoucements on pages and posts. They can be scheduled so that they are only displayed between specified dates/times. Additionally, all annoucements are edited via the built-in WordPress RTE. You can also include images and videos from your WordPress media library or YouTube. Each of your announcements has it's own location setting, allowing you to place the announcement exactly where you want it, even display it as a widget!
Author: The WP Ninjas
Version: 2.3.2
Author URI: http://wpninjas.net

/*
Copyright 2011 WP Ninjas/Kevin Stover.
/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/
define("NINJA_ANNC_DIR", WP_PLUGIN_DIR."/ninja-announcements");
define("NINJA_ANNC_URL", WP_PLUGIN_URL."/ninja-announcements");
define("NINJA_ANNC_VERSION", "2.3.2");
define("NINJA_ANNC_TYPE", "Lite");

add_action('init', 'ninja_annc_load_lang');

function ninja_annc_load_lang() {
	$plugin_dir = basename(dirname(__FILE__));
	$lang_dir = $plugin_dir.'/lang/';
	load_plugin_textdomain( 'ninja-announcements', false, $lang_dir );
}
require_once(NINJA_ANNC_DIR."/includes/cpt.php");
require_once(NINJA_ANNC_DIR."/includes/meta-boxes.php");
require_once(NINJA_ANNC_DIR."/includes/scripts-styles.php");
require_once(NINJA_ANNC_DIR."/includes/ajax-functions.php");
require_once(NINJA_ANNC_DIR."/includes/columns.php");
require_once(NINJA_ANNC_DIR."/includes/display.php");
require_once(NINJA_ANNC_DIR."/includes/widget.class.php");
require_once(NINJA_ANNC_DIR."/includes/activation.php");
require_once(NINJA_ANNC_DIR."/includes/plugin-settings.php");
if(NINJA_ANNC_TYPE == 'Pro'){
	require_once(NINJA_ANNC_DIR."/includes/pro/groups.php");
	require_once(NINJA_ANNC_DIR."/includes/pro/groups.widget.class.php");
	add_filter( 'http_request_args', 'ninja_annc_ignore_repo', 5, 2 );
}

function ninja_annc_ignore_repo( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}

function change_publish_button( $translation, $text ) {
	global $post;
	$post_status = $post->post_status;
	if($text == 'Publish'){
		if($post_status == 'draft' OR $post_status == 'auto-draft'){
			return __('Activate', 'ninja-announcements');
		}
	}elseif($text == 'Update'){
		return __('Deactivate', 'ninja-announcements');
	}elseif($text == 'Save Draft'){
		return __('Save', 'ninja-announcements');
	}	
	return $translation;
}

function ninja_annc_bulk() {
	if(isset($_REQUEST['post_type'])){
		$post_type = $_REQUEST['post_type'];
	}else{
		$post_type = '';
	}	
    if($post_type == 'ninja_annc'){
		if(isset($_REQUEST['post'])){
			$posts = $_REQUEST['post'];		
		}else{
			$posts = false;
		}		
		if(isset($_REQUEST['action'])){
			$action = $_REQUEST['action'];		
		}else{
			$action = false;
		}

		if($action == 'activate' OR $action == 'deactivate'){
			if($posts){
				foreach($posts as $post){
					$my_post = array();
					$my_post['ID'] = $post;
					if($action == 'activate'){
						$my_post['post_status'] = 'publish';
					}elseif($action == 'deactivate'){
						$my_post['post_status'] = 'draft';
					}
					wp_update_post( $my_post );
				}
			}
		}
	}
}
add_action('load-edit.php', 'ninja_annc_bulk');

add_action('wp_head', 'ninja_annc_header');
function ninja_annc_header(){
	//ninja_annc_update_groups();
	//ninja_annc_update_annc();
	if(NINJA_ANNC_TYPE == 'Pro'){
		ninja_annc_display_group_location('default');
	}
	ninja_annc_display_annc_location('default');
}

ninja_annc_initial_setup();
register_activation_hook( __FILE__, 'ninja_annc_initial_setup' );