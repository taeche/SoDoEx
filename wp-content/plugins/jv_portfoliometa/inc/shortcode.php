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

    <div id="maincontent" class="page-portfolio">
        <div id="frm-portfolio" class="<?php echo apply_filters( 'jvportfolio_fluid', $isfuild )?>">
            <?php if( $isfilter + $issort ) : ?>
            <div class="clearfix topPortfolio hidden-xs">
                <?php if( $isfilter && $tags ) : ?>
                <div class="portfolioFilter ">
                    <div class="filter-link">
                        <a class="current" data-filter="all" href="javascript:"><?php echo esc_attr_e( 'All categories', 'jvportfolio' )?></a><div class="bottom-border"></div>
                    </div>
                    <?php 
                    $otags = apply_filters( 'jvportoflio_getcategories', array( 'include' => $tags ) );
                    foreach( $otags as $titem ):
                    ?>                        
                    <div class="filter-link">
                        <a class="" data-filter="<?php echo $titem->slug?>" href="javascript:"><?php echo $titem->name?></a><div class="bottom-border"></div>
                    </div>
                    <?php endforeach ; ?>
                </div>
                <?php endif;?>
                
                <?php if( $issort ) : ?>
                <div class="portfolioSort ">
                	<div class="css-select">
                      <select name="csort" id="csort">
                      <option value=""><?php echo esc_attr_e( 'Default', 'jvportfolio' )?></option>
                      <?php foreach( $postmeta[ 'jvportfolio_sort' ] as $item ) : ?>
                      <option value="<?php echo esc_attr( $item )?>"><?php echo esc_attr_e( $item, 'jvportfolio' )?></option>
                      <?php endforeach; ?>
                      </select>
                      </div>
                      <div class="bottom-border"></div>
                </div>        
                <?php endif; ?>
                
            </div>
            <?php endif;?>
			
            <div  class="box-portfolio row">
				
				<?php $count = 0;
				if ( get_query_var( 'paged' ) ) {
					$paged = get_query_var( 'paged' );
				} elseif ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				} else {
					$paged = 1;
				}
				$per_page = $showitems = isset( $postmeta[ 'jvportfolio_limit' ] ) ? intval( $postmeta[ 'jvportfolio_limit' ] ) : get_option( 'posts_per_page' ); 
                $postmeta[ 'jvportfolio_limit' ] = $per_page;
				$technologies = isset( $wp_query->query_vars["technologies"] ) ? $wp_query->query_vars["technologies"] : "";
                
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
                        <div id="<?php echo apply_filters( 'jvportfolio_id', $post->ID )?>" class="portfolio_content itemMasonry pfo-item col-xs-6 <?php echo apply_filters( 'jvportfolio_col', $cols ) ?>" <?php if( $is_terms ) : ?> data-groups='<?php echo apply_filters( 'jvportoflio_getTermsSort', $terms )?>' <?php endif; ?> data-name="<?php echo esc_attr( $post->post_name )?>" data-date="<?php echo apply_filters( 'jvportoflio_getTimeSort', $post->post_date )?>">
                            <div class="entry">
                                <?php $meta_values        =    get_post_custom( $post->ID );
                                $post_thumbnail_id    =    get_post_thumbnail_id( $post->ID );
                                if ( empty ( $post_thumbnail_id ) ) {
                                    $args = array(
                                        'post_parent'        =>    $post->ID,
                                        'post_type'            =>    'attachment',
                                        'post_mime_type'    =>    'image',
                                        'numberposts'        =>    1
                                    );
                                    $attachments        =    get_children( $args );
                                    $post_thumbnail_id    =    key( $attachments );
                                }
                                $image            =    wp_get_attachment_image_src( $post_thumbnail_id, 'portfolio-thumb' );
                                $image_large    =    wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
                                $image_alt        =    get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
                                $image_desc     =    get_post($post_thumbnail_id);
                                $image_desc        =    $image_desc->post_content;
                                
                                if ( '1' == get_option( 'prtfl_postmeta_update' ) ) {
                                    $post_meta        =    get_post_meta( $post->ID, 'prtfl_information', true);
                                    $date_compl        =    $post_meta['_prtfl_date_compl'];
                                    if ( ! empty( $date_compl ) && 'in progress' != $date_compl) {
                                        $date_compl        = explode( "/", $date_compl );
                                        $date_compl        = date( get_option( 'date_format' ), strtotime( $date_compl[1] . "-" . $date_compl[0] . '-' . $date_compl[2] ) );
                                    }
                                    $link            =    $post_meta['_prtfl_link'];
                                    $short_descr    =    $post_meta['_prtfl_short_descr'];
                                } else {
                                    $date_compl        =    get_post_meta( $post->ID, '_prtfl_date_compl', true );
                                    if ( ! empty( $date_compl ) && 'in progress' != $date_compl) {
                                        $date_compl        =    explode( "/", $date_compl );
                                        $date_compl        =    date( get_option( 'date_format' ), strtotime( $date_compl[1] . "-" . $date_compl[0] . '-' . $date_compl[2] ) );
                                    }
                                    $link            =    get_post_meta( $post->ID, '_prtfl_link', true );
                                    $short_descr    =    get_post_meta( $post->ID, '_prtfl_short_descr', true );
                                } ?>


                                <div class="p-item-img">
                                    <div class="overaly">
                                    
                                        <div class="pfo-inner">
                                            <div class="pfo-inner2">
                                            <a href="#" class="btn btn-dark upp" data-qview='<?php echo apply_filters( 'jvportfolio_qview', $post->ID, $cols ) ?>'> <?php _e( 'Quick view', 'portfolio' ); ?></a><br />
                                            <a rel="bookmark" href="<?php echo $link; // echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" class="btn btn-dark upp"> <?php _e( 'Details', 'portfolio' ); ?></a>

<div class="pfo-more">
<br />
                                            
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
                                            if ( $islink ) {
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
                                    
                                    </div>
	                                <div><img src="<?php echo $image_large[0]; ?>" width="<?php echo $portfolio_options['prtfl_custom_size_px'][0][0]; ?>" height="<?php echo $portfolio_options['prtfl_custom_size_px'][0][1]; ?>" alt="<?php echo $image_alt; ?>" /></div>
                                </div>


                                

								<div class="portfolio-item-description p-item-description">
									<?php if( $stitle ) : ?>
									<h3>
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
                                </div><div class="bottom-border"></div><!-- .portfolio_short_content -->
                            </div><!-- .entry -->

                        </div><!-- .portfolio_content -->
                    <?php endwhile; ?>
                    <div class="pf-load">
                        <div class="box">
                            <img src="<?php echo apply_filters( 'jvportfolio_getassets', 'images/load-yellow.gif' )?>" alt="loading"/>
                            <div class=""><?php echo esc_attr_e( 'Loading the next set of posts...', 'jvportfolio' )?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- #content -->    
            <?php $mfetch = isset( $postmeta[ 'jvportfolio_mfetch' ] ) ? $postmeta[ 'jvportfolio_mfetch' ] : 'scroll' ?> 

                            
                <div class="page-number">
                    <?php if(!strcmp( $mfetch, 'button')):?>
                    <div class="text-center">
                        <a class="btn load-more"><i class="fa fa-arrow-down"></i><?php echo esc_attr_e( 'Load more new', 'jvportfolio' )?></a>
                    </div>
                    <?php endif;?>
                    <?php if( !strcmp( $mfetch, 'nav' ) ) : ?>
                    <div data-nav="" class=""></div>
                    <?php endif;?>
                </div> <!-- and page-number -->


            <div class="navigation"><a href="<?php echo esc_url( $url_next ) ?>"></a></div>
            
        </div><!-- #container -->
    </div><!-- .content-area -->