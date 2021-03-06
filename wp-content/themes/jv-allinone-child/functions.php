<?php
//
// Recommended way to include parent theme styles.
//  (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//


add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
    
    if(
        ( $extra_theme = get_option( 'theme_mods_' . get_template() ) )
        && isset( $extra_theme[ 'nav_menu_locations' ] )
        && get_theme_mod('nav_menu_locations') != $extra_theme[ 'nav_menu_locations' ] )
    {
       set_theme_mod('nav_menu_locations', $extra_theme[ 'nav_menu_locations' ]);
    }
    
}
//
// Your code goes below
//

/**
 * Store locator plugin
 */
// Store Locator - Adding custom template for displaying the store list
// TODO: Is it using DMS theme?  get_template_directory() is returning DMS path.
add_filter('wpsl_templates', 'custom_templates');
 
function custom_templates($templates) {
	$templates[] = array (
		'id'		=> 'floating-map',
		'name'		=> 'Show the store list on the map',
		'path'		=> get_template_directory() . '-child/' . 'wp-store-locator/store-listings-float.php',
	);

	return $templates;
}

/**
 * Add marker field in Categories page
 */
//utility
 function get_available_markers() {

    $marker_images = array();
    $dir           = apply_filters( 'wpsl_admin_marker_dir', WPSL_PLUGIN_DIR . 'img/markers/' );

    if ( is_dir( $dir ) ) {
        if ( $dh = opendir( $dir ) ) {
            while ( false !== ( $file = readdir( $dh ) ) ) {
                if ( $file == '.' || $file == '..' || ( strpos( $file, '@2x' ) !== false ) ) continue;
                $marker_images[] = $file;
            }

            closedir( $dh );
        }
    }

    return $marker_images;
}
 function create_marker_html( $marker_img,$selected_value ) {

    $marker_path = ( defined( 'WPSL_MARKER_URI' ) ) ? WPSL_MARKER_URI : WPSL_URL . 'img/markers/';
    $marker_list = '';
    if ( $selected_value == $marker_img ) {
        $checked   = 'checked="checked"';
        $css_class = 'class="wpsl-active-marker"';
    } else {
        $checked   = '';
        $css_class = '';
    }

    $marker_list .= '<li ' . $css_class . '>';
    $marker_list .= '<img src="' . $marker_path . $marker_img . '" />';
    $marker_list .= '<input ' . $checked . ' type="radio" name="wpsl_category_marker"  value="' . $marker_img . '" />';
    $marker_list .= '</li>';

    return $marker_list;
}

function wpsl_taxonomy_custom_fields($tag) {
    $t_id = $tag->term_id;
    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
    $selected_value=$term_meta['wpsl_category_marker'];
    ?>

    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="presenter_id"><?php _e('Marker of the Category'); ?></label>
        </th>
        <td>
            <?php
            $marker_images=get_available_markers();
            $marker_list = '<ul class="wpsl-marker-list">';

            foreach ( $marker_images as $marker_img ) {
                $marker_list .= create_marker_html( $marker_img,$selected_value);
            }

            $marker_list .= '</ul>';
            echo $marker_list;

            ?>
        </td>
    </tr>

<?php
}


function save_taxonomy_custom_fields( $term_id ) {
    if ( isset( $_POST['wpsl_category_marker'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_term_$t_id" );
        $term_meta['wpsl_category_marker'] = $_POST['wpsl_category_marker'];
        update_option( "taxonomy_term_$t_id", $term_meta );
    }
}


add_action( 'wpsl_store_category_edit_form_fields', 'wpsl_taxonomy_custom_fields');

add_action( 'edited_wpsl_store_category', 'save_taxonomy_custom_fields');

add_action( 'wpsl_store_category_add_form_fields', 'wpsl_taxonomy_custom_fields');

add_action( 'created_wpsl_store_category', 'save_taxonomy_custom_fields');

/**
 * End of Store locator plugin
 */

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


          return get_edit_billing_address_url();

           // wp_logout();
           // return get_permalink( wc_get_page_id( 'myaccount' ) ) . "?approved=false";
        }
    }
}
function get_edit_billing_address_url(){
    $account_edit_adress_url= wc_get_endpoint_url( 'edit-address', '', get_permalink( wc_get_page_id( 'myaccount' ) ) );
    $edit_billing_address_url=$account_edit_adress_url."billing/";
    return $edit_billing_address_url;
}

function get_current_url(){
    global $wp;
    $current_url = home_url().$_SERVER['REQUEST_URI'];
    return $current_url;
}
add_action('woocommerce_registration_redirect', 'user_autologout', 2);

function registration_message(){

    $not_approved_message = '';

    if( isset($_REQUEST['approved']) ){
        $approved = $_REQUEST['approved'];
        if ($approved == 'false') {
            if (function_exists("ninja_annc_display")) {
                ninja_annc_display(165);
            }
            //echo '<p class="registration successful">Registration successful! You will be notified upon approval of your account.</p>';
        }
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


add_action( 'loop_start', 'show_notice_in_billing_address');

function show_notice_in_billing_address() {
    page_action_for_unapproved_users('show_ninja_message');
}

function show_ninja_message($is_in_billing_address_page){
    if($is_in_billing_address_page) {
        if (function_exists("ninja_annc_display")) {
            ninja_annc_display(147);
        }
    }
}

add_action( 'wp', 'logout_unapproved_user', 11, 0);

function logout_unapproved_user() {
    page_action_for_unapproved_users('force_logout');
}

function force_logout($is_in_billing_address_page)
{
    if(!$is_in_billing_address_page) {
        wp_logout();
        wp_redirect(  get_permalink( wc_get_page_id( 'myaccount' ) ) );
        exit;
    }
}

function page_action_for_unapproved_users($callback){
    if ( is_user_logged_in() ) {

        $current_user = wp_get_current_user();

        if ( ! is_wp_error( $current_user ) AND ! get_user_meta( $current_user->ID, 'wp-approve-user', true ) ) {
            $current_url =get_current_url();
            $edit_billing_address_url=get_edit_billing_address_url();
            $is_in_billing_address_page=($current_url==$edit_billing_address_url);
            // when billing address page loaded, request for home url is somehow made internally.
            // to avoid logout in this situation, calling home url is also allowed.
            $home=home_url()."/";
            if($current_url==$home){
                $is_in_billing_address_page=true;
            }

            call_user_func($callback,$is_in_billing_address_page);
            // echo "<p>The page is NOT permitted to access for unapproved users. </p><p><a href='".$edit_billing_address_url."'> go to edit address page</a> </p>";
            // exit();

        }

    }
}

function action_woocommerce_customer_save_address( $user_id, $load_address ) {

        $current_user = wp_get_current_user();

        if ( ! is_wp_error( $current_user ) AND ! get_user_meta( $current_user->ID, 'wp-approve-user', true ) ) {
             if($load_address=="billing"){
                 wp_logout();
                 wp_redirect(  get_permalink( wc_get_page_id( 'myaccount' ) ) . "?approved=false" );
                 exit;
             }

        }
};

// add the action
add_action( 'woocommerce_customer_save_address', 'action_woocommerce_customer_save_address', 10, 2 );

/**
 *  extra fields for variation
 */

// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );
/**
 * Create new fields for variations
 *
 */
function variation_settings_fields( $loop, $variation_data, $variation ) {
    global $wp_roles;

    if ( class_exists( 'WP_Roles' ) ) {
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }
    }

    echo "Price by role ($):";
  //  var_dump($wp_roles->role_objects);
    foreach ( $wp_roles->role_objects as $role ) {

        woocommerce_wp_text_input(
            array(
                'id'          => $role->name.'[' . $variation->ID . ']',
                'label'       => __( ucwords($wp_roles->role_names[$role->name]), 'woocommerce' ),
                'desc_tip'    => 'true',
                'description' => __( 'Enter the custom number here.', 'woocommerce' ),
                'value'       => get_post_meta( $variation->ID, $role->name, true ),
                'custom_attributes' => array(
                    'step' 	=> 'any',
                    'min'	=> '0'
                )
            )
        );


    }
    // Number Field


}
/**
 * Save new fields for variations
 *
 */
function save_variation_settings_fields( $post_id ) {

    global $wp_roles;

    if ( class_exists( 'WP_Roles' ) ) {
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }
    }


    foreach ( $wp_roles->role_objects as $role ) {

        $field = $_POST[$role->name][ $post_id ];
        if( ! empty( $field ) ) {
            update_post_meta( $post_id, $role->name, esc_attr( $field ) );
        }


    }
}

/**
 * #3: Notify the user when order quantity equal or more than 10 boxes
 */
add_action('the_post', 'notify_shipping_for_bulkorder', 10);
function notify_shipping_for_bulkorder()
{
    global $woocommerce;

    if (is_cart()) {
        $bulkorder_minimum_weight = 19200*10;
        if ($woocommerce->cart->cart_contents_weight >= $bulkorder_minimum_weight) {
           //show alert, ninja announce plugin must be activated
            if (function_exists("ninja_annc_display")) {
                ninja_annc_display(232);
            }

        }
    }
}

/**
 * #12 For the completed orders, also send a separate email to the shipping company
 */

function woo_extra_email_recipient($recipient, $object) {
    // the option stored with the id wc_seq_recepients_of_order_completed_emails,
    // but, BE NOTICED that wc_seq is converted to woocommerce.
    // that's why we use woocommerce_recepients_of_order_completed_emails to retrieve the value.
    $woocommerce_recepients_of_order_completed_emails = get_option( 'woocommerce_recepients_of_order_completed_emails' );
    if(!empty($woocommerce_recepients_of_order_completed_emails)){
        $recipient = $recipient . ','.$woocommerce_recepients_of_order_completed_emails;
    }

    //$recipient = $recipient . ', whs@theonelogis.com, hyunkim@theonelogis.com';
    return $recipient; }

add_filter( 'woocommerce_email_recipient_customer_processing_order', 'woo_extra_email_recipient', 10, 2);

function add_additional_recepients_of_order_completed_emails( $settings ) {

    $updated_settings = array();

    foreach ( $settings as $section ) {

        // at the bottom of the General Options section

        if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&

            isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

            $updated_settings[] = array(

                'name'     => __( 'Order Completed Email Recepients', 'wc_seq_recepients_of_order_completed_emails' ),

                'desc_tip' => __( 'Additional recepients of order completed email. Separated by comma(,) for multiple email addresses.', 'wc_seq_recepients_of_order_completed_emails' ),

                'id'       => 'woocommerce_recepients_of_order_completed_emails',

                'type'     => 'text',

                'css'      => 'min-width:400px;',

                'std'      => '',  // WC < 2.0

                'default'  => '',  // WC >= 2.0

                'desc'     => __( 'Example: aaa@abc.com, bbb@abc.org', 'wc_seq_recepients_of_order_completed_emails' ),

            );

        }

        $updated_settings[] = $section;
    }
    return $updated_settings;
}
add_filter( 'woocommerce_general_settings', 'add_additional_recepients_of_order_completed_emails');

// #10 Enable the ability to impersonate users therefore allowing ordering without the end user logging in directly to the web site #10

function replace_script_for_add_order() {
    require_once(ABSPATH . 'wp-admin/includes/screen.php');
    $screen       = get_current_screen();
    $suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    if ( in_array( str_replace( 'edit-', '', $screen->id ), wc_get_order_types( 'order-meta-boxes' ) ) ) {
        wp_dequeue_script('wc-admin-order-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-order' . $suffix . '.js');
        wp_enqueue_script('wc-admin-order-meta-boxes', get_stylesheet_directory_uri() . '/meta-boxes-order.js', array( 'wc-admin-meta-boxes' ), WC_VERSION);
    }
}
add_action('current_screen', 'replace_script_for_add_order');


function filter_plugin_updates( $value ) {
    unset( $value->response['fedex-woocommerce-shipping-method/fedex-woocommerce-shipping.php'] );
    unset( $value->response['wp-store-locator/wp-store-locator.php'] );
    unset( $value->response['woocommerce-role-pricing/woo-role-pricing-light.php'] );
    unset( $value->response['ninja-announcements/ninja_annc.php'] );
    unset( $value->response['woocommerce-create-customer-on-order/create-user-order.php'] );

    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

/**
 * #20  Minimum Order for Retailer and Wholesaler
 */
add_action( 'woocommerce_check_cart_items', 'check_min_total' );
function check_min_total() {
    if(!is_user_logged_in())return;


    // Only run in the Cart or Checkout pages
    if( is_cart() || is_checkout() ) {

        global $woocommerce, $product;

        $total_quantity=0;
        // Set minimum product cart total
        $minimum_cart_product_total = 0;

        //get current user's role
        $user_role=get_user_role();
        /**
         * role name: actual role
         *
         * distributor:retailer
         * whole_saler:wholesaler
         **/
        if($user_role=='distributor'){// for retailer
            $minimum_cart_product_total=2;
        }elseif($user_role=='whole_saler'){// for wholesaler
            $minimum_cart_product_total=15;
        }else{
            return;
        }

        //loop through all cart products
        foreach ( $woocommerce->cart->cart_contents as $product ) :
                //add up all items in the cart
                $haystack = array_values($product['variation']);
                // only check for the box items
                if(in_array("caseof72_300ml",$haystack) || in_array("caseof48_500ml",$haystack) ){
                    $total_quantity += $product['quantity'];
                }

        endforeach;

        if( $total_quantity < $minimum_cart_product_total ) {
            // Display our error message
            wc_add_notice( sprintf( 'You must have an order with a minimum of %s boxes to place your order, your current order total is %s boxes.',
                    $minimum_cart_product_total,
                    $total_quantity ),
                'error' );
        }
    }

}

function get_user_role() {
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    return $user_role;
}

