<?php
function ninja_annc_initial_setup(){
	global $wpdb;
	$plugin_settings = get_option('ninja_annc_settings');
	if(!isset($plugin_settings['default_title_wrapper']) OR $plugin_settings['default_title_wrapper'] == ''){
		$plugin_settings['default_style'] = 'checked';
		$plugin_settings['default_title_wrapper'] = 'div';
		$plugin_settings['default_content_wrapper'] = 'div';
		update_option('ninja_annc_settings', $plugin_settings);
	}
	
	$ninja_annc_table_name = $wpdb->prefix."ninja_annc";
	if($wpdb->get_var("SHOW TABLES LIKE '".$ninja_annc_table_name."'") == $ninja_annc_table_name) {
		$ninja_annc_rows = $wpdb->get_results( 
		$wpdb->prepare("SELECT * FROM $ninja_annc_table_name")
		, ARRAY_A);

		if(!empty($ninja_annc_rows)){
			$x = 0;
			foreach($ninja_annc_rows as $row){
				$old_id = $row['id'];
				$content = $row['message'];
				$active = $row['active'];
				if($active == 1){
					$post_status = 'publish';
				}else{
					$post_status = 'draft';
				}
				
				$begin_date = $row['begindate'];
				if($begin_date == 0 OR !isset($row['begindate'])){
					$ignore_dates = 1;
					$begin_hr = '';
					$begin_min = '';
					$begin_ampm = '';
					$begin_date = '';
					
					$end_date = '';
					$end_hr = '';
					$end_min ='';
					$end_ampm = '';
					$end_date = '';	
				}else{
					$ignore_dates = 0;
					$begin_hr = date('g', $begin_date);
					$begin_min = date('i', $begin_date);
					$begin_ampm = date('a', $begin_date);
					$begin_date = date('m/d/Y', $begin_date);
					
					$end_date = $row['enddate'];
					$end_hr = date('g', $end_date);
					$end_min = date('i', $end_date);
					$end_ampm = date('a', $end_date);
					$end_date = date('m/d/Y', $end_date);					
				}
				
				$location = $row['location'];
				switch($location){
					case "0":
						$location = 'default';
						break;
					case "1":
						$location = 'widget';
						break;
					case "2":
						$location = 'function';
						break;
				}
				
				$new_annc = array(
					'post_status' => $post_status,
					'post_title' => 'Imported Announcement '.$x,
					'post_type' => 'ninja_annc',
					'post_content' => $content,
				);
				$new_annc_id = wp_insert_post($new_annc);
				
				$meta = array();
				//These are the values that were present in the older versions of NInja Announcements.
				$meta['location'] = $location;
				$meta['ignore_dates'] = $ignore_dates;
				$meta['begin_date'] = $begin_date;
				$meta['begin_hr'] = $begin_hr;
				$meta['begin_min'] = $begin_min;
				$meta['begin_ampm'] = $begin_ampm;			
				$meta['end_date'] = $end_date;
				$meta['end_hr'] = $end_hr;
				$meta['end_min'] = $end_min;
				$meta['end_ampm'] = $end_ampm;
				
				
				//These settings are new to Ninja Announcements and need to be set to the default values.
				$meta['show_title'] = 0;
				$meta['title_wrapper'] = 'div';
				$meta['content_wrapper'] = 'div';
				$meta['display_to'] = 'all';
				$meta['show_close'] = 0;
				$meta['show_open'] = 0;
				$meta['closed_time'] = 'none';
				$meta['Sun'] = 0;
				$meta['Mon'] = 0;
				$meta['Tue'] = 0;
				$meta['Wed'] = 0;
				$meta['Thu'] = 0;
				$meta['Fri'] = 0;
				$meta['Sat'] = 0;
				$meta['main_only'] = 0;
				$meta['main_exclude'] = 0;
				$meta['posts_only'] = 0;
				$meta['posts_exclude'] = 0;
				$meta['display_post'] = '';
				$meta['post_attach'] = '';
				$meta['pages_only'] = 0;
				$meta['pages_exclude'] = 0;
				$meta['display_page'] = '';
				$meta['page_attach'] = '';
				$meta['display_cat'] = '';
				
				add_post_meta($new_annc_id, '_ninja_annc_meta', $meta);
				
				$wpdb->query($wpdb->prepare("DELETE FROM $ninja_annc_table_name WHERE id = %d", $old_id));				
				$x++;
			}
		}
		$wpdb->query($wpdb->prepare("DROP TABLE  $ninja_annc_table_name"));		
	}
}