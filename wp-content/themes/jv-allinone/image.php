<?php
/**
 *
 * The template for displaying image attachments
 *
 * @link http://codex.wordpress.org/Template_Hierarchy 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 

get_header();
 ?>
<section  id="block-breadcrumb">
    <div class="container">
        		<?php if ( have_posts() ) : ?>

				<h1 class="entry-title"><?php the_title(); ?></h1>
                <?php endif; ?>
                <?php the_breadcrumb(); ?>
    </div>
</section>
<section id="maincontent" class="container layout-image ">
		<div id="content" class="pageBlog" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
					<header class="entry-header">
						

						

						<nav id="image-navigation" class="navigation" role="navigation">
							<span class="previous-image"><?php previous_image_link( false, __( '&larr; Previous', 'jv_allinone' ) ); ?></span>
							<span class="next-image"><?php next_image_link( false, __( 'Next &rarr;', 'jv_allinone' ) ); ?></span>
						</nav><!-- #image-navigation -->
                       
					</header><!-- .entry-header -->

					<div class="entry-content">

						<div class="entry-attachment">
							<div class="attachment">
<?php
/*
 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
 */
$attachments = array_values( call_user_func( JVLibrary::getFnCheat( 'gc' ), array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
foreach ( $attachments as $k => $attachment ) :
	if ( $attachment->ID == $post->ID )
		break;
endforeach;

// If there is more than 1 attachment in a gallery
if ( count( $attachments ) > 1 ) :
	$k++;
	if ( isset( $attachments[ $k ] ) ) :
		// get the URL of the next image attachment
		$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
	else :
		// or get the URL of the first image attachment
		$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	endif;
else :
	// or, if there's only 1 image, get the URL of the image
	$next_attachment_url = wp_get_attachment_url();
endif;
?>
								<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
								/**
 								 * Filter the image attachment size to use.
								 *
								 * @since JV Allinone 1.0
								 *
								 * @param array $size {
								 *     @type int The attachment height in pixels.
								 *     @type int The attachment width in pixels.
								 * }
								 */
								$attachment_size = apply_filters( 'allinone_attachment_size', array( 960, 960 ) );
								echo apply_filters( 'esc_html', wp_get_attachment_image( $post->ID, $attachment_size ) );
								?></a>

								<?php if ( ! empty( $post->post_excerpt ) ) : ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div>
								<?php endif; ?>
							</div><!-- .attachment -->

						</div><!-- .entry-attachment -->

						<div class="entry-description">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'jv_allinone' ), 'after' => '</div>' ) ); ?>
						</div><!-- .entry-description -->
                        
                         <footer class="entry-meta  ItemLinks ItemLinksInline">
							<?php
								$metadata = wp_get_attachment_metadata();
								printf( __( '
								<span> <i class="icon-folder2"></i> <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a></span>
								<span class="entry-date"><i class="icon-calendar3"></i> <time class="entry-date" datetime="%1$s">%2$s</time></span> 
								<span><i class="icon-zoomin4"></i> <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a></span>
								
								 
								 
								 .', 'jv_allinone' ),
									esc_attr( get_the_date() ),
									esc_html( get_the_date() ),
									esc_url( wp_get_attachment_url() ),
									$metadata['width'],
									$metadata['height'],
									esc_url( get_permalink( $post->post_parent ) ),
									esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
									get_the_title( $post->post_parent )
								);
							?>
							<?php edit_post_link( __( 'Edit', 'jv_allinone' ), '<span class="edit-link"><i class="icon-edit5"></i> ', '</span>' ); ?>
						</footer><!-- .entry-meta --> <br /><br />

					</div><!-- .entry-content -->

				</article><!-- #post -->

				<?php comments_template(); 
				
				// Previous/next post navigation.
					the_post_navigation( array(
						'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'jv_allinone' ),
					) );

				?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
</section><!-- #primary -->

<?php
	
	/* ----------------css inline -------*/
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); float:none !important; }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
			.post-navigation a {padding: 5% 10%;}
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}
?>
<style>
<?php 
	echo $css;
?>
</style>
<?php get_footer();