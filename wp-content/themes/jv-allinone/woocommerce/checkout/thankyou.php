<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php esc_attr_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'jv_allinone' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				esc_attr_e( 'Please attempt your purchase again or go to your account page.', 'jv_allinone' );
			else
				esc_attr_e( 'Please attempt your purchase again.', 'jv_allinone' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_attr_e( 'Pay', 'jv_allinone' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_attr_e( 'My Account', 'jv_allinone' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

	
        
        <div class="order_details panel-gold">
        <h2 class="panel-title"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'jv_allinone' ), $order ); ?></h2>
        <div class=" panel-body">

<table cellpadding="0" width="100%" cellspacing="0">
        		<tr>
                	<td class="class="order"">				<?php esc_attr_e( 'Order Number:', 'jv_allinone' ); ?>
				<strong><?php echo esc_html($order->get_order_number()); ?></strong></td>

                	<td class="date">				<?php _e( 'Date:', 'jv_allinone' ); ?>
				<strong><?php echo esc_html(date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) )); ?></strong></td>
                	<td class="total">				<?php _e( 'Total:', 'jv_allinone' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong></td>
			<?php if ( $order->payment_method_title ) : ?>
			<td class="method">
				<?php esc_attr_e( 'Payment Method:', 'jv_allinone' ); ?>
				<strong><?php echo esc_html($order->payment_method_title); ?></strong>
			</td>
			<?php endif; ?>

                </tr>
        </table>

		</div>
        
        
        
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'jv_allinone' ), null ); ?></p>

<?php endif;
