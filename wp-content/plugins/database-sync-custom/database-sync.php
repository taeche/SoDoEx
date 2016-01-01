<?php
/*
Plugin Name: Database Sync Custom
Description: Extend of Database sync plugin to exclude transaction data
Version: 0.1.1
Author: Yong Un Choi
*/

//What WordPress capability is required to access this plugin?
define('dbsc_REQUIRED_CAPABILITY', 'manage_options');

//API version for forward-compatibility
define('dbsc_API_VERSION', 1);

require_once 'functions.php';
require_once 'class-woo-order.php';
//add a menu item under Tools
add_action('admin_menu', 'dbsc_menu');
function dbsc_menu() {
	add_submenu_page('tools.php', 'Database Sync Custom', 'Database Sync Custom', dbsc_REQUIRED_CAPABILITY, 'dbsc_options', 'dbsc_admin_ui');
}

//display admin menu page
function dbsc_admin_ui() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	$action = isset($_REQUEST['dbsc_action']) ? $_REQUEST['dbsc_action'] : 'index';
	switch ($action) {
		case 'sync' :
			$url = esc_url($_GET['url']);
			include 'sync-screen.php';
			break;
		default :
			$tokens = get_option('outlandish_sync_tokens') ? : array();
			include 'main-screen.php';
			break;
	}
}

//do most actions on admin_init so that we can redirect before any content is output
add_action('admin_init', 'dbsc_post_actions');
function dbsc_post_actions() {
	if (!isset($_REQUEST['dbsc_action'])) return;

	if (!current_user_can(dbsc_REQUIRED_CAPABILITY)) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	$tokens = get_option('outlandish_sync_tokens') ? : array();

	switch ($_REQUEST['dbsc_action']) {
		//add a token
		case 'add' :
			$decodedToken = base64_decode($_POST['token']);
			@list($secret, $url) = explode(' ', $decodedToken);
			if (empty($secret) || empty($url) || !preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) {
				$gotoUrl = dbsc_url(array('error' => 'The token is not valid.'));
			} elseif ($url == get_bloginfo('wpurl')) {
				$gotoUrl = dbsc_url(array('error' => 'The token cannot be added as it is for this installation.'));
			} else {
				$tokens[$url] = $secret;
				update_option('outlandish_sync_tokens', $tokens);
				$gotoUrl = dbsc_url();
			}
			wp_redirect($gotoUrl);
			exit;

		//remove a token
		case 'remove' :
			unset($tokens[$_POST['url']]);
			update_option('outlandish_sync_tokens', $tokens);
			wp_redirect(dbsc_url());
			exit;

		//pull from remote db
		case 'pull' :
			try {
				//send post request with secret
				$result = dbsc_post($_REQUEST['url'], 'dbsc_pull', array('secret' => $tokens[$_REQUEST['url']]));
				if ($result == 'You don\'t know me') {
					$gotoUrl = dbsc_url(array('error' => 'Invalid site token'));
				} elseif ($result == '0') {
					$gotoUrl = dbsc_url(array('error' => 'Sync failed. Is the plugin activated on the remote server?'));
				} else {

					$sql = $result;
					if ($sql && preg_match('|^/\* Dump of database |', $sql)) {

						//backup current database
						$backupfile = dbsc_makeBackup();

						//store some options to restore after sync
						$optionCache = dbsc_cacheOptions();
						$orderCache=dbsc_cacheOrders();
						//load the new data
						if (dbsc_loadSql($sql)) {
							//clear object cache
							wp_cache_flush();

							//restore options
							dbsc_restoreOptions($optionCache);
							dbsc_restoreOrders($orderCache);
							$gotoUrl = dbsc_url(array('message' => 'Database synced successfully'));
						} else {
							//import failed part way through so attempt to restore last backup
							$compressedSql = substr(file_get_contents($backupfile), 10); //strip gzip header
							dbsc_loadSql(gzinflate($compressedSql));

							$gotoUrl = dbsc_url(array('error' => 'Sync failed. SQL error.'));
						}
					} else {
						$gotoUrl = dbsc_url(array('error' => 'Sync failed. Invalid dump.'));
					}
				}
			} catch (Exception $ex) {
				$gotoUrl = dbsc_url(array('error' => 'Remote site not accessible (HTTP ' . $ex->getCode() . ')'));
			}

			wp_redirect($gotoUrl);
			exit;

		//push to remote db
		case 'push' :
			//get SQL data
			ob_start();
			dbsc_mysqldump();
			$sql = ob_get_clean();

			try {
				//send post request with secret and SQL data
				$result = dbsc_post($_REQUEST['url'], 'dbsc_push', array(
					'secret' => $tokens[$_REQUEST['url']],
					'sql' => $sql
				));
				if ($result == 'You don\'t know me') {
					$gotoUrl = dbsc_url(array('error' => 'Invalid site token'));
				} elseif ($result == '0') {
					$gotoUrl = dbsc_url(array('error' => 'Sync failed. Is the plugin activated on the remote server?'));
				} elseif ($result == 'OK') {
					$gotoUrl = dbsc_url(array('message' => 'Database synced successfully'));
				} else {
					$gotoUrl = dbsc_url(array('error' => 'Something may be wrong'));
				}
			} catch (RuntimeException $ex) {
				$gotoUrl = dbsc_url(array('error' => 'Remote site not accessible (HTTP ' . $ex->getCode() . ')'));
			}
			wp_redirect($gotoUrl);
			exit;
	}
}

//handle remote pull action when not logged in
add_action('wp_ajax_nopriv_dbsc_pull', 'dbsc_pull_nopriv');
function dbsc_pull_nopriv() {
	//test for secret
	$secret = dbsc_getSecret();
	if (stripslashes($_REQUEST['secret']) != $secret) {
		die("You don't know me");
	}
	//dump DB
	dbsc_pull();
}

//handle pull action when logged in
add_action('wp_ajax_dbsc_pull', 'dbsc_pull');
function dbsc_pull() {
	//dump DB and GZip it
	header('Content-type: application/octet-stream');
	if(isset($_GET['dump'])){
		if ($_GET['dump'] == 'manual') {
			//manual dump, so include attachment headers
			header('Content-Description: File Transfer');
		        header('Content-Disposition: attachment; filename=data.sql');
		        header('Content-Transfer-Encoding: binary');
		        header('Expires: 0');
		        header('Cache-Control: must-revalidate');
		        header('Pragma: public');
		}
	}
	dbsc_mysqldump();
	exit;
}

//handle remote push action
add_action('wp_ajax_nopriv_dbsc_push', 'dbsc_push');
function dbsc_push() {
	//test for secret
	$secret = dbsc_getSecret();
	if (stripslashes($_REQUEST['secret']) != $secret) {
		die("You don't know me");
	}
	$tokens = get_option('outlandish_sync_tokens') ? : array();

//	echo $sql = gzinflate($_POST['sql']);
	$sql = stripslashes($_POST['sql']);
	if ($sql && preg_match('|^/\* Dump of database |', $sql)) {

		//backup current DB
		dbsc_makeBackup();

		//store options
		$optionCache = dbsc_cacheOptions();
		//store orders
		$orderCache=dbsc_cacheOrders();

		//load posted data
		dbsc_loadSql($sql);

		//clear object cache
		wp_cache_flush();

		//reinstate options
		dbsc_restoreOptions($optionCache);
		dbsc_restoreOrders($orderCache);
		echo 'OK';
	} else {
		echo 'Error: invalid SQL dump';
	}
	exit;
}

/**
 * @return array key-value pairs of selected current WordPress options
 */
function dbsc_cacheOptions() {
	//persist these options
	$defaultOptions = array('siteurl', 'home', 'outlandish_sync_tokens', 'outlandish_sync_secret');
	$persistOptions = apply_filters('dbsc_persist_options', $defaultOptions);

	$optionCache = array();
	foreach ($persistOptions as $name) {
		$optionCache[$name] = get_option($name);
	}
	return $optionCache;
}

/**
 * @param array $optionCache key-value pairs of options to restore
 */
function dbsc_restoreOptions($optionCache) {
	foreach ($optionCache as $name => $value) {
		update_option($name, $value);
	}
}

/**
 * cache order data
 */

function dbsc_cacheOrders()
{
	$orders = Array();
	global $wpdb;
	$results = $wpdb->get_results(
		"
	SELECT *
	FROM $wpdb->posts
	WHERE post_type = 'shop_order'

	"
	);

	foreach ($results as $row) {
		$order = new class_woo_order;
		$order->body['old_id']=$row->ID;
		$order->body['post_type'] = $row->post_type;
		$order->body['post_status'] = $row->post_status;
		$order->body['post_title'] = $row->post_title;
		$order->body['post_name'] = $row->post_name;
		$order->body['post_date'] = $row->post_date;
		$order->body['post_date_gmt'] = $row->post_date_gmt;
		$order->body['post_content'] = $row->post_content;
		$order->body['post_excerpt'] = $row->post_excerpt;
		$order->body['post_parent'] = $row->post_parent;
		$order->body['post_password'] = $row->post_password;
		$order->body['comment_status'] = $row->comment_status;
		$order->body['ping_status'] = $row->ping_status;
		$order->body['menu_order'] = $row->menu_order;
		$order->body['post_author'] = $row->post_author;

		$post_id = $row->ID;

		$results_meta = $wpdb->get_results(
			"
	SELECT meta_key,meta_value
	FROM $wpdb->postmeta
	WHERE post_id = " . $post_id . "

	"
		);

		//$order->meta = $results_meta;
		foreach($results_meta as $meta){
			$order->meta[$meta->meta_key]=$meta->meta_value;
		}
		array_push($orders, $order);

	}
	return $orders;
}

function dbsc_restoreOrders($orderCache){

	foreach ($orderCache as $orderCache) {
		$orderCache->save();
	}
}