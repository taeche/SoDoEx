<?php
/**
 * Danny's Skeleton child theme
 * Containing nothing other than 
 * the bare bone files 
 */


// Store Locator - Adding custom template for displaying the store list
// TODO: Is it using DMS theme?  get_template_directory() is returning DMS path.
add_filter('wpsl_templates', 'custom_templates');
 
function custom_templates($templates) {
	$templates[] = array (
		'id'		=> 'floating-map',
		'name'		=> 'Show the store list on the map',
		'path'		=> get_template_directory() . '-sodoex/' . 'wp-store-locator/store-listings-float.php',
	);

	return $templates;
}



// Add your functions below
//Add this to remove additional information in the shop- by yong 2015-11-26
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {

	unset( $tabs['additional_information'] );   // Remove the additional information tab

	return $tabs;
}

/**
* start of adding role to the customer registration
* 2015-12-7 by yong
*/

// Add validation.

add_action( 'woocommerce_register_post', 'myplugin_registration_errors', 10, 3 );
function myplugin_registration_errors(  $username, $email, $validation_errors ) {

    if ( empty( $_POST['role'] ) || ! empty( $_POST['role'] ) && trim( $_POST['role'] ) == '' ) {
         $validation_errors->add( 'role_error', __( '<strong>ERROR</strong>: Type must be selected.', '' ) );
    }

}

//save
add_action( 'woocommerce_created_customer', 'myplugin_user_register' );
function myplugin_user_register( $user_id ) {
   $role=$_POST['role'];

   $user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $role ) );
    $customer="customer";
    if($role ==$customer){// automatic approval for customer
        update_user_meta($user_id, 'wp-approve-user', true);
    }
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

function action_woocommerce_created_customer_notification( $array, $number, $number ) {
    // make action magic happen here...


};
add_action( 'woocommerce_created_customer_notification', 'action_woocommerce_created_customer_notification', 10, 3 );
// add the action


function user_autologout(){
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $approved_status = get_user_meta($user_id, 'wp-approve-user', true);

        //if the user hasn't been approved yet by WP Approve User plugin, destroy the cookie to kill the session and log them out
        if ( $approved_status == 1 ){
            return  wc_get_page_permalink( 'myaccount' );
            //return $redirect_url;
        }
        else{
            //how to change email template at this point from customer-new-account.php to customer-account-waiting-approval.php

            wp_logout();
            return get_permalink(woocommerce_get_page_id('myaccount')) . "?approved=false";
        }
    }
}
add_action('woocommerce_registration_redirect', 'user_autologout', 2);

function registration_message(){

    $not_approved_message = '';

    if( isset($_REQUEST['approved']) ){
        $approved = $_REQUEST['approved'];
        if ($approved == 'false')  echo '<p class="registration successful">Registration successful! You will be notified upon approval of your account.</p>';
        else echo $not_approved_message;
    }
    else echo $not_approved_message;
}
add_action('woocommerce_before_customer_login_form', 'registration_message', 2);
/**
* end of adding role to the customer registration
*/

//Email Notifications
//Content parsing borrowed from: woocommerce/classes/class-wc-email.php
function send_user_approve_email($user_id){

    global $woocommerce;
    //Instantiate mailer
    $mailer = $woocommerce->mailer();

    if (!$user_id) return;

    $user = new WP_User($user_id);

    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);
    $user_pass  = "As specified during registration";

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $blogname_replaced=str_replace('Degreaser','Solvent',$blogname);
    $subject  = apply_filters( 'woocommerce_email_subject_customer_new_account', sprintf( __( 'Your account on %s has been approved!', 'woocommerce'), $blogname_replaced ), $user );
    $email_heading  = "User $user_login has been approved";

    // Buffer
    ob_start();

    // Get mail template
    woocommerce_get_template('emails/customer-account-approved.php', array(
        'user_login'    => $user_login,
        'user_pass'             => $user_pass,
        'blogname'              => $blogname,
        'email_heading' => $email_heading
    ));

    // Get contents
    $message = ob_get_clean();

    // Send the mail
    woocommerce_mail( $user_email, $subject, $message, $headers = "Content-Type: text/htmlrn", $attachments = "" );
}
add_action('wpau_approve', 'send_user_approve_email', 10, 1);

function send_user_unapprove_email($user_id){
    return;
}
add_action('wpau_unapprove', 'send_user_unapprove_email', 10, 1);

add_shortcode('url','home_url');

/**
 * replace 'Degreaser' with 'Solvent' for the welcome email for the new customer
 */

add_filter('woocommerce_email_subject_customer_new_account', 'new_customer_email_subject', 1, 2);

function new_customer_email_subject( $subject ) {
    global $woocommerce;

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $blogname_replaced=str_replace('Degreaser','Solvent',$blogname);
    $subject = sprintf(' Your account on %s', $blogname_replaced);

    return $subject;
}

add_action( 'loop_start', 'personal_message_when_logged_in' );

function personal_message_when_logged_in() {

    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
       // echo 'Personal Message For ' . $current_user->user_login . '!';





            if ( ! is_wp_error( $current_user ) AND ! get_user_meta( $current_user->ID, 'wp-approve-user', true ) ) {
               // unapproved user, force logout
                wp_logout();
            }

    } else {
        echo 'Non Personalized Message!';
    }

}

//remove_action( 'wp_authenticate_user' );
