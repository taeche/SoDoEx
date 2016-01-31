<?php
/**
 *
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
function allinone_scripts() {
wp_enqueue_script( 'Allinone-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20141212', true );
}
add_action( 'wp_enqueue_scripts', 'allinone_scripts' );

require_once(get_template_directory().'/library/jvLibrary.php');
require_once(get_template_directory().'/library/jvthemes.php'); // framework functions file
//
// Set up the content width value based on the theme's design and stylesheet.
$themecolors = array(
    'bg' => 'eeeeee',
    'border' => 'eeeeee',
    'text' => '111111',
    'link' => '000000',
    'url' => '000000'
);
if ( ! isset( $content_width ) )
	$content_width = 625;


/**
 * JV Allinone setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * JV Allinone supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since JV Allinone 1.0
 */
function allinone_setup() {
	/*
	 * Makes JV Allinone available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on JV Allinone, use a find and replace
	 * to change 'allinone' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'jv_allinone', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'jv_allinone' ) );


	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
	add_theme_support( 'jetpack-responsive-videos' );
	
	// download sample data
	JVLibrary::downloadDataSanple();
}
add_action( 'after_setup_theme', 'allinone_setup' );
// body class with setting theme
add_filter( 'body_class', 'JVLibrary::getClass', 10, 1 );


/**
 * Add support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since JV Allinone 1.2
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function allinone_get_font_url() {
	$font_url = '';

	return $font_url;	
}

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since JV Allinone 1.0
 */
function allinone_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );



	$font_url = allinone_get_font_url();
	//if ( ! empty( $font_url ) ) wp_enqueue_style( 'Allinone-fonts', esc_url_raw( $font_url ), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'Allinone-style', get_stylesheet_uri() );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'Allinone-ie', get_template_directory_uri() . '/css/ie.css', array( 'Allinone-style' ), '20121010' );
	$wp_styles->add_data( 'Allinone-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'allinone_scripts_styles' );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses allinone_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since JV Allinone 1.2
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function allinone_mce_css( $mce_css ) {
	$font_url = allinone_get_font_url();

	if ( empty( $font_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'allinone_mce_css' );


/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since JV Allinone 1.0
 */
function allinone_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'allinone_page_menu_args' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since JV Allinone 1.0
 */
function allinone_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Blog Sidebar', 'jv_allinone' ),
		'description'   => __( 'Add widgets here to appear in your Blog sidebar.', 'jv_allinone' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


	register_sidebar( array(
		'name' => __( 'Panel Header', 'jv_allinone' ),
		'id' => 'sidebar-panel',
		'description'   => __( 'Add widgets here to appear in your Header panel.', 'jv_allinone' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title ">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Panel Header 4', 'jv_allinone' ),
		'id' => 'sidebar-panel-header4',
		'description'   => __( 'Add widgets here to appear in your Header 4 panel. Used for Header style 4', 'jv_allinone' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title ">',
		'after_title' => '</h3>',
	) );
	
	

	register_sidebar( array(
		'name' => __( 'Shop Sidebar', 'jv_allinone' ),
		'id' => 'shop-sidebar',
		'description'   => __( 'Add widgets here to appear in your Shop sidebar', 'jv_allinone' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title ">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'BuddyPress Sidebar', 'jv_allinone' ),
		'id' => 'buddypress-sidebar',
		'description'   => __( 'Add widgets here to appear in your BuddyPress sidebar', 'jv_allinone' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title ">',
		'after_title' => '</h3>',
	) );	
	
	register_sidebar( array(
		'name' => __( 'Bottom Column', 'jv_allinone' ),
		'id' => 'bottom',
		'description'   => __( 'Add widgets here to appear in your Bottom section - above Footer', 'jv_allinone' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Copyright', 'jv_allinone' ),
		'id' => 'footer',
		'description'   => __( 'Add widgets here to appear in your Copyright section', 'jv_allinone' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


	
}
add_action( 'widgets_init', 'allinone_widgets_init' );

if ( ! function_exists( 'allinone_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since JV Allinone 1.0
 */
function allinone_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo esc_attr($html_id); ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php esc_attr_e( 'Post navigation', 'jv_allinone' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'jv_allinone' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'jv_allinone' ) ); ?></div>
		</nav><!-- #<?php echo esc_attr($html_id); ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'allinone_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own allinone_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since JV Allinone 1.0
 */
function allinone_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php esc_attr_e( 'Pingback:', 'jv_allinone' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'jv_allinone' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
		
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
        	<?php echo get_avatar( $comment, 44 ); ?>
        
			<header class="comment-meta comment-author vcard ItemLinks ItemLinksInline ItemLinksTop">
				<?php
					
					printf( '<cite><i class="icon-user22"></i> <b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span></span>' : ''
					);
					printf( '<span><i class="icon-calendar3"></i> <a href="%1$s"><time datetime="%2$s">%3$s</time></a></span>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'jv_allinone' ), get_comment_date(), get_comment_time() )
					);
				?>
                
                
				<span class="pull-right">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'jv_allinone' ), 'after' => '<span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                
                </span>
                
                <?php edit_comment_link( __( 'Edit', 'jv_allinone' ), '<span class="edit-link pull-right"> <i class="icon-edit5"></i> ', '</span>' ); ?>
                
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'jv_allinone' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
			</section><!-- .comment-content -->


		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'allinone_entry_meta_full' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own allinone_entry_meta_full() to override in a child theme.
 *
 * @since JV Allinone 1.0
 */
 
function allinone_entry_meta_full() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'jv_allinone' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'jv_allinone' ) );
	
	global $post;
	$dates=date('Y/m/d',strtotime($post->post_date));
	$dates_arr= explode("/",$dates);
	
	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date " datetime="%3$s">%4$s</time></a>',
		esc_url(get_day_link($dates_arr[0],$dates_arr[1],$dates_arr[2])),
		esc_attr( get_the_time() ),
		esc_attr( date('Y/m/d H:i:s',strtotime($post->post_date)) ),
		esc_html( get_the_date() )
		//date_i18n("d F Y (H:i)",$post->post_date))
	);
	
	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'jv_allinone' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( '
		<span class="category"> <i class="icon-folder2"></i>   %1$s</span> 
		<span class="tag"> <i class="icon-tags2"></i>   %2$s</span> 
		<span class="date"> <i class="icon-calendar3"></i>   %3$s</span> 
		<span class="by-author"> <i class="icon-user22"></i>  %4$s</span>
		
		', 'jv_allinone' );
	} elseif ( $categories_list ) {
		$utility_text = __( '
		<span class="category"> <i class="icon-folder2"></i>   %1$s</span> 
		<span class="date"> <i class="icon-calendar3"></i>   %3$s</span> 
		<span class="by-author"> <i class="icon-user22"></i>  %4$s</span>
		', 'jv_allinone' );
	} else {
		$utility_text = __( '
		<span class="date"> <i class="icon-calendar3"></i>   %3$s</span> 
		<span class="by-author"> <i class="icon-user22"></i>  %4$s</span>
		
		
		', 'jv_allinone' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

if ( ! function_exists( 'allinone_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own allinone_entry_meta() to override in a child theme.
 *
 * @since JV Allinone 1.0
 */
 
function allinone_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'jv_allinone' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'jv_allinone' ) );
	
	global $post;
	$dates=date('Y/m/d',strtotime($post->post_date));
	$dates_arr= explode("/",$dates);
	
	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetaime="%3$s">%4$s</time></a>',
		esc_url(get_day_link($dates_arr[0],$dates_arr[1],$dates_arr[2])),
		esc_attr( get_the_time() ),
		esc_attr( date('Y/m/d H:i:s',strtotime($post->post_date)) ),
		esc_html( get_the_date() )
	);
	
	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'jv_allinone' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( '
		<span class="category"> <i class="icon-folder2"></i>   %1$s</span> 
		<span class="tag"> <i class="icon-tags2"></i>   %2$s</span> 
		
		<span class="by-author"> <i class="icon-user22"></i>  %4$s</span>
		
		', 'jv_allinone' );
	} elseif ( $categories_list ) {
		$utility_text = __( '
		<span class="category"> <i class="icon-folder2"></i>   %1$s</span> 
		
		<span class="by-author"> <i class="icon-user22"></i>  %4$s</span>
		', 'jv_allinone' );
	} else {
		$utility_text = __( '
		
		<span class="by-author"> <i class="icon-user22"></i>  %4$s</span>
		
		
		', 'jv_allinone' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since JV Allinone 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function allinone_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();
	$bg_attachment = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) )
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
			$classes[] = 'custom-background-white';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'Allinone-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'allinone_body_class' );

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since JV Allinone 1.0
 */
function allinone_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'allinone_content_width' );

/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since JV Allinone 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function allinone_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'allinone_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since JV Allinone 1.0
 */
function allinone_customize_preview_js() {
	wp_enqueue_script( 'Allinone-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
}
add_action( 'customize_preview_init', 'allinone_customize_preview_js' );
//add shortcode id widget
	function xyst_widget_shortcode( $atts ){
		$abc=extract( shortcode_atts( array(
			'id' => ''
		), $atts ) );
		
		
		$widget = '';
		global $wp_registered_sidebars, $wp_registered_widgets;
		if ( !isset($wp_registered_widgets[$id]) ) return ('Widget "'.$id.'" do not exists!');
		
		ob_start();
		
		$sidebar = array(
			'name' => __( 'Register Shortcode', 'jv_allinone' ),
			'id' => 'sidebar_register_shortcode',
			'before_widget' => '<aside id="%1$s" class="widget clearfix %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
	
			
		) ;//$wp_registered_sidebars['sidebar_register_shortcode'];
		
		$params = array_merge(
				array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
				(array) $wp_registered_widgets[$id]['params']
			);
		
		// Substitute HTML id and class attributes into before_widget
		$classname_ = '';
		foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
			if ( is_string($cn) )
				$classname_ .= '_' . $cn;
			elseif ( is_object($cn) )
				$classname_ .= '_' . get_class($cn);
		}
		$classname_ = ltrim($classname_, '_');
		$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);
	
		$params = apply_filters( 'dynamic_sidebar_params', $params );
	
		$callback = $wp_registered_widgets[$id]['callback'];
	
		do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );
	
		if ( is_callable($callback) ) {
			call_user_func_array($callback, $params);
			$did_one = true;
		}
		$widget = ob_get_contents();
		ob_end_clean();    
		
		return $widget;
	}
	JVLibrary::jvShortcode( 'jvwidget', 'xyst_widget_shortcode' );
	

	
//end add shortcode id widget


// Add Breadcrumb
function the_breadcrumb() {
    global $post;
    echo '<ul id="breadcrumbs" class="breadcrumb">';
    if (!is_home()) {
        echo '<li><a href="';
        echo home_url();
        echo '">';
		esc_attr_e( 'Home', 'jv_allinone' );
        echo '</a></li>';		
        if (is_category() || is_single()) {
            echo '<li>';
            the_category('-') ; 
			echo '</li>';
            if (is_single()) {
                echo '</li><li>';
                the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li><a href="'.esc_url(get_permalink($ancestor)).'" title="'.esc_attr(get_the_title($ancestor)).'">'.esc_html(get_the_title($ancestor)).'</a></li>';
                }
                echo $output;
                echo '<li > '.esc_html($title).'</li>';
            } else {
                echo '<li> '.esc_html(get_the_title()).'</li>';
            }
        }
    }
    if (is_tag()) { echo"<li>"; single_tag_title(); echo"</li>"; }
	
    if (is_day()) {	$day_s='F jS, Y';echo"<li> "; esc_attr_e( 'Archive for', 'jv_allinone' ); echo "<span>"; the_time($day_s); echo'</span></li>';}
    if (is_month()) { $month_s='F, Y'; echo"<li>"; esc_attr_e( 'Archive for', 'jv_allinone' );echo "<span>"; the_time($month_s); echo'</span></li>';}
    if (is_year()) { $year_s='Y'; echo"<li>"; esc_attr_e( 'Archive for', 'jv_allinone' );echo "<span>"; the_time($year_s); echo'</span></li>';}
    if (is_author()) {echo"<li>"; esc_attr_e( 'Author Archive', 'jv_allinone' ); echo'</li>';}
    if (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>"; esc_attr_e( 'Blog Archives', 'jv_allinone' ); echo'</li>';}
    if (is_search()) {echo"<li>";esc_attr_e( 'Search Results', 'jv_allinone' );  echo'</li>';}
    echo '</ul>';
}
// End Breadcrumb : the_breadcrumb(); 

// Woo loop_shop_per_page
add_filter( 'loop_shop_per_page', "acreate_function", 20 );
function acreate_function(){
		if( class_exists( 'JVLibrary' ) && $config = JVLibrary::getConfig() ) {
			$woo_display = $config->woo_display;
		//	$woo_column = $config->woo_column;
			return $woo_display;
		}
	
	return 15;
}


function allinone_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	wp_add_inline_style( 'Allinone-style', $css );
}
add_action( 'wp_enqueue_scripts', 'allinone_post_nav_background' );

/* Single product - part tab product */
function jvwoo_custom_tab ( $pid ){
	
	$text = '';
	
	if( ( $data = get_post_meta( $pid, 'frs_woo_product_tabs', true ) ) && is_array ( $data ) ) {
		
		foreach( $data as $tab ) {
		
			$content = apply_filters( 'the_content', $tab['content'] );
		
			$text .= apply_filters( 'woocommerce_custom_product_tabs_lite_heading', '<h4>' . $tab['title'] . '</h4>', $tab );
			$text .= apply_filters( 'woocommerce_custom_product_tabs_lite_content', $content, $tab );
		}
	}
	
	return $text; 
}
add_filter( 'jvwoo-custom-tab', 'jvwoo_custom_tab', 10, 1 );

function woocommerce_product_tabs( $tabs ){
	
	if( !count( $tabs ) ) { return false; }
	
	foreach( $tabs as $key => $tab ) {
			
		if( !is_array( $tab['callback'] ) ) { continue; }
		
		foreach( $tab['callback'] as $fn ) {
			
			if($fn === 'WCV_Vendor_Shop'){
				break;
				unset( $tabs[ $key ] );
			}
			else if( is_object($fn) && get_class( $fn ) === 'WooCommerceCustomProductTabsLite' || $fn === 'custom_product_tabs_panel_content' ) {
			
				unset( $tabs[ $key ] );
				break;
			}
		}
	}
	
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woocommerce_product_tabs', 99999);
/* End Single product - part tab product */

/* add filter render field search */
function render_form_search(){
	return function_exists( 'is_woocommerce' ) && is_woocommerce() ? do_shortcode("[woo_search]") : get_search_form();
}
add_filter( 'render_form_search', 'render_form_search', 10);

if( !function_exists( 'megamenu_javascript_localisation' ) ) {
	function megamenu_javascript_localisation( $params ) {
		
		if( isset( $params[ 'timeout' ] ) ) {
			$params[ 'timeout' ] = 100;
		}
		
		return $params;
	}
}
add_filter( 'megamenu_javascript_localisation', 'megamenu_javascript_localisation', 9999, 1 );

/* Change html widget category, archive*/
add_filter('wp_list_categories', 'add_span_cat_count');

function add_span_cat_count($links) {
$links = str_replace('</a> (', '<span class="catCounter">', $links);
$links = str_replace(')', '</span></a>', $links);
return $links;
}

add_filter('get_archives_link', 'archive_count_span');
function archive_count_span($links) {
$links = str_replace('</a>&nbsp;(', ' <span class="catCounter">', $links);
$links = str_replace(')', '</span></a>', $links);
return $links;
}



/*echo allione date: TRUE for full month else short*/
function jv_get_date_allinone($fullmonth){
	?>
	<h2>
		<span>
		<?php echo apply_filters( 'get_the_date', call_user_func( 'get_the_date', 'j') ); ?>
		</span>
		<span class="month">
		<?php echo ($fullmonth)? apply_filters( 'get_the_date', call_user_func( 'get_the_date', 'F') ): apply_filters( 'get_the_date', call_user_func( 'get_the_date', 'M') ); ?>
		</span>
		<span class="year">
			<?php echo apply_filters( 'get_the_date', call_user_func( 'get_the_date', 'Y' ) ); ?>
		</span>
	</h2>
	<?php
}





