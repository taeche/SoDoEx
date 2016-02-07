<?php
/**
 * Template Name: Blog-No-Sidebar
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

get_header(); ?>
<section  id="block-breadcrumb">
    <div class="container">
		<h1 class="entry-title"><?php the_title(); ?></h1>
        <?php the_breadcrumb(); ?>
    </div>
</section>
<section id="maincontent" class="container layout-no-sidebar ">
			<div id="content" class="pageBlog" role="main">
				<?php 
					get_template_part( 'blog', 'item' );
				?>	
			</div><!-- #content -->
</section>	

<?php get_footer();