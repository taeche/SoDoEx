<?php
add_action('admin_head', 'ninja_annc_edit_js');

function ninja_annc_edit_js(){
	global $post;
	$wp_version = get_bloginfo('version');
	if(isset($_REQUEST['taxonomy'])){
		$taxonomy = $_REQUEST['taxonomy'];
	}else{
		$taxonomy = '';
	}
	if((isset($post) AND $post->post_type == 'ninja_annc') OR $taxonomy == 'ninja_annc_groups'){
		wp_enqueue_script('jquery-tokeninput-js',
			NINJA_ANNC_URL .'/js/min/jquery.tokeninput.js',
			array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog'), '', false);
			
		if(version_compare( $wp_version, '3.2-Beta1' , '>')){	
			//wp_enqueue_script('ninja_annc_admin_js',
			//NINJA_ANNC_URL .'/js/dev/ninja_annc_admin.js',
			//array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
				
			wp_enqueue_script('ninja_annc_admin_js',
			NINJA_ANNC_URL .'/js/min/ninja_annc_admin.min.js',
			array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
		}else{
			//wp_enqueue_script('ninja_annc_admin_js',
					//NINJA_ANNC_URL .'/js/dev/ninja_annc_admin_3.1.js',
					//array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
					
			wp_enqueue_script('ninja_annc_admin_js',
			NINJA_ANNC_URL .'/js/min/ninja_annc_admin_3.1.min.js',
			array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
		}
		
		if(version_compare( $wp_version, '3.3-beta3-19254' , '<')){
			wp_enqueue_script( 'jquery-ui-datepicker', 
			NINJA_FORMS_URL .'/js/min/jquery.ui.datepicker.min.js',
			array('jquery', 'jquery-ui-core'));	
		}
			
		if(isset($post)){
			wp_localize_script( 'ninja_annc_admin_js', 'settings', array( 'plugin_url' => NINJA_ANNC_URL,  'post_status' => $post->post_status) );
		}
		$activate = __('Activate', 'ninja-announcements');
		$deactivate = __('Deactivate', 'ninja-announcements');
		$save = __('Save', 'ninja-announcements');
		
		wp_localize_script( 'ninja_annc_admin_js', 'ninja_annc_strings', array( 'activate' => $activate,  'deactivate' => $deactivate, 'save' => $save) );
		wp_enqueue_style( 'jquery-smoothness', NINJA_ANNC_URL .'/css/smoothness/jquery-smoothness.css');
		wp_enqueue_style( 'token-input', NINJA_ANNC_URL .'/css/token-input.css');		
		wp_enqueue_style( 'token-input-facebook-css', NINJA_ANNC_URL .'/css/token-input-facebook.css');
		wp_enqueue_style( 'ninja-annc-admin', NINJA_ANNC_URL .'/css/ninja-annc-admin.css');
		if(!isset($_REQUEST['taxonomy'])){
			add_filter( 'gettext', 'change_publish_button', 10, 2 );
		}
	}
	if(isset($_REQUEST['page']) AND $_REQUEST['page'] == 'plugin_settings' AND isset($_REQUEST['post_type']) AND $_REQUEST['post_type'] == 'ninja_annc'){
		wp_enqueue_style( 'ninja-annc-admin', NINJA_ANNC_URL .'/css/ninja-annc-admin.css');
	}
}

add_action('load-widgets.php', 'ninja_annc_widget_js');

function ninja_annc_widget_js(){
	$wp_version = get_bloginfo('version');

	if(version_compare( $wp_version, '3.2-Beta1' , '>')){	
		//wp_enqueue_script('ninja_annc_admin_js',
			//NINJA_ANNC_URL .'/js/dev/ninja_annc_admin.js',
			//array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
			
		wp_enqueue_script('ninja_annc_admin_js',
		NINJA_ANNC_URL .'/js/min/ninja_annc_admin.min.js',
		array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
	}else{
		//wp_enqueue_script('ninja_annc_admin_js',
				//NINJA_ANNC_URL .'/js/dev/ninja_annc_admin_3.1.js',
				//array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
				
		wp_enqueue_script('ninja_annc_admin_js',
		NINJA_ANNC_URL .'/js/min/ninja_annc_admin_3.1.min.js',
		array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
	}
	
	if(version_compare( $wp_version, '3.3-beta3-19254' , '<')){
		wp_enqueue_script( 'jquery-ui-datepicker', 
		NINJA_FORMS_URL .'/js/min/jquery.ui.datepicker.min.js',
		array('jquery', 'jquery-ui-core'));	
	}
	
	$activate = __('Activate', 'ninja-announcements');
	$deactivate = __('Deactivate', 'ninja-announcements');
	$save = __('Save', 'ninja-announcements');
		
	wp_localize_script( 'ninja_annc_admin_js', 'ninja_annc_strings', array( 'activate' => $activate,  'deactivate' => $deactivate, 'save' => $save) );
	wp_localize_script( 'ninja_annc_admin_js', 'settings', array( 'plugin_url' => NINJA_ANNC_URL) );
	wp_enqueue_style( 'jquery-smoothness-css', NINJA_ANNC_URL .'/css/smoothness/jquery-smoothness.css');
}

add_action('load-edit-tags.php', 'ninja_annc_tax_js');

function ninja_annc_tax_js(){
	$wp_version = get_bloginfo('version');
	if($_REQUEST['taxonomy'] == 'ninja_annc_groups'){
		wp_enqueue_script('jquery-tokeninput-js',
			NINJA_ANNC_URL .'/js/min/jquery.tokeninput.js',
			array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog'), '', false);
					
		if(version_compare( $wp_version, '3.2-Beta1' , '>')){	
			//wp_enqueue_script('ninja_annc_admin_js',
				//NINJA_ANNC_URL .'/js/dev/ninja_annc_admin.js',
				//array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
				
				wp_enqueue_script('ninja_annc_admin_js',
				NINJA_ANNC_URL .'/js/min/ninja_annc_admin.min.js',
				array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
		}else{
			//wp_enqueue_script('ninja_annc_admin_js',
					//NINJA_ANNC_URL .'/js/dev/ninja_annc_admin_3.1.js',
					//array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
					
					wp_enqueue_script('ninja_annc_admin_js',
					NINJA_ANNC_URL .'/js/min/ninja_annc_admin_3.1.min.js',
					array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);	
		}
			
		if(version_compare( $wp_version, '3.3-beta3-19254' , '<')){
			wp_enqueue_script( 'jquery-ui-datepicker', 
			NINJA_FORMS_URL .'/js/min/jquery.ui.datepicker.min.js',
			array('jquery', 'jquery-ui-core'));	
		}	
		
		$open = __('Open', 'ninja-announcements');
		$close = __('Close', 'ninja-announcements');
		
		wp_localize_script( 'ninja_annc_admin_js', 'settings', array( 'plugin_url' => NINJA_ANNC_URL, 'ninja_annc_open' => $open, 'ninja_annc_close' => $close ) );
		wp_enqueue_style( 'jquery-smoothness-css', NINJA_ANNC_URL .'/css/smoothness/jquery-smoothness.css');
		wp_enqueue_style( 'token-input', NINJA_ANNC_URL .'/css/token-input.css');		
	}
}

function ninja_annc_display_js(){
	if(!is_admin()){
		$wp_version = get_bloginfo('version');
		$plugin_settings = get_option("ninja_annc_settings");
		$default_style = $plugin_settings['default_style'];

		//wp_enqueue_script('ninja_annc_display_js',
		//NINJA_ANNC_URL .'/js/dev/ninja_annc_display.js',
		//array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);					
			
		wp_enqueue_script('ninja_annc_display_js',
		NINJA_ANNC_URL .'/js/min/ninja_annc_display.min.js',
		array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-datepicker'), '', false);			

		$open = __('Open', 'ninja-announcements');
		$close = __('Close', 'ninja-announcements');
		
		wp_localize_script( 'ninja_annc_display_js', 'ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'ninja_annc_open' => $open, 'ninja_annc_close' => $close ) );			
		if($default_style == 'checked'){
			wp_enqueue_style( 'ninja-annc-display', NINJA_ANNC_URL .'/css/ninja-annc-display.css');
		}
		/*//bootstrap
		wp_enqueue_style( 'bootstrap', NINJA_ANNC_URL .'/css/bootstrap.min.css');
		wp_enqueue_style( 'bootstrap', NINJA_ANNC_URL .'/css/bootstrap-theme.min.css');
		wp_enqueue_script('bootstrap_js',
			NINJA_ANNC_URL .'/js/min/bootstrap.min.js',
			array(), '', false);*/
	}
}