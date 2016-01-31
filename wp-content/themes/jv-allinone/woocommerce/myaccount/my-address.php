<?php
/**
 * My Addresses
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) {
	$page_title = apply_filters( 'woocommerce_my_account_my_address_title', __( 'My Addresses', 'jv_allinone' ) );
	$get_addresses    = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing Address', 'jv_allinone' ),
		'shipping' => __( 'Shipping Address', 'jv_allinone' )
	), $customer_id );
} else {
	$page_title = apply_filters( 'woocommerce_my_account_my_address_title', __( 'My Address', 'jv_allinone' ) );
	$get_addresses    = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' =>  __( 'Billing Address', 'jv_allinone' )
	), $customer_id );
}

$col = 1;
?>



<div class="panel-gold myaccount_address">

     <h2 class="panel-title"><?php echo esc_html($page_title); ?> </h2>

    <div class="panel-body">

        
        <?php echo apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'jv_allinone' ) ); ?>            
    
    </div>
</div>

<?php if ( ! wc_ship_to_billing_address_only() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) echo '<div class="w-col2-set addresses row">'; ?>

<?php foreach ( $get_addresses as $name => $title ) : ?>

	<div class="w-col-<?php echo ( ( $col = $col * -1 ) < 0 ) ? 1 : 2; ?> address col-md-6">

        <div class=" panel-gold ">

                <h3 class="panel-title"><a href="<?php echo wc_get_endpoint_url( 'edit-address', $name ); ?>" class="edit pull-right"><i class="icon-edit3"></i></a><?php echo esc_html($title); ?> </h3>

            <div class="panel-body">

                
                <address>
                    <?php
                        $address = apply_filters( 'woocommerce_my_account_my_address_formatted_address', array(
                            'first_name'  => get_user_meta( $customer_id, $name . '_first_name', true ),
                            'last_name'   => get_user_meta( $customer_id, $name . '_last_name', true ),
                            'company'     => get_user_meta( $customer_id, $name . '_company', true ),
                            'address_1'   => get_user_meta( $customer_id, $name . '_address_1', true ),
                            'address_2'   => get_user_meta( $customer_id, $name . '_address_2', true ),
                            'city'        => get_user_meta( $customer_id, $name . '_city', true ),
                            'state'       => get_user_meta( $customer_id, $name . '_state', true ),
                            'postcode'    => get_user_meta( $customer_id, $name . '_postcode', true ),
                            'country'     => get_user_meta( $customer_id, $name . '_country', true )
                        ), $customer_id, $name );
        
                        $formatted_address = WC()->countries->get_formatted_address( $address );
        
                        if ( ! $formatted_address )
                            esc_attr_e( 'You have not set up this type of address yet.', 'jv_allinone' );
                        else
                            echo apply_filters( 'esc_html', $formatted_address );
                    ?>
                </address>            
            
            </div>
        </div>
    

	</div>

<?php endforeach; ?>

<?php if ( ! wc_ship_to_billing_address_only() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) echo '</div>';
