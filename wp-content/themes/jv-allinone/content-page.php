<?php
/**
 * The template used for displaying page content in page.php 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
		<header class="entry-header">
			<?php if ( ! is_page_template( 'page-templates/front-page.php' ) ) : ?>
			<?php the_post_thumbnail(); ?>
			<?php endif; ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'jv_allinone' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<footer class="content-page entry-meta">
			<?php edit_post_link( __( 'Edit', 'jv_allinone' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
