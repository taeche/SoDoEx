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
<div class="wtestimonials slider speech style2">
	<?php if( $xtitle ) : ?>
		<h2 class="vc_custom_heading title-center widgettitle"><?php echo $xtitle; ?>
		<?php if( $xsubtitle ) : ?>
		<span class="sub-title"><?php echo $xsubtitle; ?></span>
		<?php endif; ?>
		</h2>
	<?php endif; ?>
	<div class="jv-testimonials  <?php echo esc_attr( $class_sfx ); ?>">
		<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
		<div class="testimonials-item">
			<?php if( $scontent ) : ?>
			<div class="item-quote">
				<?php the_content(); ?>
			</div>
			<?php endif; ?>
			<div class="bottom-border"></div>
			<div class="item-author">
				<?php if( $simage ) : ?>
				<span class="author">
					<?php the_post_thumbnail(); ?>
				</span>
				<?php endif; ?>
				
				<?php if( $stitle ) : ?>
				<h3><?php the_title(); ?></h3>
				<?php endif; ?>
				
				<?php if( $sexcerpt ) : ?>
				<?php the_excerpt(); ?>
				<?php endif; ?>
			</div> <!--testimonials-autor -->

		</div>
		<?php endwhile; ?>
	
	</div>
	
	

</div>