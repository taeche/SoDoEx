<?php
/**
 *
 * The default template for displaying content
 *
 * Used for both single and index/archive/search. 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 
$shortcode=array();
$class_blog="";

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
			
?>
<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php if ( is_single() ) echo  '<div class="single-posts">';  ?>

	<?php 
	if ( !is_single() ) { ?>
        <div class="post-meta">
            <div class="date">
              <?php jv_get_date_allinone(false);?>
                
            </div>
        </div>

<?php 
	if ( is_sticky()  && ! is_paged() ) : ?>
	<div class="featured-post">
		<i class="icon-star6"></i>
	</div>
<?php 
	endif; 
?>
    

		
	<?php
    }
	?>




<?php 
	if ( ! post_password_required() && ! is_attachment() ) :
		if(!empty($shortcode)){
			?>
			<div class="thumbnail_large <?php echo esc_attr($class_blog); 	  ?>">
				<?php if( !in_array( $class_blog, array( 'blog-audio' ) ) ) : ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php endif; ?>
					<?php
						if($class_blog=="blog-thumbnail"){
							echo do_shortcode($shortcode[count($shortcode)-1]);
						}
						else{
							echo do_shortcode($shortcode[count($shortcode)-1]);
						}
					?>
					<?php if( !in_array( $class_blog, array( 'blog-audio' ) ) ) : ?>
					</a>
					<?php endif; ?>
			</div>
			<?php
		}
	endif; 
?>		

<div class="blog-item-description <?php if($class_blog) echo 'is_thumbnail_large'; ?>">

     
   

	
	
<?php 
	if ( is_single() ) :
?>
		<h2 class="single-title"><?php the_title(); ?></h2>
<?php 
	else : ?>
		<h3 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h3>
<?php endif; // is_single() ?>

	<div class="entry-meta ItemLinks ItemLinksInline ItemLinksTop">
   

	<?php 
	if ( is_single() ) :
		allinone_entry_meta_full();
	else :
		allinone_entry_meta(); 
	endif;
	?>

    
	<?php edit_post_link( __( 'Edit', 'jv_allinone' ), '<span class="edit-link "><i class="icon-edit5"></i>  ', '</span>' ); ?>
                <?php 
				if ( is_single() ) {
					if ( comments_open() ) : ?>
						<span class="comments-link pull-right">
							<?php comments_popup_link( '<span class="leave-reply"> <i class="icon-comments3"></i> ' . __( '0', 'jv_allinone' ) . '</span>', __( '<i class="icon-comments3"></i> 1', 'jv_allinone' ), __( '<i class="icon-comments3"></i> %', 'jv_allinone' ) ); ?>
						</span><!-- .comments-link -->
					<?php endif;
				}// comments_open() ?>


	</div><!-- .entry-meta -->		

<?php 
	if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php 
				the_excerpt(); 
			?>
		</div><!-- .entry-summary -->
<?php 
	else : 
?>
		<div class="entry-content">
			<?php 
			if ( post_password_required( $post->ID ) )
						  echo get_the_password_form( $post->ID );
			else{
				if(is_single()){
						the_content();
				}
				else{
					$p = preg_split( '/<!--more(.*?)?-->/', apply_filters('the_content',$post->post_content ));
					if(isset($p[1])){
						echo $p[0];
					}
					else{
						the_content();
					}
				}
			}
			 ?>
			 

			<?php  wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'jv_allinone' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
<?php 
	endif; 
?>
	<?php 
    if ( !is_single() ) { ?>
        <footer class="entry-footer">
        
                <?php if ( comments_open() ) : ?>
                    <span class="comments-link pull-right">
                        <?php comments_popup_link( '<span class="leave-reply"> <i class="icon-comments3"></i> ' . __( '0', 'jv_allinone' ) . '</span>', __( '<i class="icon-comments3"></i> 1', 'jv_allinone' ), __( '<i class="icon-comments3"></i> %', 'jv_allinone' ) ); ?>
                    </span><!-- .comments-link -->
                <?php endif; // comments_open() ?>
                
                    <h6 class="readmore">
                        <a href="<?php the_permalink(); ?>" rel="bookmark"> <?php esc_attr_e( 'Read more', 'jv_allinone' ); ?><i class="icon-arrow-right10"></i></a>
                    </h6>  
        </footer>
    <?php 
    } ?>  
        
<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
	<div class="author-info">
		<div class="author-avatar">
			<?php
			/** This filter is documented in author.php */
			$author_bio_avatar_size = apply_filters( 'allinone_author_bio_avatar_size', 68 );
			echo apply_filters( 'esc_html', get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size ) );
			?>
		</div><!-- .author-avatar -->
		<div class="author-description">
			<h2><?php printf( __( 'About %s', 'jv_allinone' ), get_the_author() ); ?></h2>
			<p><?php the_author_meta( 'description' ); ?></p>
			<div class="author-link">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
					<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'jv_allinone' ), get_the_author() ); ?>
				</a>
			</div><!-- .author-link	-->
		</div><!-- .author-description -->
	</div><!-- .author-info -->
<?php endif; ?>

        
</div>                                           

<?php if ( is_single() ) echo  apply_filters( 'esc_html', '</div>' );  ?>
</article>
	<!-- #post -->
