<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>



<?php 

if ( sizeof( WC()->cart->get_cart() ) == 0 ) {	?> 

<span class="showtotal" href="javascript:void(0)" ><i class="icon-suitcase"></i> <?php esc_attr_e( '0 items', 'jv_allinone' ); ?> - <?php echo WC()->cart->get_cart_subtotal(); ?> </span>

	
<?php
}else{ ?>

<ul class="menu">
<li>




<a class="showtotal" href="javascript:void(0)" ><i class="icon-suitcase"></i> <?php echo sizeof( WC()->cart->get_cart() ); ?> <?php esc_attr_e( 'items', 'jv_allinone' ); ?> - <?php echo WC()->cart->get_cart_subtotal(); ?>  <i class="icon-arrow-down10"></i></a>

<ul class="sub-menu"><li>

<div class="cart_list  product_list_widget <?php echo esc_attr($args['list_class']); ?>">



		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					?>
					<div>
                     <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove pull-right" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'jv_allinone' ) ), $cart_item_key ); ?>
					<?php if ( ! $_product->is_visible() ) { ?>
						<?php echo apply_filters( 'esc_html', str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . $product_name ); ?>
					<?php } else { ?>
						<a href="<?php echo get_permalink( $product_id ); ?>">
							<?php echo  apply_filters( 'esc_html', str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . $product_name ); ?>
						</a>
					<?php } ?>
                    <div class="info">
						<?php echo WC()->cart->get_item_data( $cart_item ); ?>

						<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
					</div></div>
					<?php
				}
			}
		?>



</div><!-- end product list -->



	<p class="total"><strong><?php esc_attr_e( 'Subtotal', 'jv_allinone' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="buttons clearfix">
		<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="btn pull-left wc-forward"><?php esc_attr_e( 'View Cart', 'jv_allinone' ); ?></a>
		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="btn pull-right checkout wc-forward"><?php esc_attr_e( 'Checkout', 'jv_allinone' ); ?></a>
	</p>



<?php do_action( 'woocommerce_after_mini_cart' ); ?>

</li></ul>
</li>
</ul>

	
<?php 
}





