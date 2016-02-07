<?php
/**
 * Edit account form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php wc_print_notices(); ?>

<form class="forminput" action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
    
    <div class="row">
    	<div class="col-md-6">
            <p>
                <input type="text" class="input-text" placeholder="<?php esc_attr_e( 'First name ', 'jv_allinone' ); ?>" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
            </p>
            <p>
                <input type="text" class="input-text" placeholder="<?php esc_attr_e( 'Last name *', 'jv_allinone' ); ?>" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
            </p>
            <p>
                <input type="email" class="input-text"  name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
            </p>        
        
        </div>
    	<div class="col-md-6">
            <p>
                <input type="password" class="input-text"  placeholder="<?php esc_attr_e( 'Current Password', 'jv_allinone' ); ?>" name="password_current" id="password_current" />
            </p>
            <p>

                <input type="password" class="input-text"  placeholder="<?php esc_attr_e( 'New Password', 'jv_allinone' ); ?>" name="password_1" id="password_1" />
            </p>
            <p>
                <input type="password" class="input-text"  placeholder="<?php esc_attr_e( 'Confirm New Password', 'jv_allinone' ); ?>" name="password_2" id="password_2" />
            </p>        
        
        </div>
        
    </div>



    
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="submit" class="btn btn-primary " name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'jv_allinone' ); ?>" />
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	
</form>