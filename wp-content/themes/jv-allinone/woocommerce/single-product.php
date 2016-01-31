<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<section id="block-breadcrumb">
    <div class="container">
<?php 		if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="entry-title  archive"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>
        
            </div>
</section>
<div id="page-shop-sidebar" class="WooProductDetail">




	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>




		<?php while ( have_posts() ) : the_post(); ?>
		<div class="container ">

			<?php wc_get_template_part( 'content', 'single-product' ); ?>
		</div>
		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

</div>
<?php get_footer( 'shop' );