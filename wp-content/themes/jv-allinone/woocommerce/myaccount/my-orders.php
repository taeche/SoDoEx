<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.10
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );

if ( $customer_orders ) : ?>

	

<h2 class="titleTable"><?php echo apply_filters( 'woocommerce_my_account_my_orders_title', __( 'Recent Orders', 'jv_allinone' ) ); ?></h2>
<div class="wraptable">
	<table class="table_my_account_orders table_shop_cart">

		<thead>
			<tr>
				<th class="order-number"><span class="nobr"><?php esc_attr_e( 'Order', 'jv_allinone' ); ?></span></th>
				<th class="order-date"><span class="nobr"><?php esc_attr_e( 'Date', 'jv_allinone' ); ?></span></th>
				<th class="order-status"><span class="nobr"><?php esc_attr_e( 'Status', 'jv_allinone' ); ?></span></th>
				<th class="order-total"><span class="nobr"><?php esc_attr_e( 'Total', 'jv_allinone' ); ?></span></th>
				<th class="order-actions">&nbsp;</th>
			</tr>
		</thead>

		<tbody><?php
			foreach ( $customer_orders as $customer_order ) {
				$order = wc_get_order( $customer_order );
				$order->populate( $customer_order );
				$item_count = $order->get_item_count();

				?><tr class="order">
					<td class="order-number">
						<a href="<?php echo esc_url($order->get_view_order_url()); ?>">
							<?php echo apply_filters( 'esc_html', $order->get_order_number() ); ?>
						</a>
					</td>
					<td class="order-date">
						<time datetime="<?php echo esc_attr( date( 'Y-m-d', strtotime( $order->order_date ) ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo apply_filters( 'esc_html', date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ) ); ?></time>
					</td>
					<td class="order-status" style="text-align:left; white-space:nowrap;">
						<?php echo apply_filters( 'esc_html', wc_get_order_status_name( $order->get_status() ) ); ?>
					</td>
					<td class="order-total">
						<?php echo apply_filters( 'esc_html', sprintf( _n( '%s for %s item', '%s for %s items', $item_count, 'jv_allinone' ), $order->get_formatted_order_total(), $item_count ) ); ?>
					</td>
					<td class="order-actions">
						<?php
							$actions = array();

							if ( $order->needs_payment() ) {
								$actions['pay'] = array(
									'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'jv_allinone' )
								);
							}

							if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['cancel'] = array(
									'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' )),
									'name' => __( 'Cancel', 'jv_allinone' )
								);
							}

							$actions['view'] = array(
								'url'  => $order->get_view_order_url(),
								'name' => __( 'View', 'jv_allinone' )
							);

							$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );

							if ($actions) {
								foreach ( $actions as $key => $action ) {
									echo apply_filters( 'esc_html', '<a href="' . esc_url( $action['url'] ) . '" class="btn btn-xs ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>' );
								}
							}
						?>
					</td>
				</tr><?php
			}
		?></tbody>

	</table>
</div>    

<?php endif;
