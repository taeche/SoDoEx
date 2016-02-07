<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, JV Allinone already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
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

				<h1 class="entry-title"><?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'jv_allinone' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'jv_allinone' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'jv_allinone' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'jv_allinone' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'jv_allinone' ) ) . '</span>' );
						else :
							esc_attr_e( 'Archives', 'jv_allinone' );
						endif;
					?></h1>
                <?php endif; ?>
                <?php the_breadcrumb(); ?>
    </div>
</section>
<section id="maincontent" class="container layout-archive pageBlog">
	<div class="row">
		<div id="primary" class="site-content col-md-<?php if ( is_active_sidebar( 'sidebar-1' ) ) echo esc_attr('9'); else echo esc_attr('12'); ?>  ">
			<div id="content" class="pageBlog" role="main">
            

			<?php if ( have_posts() ) : ?>


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