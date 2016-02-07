<?php
/**
 * @package Hosting
 * @subpackage JV_Hosting
 * @since JV Hosting 1.0
 */
?>
<div <?php echo( ! empty( $args['cssID'] ) ? 'id="' . sanitize_html_class( $args['cssID'] ) . '"' : '' )?> class="rpwe-block  <?php echo( ! empty( $args['css_class'] ) ? '' . sanitize_html_class( $args['css_class'] ) . '' : '' )?>">
        
			<?php if( !empty( $args['title']) ) {?>
        	<h2 class="widgettitle"> <?php echo apply_filters( 'esc_html', $args['title'] ); ?> <?php if( !empty( $args['subtitle']) ) {?> <span class="sub-title"><?php echo apply_filters( 'esc_html', $args['subtitle'] ); ?></span> <?php } ?> </h2>
            <?php } ?>

			<div class="rpwe-div jv-posts-video <?php if ($args['col'] > 1 ) echo 'row'; ?>">

				<?php $i=0;
				while ( $posts->have_posts() ) : $posts->the_post(); ?>
									
					<?php // Thumbnails
					$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
					$img_url  = wp_get_attachment_url( $thumb_id ); // Get img URL.

					// Display the image url and crop using the resizer.
					$image    = rpwe_resize( $img_url, $args['thumb_width'], $args['thumb_height'], true );
					if( !( $args['thumb_width'] + $args['thumb_height'] ) ) { $image = $img_url; }
					?>
				
                     <?php if ($args['col'] > 1 ) { ?>
                    <div class="<?php echo  'col-xs-12 col-sm-'.(12/sanitize_html_class( $args['col']) );  ?>">
                     <?php } ?>
                                        
						<div class="item">
							<div class="video-wrap">
							<?php $random = rand(); ?>					

							<?php if ( $args['thumb'] ) : ?>

								<?php $album = function_exists( 'jv_get_album' ) ? jv_get_album( get_the_ID() ) : ''; ?>
                                <?php $album = apply_filters( 'jv_get_album', $album ); ?>
                              
							  <?php if ( has_post_thumbnail() or $album ) : ?>
                                
									<?php if(  $album ): 
										echo apply_filters( 'the_content', $album );

										else : ?>
										<div class="img-wrap">
										  <a href="<?php echo esc_url( get_permalink() )?>" > 
											<img class="<?php echo esc_attr( $args['thumb_align'] ) ?> rpwe-thumb" src="<?php echo esc_url( $image )?>" alt="<?php echo esc_attr( get_the_title() )?>"></a>
										  </div> 
								
									<?php endif;?>
								
								<?php elseif ( function_exists( 'get_the_image' ) ) : ?>
									<?php echo get_the_image( array( 
										'height'        => (int) $args['thumb_height'],
										'width'         => (int) $args['thumb_width'],
										'image_class'   => esc_attr( $args['thumb_align'] ) . ' rpwe-thumb get-the-image',
										'image_scan'    => true,
										'echo'          => false,
										'default_image' => esc_url( $args['thumb_default'] )
									) );?>

								
								<?php elseif ( ! empty( $args['thumb_default'] ) ) : ?>
									<?php echo sprintf( '<a class="rpwe-img" href="%1$s" rel="bookmark"><img class="%2$s rpwe-thumb rpwe-default-thumb" src="%3$s" alt="%4$s" width="%5$s" height="%6$s"></a>',
										esc_url( get_permalink() ),
										esc_attr( $args['thumb_align'] ),
										esc_url( $args['thumb_default'] ),
										esc_attr( get_the_title() ),
										(int) $args['thumb_width'],
										(int) $args['thumb_height']
									);

								endif;

							endif; ?>

							<div class="caption">
                            <a class="btn-video"   href="<?php echo esc_url( get_permalink() )?>"  > <i class="icon-video22"></i></a>

							<?php if (  isset( $args['dtitle'] ) && intval( $args['dtitle'] ) ) : ?>
								<h4 class="rpwe-title">
									<a href="<?php echo esc_url( get_permalink() )?>" title="<?php echo sprintf( esc_attr__( 'Permalink to %s', 'jv_allinone' ), the_title_attribute( 'echo=0' ) )?>" rel="bookmark"><?php echo esc_attr( get_the_title() )?> </a>
								</h4>
							<?php endif; ?>                            

							<?php 
							if ( 
								( $args['date'] ) or
								( intval( $args['dcategory'] ) && ( $cposts = get_the_category( get_the_ID() ) ) && count( $cposts ) ) or
								( intval( $args['dauthor'] ) ) or
								( intval( $args[ 'dcomment' ] ) )

							){?>
							<div class="ItemLinks ItemLinksInline ">
									<?php if ( $args['date'] ) :?>
									<span>
                                    	<?php if( $idate = apply_filters( 'rpwe_icon', 'date', $args[ 'idate' ] ) ) : ?>
                                        <i class="<?php echo $idate; ?>"></i>
                                        <?php endif; ?>
										<?php echo apply_filters( 'esc_html', get_the_date( ) );?>
									</span>
									<?php endif; ?>

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
							<?php endif;?>        

							<?php if ( intval( $args['dauthor'] ) ) : ?>
                            	<span class="author"> 
                                <?php if( $iauthor = apply_filters( 'rpwe_icon', 'author', $args[ 'iauthor' ] ) ) : ?>
                                <i class="<?php echo $iauthor; ?>"></i>
                                <?php endif; ?>
								<?php echo get_the_author_link(); ?>
                                </span>
							<?php endif;?> 

								<?php if( intval( $args[ 'dcomment' ] ) ) : 
									$comments = get_comments( array( 'post_id'=> get_the_ID(), 'count'=>1 ) );
									$clink = get_comments_link( get_the_ID() ); 
                                    $icomment = apply_filters( 'rpwe_icon', 'comment', $args[ 'icomment' ] )?>
									<?php echo sprintf('<a href="%1$s" class="moduleItemComments"><i class="%2$s"></i> %3$s</a>', $clink, $icomment, $comments);
								endif;
								?>                                                                           

							</div>
														
							<?php } ?>

							<?php if (  isset( $args['excerpt'] ) && intval( $args['excerpt'] ) ) : ?>
    							<div class="item-excerpt">
    								<?php echo wp_trim_words( apply_filters( 'the_content', get_the_content() ), $args['length'], ' [&hellip;]' )?>
    							</div>
    						<?php endif; ?>   

								<?php if ( $args['readmore'] ) : ?>
								<div class="readmore">
									<a href="<?php echo esc_url( get_permalink() )?>" class="moduleItemReadMore">
										<?php echo apply_filters( 'rpwe_readmore', $args['readmore_text'] ) ?>
									</a>
								</div>
								<?php endif;?>

							</div>
		
						</div>
                        </div>
						
                     <?php if ($args['col'] > 1 ) { ?>
                    </div>
                     <?php } ?>

				<?php 
					$i++;
				endwhile; ?>

			</div>

		</div>