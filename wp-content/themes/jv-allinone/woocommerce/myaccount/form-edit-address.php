<?php
/**
 * Edit address form
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $current_user;

$page_title = ( $load_address === 'billing' ) ? __( 'Billing Address', 'jv_allinone' ) : __( 'Shipping Address', 'jv_allinone' );

get_currentuserinfo();
?>

<?php wc_print_notices(); ?>

<?php if ( ! $load_address ) : ?>

	<?php wc_get_template( 'myaccount/my-address.php' ); ?>

<?php else : ?>

	<form method="post">


		
		<div class="panel-gold myaccount_address">
				<h3 class="panel-title"><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title ); ?></h3>

			<div class="panel-body">

				<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

				<?php foreach ( $address as $key => $field ) : ?>

					<?php woocommerce_form_field( $key, $field, ! empty( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : $field['value'] ); ?>

				<?php endforeach; ?>
				
				<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>          
			
			</div>
		</div>
		

		<p>
			<input type="submit" class="btn" name="save_address" value="<?php esc_attr_e( 'Save Address', 'jv_allinone' ); ?>" />
			<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
			<input type="hidden" name="action" value="edit_address" />
		</p>

	</form>

<?php endif;
