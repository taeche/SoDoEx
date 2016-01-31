<?php
/**
 *
 * The template for displaying posts in the Status post format 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	<div class="post-meta">
		<div class="date">
			<?php jv_get_date_allinone(false);?>
			
		</div>
	</div>   
  <div class="blog-item-description">       
		<div class="entry-header">

            
            <h3 class="entry-title"><?php the_author(); ?></h3>
            
			<?php
			/**
			 * Filter the status avatar size.
			 *
			 * @since JV Allinone 1.0
			 *
			 * @param int $size The height and width of the avatar in pixels.
			 */
			$status_avatar = apply_filters( 'allinone_status_avatar', 48 );
			echo apply_filters( 'esc_html', get_avatar( get_the_author_meta( 'ID' ), $status_avatar ) );
			?>
		</div><!-- .entry-header -->
		<div class="entry-content">
			<?php the_content( __( 'Read more', 'jv_allinone' ) ); ?>
		</div><!-- .entry-content -->
		<footer class="content-status entry-meta entry-footer">
			<?php if ( comments_open() ) : ?>
			<div class="comments-link  pull-right">
				<?php comments_popup_link( '<span class="leave-reply"> <i class="icon-comments3"></i> ' . __( '0', 'jv_allinone' ) . '</span>', __( '<i class="icon-comments3"></i> 1', 'jv_allinone' ), __( '<i class="icon-comments3"></i> %', 'jv_allinone' ) ); ?>
			</div><!-- .comments-link -->
			<?php endif; // comments_open() ?>

			
            
			<?php edit_post_link( __( 'Edit', 'jv_allinone' ), '<span class="edit-link"><i class="icon-edit5"></i> ', '</span>' ); ?>
		</footer><!-- .entry-meta -->
		
        </div>
	</article><!-- #post -->