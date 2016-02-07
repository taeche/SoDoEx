<?php
/**
 *
 * The template for displaying Search Results pages 
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

				<h1 class="entry-title"><?php printf( __( 'Search Results for : %s', 'jv_allinone' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
                <?php endif; ?>
                <?php the_breadcrumb(); ?>
    </div>
</section>
<section id="maincontent" class="container layout-category pageBlog">

	<div class="row">
		<div id="primary" class="site-content col-md-<?php if ( is_active_sidebar( 'sidebar-1' ) ) echo esc_attr( '8' ); else echo esc_attr( '12' ); ?>  ">
			<div id="content" class="pageBlog" role="main">
            

		<?php if ( have_posts() ) : ?>

			<?php // allinone_content_nav( 'nav-above' ); ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php allinone_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<article id="post-0" class="post no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php esc_attr_e( 'Nothing Found', 'jv_allinone' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php esc_attr_e( 'Sorry&#44; but nothing matched your search criteria. Please try again with some different keywords.', 'jv_allinone' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->


		
		<?php get_sidebar(); ?>
	</div>
	
</section>	
<?php get_footer();