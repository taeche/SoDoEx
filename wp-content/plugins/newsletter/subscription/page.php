<?php
// This page is used to show subscription messages to users along the various
// subscription and unsubscription steps.
//
// This page is used ONLY IF, on main configutation, you have NOT set a specific
// WordPress page to be used to show messages and when there are no alternative
// URLs specified on single messages.
//
// To create an alternative to this file, just copy the page-alternative.php on
//
//   wp-content/extensions/newsletter/subscription/page.php
//
// and modify that copy.

include '../../../../wp-load.php';

$module = NewsletterSubscription::instance();
$user = $module->get_user_from_request();
$message_key = $module->get_message_key_from_request();
$message = $newsletter->replace($module->options[$message_key . '_text'], $user);
$message .= (isset($module->options[$message_key . '_tracking']))?$module->options[$message_key . '_tracking']:"";
$alert = (isset($_REQUEST['alert']))?stripslashes($_REQUEST['alert']):"";

// Force the UTF-8 charset
header('Content-Type: text/html;charset=UTF-8');

if (is_file(WP_CONTENT_DIR . '/extensions/newsletter/subscription/page.php')) {
    include WP_CONTENT_DIR . '/extensions/newsletter/subscription/page.php';
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.5, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="handheldfriendly" content="true" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
<?php wp_head(); ?>



</head>

<body   <?php body_class(); ?>>
<div id="page" class="hfeed site">
  <div id="mainsite"> <span class="flexMenuToggle"></span>
    <section id="panel">
      <div class="container">
        <?php dynamic_sidebar( 'sidebar-panel' ); ?>
      </div>
    </section>
    <header id="header" class="jv-main-header " role="banner" >
      <div class="container"> <?php echo JVLibrary::logo(); ?>
        <nav id="nav-mainmenu">
          <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
        </nav>
        <a href="JavaScript:void(0);" class="flexMenuToggle">
        <?php _e( 'Menu', 'jv_allinone' ); ?>
        <span></span><span></span><span></span> </a>  </div>
    </header>
    <section  id="block-breadcrumb">
      <div class="container">
        <h1 class="entry-title">Newsletter</h1>
        <?php the_breadcrumb(); ?>
      </div>
    </section>
    <div id="pageNwsletter"  class="container ">
      <?php if (!empty($alert)) { ?>
      <script>
            alert("<?php echo addslashes(strip_tags($alert)); ?>");
        </script>
      <?php } ?>
      <?php echo $message; ?> </div>


	<?php if ( is_active_sidebar( 'bottom' ) ) : ?>
	<section id="Bottom" class="full-bg-grey" >
        <div class="container">
            <div class="row">
            	<?php dynamic_sidebar( 'bottom' ); ?>
            </div>
        </div>
	</section>
    <?php endif; ?>
	<?php if ( is_active_sidebar( 'footer' ) ) : ?>
	<footer id="Footer" role="contentinfo">
        <div class="container">
        <div class="row">
            	<?php dynamic_sidebar( 'footer' ); ?>
         </div>       
        </div>
	</footer>
    <?php endif; ?>
  <!-- #colophon -->      
  </div>
</div>

</body>
</html>