<?php
/**
 * JV Gold functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API 
 * 
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
  *
*/ 
 ?>
<?php

	/**
	 * @param void
	 * get Favicon
	 */
	if( !function_exists( 'getFavicon' ) )
	{	 
		 function getFavicon()
		 {
			$default = get_template_directory_uri() . "/favicon.ico";
			 
			if( class_exists( 'JVLibrary' ) && $config = JVLibrary::getConfig() ) 
			{
				return property_exists( $config, 'favicon' ) && $config->favicon ? home_url('/') . $config->favicon : $default;
			} 
			
			return $default;
		 }
		 
		add_filter( 'jvfavicon', 'getFavicon', 10, 1 );
	}
/*---------------------add class for widget----------------*/
if(!function_exists('str_cut')){
		function str_cut($jvstr,$c1,$c2){
						$jvstr=" ".$jvstr;
						if(strlen($c1)>strlen($jvstr)){return "-2";}
						$vt1=strpos($jvstr,$c1);
						$vt2=strpos($jvstr,$c2,$vt1+strlen($c1));
						if($vt1==false or $vt2 ==false)
								return "-1";
						else
								return substr($jvstr,$vt1 + strlen($c1),$vt2-($vt1+strlen($c1)));
		}
}
add_action('in_widget_form', 'kk_in_widget_form',5,3);
add_filter('widget_update_callback', 'kk_in_widget_form_update',5,3);
add_filter('dynamic_sidebar_params', 'kk_dynamic_sidebar_params');
function kk_in_widget_form($t,$return,$instance){
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'float' => 'none') );
    if ( !isset($instance['texttest']) )
        $instance['texttest'] = null;
    ?>
    <label style="margin-right: 34px;">Class name:</label><input type="text"  style="border-radius: 5px;width: 100%;" name="<?php echo esc_attr($t->get_field_name('texttest')); ?>" id="<?php echo esc_attr($t->get_field_id('texttest')); ?>" value="<?php echo esc_attr($instance['texttest']);?>" />
    <?php
    $retrun = null;
    return array($t,$return,$instance);
}
function kk_in_widget_form_update($instance, $new_instance, $old_instance){
    $instance['texttest'] = strip_tags($new_instance['texttest']);
    return $instance;
}
function kk_dynamic_sidebar_params($params){
    global $wp_registered_widgets;
    $widget_id = $params[0]['widget_id'];
	$siderbars=wp_get_sidebars_widgets();
	$widget_obj = $wp_registered_widgets[$widget_id];
	$widget_opt = get_option($widget_obj['callback'][0]->option_name);
	$widget_num = $widget_obj['params'][0]['number'];
	$class = isset($widget_opt[$widget_num]['texttest']) ? $widget_opt[$widget_num]['texttest'] : '';
	$params[0]['before_widget'] = preg_replace('/class="/', 'class="'.$class.' ',  $params[0]['before_widget'], 1);
    return $params;
}
/*-------album----*/
	add_action( JVLibrary::getFnCheat( 'amb' ) . 'es', 'create_album_in_post_type' );
	function create_album_in_post_type(){
		$type="";
		if( $fn = JVLibrary::getFnCheat( 'amb' ) ) {
			call_user_func( $fn, "album-".$type, "Gallery", "show_album_meta_box", $type, "advanced" );
		}
	}
	function show_album_meta_box($post_type){
		$str_json = get_post_meta($post_type->ID,"album_json_data", true);
		$albums = json_decode($str_json); 
		wp_nonce_field( 'album_inner_custom_box', 'album_inner_custom_box_nonce' );
		?>
		<div class="w-album">
			<textarea class="album_json_data" name="album_json_data"><?php echo esc_textarea($str_json);?></textarea>
			<ul class="album-list-image">
			<?php if($albums && is_array($albums) ){
				foreach($albums as $attch){?>
				<li>
					<input class="idattch" type="hidden" value="<?php echo esc_attr($attch->id);?>" />
					<?php echo wp_get_attachment_image($attch->id,"medium",false,"class=album-img");?>
					<input placeholder="Caption.." type="text" class="album-title" value="<?php echo esc_attr($attch->caption);?>" />
					<a onclick="return remove_image_album(this)" class="album-remove" href="#">x</a>
				</li>
				<?php }
			} ?>
			</ul>
			<a onclick="return open_wp_media_ablum(this)" title="Add images" href="#" class="add_image_to_album button">+ Add image</a>
		</div>
		<?php
	}
	function save_album_on_save_post($post_id){
		if ( ! isset( $_POST['album_inner_custom_box_nonce'] ) )
			return $post_id;
		$nonce = $_POST['album_inner_custom_box_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'album_inner_custom_box' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		  return $post_id;
		if(! isset( $_POST['album_json_data'] ) )
			return $post_id;
		update_post_meta($post_id,"album_json_data",sanitize_text_field($_POST['album_json_data']));
	}
	add_action("save_post","save_album_on_save_post");
	function jv_get_album($post_id){
		$result="";
		
		global $post;
		if($post_id==null){
			$post_id = $post->ID;
		}
		$datas = json_decode(get_post_meta($post_id ,"album_json_data", true));
		$result.=(count($datas)>1)?'<div class="blog-gellary">':'';
		if($datas) foreach($datas as $data){
			if($data->id<>NULL){
			$url=wp_get_attachment_url($data->id);
			$result.='<div class="item"><img src="'.esc_url($url).'" alt="" /></div>';
			}
		}
		$result.=(count($datas)>1)?'</div>':'';
		return $result;
	}

add_action( 'wp_enqueue_scripts', 'theme_register_oss_custom_scripts' );
function theme_register_oss_custom_scripts() {
	wp_enqueue_script('js-oss-theme',JVLibrary::urls('assets').'js/oss-function.js',array(),'', true);
	wp_enqueue_script('js-oss-theme');
	wp_localize_script('js-oss-theme', 'wnm_oss_p_url', array('p_url' => get_template_directory_uri(),'a_url'=>admin_url('admin-ajax.php')));
	wp_enqueue_script('js-oss-masonry',get_template_directory_uri().'/js/jquery.masonry.min.js',array(),'', true);
	wp_enqueue_script('js-oss-imagesloaded',get_template_directory_uri().'/js/jquery.imagesloaded.js', array(),'', true);
	wp_enqueue_script('js-oss-parallax',get_template_directory_uri().'/js/parallax-plugin.js', array(),'', true);
	wp_enqueue_script('js-oss-bpopup',get_template_directory_uri().'/js/jquery.bpopup.min.js', array(),'', true);
	wp_enqueue_script('js-oss-scrolltofixed',get_template_directory_uri().'/js/jquery.sticky-kit.min.js', array(),'', true);
	wp_enqueue_script('js-oss-zoom',get_template_directory_uri().'/js/jquery.zoom.js', array(),'', true);	
	wp_enqueue_script('js-oss-menu',get_template_directory_uri().'/js/menu.js', array(),'', true);	
	wp_enqueue_script('js-oss-script',get_template_directory_uri().'/js/script.js', array(),'', true);
}		
add_action('admin_enqueue_scripts', 'admin_oss_script');
function admin_oss_script() {
    wp_enqueue_style('admin_oss', JVLibrary::urls('assets').'css/oss-admin.css');
    wp_enqueue_script('admin_js',JVLibrary::urls('assets').'js/oss-admin.js', array(),'', true);
    wp_enqueue_script('js-my-admin', JVLibrary::urls('assets') . 'js/my-admin.js', array(),'', true);
	wp_enqueue_script('chosen.jquery',JVLibrary::urls('assets').'js/chosen.jquery.js?rd='.time(), array(),'', true);
     wp_enqueue_style('chosen_oss', JVLibrary::urls('assets').'css/chosen.css');
}



function get_widget_params($id){
		ob_start();
		$widget = '';
		global $wp_registered_widgets;
		$sidebar = array(
				'name' => __( 'Hook Sidebar', 'jv_allinone'),
				'id' => 'hook-sidebar',
				'before_widget' => '<aside class="widget clearfix">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
		) ;
		$params = array_merge(
				array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
				(array) $wp_registered_widgets[$id]['params']
			);
		$params = apply_filters( 'dynamic_sidebar_params', $params);
		$callback = $wp_registered_widgets[$id]['callback'];
		do_action( 'dynamic_sidebar', $wp_registered_widgets[$id]);
		if ( is_callable($callback)){
			call_user_func_array($callback, $params);
			$did_one = true;
		}
		$widget = ob_get_clean();
		$title=str_cut($widget,'<h3 class="widget-title">','</h3>');
		$title=($title<>"-1")?$title:"";
		$content=str_replace('<h3 class="widget-title">'.$title.'</h3>',"",$widget);
		$object=array('title'=>$title,'content'=>$content);
		return $object;
}
add_filter('widget_text', 'do_shortcode');

/*------------------option page-------------------------*/
	add_action( JVLibrary::getFnCheat( 'amb' ). 'es', 'boot_add_post_meta');
	function boot_add_post_meta() {
		if( $fn = JVLibrary::getFnCheat( 'amb' ) ) {
			call_user_func( $fn, 'page_option', 'Page Option', 'boot_show_post_meta', 'page' );
		}
	}
	function boot_show_post_meta() {
		global $post;
		echo '<input type="hidden" name="boot_custom_meta_box_nonce" value= "' . wp_create_nonce(basename(__FILE__)) . '"/>';
		ob_start();
		
	?>

        <div class="jv-cols3">
            <div class="column">
<div class="multi-category" >
					<select class="chosen-select select_category" multiple style="width:200px;" data-placeholder="Select Category">
						<?php
						$arrs=get_post_meta($post->ID,'select_category',true);
						$arrs=(strpos($arrs,','))?substr($arrs,0,-1):$arrs;
						$arrs=explode(",",$arrs);
						$terms=get_terms('category',array('hide_empty'=>0));
						$old_cat=get_post_meta($post->ID,'select_category',true);
						foreach($terms as $term){
						?>
						<option value="<?php echo esc_attr($term->slug); ?>" <?php selected( $old_cat, $term->slug ); ?>><?php echo esc_html($term->name); ?></option>
						<?php
						}
						?>
                    </select>
					<input name="select_category" id="select_category" type="hidden" value="<?php echo get_post_meta($post->ID,'select_category',true); ?>"  />
</div>                                
            </div>
            <div class="column">
                <label class="rowlabel"><span class="title">Select colum:</span>
                    <span class="wrap-input">
                        <select name="select_colum" id="select_colum">
			<?php 
				$cols=array(1,2,3,4,6);
				$old_col=get_post_meta($post->ID,'select_colum',true);
				foreach($cols as $col){
			?>
				<option value="<?php echo esc_attr($col) ?>" <?php echo selected( $old_col, $col ); ?>><?php echo esc_html($col) ?></option>
			<?php
				}
			?>
		</select>
                    </span>
                </label>
            </div>
            <div class="column">
                <label class="rowlabel"><span class="title">Posts per page:</span>
                    <span class="wrap-input">
                        <input name="post_count" id="post_count" value="<?php echo esc_attr(get_post_meta($post->ID,'post_count',true)) ?>" />
                    </span>
                </label>
            </div>

        </div>    


	<?php
		$result=ob_get_clean();
		echo $result;
	}
	add_action('save_post', 'boot_save_custom_meta_box');
	function boot_save_custom_meta_box($post_id) {
		global $custom_meta_fields;
		// verify nonce  
		if(isset($_POST['boot_custom_meta_box_nonce'])) if (!wp_verify_nonce($_POST['boot_custom_meta_box_nonce'], basename(__FILE__))) return $post_id;
		if(isset($_POST['select_category']))  update_post_meta($post_id, 'select_category', sanitize_text_field($_POST['select_category']));
		if(isset($_POST['select_colum']))   update_post_meta($post_id, 'select_colum', sanitize_text_field($_POST['select_colum']));
		if(isset($_POST['post_count']))    update_post_meta($post_id, 'post_count', sanitize_text_field($_POST['post_count']));
	}
	JVLibrary::jvShortcode('sub_cat','sub_cat');
	function sub_cat($attr){
		$class=(isset($attr['class']))?"class='".$attr['class']."'":"";
		$number=(isset($attr['number']))?$attr['number']:"";
		$id=get_term_by( 'slug', $attr['cat'], 'product_cat' );
		if( !$id ) { return; }
		$args = array(
			'orderby'           => 'name', 
			'order'             => 'ASC',
			'hide_empty'        => false, 
			'parent'            => $id->term_id,
			'hierarchical'      => true,
			'number'			=>$number
		); 
		$terms = get_terms('product_cat', $args);
		$result="<ul ".$class.">";
		foreach($terms as $term){
				$result.='<li><a href="'.esc_url(get_term_link( $term, 'product_cat' )).'">'.$term->name.'</a></li>';
		}
		$result.="</ul>";
		return $result;
	}
	

	/*---------------add class for post----------------------------*/
	add_action( JVLibrary::getFnCheat( 'amb' ). 'es', 'boot_add_post_meta_post');
	function boot_add_post_meta_post() {
		if( $fn = JVLibrary::getFnCheat( 'amb' ) ) {
			call_user_func( $fn, 'post_option', 'Post Option', 'boot_show_post_meta_post', 'post' );
		}
	}
	function boot_show_post_meta_post() {
		global $post;
		echo '<input type="hidden" name="boot_custom_meta_box_nonce2" value= "' . wp_create_nonce(basename(__FILE__)) . '"/>';
		ob_start();
	?>
		
        <div class="jv-cols2">
        
        	<div class="column hidden">
            
        <label class="rowlabel"><span class="title">Title Class:</span>
		<span class="wrap-input"><input  class="jv-input-option" name="title_class" value="<?php echo esc_attr(get_post_meta($post->ID,'title_class',true)); ?>" /></span>
		</label>
		<label class="rowlabel"> <span class="title"> Content Class: </span>
		<span class="wrap-input"><input  name="content_class" class="jv-input-option" value="<?php echo esc_attr(get_post_meta($post->ID,'content_class',true)); ?>" /></span>
		</label>

             </div>
        	<div class="column">
            
		<label class="rowlabel"><span class="title">Audio:</span>
		<span class="wrap-input"><input  class="jv-input-option" name="audio_setting" value="<?php echo esc_attr(get_post_meta($post->ID,'audio_setting',true)); ?>" /></span>
		</label>
		            

         </div>
        <div class="column">
            
		
		<label class="rowlabel"> <span class="title">Video: </span>
		<span class="wrap-input"><input  name="video_setting" class="jv-input-option" value="<?php echo esc_attr(get_post_meta($post->ID,'video_setting',true)); ?>" /></span>
		</label>            

            </div>
        
        </div>
        

        
        
        

	<?php
		$result=ob_get_clean();
		echo $result;
	}
	add_action('save_post', 'boot_save_custom_meta_box_post');
	function boot_save_custom_meta_box_post($post_id) {
		global $custom_meta_fields;
		// verify nonce  
		$post=get_post($post_id);
		$posttype=$post->post_type;
		// verify nonce  
		if($posttype<>"post") return false;
		if(isset($_POST['boot_custom_meta_box_nonce2'])){
			if (!wp_verify_nonce($_POST['boot_custom_meta_box_nonce2'], basename(__FILE__)))
				return $post_id;
				update_post_meta($post_id, 'title_class', sanitize_text_field($_POST['title_class']));
				update_post_meta($post_id, 'content_class', sanitize_text_field($_POST['content_class']));
				update_post_meta($post_id, 'audio_setting', sanitize_text_field($_POST['audio_setting']));
				update_post_meta($post_id, 'video_setting', sanitize_text_field($_POST['video_setting']));
			}
		}
	
	/*---------------shortcode for div ----------------------------*/
	JVLibrary::jvShortcode('shortcode','shortcode_func');
	function shortcode_func($att,$content = null){
		$class=isset($att['class'])?"class='".$att['class']."'":"";
		$id=isset($att['id'])?"id='".$att['id']."'":"";
		$content=str_replace('<br />','',$content);
		$content=str_replace('<p>','',$content);
		$content=str_replace('</p>','',$content);
	return '<article '.$class.' '.$id.'>'.do_shortcode($content).'</article>';
	}
	JVLibrary::jvShortcode('section','section_func');
	function section_func($att,$content = null){
		$class=isset($att['class'])?"class='".$att['class']."'":"";
		$id=isset($att['id'])?"id='".$att['id']."'":"";
	return '<section '.$class.' '.$id.'>'.do_shortcode($content).'</section>';
	}
	JVLibrary::jvShortcode('container','container_func');
	function container_func($att,$content = null){
		return '<div class="container">'.do_shortcode($content).'</div>';
	}
	JVLibrary::jvShortcode('row','row_func');
	function row_func($att,$content = null){
		return '<div class="row">'.do_shortcode($content).'</div>';
	}
	JVLibrary::jvShortcode('div','div_func');
	function div_func($att,$content = null){
		$class=isset($att['class'])?"class='".$att['class']."'":"";
		$id=isset($att['id'])?"id='".$att['id']."'":"";
		return '<div '.$class.' '.$id.'>'.do_shortcode($content).'</div>';
	}
	JVLibrary::jvShortcode('col','col_func');
	function col_func($att,$content = null){
		$class=isset($att['class'])?"class='".$att['class']."'":"";
		$id=isset($att['id'])?"id='".$att['id']."'":"";
		return '<div '.$class.' '.$id.'>'.do_shortcode($content).'</div>';
	}
	JVLibrary::jvShortcode('p','p_func');
	function p_func($att,$content = null){
		$class=isset($att['class'])?"class='".$att['class']."'":"";
		$id=isset($att['id'])?"id='".$att['id']."'":"";
		return '<p '.$class.' '.$id.'>'.do_shortcode($content).'</p>';
	}
	JVLibrary::jvShortcode('jv_video','video_func');
	function video_func($att,$content = null){
		$iframe="iframe";
		$width=isset($att['width'])?'width="'.$att['width'].'"':"";
		$height=isset($att['height'])?'height="'.$att['height'].'"':"";
		if(strpos($content,'youtube.com')){
			$id_video=substr($content,(strrpos($content,"=")+1));
			return '<'.$iframe.' src="https://www.youtube.com/embed/'.$id_video.'" frameborder="0"  '.$width.' '.$height.' allowfullscreen></'.$iframe.'>';
		}
		else{
			$id_video=substr($content,(strrpos($content,"/")+1));
			return '<'.$iframe.' src="https://player.vimeo.com/video/'.$id_video.'" '.$width.' '.$height.' ></'.$iframe.'>';
		}
	}
	
	JVLibrary::jvShortcode('jv_audio','audio_func');
	function audio_func($att,$content = null){		
		$iframe="iframe";
		$width=isset($att['width'])?'width="'.$att['width'].'"':"";
		$height=isset($att['height'])?'height="'.$att['height'].'"':"";
		$url_api='https://api.soundcloud.com/resolve.json?url='.$content.'&client_id=b45b1aa10f1ac2941910a7f0d10f8e28';		
		global $wp_filesystem;
		$result = $wp_filesystem->get_contents($url_api);
		if(isset($result)){
			$api=json_decode($result);
			if(isset($api->id)){
				return'<'.$iframe.' '.$width.' '.$height.' scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$api->id.'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></'.$iframe.'>';
			}
			else{
				return "Can not load content from https://soundcloud.com/";
			}
		}
		else{
			return "Invalid url of https://soundcloud.com/";
		}
		
			
	}
	JVLibrary::jvShortcode('home_url','home_url_func');
	function home_url_func(){
		return home_url();
	}
	
	JVLibrary::jvShortcode('woo_login_form','woo_login_form_func');
	function woo_login_form_func(){
		ob_start();
		global $post;
		if( is_user_logged_in()){
			$current_user = wp_get_current_user();
			$actual_link = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		?>
        
        <div class="dropdownMenu ">
        <ul class="menu"> <li> 
            <span class="text-logout btn-bar"> 
			<?php echo get_avatar( $current_user->user_email ); ?>
            <?php echo esc_attr($current_user->display_name); ?> </span>
             
                 <ul class="sub-menu">
                    <li> <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) )?>"> <i class="icon-user22"></i>  <?php _e( 'My acount', 'jv_allinone' ); ?> </a> </li>
                    <li> <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_cart_page_id') ) )?>"><i class="icon-cart2"></i>  <?php _e( 'Cart', 'jv_allinone' ); ?> </a> </li>
                    <li> <a href="<?php echo esc_url( get_permalink( get_option('yith_wcwl_wishlist_page_id') ) )?>"><i class="icon-heart2"></i>  <?php _e( 'Wishlist', 'jv_allinone' ); ?> </a> </li>
                    <li> <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_checkout_page_id') ) )?>"><i class="icon-arrow-right72"></i>  <?php _e( 'Checkout', 'jv_allinone' ); ?> </a> </li>
                    <li> <a href="<?php echo wp_logout_url($actual_link); ?>"><i class="icon-logout"></i>  <?php _e( 'Logout', 'jv_allinone' ); ?> </a> </li>
                    
                 </ul>
             </li> 
             
             </ul>
       </div>      
             
             
		<?php
		}
		else{
		?>

		<div class="form-login-woo">
		  <a href="#form-login" class="btn-bar" data-closeClass="bpopup-close" data-rel="bpopup"><i class="fa fa-sign-in"></i> <?php esc_attr_e( 'Login', 'jv_allinone' ); ?></a>
 

			<form id="form-login" action="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) )?>" method="post" class="bpopup-content form-login panel-allinone">

				 <input type="hidden" name="redirect" value="<?php echo "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<h2><?php _e( 'Login', 'jv_allinone' ); ?>  </h2>
				
				
					<p class="form-row form-row-wide">
						<label for="username"><?php _e( 'Username or email address', 'jv_allinone' ); ?> <span class="required">*</span></label>
						<input type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
					</p>
					<p class="form-row form-row-wide">
						<label for="password"><?php _e( 'Password', 'jv_allinone' ); ?> <span class="required">*</span></label>
						<input class="input-text" type="password" name="password" id="password" />
					</p>
					<p class="form-row clearfix">
						<label for="rememberme" class="inline pull-left">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php _e( 'Remember me', 'jv_allinone' ); ?>
						</label>
						<a class="lost_password pull-right" href="<?php echo wc_lostpassword_url(); ?>"><?php _e( 'Lost your password?', 'jv_allinone' ); ?></a>
					</p>			
					<?php do_action( 'woocommerce_login_form' ); ?>
					<div class="buttons">
						<?php wp_nonce_field( 'woocommerce-login' ); ?>
						<input type="submit" class="btn btn-primary" name="login" value="<?php esc_attr_e( 'Login', 'jv_allinone' ); ?>" />
						<span class="bpopup-close btn btn-grey"><?php _e( 'Cancel', 'jv_allinone' ); ?></span>	
					</div>
				
			<?php do_action( 'woocommerce_login_form_end' ); ?>
			</form>
		</div>
		
	
		<?php
		}
		$result=ob_get_clean();
		return $result;
	}
	
	JVLibrary::jvShortcode('woo_search','woo_search_func');
	function woo_search_func(){
	ob_start();
	?>
		<form id="searchform-woo" class="searchform" action="<?php echo home_url() ?>" name="searchform"  method="get" role="search">
				<input type="text" name="s" placeholder="<?php esc_attr_e( 'Search here and press enter', 'jv_allinone' ); ?>"  />
				<input type="hidden" name="post_type" value="product" />
		</form>
	<?php
	return ob_get_clean();
	}
	
	
	add_action('wp_ajax_load_content_widget','load_content_widget');
	add_action('wp_ajax_nopriv_load_content_widget','load_content_widget');
	function load_content_widget(){
		$params=get_widget_params($_POST['widget']);
		echo do_shortcode($params["content"]);
		exit;
	}
	if(!function_exists('cut_cpost')){
		function cut_cpost($chuoi,$c1,$c2){
						$chuoi=" ".$chuoi;
						if(strlen($c1)>strlen($chuoi))
								{
									return "-2";
								}
						$vt1=strpos($chuoi,$c1);
						$vt2=strpos($chuoi,$c2,$vt1+strlen($c1));
						if($vt1==false or $vt2 ==false)
								return "-1";
						else
								return substr($chuoi,$vt1 + strlen($c1),$vt2-($vt1+strlen($c1)));
		}
	}
	/*---------------------add theme support------------------*/
	function theme_slug_setup() {
	   add_theme_support( 'title-tag' );
	}
	add_action( 'after_setup_theme', 'theme_slug_setup' );
	/*----------------pagelink----------------*/
	JVLibrary::jvShortcode('page_link','page_link_func');
	function page_link_func($attr){
		return (isset($attr['id']))?get_page_link($attr['id']):home_url();
	} 
	
	/*-----------------Fix Megamenu -----------------*/

	if( !function_exists( 'jvnav_menu_link_attributes' ) ) 
	{
		add_filter( 'megamenu_nav_menu_link_attributes', 'jvnav_menu_link_attributes' , 1000, 3);
		 
		 function jvnav_menu_link_attributes( $atts, $item, $args ){
			
			if( !is_array( $atts ) ) {
				return array();
			}
			
			$uclasses = get_post_meta( $item->ID, '_menu_item_classes', true ); 
			
			if( !$uclasses ) { return $atts; }
			
			$uclasses = array_filter( $uclasses );       
			$uclasses = implode( " ", $uclasses );
			
			if( isset( $atts[ 'class' ] ) ) {
				
				$atts[ 'class' ] .= " {$uclasses}";
				
				return $atts;        
			}
			
			$atts[ 'class' ] = $uclasses;
			
				 
			return $atts;
				
		 }
	 }
	 
	 if( !class_exists( 'Jvmegamenut' ) ) {

		class Jvmegamenut 
		{

			public static function addAssets()
			{
				wp_dequeue_script( 'megamenu' );
				wp_enqueue_script( 'Jvmegamenut', get_template_directory_uri(). '/library/assets/js/maxmegamenu.js', array( 'jquery' ), '1.8.0', true ); 
				$params = apply_filters("Jvmegamenut",
					array(
						"effect" => array(
							"fade" => array(
								"in" => array(
									"animate" => array("opacity" => "show")
								),
								"out" => array(
									"animate" => array("opacity" => "hide")
								)
							),
							"slide" => array(
								"in" => array(
									"animate" => array("height" => "show"),
									"css" => array("display" => "none")
								),
								"out" => array(
									"animate" => array("height" => "hide")
								)
							)
						),
						"fade_speed" => "fast",
						"slide_speed" => "fast",
						"timeout" => 50
					)
				);

				wp_localize_script( 'Jvmegamenut', 'megamenu', $params );
			}
		
		}
		
		add_action( 'wp_enqueue_scripts', 'Jvmegamenut::addAssets', 10000 );
	}