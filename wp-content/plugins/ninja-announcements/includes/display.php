<?php
add_action('init', 'ninja_annc_start_session');
function ninja_annc_start_session(){
	if(session_id() == '') {
		session_start();
	}
}

function ninja_annc_display_group($group_id){
	$group = get_term($group_id, 'ninja_annc_groups');
	$widget = false;
	$plugin_settings = get_option('ninja_annc_settings');	
	$group_settings = $plugin_settings['groups'][$group_id];
	if($group_settings['override'] == 1){
		if($group_settings['location'] == 'function'){
			$num = $group_settings['show_number'];
			$order = $group_settings['show_order'];
			switch($order){
				case 'random':
					$orderby = 'rand';
					$order = '';
					break;
				case 'date_asc':
					$orderby = 'post_date';
					$order = 'ASC';
					break;
				case 'date_desc':
					$orderby = 'post_date';
					$order = 'DESC';
					break;
			}
			$args = array(
				'numberposts' => $num,
				'orderby' => $orderby,
				'post_type' => 'ninja_annc',
				'tax_query' => array(
					array(
						'taxonomy' => 'ninja_annc_groups',
						'field' => 'slug',
						'terms' => $group->slug,
					)
				)
			);
			if($order != ''){
				$args['order'] = $order;
			}
			$posts_array = get_posts( $args );
			foreach($posts_array as $post){
				echo ninja_annc_check($post->ID, $widget, $group->term_id);
			}
		}
	}
}

function ninja_annc_display($annc_id){
	echo ninja_annc_check($annc_id);
}

function ninja_annc_display_group_location($location){
	if($location == 'widget'){
		$widget = true;
	}else{
		$widget = false;
	}
	$args = array(
		'type'                     => 'ninja_annc',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'ninja_annc_groups',
		'pad_counts'               => false 
		);
	$groups = get_categories($args);
	$plugin_settings = get_option('ninja_annc_settings');	
	foreach($groups as $group){
		if(isset($plugin_settings['groups'][$group->term_id])){
			$group_settings = $plugin_settings['groups'][$group->term_id];
		}else{
			$group_settings = '';
		}
		if(isset($group_settings['override']) AND $group_settings['override'] == 1){
			if($group_settings['location'] == $location){
				$num = $group_settings['show_number'];
				$order = $group_settings['show_order'];
				switch($order){
					case 'random':
						$orderby = 'rand';
						$order = '';
						break;
					case 'date_asc':
						$orderby = 'post_date';
						$order = 'ASC';
						break;
					case 'date_desc':
						$orderby = 'post_date';
						$order = 'DESC';
						break;
				}
				$args = array(
					'numberposts' => $num,
					'orderby' => $orderby,
					'post_type' => 'ninja_annc',
					'tax_query' => array(
						array(
							'taxonomy' => 'ninja_annc_groups',
							'field' => 'slug',
							'terms' => $group->slug,
						)
					)
				);
				if($order != ''){
					$args['order'] = $order;
				}
				$posts_array = get_posts( $args );
				foreach($posts_array as $post){
					echo ninja_annc_check($post->ID, $widget, $group->term_id);
				}
			}
		}
	}
}

function ninja_annc_display_annc_location($location, $widget = false){
	$args = array(
    'offset'          => 0,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_type'       => 'ninja_annc',
    'post_status'     => 'publish' );
	
	$posts_array = get_posts( $args );
	foreach($posts_array as $post){
		$meta = get_post_meta($post->ID, '_ninja_annc_meta', true);
		$annc_location = $meta['location'];
		if($location == $annc_location){
			echo ninja_annc_check($post->ID);
		}
	}
}

function ninja_annc_check($id, $widget = false, $group = false, $before_title = false, $after_title = false){
	global $current_user, $post;
	//Setup our variables	
	$plugin_settings = get_option('ninja_annc_settings');	
	$display = true; //Initially, we assume that this announcement will be displayed.
	$this_post = get_post($id, ARRAY_A);
	$content = $this_post['post_content'];
	$title = $this_post['post_title'];
	$current_time = current_time('timestamp');
	if($group){
		$meta = $plugin_settings['groups'][$group];
		$active = $meta['active'];
		$cookie_name = 'ninja_annc_group_close_'.$group;
	}else{
		$meta = get_post_meta($id, '_ninja_annc_meta', true);
		if($this_post['post_status'] == 'publish'){
			$active = 1;
		}else{
			$active = 0;
		}
		if(NINJA_ANNC_TYPE == 'Pro'){
			//Begin Group Override Check
			if(ninja_annc_group_override_check($id)){
				$display = false;
			}//End Group Override Check
		}	
		$cookie_name = 'ninja_annc_close_'.$id;
	}
		
	if($active != 1){
		$display = false;
	}
		
	if(isset($meta['ignore_dates'])){
		$ignore_dates = $meta['ignore_dates'];
	}else{
		$ignore_dates = '';
	}
	if(isset($meta['location'])){
		$location = $meta['location'];
	}else{
		$location = '';
	}
	if(isset($meta['show_title'])){
		$show_title = $meta['show_title'];
	}else{
		$show_title = '';
	}
	if(isset($meta['begin_date'])){
		$begin_date = $meta['begin_date']." ".$meta['begin_hr'].":".$meta['begin_min']." ".$meta['begin_ampm'];
	}else{
		$begin_date = '';
	}
	if(isset($meta['end_date'])){
		$end_date = $meta['end_date']." ".$meta['end_hr'].":".$meta['end_min']." ".$meta['end_ampm'];
	}else{
		$end_date = '';
	}
	if(isset($meta['display_to'])){
		$display_to = $meta['display_to'];
	}else{
		$display_to = '';
	}
	if(isset($meta['display_post'])){
		$display_post = $meta['display_post'];
	}else{
		$display_post = '';
	}
	if(isset($meta['post_attach'])){
		$post_attach = $meta['post_attach'];
	}else{
		$post_attach = '';
	}
	if(isset($meta['display_page'])){
		$display_page = $meta['display_page'];
	}else{
		$display_page = '';
	}
	if(isset($meta['page_attach'])){
		$page_attach = $meta['page_attach'];
	}else{
		$page_attach = '';
	}
	if(isset($meta['display_cat'])){
		$display_cat = $meta['display_cat'];
	}else{
		$display_cat = '';
	}	
	if(isset($meta['display_main'])){
		$display_main = $meta['display_main'];
	}else{
		$display_main = '';
	}	
	if(isset($meta['display_front'])){
		$display_front = $meta['display_front'];
	}else{
		$display_front = '';
	}
	if(isset($meta['advanced_post'])){
		$advanced_post = $meta['advanced_post'];
	}else{
		$advanced_post = '';
	}
	if(isset($meta['display_on_posts'])){
		$display_on_posts = $meta['display_on_posts'];
	}else{
		$display_on_posts = 1;
	}	
	if(isset($meta['advanced_page'])){
		$advanced_page = $meta['advanced_page'];
	}else{
		$advanced_page = '';
	}
	if(isset($meta['display_on_pages'])){
		$display_on_pages = $meta['display_on_pages'];
	}else{
		$display_on_pages = 1;
	}
	if(isset($meta['advanced_cat'])){
		$advanced_cat = $meta['advanced_cat'];
	}else{
		$advanced_cat = '';
	}
	if(isset($_SESSION[$cookie_name])){
		$session_close = $_SESSION[$cookie_name];
	}else{
		$session_close = '';
	}
	if(isset($_COOKIE[$cookie_name])){
		$cookie_close = $_COOKIE[$cookie_name];
	}else{
		$cookie_close = '';
	}
	if(isset($meta['show_close'])){
		$show_close = $meta['show_close'];
	}else{
		$show_close = '';
	}	
	if(isset($meta['show_open'])){
		$show_open = $meta['show_open'];
	}else{
		$show_open = '';
	}
	if(isset($meta['closed_time'])){
		$closed_time = $meta['closed_time'];
	}else{
		$closed_time = '';
	}
	//Begin day of the week test.
	$days_of_the_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
	$day = date("D", $current_time);
	
	$required_days = '';
	foreach($days_of_the_week as $d){
		if($meta[$d] == 1){
			$required_days .= $d;
		}
	}
	if(strpos($required_days, $day) !== false){
		$show_day = true;
	}else{
		$show_day = false;
	}
	if(!$show_day){
		$display = false;
	}
	//End day of the week test.
	
	// Begin Date Requirement Section
	$begin_date = strtotime($begin_date);
	$end_date = strtotime($end_date);
	$today = $current_time;
	if(($begin_date < $today AND $end_date >= $today) OR $ignore_dates == 1){ //If these are true, we've passed the Date Section.
		//$display = true; //set our display variable to true.
	}else{
		$display = false; //set our display variable to false.
	}
	//End Date Requirement Section	
	
	//Begin User Requirements Section
	get_currentuserinfo();
	$user_id = $current_user->ID; //See if we have a user id.
	switch($display_to){
		case "all": //The "All visitors" option.
			//$display = true;
			break;
		case "not_logged_in":
			if($user_id){ //If we have a user_id, then someone is logged in.
				$display = false;
			}else{
				//$display = true;
			}
			break;
		case "logged_in": //Only logged-in users should see the announcement
			if($user_id){
				//$display = true;
			}else{
				$display = false;
			}			
			break;
		case "administrator": //Only administrators should see the announcement
			if(current_user_can('administrator')){
				//$display = true;
			}else{
				$display = false;
			}
			break;		
		case "author": //Only authors should see the announcement
			if(current_user_can('author')){
				//$display = true;
			}else{
				$display = false;
			}
			break;		
		case "contributor": //Only contributors should see the announcement
			if(current_user_can('contributor')){
				//$display = true;
			}else{
				$display = false;
			}
			break;
		case "editor": //Only editors should see the announcement
			if(current_user_can('editor')){
				//$display = true;
			}else{
				$display = false;
			}
			break;
		case "subscriber": //Only subscribers should see the announcement
			if(current_user_can('subscriber')){
				//$display = true;
			}else{
				$display = false;
			}
			break;
	}
	//End User Requirements Section
	
	//Begin Main Page (Homepage) display section
	if($display_main != 1 AND is_home()){
		$display = false;
	}
	
	if($display_front != 1 AND is_front_page()){
		$display = false;
	}
	//End Main Page (Homepage) display section
	
	//Begin cookie and session variable section.
	
	if(($session_close == 'closed' OR $cookie_close == 'closed') AND $show_close == 1 AND $show_open != 1){
		$display = false;
	}
	//End cookie and session variable section.
	
	
	
	//Begin Post/Page/Cat Display Section
	
	if($post_attach != ''){
		$post_found = false;
		$post_attach_array = explode(",", $post_attach);
		$post_found = false;
		foreach($post_attach_array as $post_id){
			if(is_single($post_id)){
				$post_found  = true;
				break;
			}
		}
	}	
	
	if($page_attach != ''){
		$page_found = false;
		$page_attach_array = explode(",", $page_attach);
		$page_found = false;
		foreach($page_attach_array as $page_id){
			if(is_page($page_id)){
				$page_found  = true;
				break;
			}
		}
	}
	$categories = '';
	$args = array(
		'type'                     => 'post',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'category',
		'pad_counts'               => false 
		);
	$cats = get_categories($args);
	foreach($cats as $cat){
		if(isset($meta['cat_'.$cat->slug]) AND $meta['cat_'.$cat->slug] == 1){
			$categories[] = $cat->cat_ID;
		}
	}

	if($categories != ''){	
		$cat_found = false;
		if(is_single() OR is_page()){
			$current_cats = get_the_category($post->ID);
			foreach($current_cats as $cat){
				if(in_array($cat->term_id, $categories)){
					$cat_found = true;
					break;
				}
			}
		}	
	}
	
	if($display_on_posts != 1 AND is_single()){
		$display = false;
	}	
	if($display_on_pages != 1 AND is_page()){
		$display = false;
	}
	if($advanced_cat == 1 AND is_single()){
		if($display_cat == 'cat_only'){
			if(!$cat_found){
				$display = false;
			}
		}elseif($display_cat == 'cat_exclude'){
			if($cat_found){
				$display = false;
			}
		}
	}
	

	if(is_single() AND $advanced_post == 1 AND $post_attach != ''){
		if($display_post == 'post_only' AND !$post_found){
			$display = false;
		}elseif($display_post == 'post_exclude' AND $post_found){
			$display = false;
		}
	}
	if(is_page() AND $advanced_page == 1 AND $page_attach != ''){
		if($display_page == 'page_only' AND !$page_found){
			$display = false;
		}elseif($display_page == 'page_exclude' AND $page_found){
			$display = false;
		}
	}

	if($display){
		return ninja_annc_output_display($id, $widget, $group, $before_title, $after_title);
	}
}

function ninja_annc_output_display($id, $widget = false, $group = false, $before_title = false, $after_title = false){
	if($widget){
		$widget_class = 'widget-';
	}else{
		$widget_class = '';
	}
	ninja_annc_display_js();
	$this_post = get_post($id, ARRAY_A);
	if($group){
		$plugin_settings = get_option('ninja_annc_settings');	
		$meta = $plugin_settings['groups'][$group];
		$cookie_name = 'ninja_annc_group_close_'.$group;
	}else{
		$meta = get_post_meta($id, '_ninja_annc_meta', true);
		$cookie_name = 'ninja_annc_close_'.$id;
		//echo 'post';
	}

	$content = $this_post['post_content'];
	$content = do_shortcode($content);
	$content = apply_filters('ninja_annc_content', $content);
	$content = apply_filters('ninja_annc_content_'.$id, $content);
	$title = $this_post['post_title'];
	$title = apply_filters('ninja_annc_title', $title);	
	$title = apply_filters('ninja_annc_title_'.$id, $title);	
	if(isset($meta['show_title'])){
		$show_title = $meta['show_title'];
	}else{
		$show_title = '';
	}
	if(isset($meta['content_wrapper'])){
		$content_wrapper = $meta['content_wrapper'];	
	}else{
		$content_wrapper = 'div';
	}
	if(isset($meta['title_wrapper'])){
		$title_wrapper = $meta['title_wrapper'];
	}else{
		$title_wrapper = 'div';
	}
	if(isset($_SESSION[$cookie_name])){
		$session_close = $_SESSION[$cookie_name];
	}else{
		$session_close = '';
	}
	if(isset($_COOKIE[$cookie_name])){
		$cookie_close = $_COOKIE[$cookie_name];
	}else{
		$cookie_close = '';
	}
	if(isset($meta['show_close'])){
		$show_close = $meta['show_close'];
	}else{
		$show_close = '';
	}
	if(isset($meta['closed_time'])){
		$closed_time = $meta['closed_time'];
	}else{
		$closed_time = '';
	}
	if(($session_close == 'closed' OR $cookie_close == 'closed') AND $show_close == 1){
		$display = "display:none;";
		$button = __('Open', 'ninja-announcements');
	}else{
		$display = "";
		$button = __('Close', 'ninja-announcements');
	}	
	do_action('ninja_annc_before_output');
	do_action('ninja_annc_before_output_'.$id);
	//begin output.
	$output = "<div id='my-modal' class='modal fade'>";
	$output .= "    <div class='modal-dialog' style='z-index: 9999'>";
	$output .= "        <div class='modal-content'>";
	$output .= "            <div class='modal-header'>";
	$output .= "                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>Close</button>";
	$output .= "<h4 class='modal-title'>Notice</h4>";
	$output .= "            </div>";
	$output .= "            <div class='modal-body'>";

	$output .= "<div id='ninja_annc_".$id."_wrapper' class='ninja-annc-".$widget_class."wrapper' style='".$display."'>";
	$output .= "<input type='hidden' name='' id='ninja_annc_".$id."_group' value='".$group."'>";
	$output .= "<div class='ninja-annc-content'>";
	do_action('ninja_annc_before_title');
	do_action('ninja_annc_before_title_'.$id);
	if($show_title == 1){
		if($widget){
			$output .= $before_title;
			$title = apply_filters( 'widget_title', $title);
		}else{
			$output .= "<".$title_wrapper." class='ninja-annc-title'>";
		}
		$output .= "$title";
		if($widget){
			$output .= $after_title;
		}else{
			$output .= "</".$title_wrapper.">";
		}
	}
	do_action('ninja_annc_after_title');
	do_action('ninja_annc_after_title_'.$id);
	do_action('ninja_annc_before_content');
	do_action('ninja_annc_before_content_'.$id);
	$output .= "<".$content_wrapper." class='ninja-annc-entry'>$content</".$content_wrapper.">";
	do_action('ninja_annc_after_content');
	do_action('ninja_annc_after_content_'.$id);
	$output .="</div>";
	if($meta['show_open'] != 1){
		$hide_class = 'ninja-annc-hide-close';
	}else{
		$hide_class = '';
	}
	if($meta['show_close'] == 1){
		$output .= '</div>';
		$output .= "<a href='#' id='ninja_annc_close_".$id."' name='' class='ninja-annc-close ".$hide_class."'>".$button."</a>";
		
	}else{
		$output .= '</div>';			
	}
	$output = apply_filters('ninja_annc_output', $output);
	$output = apply_filters('ninja_annc_output_'.$id, $output);
	do_action('ninja_annc_after_output');
	do_action('ninja_annc_after_output_'.$id);

	$output .= "</div>";
	$output .= "        </div>";
	$output .= "    </div> ";
	$output .= "</div>";
	$output .="<script>jQuery(document).ready(function() {
jQuery('#my-modal').modal('show');});</script>";

	return $output;
}

function ninja_annc_update_annc(){
	$args = array(
    'offset'          => 0,
    'orderby'         => 'post_date',
    'order'           => 'DESC',
    'post_type'       => 'ninja_annc',
    'post_status'     => 'publish' );
	
	$posts_array = get_posts( $args );
	foreach($posts_array as $post){
		$meta = get_post_meta($post->ID, '_ninja_annc_meta', true);
		$ignore_dates = $meta['ignore_dates'];
		$begin_date = $meta['begin_date']." ".$meta['begin_hr'].":".$meta['begin_min']." ".$meta['begin_ampm'];
		$end_date = $meta['end_date']." ".$meta['end_hr'].":".$meta['end_min']." ".$meta['end_ampm'];
		$begin_date = strtotime($begin_date);
		$end_date = strtotime($end_date);
		$today = strtotime('now');
		if($today > $end_date AND $ignore_dates != 1){
			$my_post = array();
			$my_post['ID'] = $post->ID;
			$my_post['post_status'] = 'draft';
			wp_update_post( $my_post );
		}
	}
}

function ninja_annc_group_override_check($id){
	//Begin Group Override Check
	$plugin_settings = get_option('ninja_annc_settings');	
	$groups = get_the_terms($id, 'ninja_annc_groups');
	if($groups){
		foreach($groups as $group){
			if(isset($plugin_settings['groups'][$group->term_id]['override']) AND $plugin_settings['groups'][$group->term_id]['override']  == 1){
				return $group->term_id;
			}
		}
		return false;
	}
	//End Group Override Check	
}

add_filter('ninja_annc_title_1225', 'ninja_annc_test');
add_filter('ninja_annc_content_1225', 'ninja_annc_test');
function ninja_annc_test($str){
	$str = str_replace('Christmas', 'X-mas', $str);
	return $str;
}