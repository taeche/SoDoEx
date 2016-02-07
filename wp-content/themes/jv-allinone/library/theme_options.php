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
 * JV Allinone functions and definitions
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

if(isset($_POST['theme_options_nonce']) && $_POST['theme_options_nonce'] !=''){

	if ( wp_verify_nonce( @$_POST['theme_options_nonce'], basename(__FILE__) ) ){
	
		$pref = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
		
		$theme_options = $_POST;
		
		foreach($theme_options as $key => $value) 
		{
			if( in_array( $key, array( 'theme_options_nonce', 'Submit', 'hide_ajax_notification' ) ) ) 
			{
				unset( $theme_options[ $key ] );
			}
            
            if( in_array( $key, array( 'jsOwl', 'googlefonts', 'textAnimate' ) ) && isset( $theme_options[ $key ] ) )
            {
                $theme_options[ $key ] = stripslashes_deep( $theme_options[ $key ] );
            }

		}
		
		if( !isset( $theme_options[ 'banner_ads_url' ] ) || empty( $theme_options[ 'banner_ads_url' ] ) )
		{
			$theme_options[ 'banner_ads_url' ] = 'http://joomlavi.com/';	
		}
		
		update_option( $pref.'_theme_settings', $theme_options );
		
		wp_safe_redirect( admin_url('themes.php?page=theme-settings-page&updated=1' ) );
		
	}else{
		wp_die(__("You do not have permission to edit theme settings.",'jv_allinone'));
	}
}
/*
	To display theme setting options in appearance -> theme settings
*/
if(!function_exists('theme_settings_page_callback')){
    function theme_settings_page_callback() {
        $theme_settings = JVLibrary::getConfig();    
        ?>

<div id="wtheme_options_settings" class="wrap">
    <div id="theme_options_settings">                                                                                    
    <div class="icon32 icon32-posts-post" id="icon-edit"><br>
    </div>
    <h2> <?php echo __("Theme Settings",'jv_allinone');?> </h2>
    <?php if(isset($_GET['updated']) && $_GET['updated'] !=''){?>
    <div class="updated" id="message" style="clear:both">
      <p> <?php echo __("Theme Settings",'jv_allinone');?> <strong><?php echo __("saved",'jv_allinone');?> </strong>. </p>
    </div>
    <?php }?>
    <div class="jvadmin">
      
      <ul id="tev_theme_settings" class="filter-links">
        <li class="jvGlobal active"> <a id="jvGlobal" href="javascript:void(0);" class="current"> <?php echo __("Global",'jv_allinone');?> </a> </li>
        <li class="jvGooglefonts "> <a id="jvGooglefonts" href="javascript:void(0);"> <?php echo __("Google Fonts",'jv_allinone');?> </a> </li>
        <li class="jvStyles "> <a id="jvStyles" href="javascript:void(0);" > <?php echo __("Style",'jv_allinone');?> </a> </li>
        <li class="jvJSCustom "> <a id="jvJSCustom" href="javascript:void(0);" > <?php echo __("Owl Slider",'jv_allinone');?> </a> </li>
        <li class="jvAnimate"> <a id="jvAnimate" href="javascript:void(0);" > <?php echo __("Scrolling Effect",'jv_allinone');?> </a> </li>
        <li class="jvOptimize"> <a id="jvOptimize" href="javascript:void(0);" > <?php echo __("Optimize",'jv_allinone');?> </a>    </li>
        <li class="jvAdvanced2"> <a id="jvAdvanced2" href="javascript:void(0);" > <?php echo __("Advanced Theme ",'jv_allinone');?> </a>    </li>
        <li class="jvAdvanced"> <a id="jvAdvanced" href="javascript:void(0);" > <?php echo __("Sample Data",'jv_allinone');?> </a> </li>

      </ul>
      <div class="jvcontenttabs" style="overflow:hidden">
        <form name="theme_options_settings" method="post" enctype="multipart/form-data">
            <input type="hidden" name="theme_options_nonce" value="<?php echo esc_attr(wp_create_nonce(basename(__FILE__)))?>" />
            <table id="jvGlobal" class="tmpl-theme_settings form-table active-tab">
          <tbody>
            <!-- General Settings -->
            
            <tr>
              <th><label for="logo"> <?php echo __('Logo Setting','jv_allinone');?> </label></th>
              <td><div class="input-switch">
                  <?php
                          add_thickbox();
                          $jLogo = (isset($theme_settings->logo)) ? $theme_settings->logo : array();

						  
                          $typeLogo = array(  'image' => 'Images' , 'text' => 'Text');
                          echo JVLibrary::getSelect('logo[type]', $typeLogo, @$jLogo['type'],'jLogo');
                          ?>
                </div>
                <div class="logoImage" style="display: none">
                  <h4>
                    <?php _e('Logo Desktop','jv_allinone' ); ?>
                  </h4>
                  <div class="jvbox"> 
                  <?php if (@$jLogo['normal']) { ?>
                  <img style="float:left; margin-right:10px;"  height="30" alt="" src="<?php echo  esc_attr( home_url('/') . @$jLogo['normal']) ; ?>">
                  <?php } ?>
                    <input type="text" style="width: 350px;" id="logo_url" name="logo[normal]" value="<?php echo  esc_attr(@$jLogo['normal']) ; ?>" />
                    <input id="upload_logo_button" data-target="#logo_url" type="button" class="button" value="<?php esc_attr_e( 'Upload Logo','jv_allinone' ); ?>" />
                    <span class="description">
                    <?php _e('Upload an image for the logo.','jv_allinone' ); ?>
                    </span> </div>
                  <br />
                  <h4>
                    <?php _e('Logo Mobile','jv_allinone' ); ?>
                  </h4>
                  <div class="jvbox"> 
                  
                  <?php if (@$jLogo['special']) { ?>
                  <img style="float:left; margin-right:10px;"  height="30" alt="" src="<?php echo  esc_attr( home_url('/') . @$jLogo['special']) ; ?>">
                  <?php } ?>
                    <input type="text"  style="width: 350px;" id="logom_url" name="logo[special]" value="<?php echo  esc_attr(@$jLogo['special']) ; ?>" />
                    <input id="upload_logom_button" data-target="#logom_url" type="button" class="button" value="<?php esc_attr_e( 'Upload Logo','jv_allinone' ); ?>" />
                    <span class="description">
                    <?php _e('Upload an image for the logo.','jv_allinone' ); ?>
                    </span> </div>
                </div>
                <div class="logoText" style="display: none">
                  <div class="jvbox">
                    <input name="logo[text]" placeholder="<?php esc_attr_e('Your Text Logo.','jv_allinone' ); ?>" value="<?php echo esc_attr(@$jLogo['text']); ?>" class="logoText">
                    <input name="logo[slogan]" value="<?php echo esc_attr(@$jLogo['slogan']); ?>" placeholder="<?php esc_attr_e('Your Slogan.','jv_allinone' ); ?>" class="logoSlogan">
                  </div>
                </div>



                              
                
                </td>
            </tr>
            <tr>
              <th><label for="isRTL"> <?php echo __("Direction RTL",'jv_allinone');?> </label></th>
              <td><div class="input-switch">
                  <?php
                            $rtl = array('Yes','Auto','No');
                            echo JVLibrary::getRadio('isRTL', $rtl, @$theme_settings->isRTL);
                            ?>
                </div></td>
            </tr>
            <tr>
              <th><label for="retina"> <?php echo __('Support Retina ','jv_allinone');?> </label></th>
              <td><div class="input-switch">
                  <input type="checkbox"  value="1" <?php echo (@$theme_settings->retina==1) ? esc_attr('checked') : ''?> id="retina" name="retina">
                  <label for="retina"> <?php echo __('Enable','jv_allinone');?> </label>
                </div></td>
            </tr>
            <tr>
              <th><label for="gototop"> <?php echo __('Go To Top','jv_allinone');?> </label></th>
              <td><div class="input-switch">
                  <input type="checkbox"  value="1" <?php echo (@$theme_settings->gototop==1) ? esc_attr('checked') : ''?> id="gototop" name="gototop">
                  <label for="gototop"> <?php echo __('Enable','jv_allinone');?> </label>
                </div></td>
            </tr>
            
                <tr>
                <th></th>
                  <td><p style="clear: both;" class="submit"> 
                          <input type="submit" value="<?php echo esc_attr__('Save All Settings','jv_allinone'); ?>" class="button button-primary button-hero" name="Submit">
                    </p></td>
                </tr>
                
          </tbody>
        </table>
            <table id="jvGooglefonts" class="tmpl-theme_settings form-table">
          <tbody>
            <!-- Listing Page Settings -->
            <tr>
              <th><label for="googlefonts"> <?php echo __('Google Fonts','jv_allinone');?> </label></th>
              <td><div class="input-switch">
                <div id="jvGoogleFonts">
                    <div class="jContent">
                    <?php $jvCount = 0; ?>
                    <?php if( ( $googlefonts = @$theme_settings->googlefonts ) && $jvFonts = json_decode( $googlefonts ) ) : ?>
                        <?php if( $jvFonts && is_array( $jvFonts ) ) : ?>
                                <?php $jvCount = count($jvFonts)?  count($jvFonts) : 0; ?>
                                <?php foreach ($jvFonts as $i => $font) : ?>
                              <div id="<?php echo esc_attr('jFonts-'.($i+1));?>" class="jFonts-item">
                                <div class="jFonts-title">
                                  <p onclick="jvThemeOptioins.toggle(this)">
                                    <?php if($font->title) echo esc_attr($font->title); else echo esc_attr('Title - '.($i+1));?>
                                  </p>
                                  <div class="jFonts-title-control">
                                    <input type="checkbox" <?php echo ($font->enable==1) ? esc_attr('checked') : ''?> class="jFonts-enable">
                                    <label for="enable"> <?php echo __('Enable','jv_allinone');?> </label>
                                    <a class="ui-state-default ui-corner-all jFonts-button" href="javascript:void(0)" onclick="jvThemeOptioins.removeItem(this)" title="Remove"><span class="dashicons dashicons-trash"></span></a> </div>
                                </div>
                                <div style="display:none1" class="data-input jFonts-content">
                                  <div class="jFonts-input">
                                    <label><?php echo _e('Title','jv_allinone'); ?></label>
                                    <input type="text" class="jFonts-title" value="<?php if($font->title) echo esc_attr($font->title); else echo esc_attr('jFonts-'.($i+1));?>"/>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="jFonts-input">
                                    <label><?php echo _e('Font family','jv_allinone'); ?></label>
                                    <input type="text" class="jFonts-family" value="<?php echo esc_attr($font->name); ?>"/>
                                    <input type="hidden" class="jFonts-name" value="<?php echo esc_attr($font->name); ?>"/>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="jFonts-input">
                                    <label><?php echo _e('Font weight','jv_allinone'); ?></label>
                                    <input type="text" data="<?php echo esc_attr($font->weight); ?>" class="jFonts-weight" value="<?php echo esc_attr($font->weight); ?>"/>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="jFonts-input">
                                    <label><?php echo _e('Assign to selector','jv_allinone'); ?></label>
                                    <input type="text" value="<?php echo esc_attr($font->element); ?>" class="jFonts-element"/>
                                  </div>
                                  <div class="clr"></div>
                                </div>
                              </div>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      <?php endif; ?>
                      <textarea style="display:none" id="googlefonts" name="googlefonts"><?php echo esc_attr($jvCount); ?></textarea>
                    </div>
                  </div>          
                  <input type="button" name="button" class="button jvFonts button-hero" value="Add">
                </div></td>
            </tr>
                <tr>
                <th></th>
                  <td><p style="clear: both;" class="submit"> 
                          <input type="submit" value="<?php echo esc_attr__('Save All Settings','jv_allinone'); ?>" class="button button-primary button-hero" name="Submit">
                    </p></td>
                </tr>            
          </tbody>
        </table>
        
            <!-- Detail Page Settings -->
            <table id="jvStyles" class="tmpl-theme_settings form-table">
          <tbody>
            <tr>
              <th><label for="stylelayout"> <?php echo __('Style layout ','jv_allinone');?> </label></th>
              <td><div class="input-switch">
                  <?php $styleLyout = JVLibrary::getStyleLayout();
                                    echo JVLibrary::getRadio('stylelayout', $styleLyout, @$theme_settings->stylelayout); ?>
                </div></td>
            </tr>
            <tr>
              <th><label for="bg"> <?php echo __('Background Layout','jv_allinone');?> </label></th>
              <td><div class="input-switch background-style">
                  <?php   JVLibrary::jvPickerColor();   ?>
                  
                  <div class="style_background jvbox">                  
                  <div> <h5><?php esc_attr_e( 'Background color','jv_allinone' ); ?></h5>
                    <input type="text" value="<?php echo esc_attr(@$theme_settings->bg_color); ?>" name="bg_color" class="minicolors" data-default-color="#ffffff" />
                  </div>

                  <div><h5><?php esc_attr_e( 'Background position','jv_allinone' ); ?></h5>
                    <?php $bg_position = array('inherit'=>'inherit','bottom'=>'bottom','center'=>'center','left'=>'left','right'=>'right','top'=>'top'); ?>
                  	<?php echo JVLibrary::getSelect('bg_position',$bg_position, @$theme_settings->bg_position); ?> 
                  </div>
                  
                  <div> <h5><?php esc_attr_e( 'Background attachment','jv_allinone' ); ?></h5>
                    <?php $bg_attachment = array('inherit'=>'inherit','fixed'=>'fixed','scroll'=>'scroll'); ?>
                  	<?php echo JVLibrary::getSelect('bg_attachment',$bg_attachment, @$theme_settings->bg_attachment); ?> 
                  </div>
                  

                  <div> <h5><?php esc_attr_e( 'Background repeat','jv_allinone' ); ?></h5>
                    <?php $bg_repeat = array('inherit'=>'inherit','no-repeat'=>'no-repeat','repeat'=>'repeat','repeat-x'=>'repeat-x','repeat-y'=>'repeat-y'); ?>
                  	<?php echo JVLibrary::getSelect('bg_repeat',$bg_repeat, @$theme_settings->bg_repeat); ?> 
                  </div>
                  
                  </div>                  
                  
                  
                  <?php echo JVLibrary::loadBackground(@$theme_settings->background); ?> </div></td>
            </tr>
            <tr>
              <th><label for="themecolor"> <?php echo __('Theme Color ','jv_allinone');?> </label></th>
              <td><div class="input-switch themecolor"> <?php echo JVLibrary::loadThemeColor(@$theme_settings->themecolor); ?> </div></td>
            </tr>
            <tr>
              <th><label for="themecolor"> <?php echo __('Show color demo','jv_allinone');?> </label></th>
              <td><div class="input-switch themecolor">
                  <?php $demo = array('Yes','No'); ?>
                  <?php echo JVLibrary::getRadio('show_demo',$demo, @$theme_settings->show_demo); ?> </div></td>
            </tr>
                <tr>
                <th></th>
                  <td><p style="clear: both;" class="submit"> 
                          <input type="submit" value="<?php echo esc_attr__('Save All Settings','jv_allinone'); ?>" class="button button-primary button-hero" name="Submit">
                    </p></td>
                </tr>            

          </tbody>
        </table>
            <table id="jvJSCustom" class="tmpl-theme_settings form-table">
          <tbody>
            <!-- General Settings -->
            
            <tr>
              <th><label for="version"> <?php echo __('Version','jv_allinone');?> </label></th>
              <td><div class="input-switch themecolor">
                  <?php $version = array('1.3'=>'1.3','2.0'=>'2.0'); ?>
                  <?php echo JVLibrary::getSelect('version',$version, @$theme_settings->version); ?> </div></td>
            </tr>
            <tr>
              <th><label for="owl"> <?php echo __('OWL Slide','jv_allinone');?> </label></th>
              <td><div class="input-switch">
                  <div id="OWL-list" class="w70">
                    <div id="OWL-container">
                    <?php $jvCountOwl = 0; ?>
                      <?php if( $jsOwl = @$theme_settings->jsOwl ) : ?>
                      <?php $jvOwl = json_decode($jsOwl); ?>
					<?php if( is_array( $jvOwl ) ) : ?>	
					<?php $jvCountOwl = count($jvOwl)?  count($jvOwl) : 0; ?>
                      <?php foreach ($jvOwl as $i=>$ol){ ?>
                      <div id="<?php echo esc_attr('OWL-'.($i+1));?>" class="OWL-item">
                        <div class="OWL-title">
                          <p onclick="jvThemeOptioins.toggle(this)">
                            <?php if($ol->title) echo esc_attr($ol->title); else echo esc_attr('Title slider - '.($i+1));?>
                          </p>
                          <div class="OWL-title-control">
                            <label > <?php echo __('Enable','jv_allinone');?>
                              <input type="checkbox" <?php echo ($ol->enable==1) ? esc_attr('checked') : ''?> class="OWL-enable">
                            </label>
                            <a class="ui-state-default ui-corner-all OWL-button" href="javascript:void(0)" onclick="jvThemeOptioins.removeItem(this)" title="Remove"><span class="dashicons dashicons-trash"></span></a> </div>
                        </div>
                        <div style="display:none" class="data-input OWL-content">
                          <label class="OWL-input"> <span class="spanlabel"><?php echo _e('Title','jv_allinone'); ?></span>
                            <input type="text" class="OWL-title" value="<?php if($ol->title) echo esc_attr($ol->title); else echo esc_attr('Title slider -'.($i+1));?>"/>
                          </label>
                          <label class="OWL-input"> <span class="spanlabel"><?php echo _e('Element (.class || #id)','jv_allinone'); ?></span>
                            <input type="text" value="<?php echo esc_attr($ol->element);?>" class="OWL-element"/>
                          </label>
                          <label class="OWL-input"> <span class="spanlabel"><?php echo _e('Params','jv_allinone'); ?></span>
                            <textarea class="OWL-params"><?php echo esc_textarea($ol->params); ?></textarea>
                          </label>
                        </div>
                      </div>
                      <?php } ?>
					  <?php endif; ?>
                      <?php endif; ?>
                      <textarea style="display:none" id="jsOwl" name="jsOwl"><?php echo esc_textarea($jvCountOwl); ?></textarea>
                    </div>
                  </div>
                  <input type="button" name="button" class="button jsOwlCustom button-hero" value="Add">
                </div></td>
            </tr>
                <tr>
                <th></th>
                  <td><p style="clear: both;" class="submit"> 
                          <input type="submit" value="<?php echo esc_attr__('Save All Settings','jv_allinone'); ?>" class="button button-primary button-hero" name="Submit">
                    </p></td>
                </tr>            

          </tbody>
        </table>
        
            <!-- Detail Page Settings -->
            <table id="jvAnimate" class="tmpl-theme_settings form-table">
          <tbody>
            <?php $textAnimate = @$theme_settings->textAnimate; ?>
            <tr>
              <th><label for="owl"> <?php echo __('Apply selector','jv_allinone');?> </label></th>
              <td><div class="input-switch">
                  <div id="jvAnimate-list" class="w70"></div>
                  <textarea style="display:none" id="textAnimate" name="textAnimate"><?php echo esc_textarea($textAnimate); ?></textarea>
                  <input type="button" name="button" class="button bntAnimate button-hero" value="Add">
                </div></td>
            </tr>
                <tr>
                <th></th>
                  <td><p style="clear: both;" class="submit"> 
                          <input type="submit" value="<?php echo esc_attr__('Save All Settings','jv_allinone'); ?>" class="button button-primary button-hero" name="Submit">
                    </p></td>
                </tr>            

          </tbody>
        </table>


         <table id="jvAdvanced2" class="tmpl-theme_settings form-table">
          <tbody>
            


            
            <tr>
              <th><strong ><?php _e('Favicon','jv_allinone' ); ?> </strong></th>
              <td>
              
                 <h4>
                    <?php _e('Upload Favicon','jv_allinone' ); ?>
                  </h4>                
                <div class="jvbox">
  <?php
				  
	$jfavicon = @$theme_settings->favicon;
				   ?>
  <?php if (@$jfavicon) { ?>
  <img style="float:left; margin-right:10px;"  height="30" alt="" src="<?php echo  esc_attr( home_url('/') . @$jfavicon) ; ?>">
  <?php } ?>
  <input type="text" style="width: 350px;" id="favicon_url" name="favicon" value="<?php echo  esc_attr(@$jfavicon) ; ?>" />
  <input id="upload_favicon_button" data-target="#favicon_url" type="button" class="button" value="<?php esc_attr_e( 'Upload Image','jv_allinone' ); ?>" />
  <span class="description">
  <?php _e('Upload an favicon for website.','jv_allinone' ); ?>
  </span> 
  </div>  

              
              
              </td>
            </tr>     


			<tr>
              <th><strong ><?php _e('Banner Ads of Header  style 3 and style 5 ','jv_allinone' ); ?> </strong></th>
                <td>
                    <h4>    <?php _e('Upload Banner','jv_allinone' ); ?>    </h4>
                    <div class="jvbox">
                    	<?php $bannerads = @$theme_settings->bannerads; ?>
                        <input type="text" style="width: 350px;" id="bannerads_url" name="bannerads" value="<?php echo  esc_attr(@$bannerads) ; ?>" />
                        <input id="upload_bannerads_button" data-target="#bannerads_url" type="button" class="button" value="<?php esc_attr_e( 'Upload Image','jv_allinone' ); ?>" />
                        <span class="description">
                        <?php _e('Upload an favicon for website.','jv_allinone' ); ?>
                        </span> 
                    </div>
                    <br />
                    <h4>    <?php _e('Banner Ads URL','jv_allinone' ); ?>    </h4>
                    
                    <div class="jvbox">
                        <input type="text" style="width:350px;"  id="banner_ads_url" name="banner_ads_url" value="<?php echo esc_attr( @$theme_settings->banner_ads_url ); ?>" />
                    </div>
                    
 <br />
                    <h4>    <?php _e('Target URL','jv_allinone' ); ?>    </h4>
                    
                    <div class="jvbox">
                  <?php $target_url = array('_blank'=>'_blank','_new'=>'_new','_parent'=>'_parent','_self'=>'_self','_top'=>'_top'); ?>
                  <?php echo JVLibrary::getSelect('target_url',$target_url, @$theme_settings->target_url); ?>
                    </div>




                    
                    <?php
                          
                    $bannerads = @$theme_settings->bannerads;
                    if (@$bannerads) { ?>
                    <p> <img  height="90" alt="" src="<?php echo  esc_attr( home_url('/') . @$bannerads) ; ?>"> </p>
                    <?php } ?>
                </td>
            </tr>
            
            
            
            
            

			<tr>
              <th> <?php echo __(' Background Breadcrumb','jv_allinone');?> </th>
              <td>
              <h4> <?php _e('Upload Background','jv_allinone' ); ?>                  </h4>   

                  <div class="jvbox"> 
                  <?php
				  
				  $jimage_header = @$theme_settings->image_header;
				   ?>
                  <?php if (@$jimage_header) { ?>
                  <img style="float:left; margin-right:10px;"  height="30" alt="" src="<?php echo  esc_attr( home_url('/') . @$jimage_header) ; ?>">
                  <?php } ?>
                  
                  
                  
                    <input type="text" style="width: 350px;" id="image_header_url" name="image_header" value="<?php echo  esc_attr(@$jimage_header) ; ?>" />
                    <input id="upload_image_header_button" data-target="#image_header_url" type="button" class="button" value="<?php esc_attr_e( 'Upload Image','jv_allinone' ); ?>" />
                    
                    <span class="description">
                    <?php _e('Upload an image for header.','jv_allinone' ); ?>
                    </span> </div>

                </td>
            </tr>                   
            <tr>
              <th><strong > <?php echo __('WooCommerce','jv_allinone');?> </strong></th>
              <td>
                    <h4> <?php _e('Show login anchor in the header (Woocomerce)','jv_allinone' ); ?></h4>   
                  


                    <div class="jvbox">
                  <?php $loginwoo = array('Yes'=>'Yes','No'=>'No'); ?>
                  <?php echo JVLibrary::getSelect('loginwoo',$loginwoo, @$theme_settings->loginwoo); ?>
                    </div>                                 <br />
              


                    <h4> <?php _e('Number of products displayed per page','jv_allinone' ); ?></h4> 
                    <?php if( !@$theme_settings->woo_display ) : ?>
                    	<?php @$theme_settings->woo_display = 15; ?>
                    <?php endif; ?>
                     <input type="text"  id="woo_display" name="woo_display" value="<?php echo esc_attr( @$theme_settings->woo_display ); ?>" />   <br /><br />
                     
                    
                    <h4> <?php _e('Number of column in shop page (1,2,3,4,6) ','jv_allinone' ); ?></h4> 

                    <?php 
					if( !@$theme_settings->woo_column ) { 
						@$theme_settings->woo_column = 3;  
					}		 
					elseif (@$theme_settings->woo_column < 1){
						@$theme_settings->woo_column = 1;
					}
					elseif (@$theme_settings->woo_column == 5 ) {
						@$theme_settings->woo_column = 4;
					}
					elseif (@$theme_settings->woo_column > 6 ) {
						@$theme_settings->woo_column = 6;
					} ?>
                      
                     <input type="text"  id="woo_column" name="woo_column" value="<?php echo esc_attr( @$theme_settings->woo_column ); ?>" />   <br /><br />

                    
                    <h4> <?php _e('Woocommerce Product Detail Images','jv_allinone' ); ?></h4>   
                  
                    
                    <div class="input-switch jvbox">
                                      <?php
                                                $imagewoo = array('Override','WooCommerce Core');
                                                echo JVLibrary::getRadio('imageWoo', $imagewoo, @$theme_settings->imageWoo);
                                      ?>
                    </div>
              
              
              </td>
            </tr>



<tr>
                <th></th>
                  <td><p style="clear: both;" class="submit"> 
                          <input type="submit" value="<?php echo esc_attr__('Save All Settings','jv_allinone'); ?>" class="button button-primary button-hero" name="Submit">
                    </p></td>
                </tr>
                            

          </tbody>
        </table>        
        
        
            <!-- Custom Theme -->
        
            <table id="jvAdvanced" class="tmpl-theme_settings form-table">
          <tbody>
            <tr>
              <th><label for="owl"> <?php echo __('Dummy Data','jv_allinone');?> </label></th>
              <td><div class="input-switch">
					<?php if( is_plugin_active( 'jv-sampledata/jv-sampledata.php' ) ) : ?>
						<?php // $sample_data = array('Yes','No'); ?>
					  <?php // if( !@$theme_settings->sample_data ) { @$theme_settings->sample_data = 'yes'; } ?>
					  <?php // echo JVLibrary::getRadio('sample_data',$sample_data, @$theme_settings->sample_data); ?>
					  <div class="jvbox"> <?php echo _e('Do you want to show button "install sample data" ?','jv_allinone'); ?>
						<?php $btn_sample_data = array('yes'=>'Yes','no'=>'No'); ?>
						<?php echo JVLibrary::getSelect('btn_sample_data',$btn_sample_data, @$theme_settings->btn_sample_data); ?> </div>
					  <?php if(isset($theme_settings->datasample)){ ?>
					  <div class="installed jvbox" ><?php echo _e('You have installed data sample!','jv_allinone'); ?></div>
					  <?php }else { ?>
					  <div class="div_no_install jvbox"> 
						<div class="no_install " ><?php echo _e('WARNING: Importing demo content will give you all content and other settings. This will replicate the live demo!','jv_allinone'); ?></div>
					  </div>
					  <?php } ?>
					</div>
					
					
					
					
					   <p class="btnsampledata" >
					  <button type="button" data-action="import" class="button button-primary button-hero "> <?php echo esc_attr(__('Import dummy data','jv_allinone')); ?> </button>
					  </p> <br />
					  <p>    <button type="button" data-action="export" class="button button-hero"> <?php echo esc_attr(__('Export dummy data','jv_allinone')); ?> </button></p>
					  
					  <span class="spinner" ></span>
					  <div class="installMsg" style=" padding-top:10px;"></div>
					<?php else : ?>
					<div class="installMsg" >
						<?php esc_attr_e( 'Please install plugin "Jv-sampledata"','jv_allinone' ); ?>
					</div>
					<?php endif; ?>
                </td>
            </tr>
            
                <tr>
                <th></th>
                  <td><p style="clear: both;" class="submit"> 
                          <input type="submit" value="<?php echo esc_attr__('Save All Settings','jv_allinone'); ?>" class="button button-primary button-hero" name="Submit">
                    </p></td>
                </tr>            

          </tbody>
        </table>
        </form>
        <table id="jvOptimize" class="tmpl-theme_settings form-table">
            <tbody>
                <tr>
					<?php if( is_plugin_active( 'autoptimize/autoptimize.php' ) ) : ?>
					<td style="padding:0">
						<?php do_action( 'jvtheme_optimize' ); ?>
					</td>
					<?php else : ?>
						<th> <?php esc_attr_e( 'Compress','jv_allinone' ); ?></th>
						<td ><div class="installMsg"><?php esc_attr_e( 'Please install plugin "autooptimize"','jv_allinone' ); ?></div></td>
					<?php endif; ?>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php
        do_action( 'jvtheme_adv' );
	}
}

/* add script in footer to show hide theme options*/

$page = (isset($_GET['page']))?$_GET['page'] : '';

if($page =='theme-settings-page') 
    add_action('admin_head','tmpl_themeoptions_script');

function tmpl_themeoptions_script(){ ?>
<script src="<?php echo JVLibrary::urls('theme'); ?>library/assets/js/json2.js" type="text/javascript"></script> 
<script src="<?php echo JVLibrary::urls('theme'); ?>library/assets/js/select2.js" type="text/javascript"></script> 
<script src="<?php echo JVLibrary::urls('theme'); ?>library/assets/js/themeoptions.js" type="text/javascript"></script>
<link rel='stylesheet'  href='<?php echo JVLibrary::urls('theme'); ?>library/assets/css/select2.css' type='text/css'  />
<link rel='stylesheet'  href='<?php echo JVLibrary::urls('theme'); ?>library/assets/css/themeoptions.css' type='text/css'  />
<style type="text/css">
    #jvStyles .background-style label.radiobtn { width: 50px; height: 50px; height: auto; float: left; clear: none}
    #jvStyles .background-style span.bg-list {padding:10px; border: 2px solid #CCCCCC; width: 70px; height: 70px; float: left; text-indent: -999em; font-size: 0; color: #fff; margin-right: 10px; }
    #jvStyles .background-style span.bgactive {  border-color:#62C022; }
    
    #jvStyles .themecolor label.radiobtn { width: 50px; height: 50px; height: auto; float: left; clear: none}
    #jvStyles .themecolor span.color-list {padding:10px; margin-bottom:5px; cursor: pointer;  background-color: #FFFFFF !important; border: 2px solid #CCCCCC; width: 180px; height: 260px; float: left; text-indent: -999em; font-size: 0; color: #fff; margin-right: 10px; }
    #jvStyles .themecolor span.active {  border-color: #62C022; }
</style>
<script type="text/javascript">

/* Script to add tabs without refresh in tevolution general settings */
jQuery( function ( $ ){
    
    $('.jFonts-item .data-input').fadeOut();
    var listText = $('#textAnimate').val(),
        listJson = {},
        totalAni = 0
    ;
    try{
        
        if(listText) listJson = JSON.parse(listText);
        
    }catch( e ){ console.log( e ); }
    
    if(listJson){
        $.each(listJson, function(key, item){
            jvThemeOptioins.addAnimate(key, item);
            totalAni++;
        })
    }
    $('input.bntAnimate').click(function(){
        jvThemeOptioins.addAnimate(totalAni);
    });
    
    var urlsite = '<?php echo JVLibrary::urls('site'); ?>/',
        jLogo = jQuery('#jLogo')
    ;
    // logo
    jvThemeOptioins.upload(urlsite);
    jvThemeOptioins.logo(jLogo);
	jvThemeOptioins.btn_show_hide.call( $( '#btn_sample_data' ), $('.jvadmin .btnsampledata, .jvadmin .div_no_install') );
    jLogo.change(function(){ 
        jvThemeOptioins.logo($(this));
    });
    $('.jvInstallData').click(function(){
        var agree = confirm("WARNING: Importing demo content will give you all content and other settings. This will replicate the live demo!");
        if(agree) jvThemeOptioins.InstallDataSample();
    });
    
    //fonts
    jvThemeOptioins.initFonts(parseInt($('#googlefonts').val()));
    jvThemeOptioins.initOwl(parseInt($('#jsOwl').val()));
    $('input.jvFonts').click(function(){
        jvThemeOptioins.addNewFont();
    });
    
    $('input.jsOwlCustom').click(function(){
        jvThemeOptioins.addNewOwl();
    })
    
    $('#jvGoogleFonts .jContent').find(".jFonts-item").each(function(){
        jvThemeOptioins.jvSelect2(this);
    });
    //save options
    $('.submit input[type="submit"]').click(function(){
        jvThemeOptioins.jFontsSetValue();
        jvThemeOptioins.OWLSetValue();
        jvThemeOptioins.AniSetValue();
        return true;
    });
    
    $('span.color-list').each(function(){
        jQuery(this).click(function(){
            jQuery('span.color-list').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('#themecolor').val(jQuery(this).text());
        })		
    });		
    
    $('span.bg-list').each(function(){
        jQuery(this).click(function(){
            jQuery('span.bg-list').removeClass('bgactive');
            jQuery(this).addClass('bgactive');
            jQuery('#background').val(jQuery(this).text());
        })		
    });		

    $('#tev_theme_settings li a').click(function (e) {
        $("#wtheme_options_settings .tmpl-theme_settings").removeClass('active-tab');
        $("#tev_theme_settings li a").removeClass('current');
        $(this).parents('li').addClass('active');
        $(this).addClass('current');
        $("#wtheme_options_settings table#"+this.id)
        .addClass('active-tab')
        .find( '.input-select2' ).each( function(){
			
			$( this ).trigger( 'init-select2' );
			
		} );	
    });
});
</script>
<?php } 
