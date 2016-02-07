<?php
/**
 * Ninja Announcement Widget Class
 */
class Ninja_Annc_Widget extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::WP_Widget( /* Base ID */'ninja_annc_widget', /* Name */'Ninja Announcements', array( 'description' => 'Ninja Announcement Widget Area' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		//ninja_annc_update_all();
		$annc_id = $instance['annc_id'];
		$meta = get_post_meta($annc_id, '_ninja_annc_meta', true);
		$show_title = $meta['show_title'];
		$location = $meta['location'];
		$annc_post = get_post($annc_id, ARRAY_A);
		$post_status = $annc_post['post_status'];
		if( $location == 'widget' && $post_status == 'publish' ){
			echo $before_widget;
			echo ninja_annc_check($annc_id, true, false, $before_title, $after_title);
			echo $after_widget;
		}
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['annc_id'] = $new_instance['annc_id'];		
		$annc_id = $new_instance['annc_id'];
		$annc_post = get_post($annc_id, ARRAY_A);
		$meta = get_post_meta($annc_id, '_ninja_annc_meta', true);
		$meta['ignore_dates'] = $new_instance['ignore_dates'];
		
		$meta['begin_date'] = $new_instance['begin_date'];
		$meta['begin_hr'] = $new_instance['begin_hr'];
		$meta['begin_min'] = $new_instance['begin_min'];
		$meta['begin_ampm'] = $new_instance['begin_ampm'];		
		
		$meta['end_date'] = $new_instance['end_date'];
		$meta['end_hr'] = $new_instance['end_hr'];
		$meta['end_min'] = $new_instance['end_min'];
		$meta['end_ampm'] = $new_instance['end_ampm'];
		
		$instance['title'] = $annc_post['post_title'];
		$active = $new_instance['active'];
		$my_post = array();
		if($active == 'checked'){
			$my_post['post_status'] = 'publish';
		}else{
			$my_post['post_status'] = 'draft';
		}
		$my_post['ID'] = $annc_id;
		wp_update_post( $my_post );
		update_post_meta($annc_id, '_ninja_annc_meta', $meta);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		$args = array( 'post_type' => 'ninja_annc', 'numberposts' => -1, 'post_status' => array('draft', 'publish')); 
		$announcements = get_posts($args);
		$annc = array();
		$x = 0;
		foreach($announcements as $post){
			$meta = get_post_meta($post->ID, '_ninja_annc_meta', true);
			$location = $meta['location'];
			if($location == 'widget' AND !ninja_annc_group_override_check($post->ID)){
				$annc[$x]['title'] = $post->post_title;
				$annc[$x]['id'] = $post->ID;
				$x++;				
			}
		}
		if($instance){
			$title = esc_attr( $instance[ 'title' ] );
			$annc_id = $instance['annc_id'];
			$annc_post = get_post($annc_id, ARRAY_A);
			$title = $annc_post['post_title'];
			$post_status = $annc_post['post_status'];
			$meta = get_post_meta($annc_id, '_ninja_annc_meta', true);
			$ignore_dates = $meta['ignore_dates'];
			if($ignore_dates == 1){
				$disabled = 'disabled';
			}else{
				$disabled = '';
			}
			if(isset($meta['begin_date'])){
				$begin_date = $meta['begin_date'];
			}else{
				$begin_date = '';
			}
			if(isset($meta['begin_hr'])){
				$begin_hr = $meta['begin_hr'];
			}else{
				$begin_hr = '';
			}
			if(isset($meta['begin_min'])){
				$begin_min = $meta['begin_min'];
			}else{
				$begin_min = '';
			}
			if(isset($meta['begin_ampm'])){
				$begin_ampm = $meta['begin_ampm'];			
			}else{
				$begin_ampm = '';
			}
			if(isset($meta['end_date'])){
				$end_date = $meta['end_date'];
			}else{
				$end_date = '';
			}
			if(isset($meta['end_hr'])){
				$end_hr = $meta['end_hr'];
			}else{
				$end_hr = '';
			}
			if(isset($meta['end_min'])){
				$end_min = $meta['end_min'];
			}else{
				$end_min = '';
			}
			if(isset($meta['end_ampm'])){
				$end_ampm = $meta['end_ampm'];
			}else{
				$end_ampm = '';
			}
		}else{
			$post_status = '';
			$ignore_dates = '';
			$disabled = '';
			$begin_date = '';
			$begin_hr = '';
			$begin_min = '';
			$begin_ampm = '';
			$end_date = '';
			$end_hr = '';
			$end_min = '';
			$end_ampm = '';
			$title = __( 'New title', 'ninja-announcements');
			$annc_id = "NEW";
		}
		?>
		<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="hidden" value="<?php echo $title; ?>" />
		<p>
		<label for=""><?php _e('Announcement:', 'ninja-announcements');?></label>
		<select id="<?php echo $this->get_field_id('annc_id'); ?>" name="<?php echo $this->get_field_name('annc_id'); ?>" class="ninja-annc-widget-select">
		<option value="" selected>--<?php _e('Select an announcement', 'ninja-announcements');?></option>
		<?php
		
		foreach($annc as $a){
			if($a['title'] != ''){
		?>
		<option value="<?php echo $a['id'];?>" <?php if($annc_id == $a['id']){ echo 'selected';}?>><?php echo $a['title'];?></option>
		<?php
			}
		}
		?>
		</select>
		<div id="<?php echo $this->get_field_id('ninja_annc_loading'); ?>" style="display:none;"><img src="<?php echo NINJA_ANNC_URL;?>/images/loading.gif"></div>
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('active'); ?>" name="<?php echo $this->get_field_name('active'); ?>" class="" value="checked" <?php if($post_status == 'publish'){ echo 'checked';}?>> <label for="<?php echo $this->get_field_id('active'); ?>"><?php _e('Active', 'ninja-announcements');?></a>
		</p>	
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('ignore_dates'); ?>" name="<?php echo $this->get_field_name('ignore_dates'); ?>" class="ninja-annc-ignore-dates" value="1" <?php if($ignore_dates ==1){ echo 'checked';}?>> <label for="<?php echo $this->get_field_id('ignore_dates'); ?>"><?php _e('Not Scheduled (Will show whenever active)', 'ninja-announcements');?></a>
		</p>
		<p>
		<label for=""><?php _e('Begin Date:', 'ninja-announcements');?></label>
		<input type="text" id="<?php echo $this->get_field_id('begin_date'); ?>" name="<?php echo $this->get_field_name('begin_date'); ?>" class="date <?php echo $this->get_field_id('ninja-annc-schedule'); ?>" value="<?php echo $begin_date;?>" <?php echo $disabled;?>>
		</p>		
		<p>
		<label for=""><?php _e('Begin Time:', 'ninja-announcements');?></label>
		<select id="<?php echo $this->get_field_id('begin_hr'); ?>" name="<?php echo $this->get_field_name('begin_hr'); ?>" class="<?php echo $this->get_field_id('ninja-annc-schedule'); ?>" <?php echo $disabled;?>>
			<option value="1" <?php if($begin_hr == 1){ echo 'selected';}?>>1</option>
			<option value="2" <?php if($begin_hr == 2){ echo 'selected';}?>>2</option>
			<option value="3" <?php if($begin_hr == 3){ echo 'selected';}?>>3</option>
			<option value="4" <?php if($begin_hr == 4){ echo 'selected';}?>>4</option>
			<option value="5" <?php if($begin_hr == 5){ echo 'selected';}?>>5</option>
			<option value="6" <?php if($begin_hr == 6){ echo 'selected';}?>>6</option>
			<option value="7" <?php if($begin_hr == 7){ echo 'selected';}?>>7</option>
			<option value="8" <?php if($begin_hr == 8){ echo 'selected';}?>>8</option>
			<option value="9" <?php if($begin_hr == 9){ echo 'selected';}?>>9</option>
			<option value="10" <?php if($begin_hr == 10){ echo 'selected';}?>>10</option>
			<option value="11" <?php if($begin_hr == 11){ echo 'selected';}?>>11</option>
			<option value="12" <?php if($begin_hr == 12){ echo 'selected';}?>>12</option>
		</select>
		<select id="<?php echo $this->get_field_id('begin_min'); ?>" name="<?php echo $this->get_field_name('begin_min'); ?>" class="<?php echo $this->get_field_id('ninja-annc-schedule'); ?>" <?php echo $disabled;?>>
			<option value="00" <?php if($begin_min == '00'){ echo 'selected';}?>>00</option>
			<option value="05" <?php if($begin_min == '05'){ echo 'selected';}?>>05</option>
			<option value="10" <?php if($begin_min == '10'){ echo 'selected';}?>>10</option>
			<option value="15" <?php if($begin_min == '15'){ echo 'selected';}?>>15</option>
			<option value="20" <?php if($begin_min == '20'){ echo 'selected';}?>>20</option>
			<option value="25" <?php if($begin_min == '25'){ echo 'selected';}?>>25</option>
			<option value="30" <?php if($begin_min == '30'){ echo 'selected';}?>>30</option>
			<option value="35" <?php if($begin_min == '35'){ echo 'selected';}?>>35</option>
			<option value="40" <?php if($begin_min == '40'){ echo 'selected';}?>>40</option>
			<option value="45" <?php if($begin_min == '45'){ echo 'selected';}?>>45</option>
			<option value="50" <?php if($begin_min == '50'){ echo 'selected';}?>>50</option>
			<option value="55" <?php if($begin_min == '55'){ echo 'selected';}?>>55</option>
		</select>
		<select id="<?php echo $this->get_field_id('begin_ampm'); ?>" name="<?php echo $this->get_field_name('begin_ampm'); ?>" class="<?php echo $this->get_field_id('ninja-annc-schedule'); ?>" <?php echo $disabled;?>>
			<option value="am" <?php if($begin_ampm == 'am'){ echo 'selected';}?>>am</option>
			<option value="pm" <?php if($begin_ampm == 'pm'){ echo 'selected';}?>>pm</option>
		</select>
		</p>		
		<p>
		<label for=""><?php _e('End Date:', 'ninja-announcements');?></label>
		<input type="text" id="<?php echo $this->get_field_id('end_date'); ?>" name="<?php echo $this->get_field_name('end_date'); ?>" class="date <?php echo $this->get_field_id('ninja-annc-schedule'); ?>" value="<?php echo $end_date;?>" <?php echo $disabled;?>>
		</p>		
		<p>
		<label for=""><?php _e('End Time:', 'ninja-announcements');?></label>
		<select id="<?php echo $this->get_field_id('end_hr'); ?>" name="<?php echo $this->get_field_name('end_hr'); ?>" class="<?php echo $this->get_field_id('ninja-annc-schedule'); ?>" <?php echo $disabled;?>>
			<option value="1" <?php if($end_hr == 1){ echo 'selected';}?>>1</option>
			<option value="2" <?php if($end_hr == 2){ echo 'selected';}?>>2</option>
			<option value="3" <?php if($end_hr == 3){ echo 'selected';}?>>3</option>
			<option value="4" <?php if($end_hr == 4){ echo 'selected';}?>>4</option>
			<option value="5" <?php if($end_hr == 5){ echo 'selected';}?>>5</option>
			<option value="6" <?php if($end_hr == 6){ echo 'selected';}?>>6</option>
			<option value="7" <?php if($end_hr == 7){ echo 'selected';}?>>7</option>
			<option value="8" <?php if($end_hr == 8){ echo 'selected';}?>>8</option>
			<option value="9" <?php if($end_hr == 9){ echo 'selected';}?>>9</option>
			<option value="10" <?php if($end_hr == 10){ echo 'selected';}?>>10</option>
			<option value="11" <?php if($end_hr == 11){ echo 'selected';}?>>11</option>
			<option value="12" <?php if($end_hr == 12){ echo 'selected';}?>>12</option>
		</select>
		<select id="<?php echo $this->get_field_id('end_min'); ?>" name="<?php echo $this->get_field_name('end_min'); ?>" class="<?php echo $this->get_field_id('ninja-annc-schedule'); ?>" <?php echo $disabled;?>>
			<option value="00" <?php if($end_min == '00'){ echo 'selected';}?>>00</option>
			<option value="05" <?php if($end_min == '05'){ echo 'selected';}?>>05</option>
			<option value="10" <?php if($end_min == '10'){ echo 'selected';}?>>10</option>
			<option value="15" <?php if($end_min == '15'){ echo 'selected';}?>>15</option>
			<option value="20" <?php if($end_min == '20'){ echo 'selected';}?>>20</option>
			<option value="25" <?php if($end_min == '25'){ echo 'selected';}?>>25</option>
			<option value="30" <?php if($end_min == '30'){ echo 'selected';}?>>30</option>
			<option value="35" <?php if($end_min == '35'){ echo 'selected';}?>>35</option>
			<option value="40" <?php if($end_min == '40'){ echo 'selected';}?>>40</option>
			<option value="45" <?php if($end_min == '45'){ echo 'selected';}?>>45</option>
			<option value="50" <?php if($end_min == '50'){ echo 'selected';}?>>50</option>
			<option value="55" <?php if($end_min == '55'){ echo 'selected';}?>>55</option>
		</select>
		<select id="<?php echo $this->get_field_id('end_ampm'); ?>" name="<?php echo $this->get_field_name('end_ampm'); ?>" class="<?php echo $this->get_field_id('ninja-annc-schedule'); ?>" <?php echo $disabled;?>>
			<option value="am" <?php if($end_ampm == 'am'){ echo 'selected';}?>>am</option>
			<option value="pm" <?php if($end_ampm == 'pm'){ echo 'selected';}?>>pm</option>
		</select>
		</p>	

		<?php
	}

} // class Ninja_Annc_Widget

// register Ninja_Annc_Widget
add_action( 'widgets_init', create_function( '', 'register_widget("Ninja_Annc_Widget");' ) );