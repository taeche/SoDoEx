<?php
function ninja_annc_display_meta($post, $args) {
	global $post;

	$ninja_annc_meta = get_post_meta($post->ID, '_ninja_annc_meta', true);
	foreach($args['args'] as $meta_box) {  
		if(isset($meta_box['name'])){
			$name = $meta_box['name'];
		}else{
			$name = '';
		}
		if(isset($meta_box['default'])){
			$default = $meta_box['default'];
		}else{
			$default = '';
		}
		if(isset($ninja_annc_meta[$name])){
			$value = $ninja_annc_meta[$name];
		}else{
			$value = '';
		}
		$checked = '';
		if(isset($meta_box['disabled'])){
			$disabled = $meta_box['disabled'];
		}else{
			$disabled = '';
		}
		if($value == ''){
			$value = $default;
		}
		if(isset($meta_box['class'])){
			$class = $meta_box['class'];
		}else{
			$class = '';
		}		
		if(isset($meta_box['before_label'])){
			$before_label = $meta_box['before_label'];
		}else{
			$before_label = '';
		}		
		if(isset($meta_box['after_label'])){
			$after_label = $meta_box['after_label'];
		}else{
			$after_label = '';
		}		
		if(isset($meta_box['extra_type'])){
			$extra_type = $meta_box['extra_type'];
		}else{
			$extra_type = '';
		}		
		if(isset($meta_box['field'])){
			$field = $meta_box['field'];
		}else{
			$field = '';
		}		
		if(isset($meta_box['title'])){
			$title = $meta_box['title'];
		}else{
			$title = '';
		}				
		if(isset($meta_box['type'])){
			$type = $meta_box['type'];
		}else{
			$type = '';
		}				
		if(isset($meta_box['options'])){
			$options = $meta_box['options'];
		}else{
			$options = '';
		}		
		if(isset($meta_box['after'])){
			$after = $meta_box['after'];
		}else{
			$after = '';
		}				
		if(isset($meta_box['before'])){
			$before = $meta_box['before'];
		}else{
			$before = '';
		}			
		if(isset($meta_box['field_style'])){
			$field_style = $meta_box['field_style'];
		}else{
			$field_style = '';
		}				
		if(isset($meta_box['size'])){
			$size = $meta_box['size'];
		}else{
			$size = '';
		}		
		if(isset($meta_box['extra']) AND $meta_box['extra'] == 'pre-populate' AND $value != ''){
			$post_ids = explode(",", $value);
			foreach($post_ids as $post_id){
				$post_title = esc_html(get_the_title($post_id));
				echo '<input type="hidden" id="'.$post_id.'" value="'.$post_title.'" class="ninja-annc-'.$extra_type.'-attach" '.$disabled.'>';
			}
		}
		if( $type == 'checkbox' ) {
			if($value) { $checked = 'checked="checked"'; }	
			echo $before;
			echo '<input type="hidden" name="'.$field.'['.$name.']" value="0">';
			echo '<input type="checkbox" name="'.$field.'['.$name.']" id="'.$name.'" '.$checked.' value="1" class="'.$class.'" '.$disabled.'/>';
			echo $before_label;
			echo '<label for="'.$name.'">'.$title.'</label>';
			echo $after_label;
			echo $after;
		}elseif( $type == 'select' ) {
			echo $before;
			echo $before_label;
			echo $title;
			echo $after_label;
			echo '<select  id="'.$name.'" name="'.$field.'['.$name.']" class="'.$class.'" '.$disabled.'>';
				foreach($options as $option) {
					if( $value == $option['value']) { $selected = 'selected'; } else { $selected = ''; }
					echo '<option value="'.$option['value'].'" '.$selected.'>'.$option['label'].'</option>';
				}
			echo '</select>';
			echo $after;
		}elseif( $type == 'text' ) {
			echo $before;
			if($title != ''){
				echo $before_label;
				echo'<label for="'.$name.'">'.$title.': </label>';
				echo $after_label;
			}
			echo '<input type="text" id="'.$name.'" name="'.$field.'['.$name.']" class="'.$class.'" value="'.$value.'" size="'.$size.'" '.$field_style.' '.$disabled.' />';
			echo $after;
		}elseif( $type == 'radio' ) {
			if($value == $default) { $checked = 'checked="checked"'; }	
			echo $before;
			echo '<input type="radio" id="'.$name.'-'.$default.'" name="'.$field.'['.$name.']" class="'.$class.'" value="'.$default.'" size="'.$size.'" '.$field_style.' '.$checked.' '.$disabled.'/>';
			echo $before_label;
			echo'<label for="'.$name.'-'.$default.'">'.$title.'</label>';
			echo $after_label;			
			echo $after;
		}elseif($type == 'desc'){
			echo $before;
			echo $before_label;
			echo $title;
			echo $after_label;
			echo $after;
		}elseif($type == 'nonce'){
			wp_nonce_field( basename( __FILE__ ), 'ninja_annc_nonce' );
		}else{
			echo $before;
			echo $before_label;
			echo '<label for="'.$name.'">'.$title.': </label>';
			echo $after_label;
			echo '<input type="text" id="'.$name.'"  name="'.$field.'['.$name.']" class="'.$class.'" value="'.$value.'" size="30" '.$disabled.' /><br />';
			echo $after;
		}
	}
}


function ninja_annc_save_meta($post_id) {
	global $post;
	/* Verify the nonce before proceeding. */
    if ( !isset( $_POST['ninja_annc_nonce'] ) || !wp_verify_nonce( $_POST['ninja_annc_nonce'], basename( __FILE__ ) ) )
        return $post_id;
	
	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );
	
	/* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;
	
	/* Get the posted data and sanitize it for use as an HTML class. */
    $ninja_annc_meta = ( isset( $_POST['_ninja_annc_meta'] ) ? add_magic_quotes( $_POST['_ninja_annc_meta'] ) : '' );
	
	/* Get the meta value of the custom field key. */
    $ninja_annc_meta_values = get_post_meta( $post_id, '_ninja_annc_meta', true );
		
	if ( $ninja_annc_meta == "" ) :
		delete_post_meta($post_id, '_ninja_annc_meta');
	elseif( $ninja_annc_meta && $ninja_annc_meta != $ninja_annc_meta_values ) :
		update_post_meta( $post_id, '_ninja_annc_meta', $ninja_annc_meta );
	endif;
			
}

add_action('save_post', 'ninja_annc_save_meta');


add_action('add_meta_boxes', 'ninja_annc_create_meta');
function ninja_annc_create_meta($post) {
	global $post;
	$meta = get_post_meta($post->ID, '_ninja_annc_meta', true);
	$plugin_settings = get_option('ninja_annc_settings');
	$default_title_wrapper = $plugin_settings['default_title_wrapper'];
	$default_content_wrapper = $plugin_settings['default_content_wrapper'];
	if((isset($meta['closed_time']) AND $meta['closed_time'] != 'days') OR (!isset($meta['closed_time']) OR $meta['closed_time'] == '')){
		$display_days = 'display:none;';
	}else{
		$display_days = '';
	}
	if(isset($meta['show_close']) AND $meta['show_close'] != 1){
		$disable_close = "disabled";
	}else{
		$disable_close = '';
	}
	if(isset($meta['Sun']) AND $meta['Sun'] == 1 AND isset($meta['Mon']) AND $meta['Mon'] == 1 AND isset($meta['Tue']) AND $meta['Tue'] == 1 AND isset($meta['Wed']) AND $meta['Wed'] == 1 AND isset($meta['Thu']) AND $meta['Thu'] == 1 AND isset($meta['Fri']) AND $meta['Fri'] == 1 AND isset($meta['Sat']) AND $meta['Sat'] == 1){
		$select_all_days = 'checked';
	}else{
		$select_all_days = '';
	}
	if(!isset($meta['Sun']) AND !isset($meta['Mon']) AND !isset($meta['Tue']) AND !isset($meta['Wed']) AND !isset($meta['Thu']) AND !isset($meta['Fri']) AND !isset($meta['Sat'])){
		$select_all_days = 'checked';
	}
	if(isset($meta['advanced_post']) AND $meta['advanced_post'] == 1){
		$advanced_post = '';
	}else{
		$advanced_post = 'ninja-annc-hidden';
	}	
	if(isset($meta['advanced_page']) AND $meta['advanced_page'] == 1){
		$advanced_page = '';
	}else{
		$advanced_page = 'ninja-annc-hidden';
	}	
	if(isset($meta['advanced_cat']) AND $meta['advanced_cat'] == 1){
		$advanced_cat = '';
	}else{
		$advanced_cat = 'ninja-annc-hidden';
	}
	$schedule =
	array(
		'nonce' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'nonce',
			'default' => '',
			'title' => '',
			'type' => 'nonce',
			'before' => '',
			'before_label' => '',
			'after_label' => '',
			'after' => '',
			'class' => ''
		),				
		'ignore_dates' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'ignore_dates',
			'default' => 'checked',
			'title' => __('Not Scheduled (Will display whenever active)', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '<h4>',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '</h4>',
			'class' => 'ninja-annc-ignore-dates'
		),		
		'begin_date' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'begin_date',
			'default' => '',
			'title' => __('Begin Date', 'ninja-announcements'),
			'type' => 'text',
			'before' => '<table><tr>',
			'before_label' => '<td>',
			'after_label' => '</td><td>',
			'after' => '</td>', 
			'class' => 'date ninja-annc-schedule',
		),
		'begin_hr' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'begin_hr',
			'default' => '',
			'title' => __('Time ', 'ninja-announcements'),
			'type' => 'select',
			'before' => '',
			'before_label' => '<td>',
			'after_label' => '</td><td>',
			'after' => '',
			'options' => array(
				array('label' => '1', 'value' => '1'),
				array('label' => '2', 'value' => '2'),
				array('label' => '3', 'value' => '3'),
				array('label' => '4', 'value' => '4'),
				array('label' => '5', 'value' => '5'),
				array('label' => '6', 'value' => '6'),
				array('label' => '7', 'value' => '7'),
				array('label' => '8', 'value' => '8'),
				array('label' => '9', 'value' => '9'),
				array('label' => '10', 'value' => '10'),
				array('label' => '11', 'value' => '11'),
				array('label' => '12', 'value' => '12'),
			),
			'class' => 'ninja-annc-schedule'
		),
		'begin_min' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'begin_min',
			'default' => '',
			'title' => '',
			'type' => 'select',
			'before' => '',
			'before_label' => '',
			'after_label' => '',
			'after' => '',
			'options' => array(
				array('label' => '00', 'value' => '00'),
				array('label' => '05', 'value' => '05'),
				array('label' => '10', 'value' => '10'),
				array('label' => '15', 'value' => '15'),
				array('label' => '20', 'value' => '20'),
				array('label' => '25', 'value' => '25'),
				array('label' => '30', 'value' => '30'),
				array('label' => '35', 'value' => '35'),
				array('label' => '40', 'value' => '40'),
				array('label' => '45', 'value' => '45'),
				array('label' => '50', 'value' => '50'),
				array('label' => '55', 'value' => '55'),
			),
			'class' => 'ninja-annc-schedule'
		),
		'begin_ampm' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'begin_ampm',
			'default' => '',
			'title' => '',
			'type' => 'select',
			'before' => '',
			'before_label' => '',
			'after_label' => '',
			'after' => '</td></tr>',
			'options' => array(
				array('label' => 'am', 'value' => 'am'),
				array('label' => 'pm', 'value' => 'pm'),
			),
			'class' => 'ninja-annc-schedule'
		),
		'end_date' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'end_date',
			'default' => '',
			'title' =>__('End Date', 'ninja-announcements'),
			'type' => 'text',
			'before' => '<tr>',
			'before_label' => '<td>',
			'after_label' => '</td><td>',
			'after' => '</td>', 
			'class' => 'date  ninja-annc-schedule',
		),
		'end_hr' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'end_hr',
			'default' => '',
			'title' => __('Time', 'ninja-announcements'),
			'type' => 'select',
			'before' => '',
			'before_label' => '<td>',
			'after_label' => '</td><td>',
			'after' => '',
			'options' => array(
				array('label' => '1', 'value' => '1'),
				array('label' => '2', 'value' => '2'),
				array('label' => '3', 'value' => '3'),
				array('label' => '4', 'value' => '4'),
				array('label' => '5', 'value' => '5'),
				array('label' => '6', 'value' => '6'),
				array('label' => '7', 'value' => '7'),
				array('label' => '8', 'value' => '8'),
				array('label' => '9', 'value' => '9'),
				array('label' => '10', 'value' => '10'),
				array('label' => '11', 'value' => '11'),
				array('label' => '12', 'value' => '12'),
			),
			'class' => 'ninja-annc-schedule'
		),
		'end_min' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'end_min',
			'default' => '',
			'title' => '',
			'type' => 'select',
			'before' => '',
			'before_label' => '',
			'after_label' => '',
			'after' => '',
			'options' => array(
				array('label' => '00', 'value' => '00'),
				array('label' => '05', 'value' => '05'),
				array('label' => '10', 'value' => '10'),
				array('label' => '15', 'value' => '15'),
				array('label' => '20', 'value' => '20'),
				array('label' => '25', 'value' => '25'),
				array('label' => '30', 'value' => '30'),
				array('label' => '35', 'value' => '35'),
				array('label' => '40', 'value' => '40'),
				array('label' => '45', 'value' => '45'),
				array('label' => '50', 'value' => '50'),
				array('label' => '55', 'value' => '55'),
			),
			'class' => 'ninja-annc-schedule'
		),
		'end_ampm' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'end_ampm',
			'default' => '',
			'title' => '',
			'type' => 'select',
			'before' => '',
			'before_label' => '',
			'after_label' => '',
			'after' => '</td></tr></table>',
			'options' => array(
				array('label' => 'am', 'value' => 'am'),
				array('label' => 'pm', 'value' => 'pm'),
			),
			'class' => 'ninja-annc-schedule'
		),		
		'select_all_days' => array(
			'field' => '',
			'name' => 'select_all_days',
			'default' => $select_all_days,
			'title' => __('Select All', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '<h4>'.__('Days of the week', 'ninja-announcements').' &nbsp;&nbsp;',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '</h4>',
			'class' => ''
		),
		'Sun' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'Sun',
			'title' => __('Sunday', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '&nbsp;&nbsp;&nbsp;',
			'default' => 'checked',
			'class' => 'ninja-day'
		),
		'Mon' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'Mon',
			'title' => __('Monday', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '&nbsp;&nbsp;&nbsp;',
			'default' => 'checked',
			'class' => 'ninja-day '
		),		
		'Tue' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'Tue',
			'title' => __('Tuesday', 'ninja-announcements'),
			'type' => 'checkbox',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '&nbsp;&nbsp;&nbsp;',
			'default' => 'checked',
			'class' => 'ninja-day'
		),		
		'Wed' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'Wed',
			'title' => __('Wednsday', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '&nbsp;&nbsp;&nbsp;',
			'default' => 'checked',
			'class' => 'ninja-day'
		),
		'Thu' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'Thu',
			'title' => __('Thursday', 'ninja-announcements'),
			'type' => 'checkbox',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '&nbsp;&nbsp;&nbsp;',
			'default' => 'checked',
			'class' => 'ninja-day'
		),		
		'Fri' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'Fri',
			'title' => __('Friday', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '&nbsp;&nbsp;&nbsp;',
			'default' => 'checked',
			'class' => 'ninja-day'
		),		
		'Sat' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'Sat',
			'title' => __('Saturday', 'ninja-announcements'),
			'type' => 'checkbox',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '&nbsp;&nbsp;&nbsp;',
			'default' => 'checked',
			'class' => 'ninja-day'
		),
		
	);	
	$annc_options =
	array(
		'show_title' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'show_title',
			'default' => 'checked',
			'title' => __('Show Title', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '',
		),	
		'location' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'location',
			'default' => '',
			'title' => __('Location', 'ninja-announcements'),
			'type' => 'select',
			'before' => '<p>',
			'before_label' => '',
			'after_label' => '&nbsp;',
			'after' => '</p>',
			'options' => array(
				array('label' => __('Header (Default)', 'ninja-announcements'), 'value' => 'default'), 
				array('label' => __('Sidebar (Widget)', 'ninja-announcements'), 'value' => 'widget'),
				array('label' => __('Manual (Function)', 'ninja-announcements'), 'value' => 'function'),
			),
		)
	);
	if(NINJA_ANNC_TYPE == 'Pro'){
		require_once(NINJA_ANNC_DIR."/includes/pro/meta-boxes-1.php");
	}
	$annc_options['show_close'] = array(
			'field' => '_ninja_annc_meta',
			'name' => 'show_close',
			'default' => 'checked',
			'title' => __('Allow the user to close the announcement', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '<p>',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '</p>',
		);
	$annc_options['show_open'] = array(
			'field' => '_ninja_annc_meta',
			'name' => 'show_open',
			'default' => 'checked',
			'title' => __('Allow the announcement to be re-opened', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '<p>',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '</p>',
			'class' => 'ninja-annc-close-setting',
			'disabled' => $disable_close,		
		);
		
	if(NINJA_ANNC_TYPE == 'Pro'){
		require_once(NINJA_ANNC_DIR."/includes/pro/meta-boxes-2.php");
	}
	
	$display_options =
	array(
		'display_main' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'display_main',
			'default' => 'checked',
			'title' => __('Display on your Homepage', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '<p>',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '</p>',
			'class' => ''
		),
		'display_front' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'display_front',
			'default' => 'checked',
			'title' => __('Display on your Frontpage', 'ninja-announcements'),
			'type' => 'checkbox',
			'before' => '<p>',
			'before_label' => '&nbsp;',
			'after_label' => '',
			'after' => '</p>',
			'class' => ''
		),					
	);			

	$no_options =
	array(
		'no_options' => array(
			'field' => '_ninja_annc_meta',
			'name' => 'no_options',
			'default' => '',
			'title' => __('This announcement\'s settings are being controlled by one of its groups. Please remove this announcement from the controlling group, or visit the group edit page to change its settings.', 'ninja-announcements'),
			'type' => 'desc',
			'before' => '<h4>',
			'before_label' => '',
			'after_label' => '',
			'after' => '</h4>',
		),			
	);	
	
	if(NINJA_ANNC_TYPE == 'Pro'){
		if(ninja_annc_group_override_check($post->ID)){
			add_meta_box( 'ninja_annc_options', __('Announcement Options', 'ninja-announcements'), 'ninja_annc_display_meta', 'ninja_annc', 'normal', 'core', $no_options );
		}else{
			if ( function_exists('add_meta_box') ) {
				add_meta_box( 'ninja_annc_schedule', __('Announcement Schedule', 'ninja-announcements'), 'ninja_annc_display_meta', 'ninja_annc', 'normal', 'core', $schedule );
				add_meta_box( 'ninja_annc_options', __('Announcement Options', 'ninja-announcements'), 'ninja_annc_display_meta', 'ninja_annc', 'normal', 'core', $annc_options );			
				add_meta_box( 'ninja_annc_display', __('Main Page Display Options', 'ninja-announcements'), 'ninja_annc_display_meta', 'ninja_annc', 'normal', 'core', $display_options );			
				require_once(NINJA_ANNC_DIR."/includes/pro/meta-boxes-3.php");
				
			}
		}
	}else{
		if ( function_exists('add_meta_box') ) {
			add_meta_box( 'ninja_annc_schedule', __('Announcement Schedule', 'ninja-announcements'), 'ninja_annc_display_meta', 'ninja_annc', 'normal', 'core', $schedule );
			add_meta_box( 'ninja_annc_options', __('Announcement Options', 'ninja-announcements'), 'ninja_annc_display_meta', 'ninja_annc', 'normal', 'core', $annc_options );			
			add_meta_box( 'ninja_annc_display', __('Main Page Display Options', 'ninja-announcements'), 'ninja_annc_display_meta', 'ninja_annc', 'normal', 'core', $display_options );			
		}
	}
}