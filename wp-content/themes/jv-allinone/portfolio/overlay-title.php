<?php
/**
 * JV Gold functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API 
 * 
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
  *
*/ 
 ?>
<?php 
    $tags               = isset( $postmeta[ 'jvportfolio_tags' ] ) ? $postmeta[ 'jvportfolio_tags' ] : false ;
    $cols               = intval( isset( $postmeta[ 'jvportfolio_col' ] ) ? $postmeta[ 'jvportfolio_col' ] : 3 ) ;
    $stag               = isset( $postmeta[ 'jvportfolio_stag' ] ) && intval( $postmeta[ 'jvportfolio_stag' ] );
    $isfilter           = isset( $postmeta[ 'jvportfolio_filter' ] ) && intval( $postmeta[ 'jvportfolio_filter' ] );
    $isfuild            = isset( $postmeta[ 'jvportfolio_fluid' ] ) && intval( $postmeta[ 'jvportfolio_fluid' ] );
	$islink            	= isset( $postmeta[ 'jvportfolio_slink' ] ) && intval( $postmeta[ 'jvportfolio_slink' ] );
    $issort             = isset( $postmeta[ 'jvportfolio_sort' ] ) && count( $postmeta[ 'jvportfolio_sort' ] );
	
	$stitle            	= isset( $postmeta[ 'jvportfolio_stitle' ] ) && intval( $postmeta[ 'jvportfolio_stitle' ] );
	$sdate            	= isset( $postmeta[ 'jvportfolio_sdate' ] ) && intval( $postmeta[ 'jvportfolio_sdate' ] );
	$sdesc            	= isset( $postmeta[ 'jvportfolio_sdesc' ] ) && intval( $postmeta[ 'jvportfolio_sdesc' ] );
	
	
    $portfolio_options  = get_option( 'prtfl_options' );

?>
			
 <div class="jv-porfolio overlay-title">
				
				<?php $count = 0;
				if ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				} elseif ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				} else {
					$paged = 1;
				}
				$per_page = $showitems = isset( $postmeta[ 'jvportfolio_limit' ] ) ? intval( $postmeta[ 'jvportfolio_limit' ] ) : get_option( 'posts_per_page' ); 
                $postmeta[ 'jvportfolio_limit' ] = $per_page;
				$technologies = get_query_var( "technologies", "" );
                
                if( $tags ) { $technologies = $postmeta[ 'jvportfolio_tags' ]; }
                
                $executor_profile = get_query_var( "portfolio_executor_profile", "" );
                if ( "" != $technologies ) {
                    $args = array(
                        'post_type'         => 'portfolio',
                        'post_status'         => 'publish',
                        'orderby'             => $portfolio_options['prtfl_order_by'],
                        'order'                 => $portfolio_options['prtfl_order'],
                        'posts_per_page'    => $per_page,
                        'paged'             => $paged,
                        'tax_query'         => array(
                            array(
                                'taxonomy'     => 'portfolio_technologies',
                                'field'     => 'term_id',
                                'terms'     => $technologies
                            )
                        )
                    );
                } else if ( "" != $executor_profile ) {
                    $args = array(
                        'post_type'         => 'portfolio',
                        'post_status'         => 'publish',
                        'orderby'            => $portfolio_options['prtfl_order_by'],
                        'order'             => $portfolio_options['prtfl_order'],
                        'posts_per_page'     => $per_page,
                        'paged'             => $paged,
                        'tax_query'         => array(
                            array(
                                'taxonomy'     => 'portfolio_executor_profile',
                                'field'     => 'slug',
                                'terms'     => $executor_profile
                            )
                        )
                    );
                } else {
                    $args = array(
                        'post_type'            =>    'portfolio',
                        'post_status'        =>    'publish',
                        'orderby'            =>    $portfolio_options['prtfl_order_by'],
                        'order'                =>    $portfolio_options['prtfl_order'],
                        'posts_per_page'    =>    $per_page,
                        'paged'                =>    $paged
                    );
                }

                $second_query   = new WP_Query( $args );  
                $url_next       = apply_filters( 'jvportfolio_nextpage', get_pagenum_link( 2 ) );
                $request        = $second_query->request;

                if ( $second_query->have_posts() ) :
                
                    do_action( 'jvportfolio_ajax',  'content', $postmeta, intval( $second_query->found_posts ), $url_next );
                     
                    while ( $second_query->have_posts() ) : $second_query->the_post(); ?>
                    <?php 
                    $post = $second_query->post;
                    $terms = wp_get_object_terms( $post->ID, 'portfolio_technologies' );
                    $is_terms = is_array( $terms ) && 0 < count( $terms );
                    ?>
                        <div id="<?php echo apply_filters( 'jvportfolio_id', $post->ID )?>" class="item col-md-3"

			<?php if( $is_terms ) : ?>

			data-groups='<?php echo apply_filters( 'jvportoflio_getTermsSort', $terms )?>' 

			<?php endif; ?>

			data-name="<?php echo esc_attr( $post->post_name )?>" 

			data-date="<?php echo apply_filters( 'jvportoflio_getTimeSort', $post->post_date )?>">

				<div class="entry ">

					<?php $meta_values		=	get_post_custom( $post->ID );

					$post_thumbnail_id	=	get_post_thumbnail_id( $post->ID );

					if ( empty ( $post_thumbnail_id ) ) {

						$args = array(

							'post_parent'		=>	$post->ID,

							'post_type'			=>	'attachment',

							'post_mime_type'	=>	'image',

							'numberposts'		=>	1

						);

						$attachments		=	call_user_func( JVLibrary::getFnCheat( 'gc' ), $args );

						$post_thumbnail_id	=	key( $attachments );

					}

					$image			=	wp_get_attachment_image_src( $post_thumbnail_id, 'portfolio-thumb' );

					$image_large	=	wp_get_attachment_image_src( $post_thumbnail_id, 'large' );

					$image_alt		=	get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );

					$image_desc 	=	get_post($post_thumbnail_id);

					$image_desc		=	$image_desc->post_content;

					

					if ( '1' == get_option( 'prtfl_postmeta_update' ) ) {

						$post_meta		=	get_post_meta( $post->ID, 'prtfl_information', true);

						$date_compl		=	$post_meta['_prtfl_date_compl'];

						if ( ! empty( $date_compl ) && 'in progress' != $date_compl) {

							$date_compl		= explode( "/", $date_compl );

							$date_compl		= date( get_option( 'date_format' ), strtotime( $date_compl[1] . "-" . $date_compl[0] . '-' . $date_compl[2] ) );

						}

						$link			=	$post_meta['_prtfl_link'];

						$short_descr	=	$post_meta['_prtfl_short_descr'];

					} else {

						$date_compl		=	get_post_meta( $post->ID, '_prtfl_date_compl', true );

						if ( ! empty( $date_compl ) && 'in progress' != $date_compl) {

							$date_compl		=	explode( "/", $date_compl );

							$date_compl		=	date( get_option( 'date_format' ), strtotime( $date_compl[1] . "-" . $date_compl[0] . '-' . $date_compl[2] ) );

						}

						$link			=	get_post_meta( $post->ID, '_prtfl_link', true );

						$short_descr	=	get_post_meta( $post->ID, '_prtfl_short_descr', true );

					} ?>

                    <div class="overaly">
                           
							<div class="pfo-inner">

								<div class="pfo-inner2">
                                 <div class="triangle-corner"> <a href="#"><i class="icon icon-plus6"></i></a></div>

						<?php if( $stitle ) : ?>

						<h3  class="pfo-title">

								<a href="<?php echo $link; // echo get_permalink(); ?>" rel="bookmark"><?php echo get_the_title(); ?></a>

						</h3>

						<?php endif; ?>

                                <?php if( $stag && $is_terms ) : ?> 

                                <div class="gray-italic">

                                    <?php // echo $portfolio_options['prtfl_technologies_text_field'];

                                    $count = 0;

                                    foreach ( $terms as $term ) {

                                        if ( 0 < $count )

                                            echo ', ';

                                        echo $term->name;

                                        $count++;

                                    } ?>

                                </div><!-- .portfolio_terms -->

                                <?php endif;?>



                                            <?php

											if ( $sdesc ) { ?>

												<p><?php echo $short_descr; ?></p>

											<?php } ?>


											<?php if ( $sdate ) { ?>

                                                <p>
                                                    <span class="icon-calendar3"></span> <?php echo $date_compl; ?>

                                                </p>

                                            <?php }

                                            $user_id = get_current_user_id();

                                            if ( $islink and ($link !='') ) {

                                                if ( false !== parse_url( $link ) ) { ?>

                                                    <?php if ( ( 0 == $user_id && 0 == $portfolio_options['prtfl_link_additional_field_for_non_registered'] ) || 0 != $user_id ) { ?>

                                                        <p> <a href="<?php echo $link; ?>"><span class="icon-link"></span><?php echo $link; ?></a></p>

                                                    <?php } else { ?>

                                                        <p><span class="icon-link"></span> <?php echo $link; ?></p>

                                                    <?php }

                                                } else { ?>

                                                    <p><span class="icon-link"></span> <?php echo $link; ?></p>

                                                <?php }

                                            }

                                         ?>  
		 
								</div>

							</div> 

						</div>

                    <div class="por-img">
					
						<img src="<?php echo esc_url($image_large[0]); ?>" width="<?php echo esc_attr($portfolio_options['prtfl_custom_size_px'][0][0]); ?>" height="<?php echo esc_attr($portfolio_options['prtfl_custom_size_px'][0][1]); ?>" alt="<?php echo esc_attr($image_alt); ?>" />

					</div>
		

				</div><!-- .entry -->


			</div><!-- .portfolio_content -->
                    <?php endwhile; ?>
                    
                <?php endif; ?>
            </div><!-- #content -->    


