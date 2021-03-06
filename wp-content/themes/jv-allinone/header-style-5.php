<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Allinone
 * @subpackage JV_Allinone
 * @since JV Allinone 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.5, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="handheldfriendly" content="true" />

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body id="style-index-5"  <?php body_class(); ?>>
<div id="page" class="hfeed site">
<div id="mainsite">
	<span class="flexMenuToggle"></span>

	<section id="panel">
        <div class="container">
			<?php   
			if( class_exists( 'JVLibrary' ) && $config = JVLibrary::getConfig() ) {
				$loginwoo = @$config->loginwoo; 
			}
			if($loginwoo==''){
				$loginwoo ='Yes';
			}
			
			if(class_exists('Woocommerce') && $loginwoo=='Yes')  { 
            echo '<aside class=" widget widget_text_login" >			<div class="textwidget"> ';
            echo do_shortcode('[woo_login_form]');
            echo '</div></aside>';
            }?>

			<?php dynamic_sidebar( 'sidebar-panel' ); ?>   
        </div>
	</section>
    
	<header id="header" class="jv-main-header " role="banner" >
		<div class="container">
				<?php echo JVLibrary::logo(); ?>
                
                <a href="JavaScript:void(0);" class="flexMenuToggle"> <?php _e( 'Menu', 'jv_allinone' ); ?> <span></span><span></span><span></span>  </a>

				<?php 
                if( class_exists( 'JVLibrary' ) && $config = JVLibrary::getConfig() ) {
                    $banner_ads_url = @$config->banner_ads_url; 
                    $bannerads = @$config->bannerads; 
					$target_url = @$config->target_url; 
					
                	if ($bannerads !='') {	
				?>
                        <a href="<?php echo $banner_ads_url; ?>" target="<?php echo $target_url; ?>" class="topbanner" >
                            <img alt="Banner" src='<?php echo esc_url(  home_url() ).'/'.$bannerads; ?>'>
                        </a>
                
                <?php
					}
                }?>


        </div>
		
	</header>
	<nav id="nav-mainmenu">
		<div class="container">
        <a class="btnsearchtop icon-search8 pull-right" data-hover="Search" href="javascript:void(0)"></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
				 
		</div>
	</nav>
	
	