<?php
/**
 * The template for displaying Category pages
 *
 * Used to display archive-type pages for posts in a category.
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

get_header(); ?>
<section  id="block-breadcrumb">
    <div class="container">
        		<?php if ( have_posts() ) : ?>

				<h1 class="entry-title"><?php printf( __( 'Category : %s', 'jv_allinone' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
                <?php endif; ?>
                <?php the_breadcrumb(); ?>
    </div>
</section>
<section id="maincontent" class="container layout-category  ">
	
	<div class="row">
		<div id="primary" class="site-content col-md-<?php if ( is_active_sidebar( 'sidebar-1' ) ) echo esc_attr('9'); else echo esc_attr('12'); ?>  ">
			<div id="content" class="pageBlog" role="main">
            

		<?php if ( have_posts() ) : ?>

				

			<?php if ( category_description() ) : // Show an optional category description ?>
				<div class="archive-meta">
				<h2><?php printf( __( '%s', 'jv_allinone' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h2>
				<?php echo apply_filters( 'esc_html', category_description() ); ?></div>
			<?php endif; ?>
			<!-- .archive-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called 
				 * (where  is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			endwhile;

			allinone_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->


		
		<?php get_sidebar(); ?>
	</div>
	
</section>	
<?php get_footer();