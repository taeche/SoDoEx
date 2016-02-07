<?php
//----------------edit custom columns display for back-end 
add_action("manage_posts_custom_column", "ninja_annc_custom_columns");
add_filter("manage_edit-ninja_annc_columns", "ninja_annc_columns");
 
function ninja_annc_columns($columns){ //this function display the columns headings
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"action" => __('Action', 'ninja-announcements'),
		"title" => __('Announcement Title', 'ninja-announcements'),
		);
	if(NINJA_ANNC_TYPE == 'Pro'){
		$columns['groups'] = __('Groups', 'ninja-announcements');
	}		
	
	$columns["dates"] = __('Scheduled Dates', 'ninja-announcements');
	$columns["content"] = __('Description', 'ninja-announcements');
	$columns["function"] = __('Function Code', 'ninja-announcements');

	return $columns;
}
 
function ninja_annc_custom_columns($column){
	global $post;
	$meta = get_post_meta($post->ID, '_ninja_annc_meta', true);
	if(isset($meta['ignore_dates']) AND $meta['ignore_dates'] == 1){
		$dates = __('Not Scheduled (Will show whenever active)', 'ninja-announcements');
	}else{
		if(isset($meta['begin_date'])){
			$begin_date = $meta['begin_date'];
		}else{
			$begin_date = '';
		}
		if(isset($meta['end_date'])){
			$end_date = $meta['end_date'];
		}else{
			$end_date = '';
		}
		$dates = $begin_date." - ".$end_date;
	}
	if(NINJA_ANNC_TYPE == 'Pro'){
		if(ninja_annc_group_override_check($post->ID)){
			$dates = __('Announcement controlled via group settings', 'ninja-announcements');
		}
	}
	switch ($column){
		case 'ID':
			echo $post->ID; //displays title
			break;
		case 'action':
			if($post->post_status == 'draft'){
				echo '<a href="#" class="ninja-annc-activate" id="ninja_annc_'.$post->ID.'">';
				_e('Activate', 'ninja-announcements');
				echo '</a>';
			}else{
				echo '<a href="#" class="ninja-annc-deactivate" id="ninja_annc_'.$post->ID.'">';
				_e('Deactivate', 'ninja-announcements');
				echo '</a>';
			}
			break;
		case 'content':
			$content = $post->post_content;
			$content = strip_tags($content);			
			$content = substr($content, 0, 150);
			echo $content; //displays the content excerpt
			break;
		case 'dates':
			echo $dates;
			break;
		case 'groups':
			$terms = wp_get_post_terms($post->ID, 'ninja_annc_groups');
			if($terms){
				$x = 0;
				foreach($terms as $term){
					$url = admin_url( 'edit-tags.php?action=edit&taxonomy=ninja_annc_groups&tag_ID='.$term->term_id.'&post_type=ninja_annc');
					if($x == 0){
						echo '<a href="'.$url.'">'.$term->name.'</a>';
					}else{
						echo ', <a href="'.$url.'">'.$term->name.'</a>';
					}
					$x++;
				}
			}
			break;
		case 'function':
			echo '&#60;&#63;php
				if (function_exists("ninja_annc_display")) {
					ninja_annc_display('.$post->ID.');
				}
				&#63;&#62;';
			break;
	}
}

// Make these columns sortable
function sortable_columns() {
  return array(
	'title'  => 'title',
    'dates' => 'dates'   
  );
}

add_filter( "manage_edit-ninja_annc_sortable_columns", "sortable_columns" );