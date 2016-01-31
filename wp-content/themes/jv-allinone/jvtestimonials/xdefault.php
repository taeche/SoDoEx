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
?>
<div class="wtestimonials">
	<?php if( $xtitle ) : ?>
	<h2 class="vc_custom_heading title-center"><?php echo $xtitle; ?></h2>
	<?php endif; ?>
	<div class="jv-testimonials <?php echo esc_attr( $class_sfx ); ?>">
		<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
		<div class="item">
			<?php if( $scontent ) : ?>
			<div class="item-content"><?php the_content(); ?></div>
			<?php endif; ?>
			
			<div class="item-author">
				<?php if( $simage ) : ?>
				<span class="author">
					<?php the_post_thumbnail(); ?>
				</span>
				<?php endif; ?>
				
				<?php if( $stitle ) : ?>
				<h6><?php the_title(); ?></h6>
				<?php endif; ?>
				
				<?php if( $sexcerpt ) : ?>
				<p><i><?php the_excerpt(); ?></i> </p>
				<?php endif; ?>
				
			</div>
		</div>

		<?php endwhile; ?>
	</div>
</div>