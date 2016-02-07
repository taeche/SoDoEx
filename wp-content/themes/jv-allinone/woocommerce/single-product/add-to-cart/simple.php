<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';
	
	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart cart-simple borderBottom " method="post" enctype='multipart/form-data'>
	 	<?php  do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	 	<?php
	 		if ( ! $product->is_sold_individually() ) {
	 			woocommerce_quantity_input( array(
	 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
	 				'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
	 			) );
	 		}
	 	?>

	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

	 	<button type="submit" class="single_add_to_cart_button btn btn-primary alt"><i class="icon-cart42"></i> <?php echo esc_html($product->single_add_to_cart_text()); ?></button>


		   	<?php if(shortcode_exists( 'yith_wcwl_add_to_wishlist' )){?><div class="item-btn"> <?php  echo do_shortcode("[yith_wcwl_add_to_wishlist]"); ?></div><?php } ?> 
		    <?php if(shortcode_exists( 'yith_compare_button' )){?><div class="item-btn"> <?php  echo do_shortcode("[yith_compare_button]"); ?></div><?php } ?> 

            
       
		<?php  do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php  do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif;