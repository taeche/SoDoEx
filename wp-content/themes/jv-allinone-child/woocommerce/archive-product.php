<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); 
?>

<?php if ( have_posts() ) { ?>
<script language="javascript">
jQuery(function($){
	var $container = $('.shop-main-products-list .product_list_widget').imagesLoaded(function(){
		$container.masonry({
		  itemSelector: '.itemMasonry'
		});
	});


		$('.group-list').each(function(){
			var group = $(this),panel = group.parents('body').first();
			var btns = group.find('.set-col').click(function(){
				btns.removeClass('btn-dark');
				panel.attr('data-style',$(this).addClass('btn-dark').data('style'));
			});
			panel.attr('data-style',btns.filter('.btn-dark').data('style'));
		});	

});		
</script>
<?php } ?>

<section id="block-breadcrumb">
    <div class="container">
<?php 		if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="entry-title  archive"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>
        
            </div>
</section>
<div id="page-shop-sidebar" class="container">
<div class="row">



	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );




		add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
		function woocommerce_category_image() {
			if ( is_product_category() ){
				global $wp_query;
				$cat = $wp_query->get_queried_object();
				$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				
				

					if ( $cat->description && $image ) { ?>
					
						<div class="woo-category border-allinone">
						
						<img  src="<?php echo esc_url($image); ?>" alt="" /> 
						
						</div>
					<?php 
					
					}
				
			}
		} 
		?>	
		<?php 	do_action( 'woocommerce_archive_description' ); ?>
        	
		<?php if ( have_posts() ) : ?>

<div class="slider-subcategories subcategories owl-carousel space20">
<?php woocommerce_product_subcategories(); ?>		
</div>		





			<?php woocommerce_product_loop_start(); ?>

				

				<?php while ( have_posts() ) : the_post(); ?>

					<?php   wc_get_template_part( 'content', 'product' ); // show products ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<div class="woocommerce_count"></div>
			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */

		get_sidebar('shop');

	?>
</div>    
</div>
<?php get_footer();
