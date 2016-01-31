<?php
/**
 * Various functions used by the plugin.
 *
 * @package    Recent_Posts_Widget_Extended
 * @since      0.9.4
 * @author     Satrya
 * @copyright  Copyright (c) 2014, Satrya
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Sets up the default arguments.
 * 
 * @since  0.9.4
 */
 
if( !class_exists( 'rpewExtend' ) ) {
    
    class rpewExtend {
        
        public static function calbum( $d = '' ) {
            
            return $d ? "<div class='thumbnail_large'>{$d}</div>" : '';
        }
		
		public static function readmore( $t = '' ) {
			
			return $t ? $t : esc_attr_e( 'Read more', 'rpwe' );
		}
        
        public static function icon( $t = '', $i = '' ) {
            $icons = array(
                'date'      => 'icon-calendar3',
                'comment'   => 'fa fa-comments',
                'tag'       => 'icon-tags2',
                'author'    => 'icon-user22',
                'category'  => 'icon-folder2',
            );
            if( empty( $t ) || !isset( $icons[ $t ] ) || $i === '0' ){ return ''; }
            
            if( empty( $i ) ){ return $icons[ $t ]; }
            
            return $i;
        }    
    }    
 }
 
add_filter( 'jv_get_album',  'rpewExtend::calbum', 10, 1 );
add_filter( 'rpwe_readmore',  'rpewExtend::readmore', 10, 1 );
                                                            
add_filter( 'rpwe_icon',  'rpewExtend::icon', 10, 2 );  
   
function rpwe_get_default_args() {

	$css_defaults = ".rpwe-block ul{\nlist-style: none !important;\nmargin-left: 0 !important;\npadding-left: 0 !important;\n}\n\n.rpwe-block li{\nborder-bottom: 1px solid #eee;\nmargin-bottom: 10px;\npadding-bottom: 10px;\nlist-style-type: none;\n}\n\n.rpwe-block a{\ndisplay: inline !important;\ntext-decoration: none;\n}\n\n.rpwe-block h3{\nbackground: none !important;\nclear: none;\nmargin-bottom: 0 !important;\nmargin-top: 0 !important;\nfont-weight: 400;\nfont-size: 12px !important;\nline-height: 1.5em;\n}\n\n.rpwe-thumb{\nborder: 1px solid #eee !important;\nbox-shadow: none !important;\nmargin: 2px 10px 2px 0;\npadding: 3px !important;\n}\n\n.rpwe-summary{\nfont-size: 12px;\n}\n\n.rpwe-time{\ncolor: #bbb;\nfont-size: 11px;\n}\n\n.rpwe-alignleft{\ndisplay: inline;\nfloat: left;\n}\n\n.rpwe-alignright{\ndisplay: inline;\nfloat: right;\n}\n\n.rpwe-aligncenter{\ndisplay: block;\nmargin-left: auto;\nmargin-right: auto;\n}\n\n.rpwe-clearfix:before,\n.rpwe-clearfix:after{\ncontent: \"\";\ndisplay: table !important;\n}\n\n.rpwe-clearfix:after{\nclear: both;\n}\n\n.rpwe-clearfix{\nzoom: 1;\n}\n";

	$defaults = array(
		'title'             => esc_attr__( '', 'rpwe' ),
		'subtitle'             => esc_attr__( '', 'rpwe' ),
		'title_url'         => '',

		'limit'            => 5,
		'offset'           => 0,
		'order'            => 'DESC',
		'orderby'          => 'date',
		'cat'              => array(),
		'tag'              => array(),
		'taxonomy'         => '',
		'post_type'        => array( 'post' ),
		'post_status'      => 'publish',
		'ignore_sticky'    => 1,

		'excerpt'          => false,
		'length'           => 10,
		'thumb'            => true,
		'thumb_height'     => 45,
		'thumb_width'      => 45,
		'thumb_default'    => 'http://placehold.it/45x45/f0f0f0/ccc',
		'thumb_align'      => 'rpwe-alignleft',
		'date'             => false,
		'date_relative'    => false,
		'readmore'         => false,
		'readmore_text'    => __( 'Read More &raquo;', 'rpwe' ),

		'styles_default'   => true,
		'css'              => $css_defaults,
		'cssID'            => '',
		'css_class'        => '',
		'before'           => '',
		'after'            => '',
		'tmpl'			   => '',
		'col'			   => '1',
        'dtitle'           => '0',
		'dcomment'		   => '0',
		'dauthor'		   => '0',
        'dcategory'        => '0',
        'dtag'             => '0',
        'idate'            => '',
        'icomment'         => '',
        'iauthor'          => '',
        'icate'            => '',
		'itag'		       => '',
	);

	// Allow plugins/themes developer to filter the default arguments.
	return apply_filters( 'rpwe_default_args', $defaults );

}

/**
 * Outputs the recent posts.
 * 
 * @since  0.9.4
 */
function rpwe_recent_posts( $args = array() ) {
	echo rpwe_get_recent_posts( $args );
}

/**
 * Generates the posts markup.
 *
 * @since  0.9.4
 * @param  array  $args
 * @return string|array The HTML for the random posts.
 */
function rpwe_get_recent_posts( $args = array() ) {

	// Set up a default, empty variable.
	$html = '';

	// Merge the input arguments and the defaults.
	$args = wp_parse_args( $args, rpwe_get_default_args() );

	// Extract the array to allow easy use of variables.
	extract( $args );

	// Allow devs to hook in stuff before the loop.
	do_action( 'rpwe_before_loop' );
	
	// Get the posts query.
	$posts = rpwe_get_posts( $args );
	
	
	if ( $posts->have_posts() ) :
		
		
		$tmpl = apply_filters( 'rpwe_tmpl', $args[ 'tmpl' ] );
        
        $template_name = "{$tmpl}.php";
                    
        if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
            
            $tmpl = STYLESHEETPATH . '/' . $template_name; 
            
        } else if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
            
            $tmpl = TEMPLATEPATH . '/' . $template_name;
            
        }
		
		if( file_exists( $tmpl ) ) {
			
			ob_start();
			
			require( $tmpl ); 
			
			$html = ob_get_clean();
		}else {

		?>
		<?php ob_start();?>
		<div <?php echo( ! empty( $args['cssID'] ) ? 'id="' . sanitize_html_class( $args['cssID'] ) . '"' : '' )?> class="rpwe-block  <?php echo( ! empty( $args['css_class'] ) ? '' . sanitize_html_class( $args['css_class'] ) . '' : '' )?>">
        
        
			<?php if( !empty( $args['title']) ) {?>
        	<h2 class="widgettitle"> <?php echo apply_filters( 'esc_html', $args['title'] ); ?> <?php if( !empty( $args['subtitle']) ) {?> <span class="sub-title"><?php echo apply_filters( 'esc_html', $args['subtitle'] ); ?></span> <?php } ?> </h2>
            <?php } ?>

			<div class="rpwe-div jv-posts <?php if ($args['col'] > 1 ) echo 'row'; ?>">

				<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
				
				

					
					<?php // Thumbnails
					$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
					$img_url  = wp_get_attachment_url( $thumb_id ); // Get img URL.

					// Display the image url and crop using the resizer.
					$image    = rpwe_resize( $img_url, $args['thumb_width'], $args['thumb_height'], true );
					if( !( $args['thumb_width'] + $args['thumb_height'] ) ) { $image = $img_url; }
					?>

					
                   
                     <?php if ($args['col'] > 1 ) { ?>
                    <div class="<?php echo  'col-md-'.(12/sanitize_html_class( $args['col']) );  ?>">
                     <?php } ?>
                    
                    
						<div class="item">
						
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
									
										<a class="rpwe-img moduleItemImage" href="<?php echo esc_url( get_permalink() )?>"  rel="bookmark">
										<?php if ( $image ) : ?>
											<img class="<?php echo esc_attr( $args['thumb_align'] ) ?> rpwe-thumb" src="<?php echo esc_url( $image )?>" alt="<?php echo esc_attr( get_the_title() )?>">
										<?php else : ?>
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
						



							<div class="content-item-description">

							<?php if (  isset( $args['dtitle'] ) && intval( $args['dtitle'] ) ) : ?>
														<h4 class="post-title">
															<a href="<?php echo esc_url( get_permalink() )?>" title="<?php echo sprintf( esc_attr__( 'Permalink to %s', 'rpwe' ), the_title_attribute( 'echo=0' ) )?>" rel="bookmark"><?php echo esc_attr( get_the_title() )?></a>
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
										<span class="d"><?php echo apply_filters( 'get_the_date', get_the_date( 'd' ) )?></span>
										<span class="m"><?php echo apply_filters( 'get_the_date', get_the_date( 'M' ) )?></span>
										<span class="y"><?php echo apply_filters( 'get_the_date', get_the_date( 'Y' ) )?></span>
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
							<?php endif; ?>
								
                            <?php if ( intval( $args['dtag'] ) && ( $tags_post = get_the_tags( get_the_ID() ) ) && count( $tags_post ) ) : ?>
                                <span class="categories">
                                
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
                            	<span> 
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
														<div class="moduleItemIntrotext">
															
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
                    
                    
                    

				<?php endwhile; ?>

			</div>

		</div>
	<?php 
		$html = ob_get_clean();
		}
	endif;

	// Restore original Post Data.
	wp_reset_postdata();

	// Allow devs to hook in stuff after the loop.
	do_action( 'rpwe_after_loop' );
	
	// Return the  posts markup.
	return $args['before'] . apply_filters( 'rpwe_markup', $html ) . $args['after'];

}

/**
 * The posts query.
 *
 * @since  0.0.1
 * @param  array  $args
 * @return array
 */
function rpwe_get_posts( $args = array() ) {

	// Query arguments.
	$query = array(
		'offset'              => $args['offset'],
		'posts_per_page'      => $args['limit'],
		'orderby'             => $args['orderby'],
		'order'               => $args['order'],
		'post_type'           => $args['post_type'],
		'post_status'         => $args['post_status'],
		'ignore_sticky_posts' => $args['ignore_sticky'],
	);

	// Limit posts based on category.
	if ( ! empty( $args['cat'] ) ) {
		$query['category__in'] = $args['cat'];
	}

	// Limit posts based on post tag.
	if ( ! empty( $args['tag'] ) ) {
		$query['tag__in'] = $args['tag'];
	}

	/**
	 * Taxonomy query.
	 * Prop Miniloop plugin by Kailey Lampert.
	 */
	if ( ! empty( $args['taxonomy'] ) ) {

		parse_str( $args['taxonomy'], $taxes );

		$operator  = 'IN';
		$tax_query = array();
		foreach( array_keys( $taxes ) as $k => $slug ) {
			$ids = explode( ',', $taxes[$slug] );
			if ( count( $ids ) == 1 && $ids['0'] < 0 ) {
				// If there is only one id given, and it's negative
				// Let's treat it as 'posts not in'
				$ids['0'] = $ids['0'] * -1;
				$operator = 'NOT IN';
			}
			$tax_query[] = array(
				'taxonomy' => $slug,
				'field'    => 'id',
				'terms'    => $ids,
				'operator' => $operator 
			);
		}

		$query['tax_query'] = $tax_query;

	}

	// Allow plugins/themes developer to filter the default query.
	$query = apply_filters( 'rpwe_default_query_arguments', $query );

	// Perform the query.
	$posts = new WP_Query( $query );
	
	return $posts;

}

/**
 * Custom Styles.
 *
 * @since  0.8
 */
function rpwe_custom_styles() {
	?>

	<?php
}