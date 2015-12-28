<?php
/**
 * Customer new account email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
$email_heading_replaced=str_replace('Degreaser','Solvent',$email_heading );
$blogname_replace=str_replace('Degreaser','Solvent',$blogname );
do_action( 'woocommerce_email_header', $email_heading_replaced );
?>

<p><?php printf( __( "Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'woocommerce' ), esc_html( $blogname_replace ), esc_html( $user_login ) ); ?></p>

<?php if ( get_option( 'woocommerce_registration_generate_password' ) == 'yes' && $password_generated ) : ?>

	<p><?php printf( __( "Your password has been automatically generated: <strong>%s</strong>", 'woocommerce' ), esc_html( $user_pass ) ); ?></p>

<?php endif; ?>

<?php

$user = get_user_by( 'login', $user_login  );
$role="";
if(!empty($user->roles)){
	$role= $user->roles[0];
}
//$customer="customer";
$special_customers=array('whole_saler','distributor');
if (!in_array($role,$special_customers)) { ?>

	<p><?php printf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), wc_get_page_permalink( 'myaccount' ) ); ?></p>

<?php }else{ ?>

	<p><?php printf( __( 'Thank you for registrating your account with us.  Since you have selected (Wholesaler|Distributor) as your business, we will be reaching out to you shortly for the verification process.  Once your business status has been verified and your account will be active and you can enjoy the price rate reservered for you.', 'woocommerce' ) ); ?></p>

<?php } ?>
<?php do_action( 'woocommerce_email_footer' ); ?>
