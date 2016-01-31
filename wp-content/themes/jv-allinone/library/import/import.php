<?php


/*


 # JV Library - JV Theme


 # ------------------------------------------------------------------------


 # author    Open Source Code Solutions Co


 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.


 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.


 # Websites: http://www.joomlavi.com


 # Technical Support:  http://www.joomlavi.com/my-tickets.html


-------------------------------------------------------------------------*/





/**
 *
 *
 * JV Allinone functions and definitions
 *
 *
 *
 *
 *
 * Sets up the theme and provides some helper functions, which are used
 *
 *
 * in the theme as custom template tags. Others are attached to action and
 *
 *
 * filter hooks in WordPress to change core functionality.
 *
 *
 *
 *
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 *
 *
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 *
 *
 *
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 *
 *
 * functions.php file. The child theme's functions.php file is included before the parent
 *
 *
 * theme's file, so the child theme functions would be used.
 *
 *
 *
 *
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 *
 *
 * to a filter or action hook.
 *
 *
 *
 *
 *
 *
 *
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * 
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/







class jvImport{


	


	static $comment = array('#','--','-- ','DELIMITER','/*!');


	static $delimiter = ';';


	static $string_quotes = '\''; 


	static $db_connection_charset = 'utf8';


	static $max_query_lines = 300000;


	static $gzipmode = false;


        static $DATA_CHUNK_LENGTH = 16384;


        static $domain = array('192.168.1.8','joomlao2.com');


	


	public static function getDb(){


		global $wpdb;


                return $wpdb;


	}


	


	public static function updateWooc(){


            $permalinks = get_option( 'woocommerce_permalinks' );


            update_option( 'woocommerce_permalinks', $permalinks );


            $woopages = array(


                    'woocommerce_shop_page_id' => 'Shop',


                    'woocommerce_cart_page_id' => 'Cart',


                    'woocommerce_checkout_page_id' => 'Checkout',


                    'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',


                    'woocommerce_thanks_page_id' => 'Order Received',


                    'woocommerce_myaccount_page_id' => 'My Account',


                    'woocommerce_edit_address_page_id' => 'Edit My Address',


                    'woocommerce_view_order_page_id' => 'View Order',


                    'woocommerce_change_password_page_id' => 'Change Password',


                    'woocommerce_logout_page_id' => 'Logout',


                    'woocommerce_lost_password_page_id' => 'Lost Password'


            );


            foreach($woopages as $woo_page_name => $woo_page_title) {


                    $woopage = get_page_by_title( $woo_page_title );


                    if(isset( $woopage ) && $woopage->ID) {


                            update_option($woo_page_name, $woopage->ID); // Front Page


                    }


            }


            // We no longer need to install pages


            delete_option( '_wc_needs_pages' );


            delete_transient( '_wc_activation_redirect' );


            // Flush rules after install


            flush_rewrite_rules();


            


	}   


    


    public static function downloadSampleData() {


        


        $files = array(


            'upload' => 'uploads.zip'


        );


        if( isset( $_[ 'file' ] ) && isset( $files[ $_POST[ 'file' ] ] ) ) {


            


            global $wp_filesystem;


            WP_Filesystem();


            $file       = $files[ $_POST[ 'file' ] ];


            $local      = JVLibrary::path('theme') ."library/import/data/{$file}";


            $uploads    = JVLibrary::urls('demo') . "/{$file}";


            $contents   = $wp_filesystem->get_contents($uploads);


            $wp_filesystem->put_contents($local, $contents);    


        }


        


        die( 'done' );    


    }


	public static function delTree($dir) { 
		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) { 
			(is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file"); 
		} 
		return rmdir($dir); 
	}


	public static function sampleData() {


        


        global $wp_filesystem;


		


		$local      = rtrim( JVLibrary::path('theme'), '/' ) . "/inc";


		$vipscanner = isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] === 'vip-scanner';
		
		$dir_import = $local . '/import';
		
		if( $vipscanner && is_dir( $dir_import ) ) {
			
			self::delTree( $dir_import );
			
		}
		


		if( is_dir( $dir_import ) || $vipscanner ) { return false; }


		


		$cache 		= $local . "/import.zip";


		


		$uploads    = rtrim( JVLibrary::urls('demo'), '/' ) . "/plugins/import_v1.2.zip";


		$contents   = $wp_filesystem->get_contents( $uploads );


		$wp_filesystem->put_contents( $cache, $contents);


        unzip_file( $cache, $local ); 


		$wp_filesystem->delete( $cache );


    }


	





        public static function importImages(){


            


            global $wp_filesystem;


            WP_Filesystem();


            


            $wp_content = JVLibrary::path('wp-content');


            $pzip       = JVLibrary::path('theme') .'library/import/data/';


            $zips       = glob( plugin_dir_path( __FILE__ ) . "data/*.zip" );


            


            foreach( $zips as $zip ) {


                


                if( !file_exists( $zip ) ) { continue; }


                


                unzip_file( $zip, $wp_content ); 


				


				$wp_filesystem->delete( $zip );


				


            }


        }





        public static function replaceImageUrl($str){


            $db = self::getDb();


			call_user_func( 'require', ABSPATH . WPINC . '/version.php' );


            global $wp_db_version;


            $str = str_replace('#__', $db->prefix, $str);


            $str = str_replace( 'jvtemplate', get_template(), $str );


			$str = str_replace( 'rdbversion', $wp_db_version, $str );


            $config = JVLibrary::getConfig();


            $patterns  = array(


                'http://192.168.1.8/club/wordpress/jv-allinone',


                'http:\\/\\/192.168.1.8\\/club\\/wordpress\\/jv-allinone',


                'http://joomlao2.com/club/wordpress/jv-allinone',


                'http:\\/\\/joomlao2.com\\/club\\/wordpress\\/jv-allinone'


            );


            $replace = array(


                $config->siteurl,


                str_replace('/', '\\/', $config->siteurl),


                $config->siteurl,


                str_replace('/', '\\/', $config->siteurl)


            );    


            $str = str_replace($patterns, $replace, $str);


            return $str;


        }





        


        public static function importSQL(){


            //@ini_set('mysql.connect_timeout', 2000);


            //@ini_set('default_socket_timeout', 2000);


            @set_time_limit(0);


            global $wp_filesystem; 


            $sql = JVLibrary::path('theme')."library/import/data/jv_allinone.sql";


            $sample = JVLibrary::urls('demo') . '/jv_allinone.sql';


			$contents = $wp_filesystem->get_contents($sample);


			$wp_filesystem->put_contents($sql, $contents);


            


            $db = self::getDb();


            $jvfn = 'f'.'open';


		    if(!$file=  $jvfn($sql, 'r')) return;


		    $query="";


		    $queries = $totalqueries = $querylines = $inparents = $linenumber = 0;


		    while ($linenumber < self::$max_query_lines || $query!="") {


			    // Read the whole next line


			    $dumpline = "";


			    while (!feof($file) && substr ($dumpline, -1) != "\n" && substr ($dumpline, -1) != "\r") {


				    $dumpline .= fgets($file, self::$DATA_CHUNK_LENGTH);


				    //else $dumpline .= gzgets($file, self::$DATA_CHUNK_LENGTH);


			    }


			    if ($dumpline==="") break;


			    // Remove UTF8 Byte Order Mark at the file beginning if any


			    $dumpline=preg_replace('|^\xEF\xBB\xBF|','',$dumpline);


			    


			    // Handle DOS and Mac encoded linebreaks (I don't know if it really works on Win32 or Mac Servers)


			    $dumpline=str_replace("\r\n", "\n", $dumpline);


			    $dumpline=str_replace("\r", "\n", $dumpline);


			    // DIAGNOSTIC


			    // echo ("<p>Line $linenumber: $dumpline</p>\n");


			    // Recognize delimiter statement


			    if (!$inparents && strpos ($dumpline, "DELIMITER ") === 0) self::$delimiter = str_replace ("DELIMITER ","",trim($dumpline));


			    // Skip comments and blank lines only if NOT in parents


			    if (!$inparents) { 


				    $skipline=false;


				    reset(self::$comment);


				    foreach (self::$comment as $comment_value) { 


					    if (trim($dumpline)=="" || strpos (trim($dumpline), $comment_value) === 0) { 


						    $skipline=true;


						    break;


					    }


				    }


				    if ($skipline) { 


					    $linenumber++;


					    continue;


				    }


			    }


			    // Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)


			    $dumpline_deslashed = str_replace ("\\\\","",$dumpline);


			    // Count ' and \' (or " and \") in the dumpline to avoid query break within a text field ending by $delimiter


                            $string_quotes = self::$string_quotes;


			    $parents=substr_count ($dumpline_deslashed, self::$string_quotes)-substr_count ($dumpline_deslashed, "\\$string_quotes");


			    if ($parents % 2 != 0)


			    $inparents=!$inparents;


			    // Add the line to query


			    $query .= $dumpline;


			    // Don't count the line if in parents (text fields may include unlimited linebreaks)


			    if (!$inparents) $querylines++;


			    // Stop if query contains more lines as defined by $max_query_lines


	    


                            if ($querylines>self::$max_query_lines) {


				    $error[] = 'Too long';


				    break;


			    }


			    // Execute query if end of query detected ($delimiter as last character) AND NOT in parents


                            $query = self::replaceImageUrl($query);


			    if ((preg_match('/'.preg_quote(self::$delimiter,'/').'$/',trim($dumpline)) || self::$delimiter=='') && !$inparents) { 


				    // Cut off delimiter of the end of the query


				    $query = substr(trim($query),0,-1*strlen(self::$delimiter));


                                  


				    // echo ("<p>Query: ".trim(nl2br(htmlentities($query)))."</p>\n");


				    if (!$db->query($query)) { 


                                        


					    //echo ("<p class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");


					    echo ("<p>Query: ".trim(nl2br(htmlentities($query)))."</p>\n");


					    echo ("<p>MySQL: ".$db->last_error."</p>\n");


				    


					    break;


				    } 


				    $totalqueries++;


				    $queries++;


				    $query="";


				    $querylines=0;


			    }


			    $linenumber++;


		    }


            $wp_filesystem->delete($sql);


	    }


        


        public static function setOptions() {


		// THIS IS THE EXPORTED THEME OPTIONS


            global $wp_filesystem; 


            $import_file = JVLibrary::path('theme')."library/import/data/themeoptions.txt";


            $import_code = $wp_filesystem->get_contents( $import_file );


            $import_code = JVLibrary::decodeOpiton($import_code);


            update_option(JVLibrary::getKey().'_theme_settings',$import_code);


		


	    }


        


        private static function clear_widgets() {


		$sidebars = wp_get_sidebars_widgets();


		$inactive = isset($sidebars['wp_inactive_widgets']) ? $sidebars['wp_inactive_widgets'] : array();


		unset($sidebars['wp_inactive_widgets']);


		foreach ( $sidebars as $sidebar => $widgets ) {


			//$inactive = array_merge($inactive, $widgets);


			$sidebars[$sidebar] = array();


		}


		$sidebars['wp_inactive_widgets'] = $inactive;


		wp_set_sidebars_widgets( $sidebars );


	}


        


	public static function parseWidget($all){


            $widgets = array();


            foreach ($all as $title=>$lists){


                foreach ($lists as $k=>$v) if($k != '_multiwidget') $widgets[$title][$k] = true;


            }


            return $widgets;


        }





        // Parsing Widgets Function


        // Thanks to http://wordpress.org/plugins/widget-settings-importexport/





        public static function widget(){


                global $wp_filesystem;


                $import_file = JVLibrary::path('theme')."library/import/data/widget_data.txt";


                self::clear_widgets();


		$json_data = $wp_filesystem->get_contents( $import_file );


		


		$json_data = self::replaceImageUrl($json_data);


		


		$json_data = json_decode( $json_data, true );


                $widgets = self::parseWidget($json_data[1]);


		$sidebar_data = $json_data[0];


		$widget_data = $json_data[1];


		foreach ( $sidebar_data as $title => $sidebar ) {


			$count = count( $sidebar );


			for ( $i = 0; $i < $count; $i++ ) {


				$widget = array( );


				$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );


				$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );


				if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {


					unset( $sidebar_data[$title][$i] );


				}


			}


			$sidebar_data[$title] = array_values( $sidebar_data[$title] );


		}





		foreach ( $widgets as $widget_title => $widget_value ) {


			foreach ( $widget_value as $widget_key => $widget_value ) {


				$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];


			}


		}





		$sidebar_data = array( array_filter( $sidebar_data ), $widgets );


		return self::parse_import_data( $sidebar_data );


        }





        


        /**


	 * Import widgets


	 * @param array $import_array


	 */


	public static function parse_import_data( $import_array ) {


		$sidebars_data = $import_array[0];


		$widget_data = $import_array[1];


		$current_sidebars = get_option( 'sidebars_widgets' );


		$new_widgets = array( );





		foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :





			foreach ( $import_widgets as $import_widget ) :


				//if the sidebar exists


				if ( isset( $current_sidebars[$import_sidebar] ) ) :


					$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );


					$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );


					$current_widget_data = get_option( 'widget_' . $title );


					$new_widget_name = self::get_new_widget_name( $title, $index );


					$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );





					if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {


						while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {


							$new_index++;


						}


					}


					$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;


					if ( array_key_exists( $title, $new_widgets ) ) {


						$new_widgets[$title][$new_index] = $widget_data[$title][$index];


						$multiwidget = $new_widgets[$title]['_multiwidget'];


						unset( $new_widgets[$title]['_multiwidget'] );


						$new_widgets[$title]['_multiwidget'] = $multiwidget;


					} else {


						$current_widget_data[$new_index] = $widget_data[$title][$index];


						$current_multiwidget = @$current_widget_data['_multiwidget'];


						$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;


						$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;


						unset( $current_widget_data['_multiwidget'] );


						$current_widget_data['_multiwidget'] = $multiwidget;


						$new_widgets[$title] = $current_widget_data;


					}





				endif;


			endforeach;


		endforeach;





		if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {


			update_option( 'sidebars_widgets', $current_sidebars );





			foreach ( $new_widgets as $title => $content ) {


				$content = apply_filters( 'widget_data_import', $content, $title );


				update_option( 'widget_' . $title, $content );


			}





			return true;


		}





		return false;


	}


        


        /**


	 *


	 * @param string $widget_name


	 * @param string $widget_index


	 * @return string


	 */


	public static function get_new_widget_name( $widget_name, $widget_index ) {


		$current_sidebars = get_option( 'sidebars_widgets' );


		$all_widget_array = array( );


		foreach ( $current_sidebars as $sidebar => $widgets ) {


			if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {


				foreach ( $widgets as $widget ) {


					$all_widget_array[] = $widget;


				}


			}


		}


		while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {


			$widget_index++;


		}


		$new_widget_name = $widget_name . '-' . $widget_index;


		return $new_widget_name;


	}


        


        


	


}


 