<?php
add_action('wp_ajax_ninja_annc_activate', 'ninja_annc_activate');
function ninja_annc_activate(){
	global $wpdb;
	$post_id = $_REQUEST['post_id'];
	$my_post = array();
	$my_post['ID'] = $post_id;
	$my_post['post_status'] = 'publish';
	wp_update_post( $my_post );
	die();
}

add_action('wp_ajax_ninja_annc_group_activate', 'ninja_annc_group_activate');
function ninja_annc_group_activate(){
	$term_id = esc_html($_REQUEST['term_id']);
	$plugin_settings = get_option('ninja_annc_settings');
	$plugin_settings['groups'][$term_id]['active'] = 1;
	update_option('ninja_annc_settings', $plugin_settings);
	die();
}

add_action('wp_ajax_ninja_annc_deactivate', 'ninja_annc_deactivate');
function ninja_annc_deactivate(){
	global $wpdb;
	$post_id = $_REQUEST['post_id'];
	$my_post = array();
	$my_post['ID'] = $post_id;
	$my_post['post_status'] = 'draft';
	wp_update_post( $my_post );
	die();
}

add_action('wp_ajax_ninja_annc_group_deactivate', 'ninja_annc_group_deactivate');
function ninja_annc_group_deactivate(){
	$term_id = esc_html($_REQUEST['term_id']);
	$plugin_settings = get_option('ninja_annc_settings');
	$plugin_settings['groups'][$term_id]['active'] = 0;
	update_option('ninja_annc_settings', $plugin_settings);
	die();
}

add_action('wp_ajax_ninja_annc_widget_change', 'ninja_annc_widget_change');
function ninja_annc_widget_change(){
	global $wpdb;
	$group = $_REQUEST['group'];
	$post_id = $_REQUEST['post_id'];	
	if($group == 1){
		$plugin_settings = get_option('ninja_annc_settings');	
		$meta = $plugin_settings['groups'][$post_id];
		if($meta['active'] == 1){
			$post_status = 'publish';
		}else{
			$post_status = 'draft';
		}
		$meta['group'] = $group;		
	}else{
		$annc_post = get_post($post_id, ARRAY_A);
		$meta = get_post_meta($post_id, '_ninja_annc_meta', true);	
		$post_status = $annc_post['post_status'];
		$meta['post_status'] = $post_status;		
	}
	header("Content-type: application/json");
	echo json_encode($meta);
	die();
}

add_action('wp_ajax_ninja_annc_autocomplete', 'ninja_annc_autocomplete');

function ninja_annc_autocomplete(){
	global $wpdb;
	$posts_table_name = $wpdb->prefix."posts";
	$pages_table_name = $wpdb->prefix."posts";
	$q = $_REQUEST['q'];
	$type = $_REQUEST['type'];
	$data = array();
	//echo $wpdb->prepare("SELECT post_title, ID FROM $posts_table_name WHERE post_title LIKE %s", "%$q%");
	$posts = $wpdb->get_results($wpdb->prepare("SELECT post_title, ID FROM $posts_table_name WHERE post_type = %s AND post_title LIKE %s LIMIT 10", $type, "%$q%"), ARRAY_A);
	foreach($posts as $post){
		$title = $post['post_title'];
		$id = $post['ID'];
		array_push($data, array("name" => $title, "id" => $id));
	}	
	header("Content-type: application/json");
	echo json_encode($data);
	die();
}

add_action('wp_ajax_nopriv_ninja_annc_close', 'ninja_annc_close');
add_action('wp_ajax_ninja_annc_close', 'ninja_annc_close');
function ninja_annc_close(){
	$id = esc_html($_REQUEST['annc_id']);
	//$group_id = esc_html($_REQUEST['group_id']);
	$group_id = ninja_annc_group_override_check($id);
	if($group_id){
		$plugin_settings = get_option('ninja_annc_settings');	
		$meta = $plugin_settings['groups'][$group_id];
		$cookie_name = 'ninja_annc_group_close_'.$group_id;
	}else{
		$meta = get_post_meta($id, '_ninja_annc_meta', true);
		$cookie_name = 'ninja_annc_close_'.$id;
	}
	if(isset($meta['closed_time'])){
		$closed_time = $meta['closed_time'];
	}else{
		$closed_time = 'session';
	}
	if(session_id() == '') {
		session_start();
	}
	$_SESSION[$cookie_name] = 'closed';;
	switch($closed_time){
		case "session":
			setcookie ($cookie_name, "", time() - 3600, "/");
			break;
		case "days":
			$days = $meta['closed_days'];
			$expires = time()+60*60*24*$days;
			setcookie($cookie_name, 'closed', $expires,"/");
			break;
		case "forever";
			$expires = time()+60*60*24*999;
			setcookie($cookie_name, 'closed', $expires,"/");
			break;
	}
	die();
}
