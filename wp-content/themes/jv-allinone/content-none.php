<?php
/**
 *
 * The template for displaying a "No posts found" message 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 
?>

	<article id="post-0" class="post no-results not-found">
    
    
      <div class="blog-item-description">

			<h1 class="entry-title"><?php esc_attr_e( 'Nothing Found', 'jv_allinone' ); ?></h1>


		<div class="entry-content">
			<p><?php esc_attr_e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'jv_allinone' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
        </div>
        <div class="bottom-border"></div>
	</article><!-- #post-0 -->
