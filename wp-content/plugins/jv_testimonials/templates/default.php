<?php
/* Start the Loop */
?>
<div class="JVExtendedPosts <?php echo esc_attr( $class_sfx ); ?>">
	<?php if( $xtitle ) : ?>
	<h2 class="widgettitle"><?php echo $xtitle; ?></h2>
	<?php endif; ?>
	<div class=" row ">
		<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
		<div class="item col-sm-3 col-xs-6">

			
			<div class="item-author">
				<?php if( $simage ) : ?>
				<p class="author">
					<?php the_post_thumbnail(); ?>
				</p>
				<?php endif; ?>
				
				<?php if( $stitle ) : ?>
				<h6><?php the_title(); ?></h6>
				<?php endif; ?>
				
				<?php if( $sexcerpt ) : ?>
				<div class="excerpt"><?php the_excerpt(); ?></div>
				<?php endif; ?>
				
			</div>

			<?php if( $scontent ) : ?>
			<div class="item-content"><?php the_content(); ?></div>
			<?php endif; ?>

            
		</div>
		<?php endwhile; ?>
	</div>
</div>