<?php
/**
 * Cart Page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>


<form  action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>
<div class="wraptable">
<table class="table_shop_cart" cellspacing="0" cellpadding="0" >
	<thead>
		<tr>

			<th width="14%" class="product-thumbnail"><?php esc_attr_e( 'Images', 'jv_allinone' ); ?></th>
			<th class="product-name"><?php esc_attr_e( 'Product', 'jv_allinone' ); ?></th>
			<th class="product-price"><?php esc_attr_e( 'Price', 'jv_allinone' ); ?></th>
			<th class="product-quantity"><?php esc_attr_e( 'Quantity', 'jv_allinone' ); ?></th>
			<th class="product-subtotal"><?php esc_attr_e( 'Total', 'jv_allinone' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">



					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
						?>
					</td>

					<td class="product-name">
						<?php
							if ( ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							else
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

               				// Backorder notification
               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'jv_allinone' ) . '</p>';
						?>
					</td>

					<td class="product-price">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
						?>
                        
                        <?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s"><i class=" icon-cancel3"></i></a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'jv_allinone' ) ), $cart_item_key );
						?>
					</td>

					<td class="product-subtotal">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">

				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="coupon">
						<?php do_action('woocommerce_cart_coupon'); ?>
                        
                        <div class="input-group ">
                        <label class="input-group-addon" for="coupon_code"><?php esc_attr_e( 'Coupon', 'jv_allinone' ); ?>:</label>
                         <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'jv_allinone' ); ?>" />
                        <span class="input-group-btn">
                        <input type="submit" class="btn btn-primary" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'jv_allinone' ); ?>" />   
                        </span>
                        </div>

					</div>
				<?php } ?>



			</td>
		</tr>

		<tr>
			<td colspan="6" class="actions" align="center">



				<input type="submit" class="btn btn-grey" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'jv_allinone' ); ?>" /> 

			</td>
		</tr>

		<tr>
			<td colspan="6" class="actions" align="center">


                <a class="btn btn-black" href="../">  <?php esc_attr_e( 'Continue Shopping', 'jv_allinone' ); ?></a> &nbsp; 
                <input type="submit" class="checkout-button btn btn-primary alt wc-forward" name="proceed" value="<?php esc_attr_e( 'Proceed to Checkout', 'jv_allinone' ); ?>" />


				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
		</tr>
        

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>
</div>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>


<div class="row cartCollaterals">
	
    
	<div class=" col-sm-6">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

	<?php // woocommerce_cart_totals(); ?>

	
	

	</div>
	<div class=" col-sm-6">


	<?php woocommerce_shipping_calculator(); ?>
	
	

	</div> 
</div>        

<?php do_action( 'woocommerce_after_cart' );
