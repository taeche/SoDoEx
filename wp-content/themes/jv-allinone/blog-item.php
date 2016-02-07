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
$cat= get_post_meta($post->ID,'select_category',true);
$cat=(strpos($cat,','))?substr($cat,0,-1):$cat;
$cat=explode(",",$cat);
$col= get_post_meta($post->ID,'select_colum',true);
$post_count=get_post_meta($post->ID,'post_count',true);
$post_count=($post_count=="")?"-1":$post_count; 
$cols=12/$col; 


if($col > 1) { ?>
	<script language="javascript">
	jQuery(function($){
		var $container = $('.blogMasonry').imagesLoaded(function(){
			$container.masonry({
			  itemSelector: '.itemMasonry'
			});
		});

	});		
	</script>

	<div class="row blogMasonry">
<?php 
} 

	if ( get_query_var('paged') ) {
		$page = get_query_var('paged');
	} else if ( get_query_var('page') ) {
		$page = get_query_var('page');
	} else {
		$page = 1;
	} 

	$query = array(
		'post_type' => 'post' ,'posts_per_page' =>$post_count,'paged' => $page,'status' => array('publish','private'),
							'tax_query' => array(array('taxonomy' =>'category','field' => 'slug','terms' => $cat))
	);
		$the_query = new WP_Query($query);
	while($the_query->have_posts()) 
	{ 
		$the_query->the_post();
		$post=get_post();
		$p = preg_split( '/<!--more(.*?)?-->/', $post->post_content );
		$exc_cpost=$p[0];
			$shortcode="";
			$class_blog="";
			$slider=cut_cpost(" ".$exc_cpost,"[rev_slider ","]");
			
			if($slider<>-1){
				$shortcode[]="[rev_slider ".$slider."]";
				$class_blog="blog-slideshow";
			}
			
			$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
			if($url<>""){
				$shortcode[]='<img alt=""  src="'.esc_url($url).'" />';
				$class_blog="blog-thumbnail";
			}
			$audio=get_post_meta($post->ID,'audio_setting',true);
			if($audio<>""){
				$shortcode[]=$audio;
				$class_blog="blog-audio";
			}
			$video=get_post_meta($post->ID,'video_setting',true);
			if($video<>""){
				$shortcode[]=$video;
				$class_blog="blog-video";
			}
			
			if(jv_get_album($post->ID)<>""){
				$shortcode[]=jv_get_album($post->ID);
				$class_blog="blog-gallery";
			}
			if($shortcode==""){
				$shortcode=array();
			}
		?>
<?php 
if($col > 1) { ?>
	<div class="itemMasonry  col-md-<?php  echo esc_attr($cols); ?> col-sm-6 col-xs-6">
<?php 
} ?>
        
		<article class="blog-item item"  >	
        
<?php 
if($col == 1) { ?>
        
			<div class="post-meta">
				<div class="date">
					<?php jv_get_date_allinone(false);?>
				</div>
			</div>
			
			
            
            <?php 
			if ( is_sticky() &&  ! is_paged() ) : ?>
			<div class="featured-post">
				<i class="icon-star6"></i>
			</div>
			<?php 
			endif; 
			?>
<?php 
} ?>        
        
        

		<?php 
		if ( ! post_password_required() && ! is_attachment() ) :
			if(!empty($shortcode)){ ?>
				<div class="thumbnail_large <?php echo esc_attr($class_blog) ?>">
				<?php echo do_shortcode($shortcode[count($shortcode)-1]);?>
				</div>
			<?php
			}
		endif; 
		?>	     


			<div class="blog-item-description <?php if($class_blog) echo 'is_thumbnail_large'; ?>"> 
            	<?php edit_post_link( __( 'Edit', 'jv_allinone' ), '<span class="edit-link"><i class="icon-edit5"></i>  ', '</span>' ); ?>
            
            

            
            
				<h3 class="entry-title"> <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>                        
				<div class="entry-meta ItemLinks ItemLinksInline ItemLinksTop ">


<?php 
if($col > 1) { ?>
				<div class="post-date">
                <i class="icon-calendar3"></i>
						<span><?php echo apply_filters( 'esc_html', date('d',strtotime($post->post_date)) ) ?></span>
						<span class="month"><?php echo apply_filters( 'esc_html', date('M',strtotime($post->post_date)) ) ?></span>
						<span class="year"><?php echo apply_filters( 'esc_html', date('Y',strtotime($post->post_date)) ) ?></span>
				</div>

<?php 
} ?>   
                
				<?php   allinone_entry_meta(); ?>

                
				
				</div>

				<div class="entry-summary">
				<?php 
				if ( post_password_required( $post->ID ) )
					echo get_the_password_form( $post->ID );
				else{
					$result = $post->post_content;
					foreach($shortcode as $short){
					$result=str_replace($short,"",$result);
					}
					$p = preg_split( '/<!--more(.*?)?-->/', apply_filters('the_content',$result ));
					echo $p[0];
				}?>
				</div><!-- .entry-summary -->

				<footer class="entry-footer">
					<?php 
					if ( comments_open() ) : ?>
						<span class="comments-link pull-right">
						<?php comments_popup_link( '<span class="leave-reply"> <i class="icon-comments3"></i> ' . __( '0', 'jv_allinone' ) . '</span>', __( '<i class="icon-comments3"></i> 1', 'jv_allinone' ), __( '<i class="icon-comments3"></i> %', 'jv_allinone' ) ); ?>
						</span><!-- .comments-link -->
					<?php endif; // comments_open() ?>
					<h6 class="readmore">
					<a href="<?php the_permalink(); ?>" rel="bookmark"> <?php esc_attr_e( 'Read more', 'jv_allinone' ); ?><i class="icon-arrow-right10"></i></a>
					</h6>  
				</footer>
			</div>
		</article>

		<?php 
		if($col > 1) { ?>
			</div>
		<?php 
		} 
	} 
 ?>
<?php if($col > 1) { ?>
</div>
<?php } ?>   

		 <div class="ar navigation pagination">
									<?php
									 echo paginate_links(array(
									 'base' => esc_url( @add_query_arg('page','%#%') ),
									 'format' => '',
									 'current' => max(1, get_query_var('page') ),
									 'total' => $the_query->max_num_pages,
									 'type' => 'list',
									 'end_size'     => 5,
									 'mid_size'     => 5,
									));
									?>
		</div> 

             
