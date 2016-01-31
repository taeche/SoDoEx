<?php
/**
 * The Template for displaying all single posts 
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
<section  id="block-breadcrumb">
    <div class="container">
		<h1 class="entry-title"><?php esc_attr_e( 'Blog', 'jv_allinone' ); ?></h1>
        <?php the_breadcrumb(); ?>
    </div>
</section>
<section id="maincontent" class="container layout-single">

	<div class="row">
		<div id="primary" class="site-content col-md-<?php if ( is_active_sidebar( 'sidebar-1' ) ) echo esc_attr('9'); else echo esc_attr('12'); ?>  ">
			<div id="content" role="main">
            

				<?php while ( have_posts() ) : the_post(); ?>

					<?php 	get_template_part( 'content', get_post_format() );	 ?>

					<nav class="nav-single">
						<span class="nav-previous"><?php previous_post_link( '%link', '<span >' . esc_attr_x('&larr;','Previous post link', 'jv_allinone' ) . '</span> %title' ); ?>&nbsp;</span>
						<span class="nav-next"><?php next_post_link( '%link', '%title <span >' . esc_attr_x('&rarr;','Next post link', 'jv_allinone' ) . '</span>' ); ?>&nbsp;</span>
					</nav>

                    <!-- .nav-single -->

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->


		
		<?php get_sidebar(); ?>
	</div>
	
</section>	


<?php get_footer();