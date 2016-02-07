<?php
/**
 *
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 
get_header();
 ?>
<section id="block-breadcrumb">
    <div class="container">
        <?php if ( is_home() && ! is_front_page() ) : ?>
                <h1 class="entry-title"><?php single_post_title(); ?></h1>
        <?php endif; ?>
         
        <?php the_breadcrumb(); ?>
        </div>
</section>
<main id="main" class="site-main " role="main">
	<div class="container">
    <div class="row">
		<div id="content" class="col-md-8 pageBlog">
	<?php if ( have_posts() ) : ?>
    
        <?php if ( is_home() && ! is_front_page() ) : ?>
                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
        <?php endif; ?>
    
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post();
    
            /*
             * Include the Post-Format-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content .php (where  is the Post Format name) and that will be used instead.
             */
            get_template_part( 'content', get_post_format() );
    
        // End the loop.
        endwhile;
    
        // Previous/next page navigation.
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous page', 'jv_allinone' ),
            'next_text'          => __( 'Next page', 'jv_allinone' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'jv_allinone' ) . ' </span>',
        ) );
    
    // If no content, include the "No posts found" template.
    else :
        get_template_part( 'content', 'none' );
    
    endif;
    ?>
    </div>
    
    <?php
	
	get_sidebar();
	?>
    </div>
    
	</div>
</main>
        
<?php get_footer();