<?php
/** 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/
?>
<div id="<?php echo esc_attr( $args['cssID'] ); ?>" class="rpwe-block <?php echo esc_attr( $args['css_class'] )?>">
	<?php if( !empty( $args['title']) ) {?>
    <h2 class="widgettitle"> <?php echo apply_filters( 'esc_html', $args['title'] ); ?> <?php if( !empty( $args['subtitle']) ) {?> <span class="sub-title"><?php echo apply_filters( 'esc_html', $args['subtitle'] ); ?></span> <?php } ?> </h2>
    
    <?php } ?>
	<div class="rpwe-div owl-carousel owl-carousel-item4 gridItem">

		<?php while ( $posts->have_posts() ) : $posts->the_post();
			// Thumbnails
			$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
			$img_url  = wp_get_attachment_url( $thumb_id ); // Get img URL.

			// Display the image url and crop using the resizer.
			$image    = rpwe_resize( $img_url, $args['thumb_width'], $args['thumb_height'], true );
			if( !( $args['thumb_width'] + $args['thumb_height'] ) ) { $image = $img_url; }

			
			// Start recent posts markup. ?>
			<div class="rpwe-li item">
				<?php if ( $args['date'] ) : ?>
					<div class="post-item-header">
						<div class="dateItem">
							<span class="d">
								<?php echo apply_filters( 'get_the_date', call_user_func( 'get_the_date', 'j') ); ?>
								</span>
							<span class="m">
								<?php echo apply_filters( 'get_the_date', call_user_func( 'get_the_date', 'M') ); ?>
								</span>
							<span class="y">
								<?php echo apply_filters( 'get_the_date', call_user_func( 'get_the_date', 'Y' ) ); ?></span>
						</div>
						<div class="bottom-border"></div>
					</div>
				<?php endif; ?>
				
				<div class="innerItem">
					<?php if ( $args['thumb'] ) : ?>
						<?php $audio = get_post_meta( get_the_ID(), 'audio_setting', true ); ?>
						<?php $video = get_post_meta( get_the_ID(), 'video_setting', true ); ?>
                        <?php $album = function_exists( 'jv_get_album' ) ? jv_get_album( get_the_ID() ) : ''; ?>
                        <?php $album = apply_filters( 'jv_get_album', $album ); ?>
						<?php if ( has_post_thumbnail() or $audio or $video or $album ) : ?>
							<?php if( $audio or $video or $album ) : ?>
								<?php if( $audio and $video ) : ?>
									<?php $media = $video  ?>
								<?php else : ?>
									<?php $media = $audio . $video . $album  ?>
								<?php endif;?>
								<?php echo apply_filters( 'the_content', $media )?>
							<?php else : ?>
								<a class="rpwe-img moduleItemImage" href="<?php echo esc_url( get_permalink() )?>"  rel="bookmark"><?php if ( $image ) : ?><img class="<?php echo esc_attr( $args['thumb_align'] ) ?> rpwe-thumb" src="<?php echo esc_url( $image )?>" alt="<?php echo esc_attr( get_the_title() )?>"><?php else : ?>
									<?php 
										get_the_post_thumbnail( get_the_ID(),
											array( $args['thumb_width'], $args['thumb_height'] ),
											array( 
												'class' => $args['thumb_align'] . ' rpwe-thumb the-post-thumbnail',
												'alt'   => esc_attr( get_the_title() )
											)
										);
									?>
								<?php endif;?>
								</a>
							<?php endif;?>
						<?php elseif ( function_exists( 'get_the_image' ) ) : ?>
							<?php get_the_image( array( 
								'height'        => (int) $args['thumb_height'],
								'width'         => (int) $args['thumb_width'],
								'image_class'   => esc_attr( $args['thumb_align'] ) . ' rpwe-thumb get-the-image',
								'image_scan'    => true,
								'echo'          => false,
								'default_image' => esc_url( $args['thumb_default'] )
							) ); ?>
						<?php elseif ( ! empty( $args['thumb_default'] ) ) : ?>
							<?php sprintf( '<a class="rpwe-img" href="%1$s" rel="bookmark"><img class="%2$s rpwe-thumb rpwe-default-thumb" src="%3$s" alt="%4$s" width="%5$s" height="%6$s"></a>',
								esc_url( get_permalink() ),
								esc_attr( $args['thumb_align'] ),
								esc_url( $args['thumb_default'] ),
								esc_attr( get_the_title() ),
								(int) $args['thumb_width'],
								(int) $args['thumb_height']
							); ?>
						<?php endif; ?>
					<?php endif; ?>
					<div class="content-item-description">
						<?php if (  isset( $args['dtitle'] ) && intval( $args['dtitle'] ) ) : ?>
                        <h3 class="rpwe-title"><a href="<?php echo esc_url( get_permalink() )?>" title="<?php echo sprintf( esc_attr__( 'Permalink to %s', 'jv_allinone' ), the_title_attribute( 'echo=0' ) )?>" rel="bookmark"><?php echo apply_filters( 'esc_html', get_the_title() ) ?></a></h3>
						<?php endif; ?>
						<div class="moduleItemIntrotext"><?php echo wp_trim_words( apply_filters( 'the_content', get_the_content() ), $args['length'], ' [&hellip;]' )?></div>
						<div class="clearfix readmore">
								<?php if( intval( $args[ 'dcomment' ] ) ) : 
									$comments = get_comments( array( 'post_id'=> get_the_ID(), 'count'=>1 ) );
									$clink = get_comments_link( get_the_ID() ); ?>
									<?php echo apply_filters( 'esc_html', sprintf('<a href="%1$s" class="moduleItemComments"><i class="fa fa-comments"></i> %2$s</a>', $clink, $comments) );
								endif;
								if ( $args['readmore'] ) : ?><a href="<?php echo esc_url( get_permalink() )?>" class="moduleItemReadMore"><?php echo apply_filters( 'rpwe_readmore', $args['readmore_text'] ) ?></a><?php endif;?>
						</div>
						
					</div>
					<div class="bottom-border"></div>
				
				</div>

			</div>

		<?php endwhile; ?>

	</div>

</div>