<?php
/**
 * Template Name: Blog-Sidebar-Left
 *
 * Description: JV Allinone loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere. * 
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 */
 
$col= get_post_meta($post->ID,'select_colum',true);

get_header(); ?>
<section  id="block-breadcrumb">
    <div class="container">
		<h1 class="entry-title"><?php the_title(); ?></h1>
        <?php the_breadcrumb(); ?>
    </div>
</section>
<section id="maincontent" class="container layout-category ">

	<div class="row">

		<?php get_sidebar(); ?>	
		<div id="primary" class="site-content col-md-<?php if ( is_active_sidebar( 'sidebar-1' ) ) echo esc_attr('9'); else echo esc_attr('12'); ?>  ">
			<div id="content" class="pageBlog" role="main">
				<?php 
					get_template_part( 'blog', 'item' );
				?>	
			</div><!-- #content -->
		</div><!-- #primary -->


		

	</div>
	
</section>	

<?php get_footer();