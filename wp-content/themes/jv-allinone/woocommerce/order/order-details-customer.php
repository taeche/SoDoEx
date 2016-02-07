<?php
/**
 * Order Customer Details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h2 class="titleTable"><?php _e( 'Customer Details', 'jv_allinone' ); ?></h2>
<table class="table_shop_cart customer_details">


	<?php if ( $order->customer_note ) : ?>
		<tr>
			<th><?php _e( 'Note:', 'jv_allinone' ); ?></th>
			<td><?php echo wptexturize( $order->customer_note ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->billing_email ) : ?>
		<tr>
			<th><?php _e( 'Email:', 'jv_allinone' ); ?></th>
			<td><?php echo esc_html( $order->billing_email ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->billing_phone ) : ?>
		<tr>
			<th><?php _e( 'Telephone:', 'jv_allinone' ); ?></th>
			<td><?php echo esc_html( $order->billing_phone ); ?></td>
		</tr>
	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
</table> <br /> <br />






<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

<div class="w-col2-set addresses row">

	<div class="w-col-1 col-md-6">

<?php endif; ?>


        
		<div class="panel panel-default ">
            <div class="panel-heading">
                <h3 class="panel-title"><?php esc_attr_e( 'Billing Address', 'jv_allinone' ); ?></h3>
            </div>
            <div class="panel-body">
                <address>
                    <?php
                        if ( ! $order->get_formatted_billing_address() ) esc_attr_e( 'N/A', 'jv_allinone' ); else echo $order->get_formatted_billing_address();
                    ?>
                </address>      
            
            </div>
        </div>
        
        
<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>



	</div><!-- /.col-1 -->

	<div class="w-col-2 col-md-6">
		<div class="panel panel-default ">
            <div class="panel-heading">
                <h3 class="panel-title"><?php esc_attr_e( 'Shipping Address', 'jv_allinone' ); ?> </h3>
            </div>
            <div class="panel-body">
                <address>
                <?php
                if ( ! $order->get_formatted_shipping_address() ) esc_attr_e( 'N/A', 'jv_allinone' ); else echo $order->get_formatted_shipping_address();
                ?>
                </address>            
            
            </div>
        </div>

        

	</div><!-- /.col-2 -->

</div><!-- /.col2-set -->


<?php endif; 

