<?php
/**
 * @package Allinone
 * @subpackage JV_Allinone
 * @since JV Allinone 1.0
 */
?>
<div <?php echo esc_attr( ! empty( $args['cssID'] ) ? 'id="' . sanitize_html_class( $args['cssID'] ) . '"' : '' )?> class="rpwe-block <?php echo esc_attr( ! empty( $args['css_class'] ) ? '' . sanitize_html_class( $args['css_class'] ) . '' : '' )?>">
	<?php if( !empty( $args['title']) ) :?>
    <h2 class="widgettitle"> <?php echo apply_filters( 'esc_html', $args['title'] ); ?> 
    <?php if( !empty( $args['subtitle']) ) :?> 
        <span class="sub-title"><?php echo apply_filters( 'esc_html', $args['subtitle'] ); ?></span> 
    <?php endif; ?> </h2>
 <?php endif; ?>
	<div class="rpwe-div focus-top blog-photo-only blog-grid row ">

		<?php 
			$count=0;
			$row_count=6;
			while ( $posts->have_posts() ) : $posts->the_post();
			$count++;
			// Thumbnails
			$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
			$img_url  = wp_get_attachment_url( $thumb_id ); // Get img URL.

			// Display the image url and crop using the resizer.
			$image    = rpwe_resize( $img_url, $args['thumb_width'], $args['thumb_height'], true );
			if( !( $args['thumb_width'] + $args['thumb_height'] ) ) { $image = $img_url; }
			
			?>
				<div class="innerItem  <?php  echo ($count==1)? 'col-xs-12 top-item' : 'col-xs-6 more-item'; ?>">
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
								<a class="rpwe-img moduleItemImage zoomEffect" href="<?php echo esc_url( get_permalink() )?>"  rel="bookmark"><?php if ( $image ) : ?><img class="<?php echo esc_attr( $args['thumb_align'] ) ?> rpwe-thumb" src="<?php echo esc_url( $image )?>" alt="<?php echo esc_attr( get_the_title() )?>"><?php else : ?>
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
					<!--<div class="item-desc">	-->

						<?php  if ( $count == 1 ) : ?>
						<div class="item-desc">
							<div class="readmore-comment pull-right">
									<?php if( intval( $args[ 'dcomment' ] ) ) : 
										$comments = get_comments( array( 'post_id'=> get_the_ID(), 'count'=>1 ) );
										$clink = get_comments_link( get_the_ID() ); ?>
										<?php echo sprintf('<a href="%1$s" class="moduleItemComments text-overlay"><i class="fa fa-comments"></i> %2$s</a>', $clink, $comments);
									endif;
									if ( $args['readmore'] ) : ?><a href="<?php echo esc_url( get_permalink() )?>" class="moduleItemReadMore text-overlay"><?php echo apply_filters( 'rpwe_readmore', $args['readmore_text'] ) ?></a><?php endif;?>
							</div><!-- end readmore-comment -->
							<?php if ( $args['date'] ) : ?>
								<h5 class="dateItem text-overlay">
									<?php if( $idate = apply_filters( 'rpwe_icon', 'date', $args[ 'idate' ] ) ) : ?>
										<i class="<?php echo $idate; ?>"></i>
									<?php endif; ?>
									<?php echo apply_filters( 'esc_html', call_user_func( 'get_the_date', 'd M Y') ); ?>
								</h5>
							<?php endif; ?>
							
							<?php if ( intval( $args['dcategory'] )||  intval( $args['dtag'] ) || intval( $args['dauthor'] ) ):?>
								<div class="meta-details text-overlay">

									<?php if ( intval( $args['dcategory'] ) && ( $cposts = get_the_category( get_the_ID() ) ) && count( $cposts ) ) : ?>
										<span class="categories">
		                                
		                                <?php if( $icategory = apply_filters( 'rpwe_icon', 'category', $args[ 'icate' ] ) ) : ?>
		                                <i class="<?php echo $icategory; ?>"></i>
		                                <?php endif; ?>
										<?php foreach( $cposts as $cpost) : ?>
											<a class="category-name" href="<?php echo get_category_link( $cpost->term_id )?>">
												<?php echo esc_html( $cpost->cat_name ) ?>
											</a>
										<?php endforeach;?>
										</span>
									<?php endif; ?>
										
		                            <?php if ( intval( $args['dtag'] ) && ( $tags_post = get_the_tags( get_the_ID() ) ) && count( $tags_post ) ) : ?>
		                                <span class="tags">
		                                <?php if( $itag = apply_filters( 'rpwe_icon', 'tag', $args[ 'itag' ] ) ) : ?>
		                                <i class="<?php echo $itag; ?>"></i>
		                                <?php endif; ?>
		                                <?php foreach( $tags_post as $tag_post) : ?>
		                                    <span class="tag-name">
		                                          <?php echo esc_html( $tag_post->name ) ?>
		                                    </span>
										<?php endforeach;?>
										</span>
									<?php endif; ?>  


									<?php if ( intval( $args['dauthor'] ) ) : ?>
		                            	<span class="author"> 
		                                <?php if( $iauthor = apply_filters( 'rpwe_icon', 'author', $args[ 'iauthor' ] ) ) : ?>
		                                <i class="<?php echo $iauthor; ?>"></i>
		                                <?php endif; ?>
										<?php echo get_the_author_link(); ?>
		                                </span>
									<?php endif;?> 

								</div> <!-- end text-overlay -->
							<?php endif; ?>

							
						</div>  <!-- end item-desc -->

						<?php if (  isset( $args['dtitle'] ) && intval( $args['dtitle'] ) ) : ?>
                        <h3 class="rpwe-title text-overlay"><a href="<?php echo esc_url( get_permalink() )?>" title="<?php echo sprintf( esc_attr__( 'Permalink to %s', 'jv_allinone' ), the_title_attribute( 'echo=0' ) )?>" rel="bookmark"><?php echo esc_attr( get_the_title() ) ?></a></h3>
						<?php endif; ?>
						

						<?php if (  isset( $args['excerpt'] ) && intval( $args['excerpt'] ) ) : ?>
							<div class="item-excerpt text-overlay">
								<?php echo wp_trim_words( apply_filters( 'the_content', get_the_content() ), $args['length'], ' [&hellip;]' )?>
							</div>
						<?php endif; ?>   

						<?php endif;//endif count == 1 ?>
					
					<!--</div>--> <!-- end content-item-description -->
				</div> <!-- end innerItem featured-->
				
		<?php endwhile; ?>
 		
	</div>

</div>
