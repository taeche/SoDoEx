<?php
function ninja_annc_add_menu() {
    add_submenu_page( 'edit.php?post_type=ninja_annc', __('Plugin Settings', 'ninja-announcements'), __('Plugin Settings', 'ninja-announcements'), 'edit_users', 'plugin_settings', 'ninja_annc_show_menu' );
}

add_action( 'admin_menu', 'ninja_annc_add_menu' );

function ninja_annc_show_menu(){
	if(!empty($_POST) && check_admin_referer('ninja_save_plugin_settings','ninja_plugin_settings')){
		$current_settings = get_option("ninja_annc_settings");
		
		foreach($_POST as $key => $val){
			if($key != 'submitted' && $key != 'submit'){
				$current_settings[$key] = $val;
			}
		}
		update_option("ninja_annc_settings", $current_settings);
	}
	
	$plugin_settings = get_option("ninja_annc_settings");
	if(isset($plugin_settings['default_title_wrapper'])){
		$title_wrapper = $plugin_settings['default_title_wrapper'];
	}else{
		$title_wrapper = '';
	}	
	if(isset($plugin_settings['default_content_wrapper'])){
		$content_wrapper = $plugin_settings['default_content_wrapper'];
	}else{
		$content_wrapper = '';
	}
?>
	<div class="wrap">
		<div id="icon-ninja-annc" class="icon32"><img src="<?php echo NINJA_ANNC_URL;?>/images/head-ico.png"></div>
		<h2><?php echo 'Ninja Announcements '.NINJA_ANNC_TYPE.' - ';
		_e('Plugin Settings', 'ninja-announcements'); 
		?></h2>
		<div class="wrap-left">
		<h3><?php _e('Version', 'ninja-announcements');?> <?php echo NINJA_ANNC_VERSION;?></h3>
		<form id="" name="" action="" method="post">
		<?php wp_nonce_field('ninja_save_plugin_settings','ninja_plugin_settings'); ?>
		<input type="hidden" name="submitted" value="yes">
		<input type="hidden" name="default_style" value="unchecked"><input type="checkbox" name="default_style" id="default_style" value="checked" <?php echo $plugin_settings['default_style'];?>><label for="default_style"> <?php _e('Use Ninja Announcements default stylesheet', 'ninja-announcements');?></label><br />
		<?php
			if(NINJA_ANNC_TYPE == 'Pro'){
				require_once(NINJA_ANNC_DIR."/includes/pro/plugin-settings-extra.php");
			}
		?>
		
		<br><br>
		<input class="button-primary ninja_save_data" type="submit" value="<?php _e('Save Changes', 'ninja-announcements');?>" />
		</form>	
		</div>
		<?php
			if(NINJA_ANNC_TYPE == 'Lite'){
			?>
		<div class="wrap-right" >
			<img src="<?php echo NINJA_ANNC_URL;?>/images/wpnj-logo-wt.png" width="263px" height="45px" />
			<h2>Upgrade to Ninja Announcements Pro for many more great features including...</h2>
			<ul>
				<li><a href="http://wpninjas.net/plugin/ninja-announcements/advanced-display-options/">Advanced Display Options</a></li>
				<li><a href="http://wpninjas.net/plugin/ninja-announcements/advanced-hooks-filters/">Hooks and Filters</a></li>
				<li><a href="http://wpninjas.net/plugin/ninja-announcements/advanced-html-markup-control/">HTML Markup Control</a></li>
				<li><a href="http://wpninjas.net/plugin/ninja-announcements/announcement-groups/">Announcement Groups</a></li>
			</ul>
			<a class="button-primary" href="http://wpninjas.net/product/ninja-announcements-plugin/">Upgrade Now!</a>
		</div>
			<?php
			}
			?>
		
	</div>
<?php
}