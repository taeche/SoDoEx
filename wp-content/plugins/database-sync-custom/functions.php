<?php

/**
 * Get secret or generate one if none is found
 * @return string Secret key
 */
function dbsc_getSecret() {
	$key = get_option('outlandish_sync_secret');
	if (!$key) {
		$key = '';
		$length = 16;
		while ($length--) {
			$key .= chr(mt_rand(33, 126));
		}
		update_option('outlandish_sync_secret', $key);
	}

	return $key;
}

/**
 * @return string Encoded secret+URL token
 */
function dbsc_getToken() {
	return trim(base64_encode(dbsc_getSecret() . ' ' . get_bloginfo('wpurl')), '=');
}

/**
 * @param $url
 * @return string $url with leading http:// stripped
 */
function dbsc_stripHttp($url) {
	return preg_replace('|^https?://|', '', $url);
}

/**
 * Load a series of SQL statements.
 * @param $sql string SQL dump
 */
function dbsc_loadSql($sql) {
	$sql = preg_replace("|/\*.+\*/\\n|", "", $sql);
	$queries = explode(";\n", $sql);
	foreach ($queries as $query) {
		if (!trim($query)) continue;
		global $wpdb;
		if ($wpdb->query($query) === false) {
			return false;
		}
	}

	return true;
}

/**
 * Generate a URL for the plugin.
 * @param array $params
 * @return string
 */
function dbsc_url($params = array()) {
	$params = array_merge(array('page'=>'dbsc_options'), $params);
	return admin_url('tools.php?' . http_build_query($params));
}

/**
 * @param $url string Remote site wpurl base
 * @param $action string dbsc_pull or dbsc_push
 * @param $params array POST parameters
 * @return string The returned content
 * @throws RuntimeException
 */
function dbsc_post($url, $action, $params) {
	$remote = $url . '/wp-admin/admin-ajax.php?action=' . $action . '&api_version=' . dbsc_API_VERSION;
	$ch = curl_init($remote);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	$result = curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($code != 200) {
		throw new RuntimeException('HTTP Error', $code);
	}
	return $result;
}

/**
 * Dump the database and save
 */
function dbsc_makeBackup() {
	ob_start();
	dbsc_mysqldump();
	$sql = ob_get_clean();

	$tempdir = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'backups';
	if (!file_exists($tempdir)) mkdir($tempdir);
	$filename = $tempdir . DIRECTORY_SEPARATOR .'db'.date('Ymd.His').'.sql.gz';
	file_put_contents($filename, gzencode($sql));

	return $filename;
}
/**
 * 2015.12.30 YongUn Choi
 *  array to store list of post id of shop_order type post which is order data,
 *  collected when creating sql for wp_posts table,
 *  used when creating sql for wp_postmeta table to avoid syncing additional information of order data.
 */
$order_post_list;
$order_type_column_no=20;
$post_id_column_no=0;
/**
 * Dump the current MYSQL table.
 * Original code (c)2006 Huang Kai <hkai@atutility.com>
 * Extended to exclude cirtain tables
 */
function dbsc_mysqldump() {
	global $wpdb;
	global $order_post_list;
	$order_post_list=fetch_order_post_list();

    $tables_to_be_excluded=array($wpdb->users,$wpdb->usermeta);

    $tables_to_be_excluded_by_string=array('woocommerce_order_items','woocommerce_order_itemmeta');
	$sql = "SHOW TABLES;";
	echo '/* Dump of database '.DB_NAME.' on '.$_SERVER['HTTP_HOST'].' at '.date('Y-m-d H:i:s')." */\n\n";
	$results = $wpdb->get_results($sql, ARRAY_N);

	foreach ($results as $row) {
        $table=$row[0];
        if(in_array($table,$tables_to_be_excluded)){
            continue;
        }
        if(contained_in_array($table,$tables_to_be_excluded_by_string)){
            continue;
        }
		echo dbsc_mysqldump_table($table);
	}
	$wpdb->flush();
}

function contained_in_array($small_string,$arr){
	foreach ($arr as $big_string) {
		$result=contained($small_string,$big_string);
		if($result ==true){
			return true;
		}
	}
	return false;

}

function contained($small_string,$big_string){
    if (strpos($small_string,$big_string) !== false) {
        return true;
    }else{
        return false;
    }
}


/**
 * Original code (c)2006 Huang Kai <hkai@atutility.com>
 * @param $table string Table name
 * @return string SQL
 */
function dbsc_mysqldump_table($table) {
	global $wpdb;
	echo "/* Table structure for table `$table` */\n\n";

	$sql = "SHOW CREATE TABLE `$table`; ";
	$result = $wpdb->get_results($sql, ARRAY_A);
	if ($result) {
        if (isset($result[0]['View'])) {
            echo "DROP VIEW IF EXISTS `$table`;\n\n";
            echo $result[0]['Create View'] . ";\n\n";
            return;
        }

	    echo "DROP TABLE IF EXISTS `$table`;\n\n";
	    echo $result[0]['Create Table'] . ";\n\n";
	}
	$wpdb->flush();

	$sql = "SELECT * FROM `$table`;";
	$result = $wpdb->get_results($sql, ARRAY_N);

	if ($result) {
		$num_rows = count($result);
		$num_fields = count($result[0]);
		if ($num_rows > 0) {
			echo "/* dumping data for table `$table` */\n";
			$field_type = $wpdb->get_col_info();
			$maxInsertSize = 100000;
			$index = 0;
			$statementSql = '';
			foreach ($result as $row) {
				/**
				 * 2015.12.30 YongUn Choi
				 * skip if post_type == shop_order to avoid overriding order information
				 */
				if($table == $wpdb->posts){
					if(is_order_information($row)){
							$index++;
							continue;
					}
				}
				if($table ==$wpdb->postmeta){
					if(!empty($order_post_list)){
						if(in_array($row['post_id'],$order_post_list)){
							$index++;
							continue;
						}
					}
				}
				/**
				 * end .2015.12.30 YongUn Choi
				 */
				if (!$statementSql) $statementSql .= "INSERT INTO `$table` VALUES\n";
				$statementSql .= "(";
				for ($i = 0; $i < $num_fields; $i++) {
					if (is_null($row[$i]))
						$statementSql .= "null";
					else {
						switch ($field_type[$i]) {
							case 'int':
								$statementSql .= $row[$i];
								break;
							case 'string':
							case 'blob' :
							default:
								$statementSql .= "'" . $wpdb->_real_escape($row[$i]) . "'";

						}
					}
					if ($i < $num_fields - 1)
						$statementSql .= ",";
				}
				$statementSql .= ")";

				if (strlen($statementSql) > $maxInsertSize || $index == $num_rows - 1) {
					echo $statementSql.";\n";
					$statementSql = '';
				} else {
					$statementSql .= ",\n";
				}

				$index++;
			}
		}
	}
	$wpdb->flush();
	echo "\n";
}

function is_order_information($row){
	global $order_type_column_no;
	if($row[$order_type_column_no]=='shop_order')
		return true;
	else
		return false;
}

function fetch_order_post_list(){
	global  $wpdb;
	$pids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='shop_order'");
	return $pids;
}

