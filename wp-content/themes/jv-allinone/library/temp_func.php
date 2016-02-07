<?php
/*
 # JV Library - JV Theme
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/

/**
 * JV Allinone functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 * 
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/
	if(!function_exists("install_plugin_information")){
		function install_plugin_information(){}
	}
	
	if(!function_exists("plugins_api")){
		function plugins_api(){}
	}
	
	if(!function_exists("wc_print_notices")){
		function wc_print_notices(){}
	}
	
	if(!function_exists("woocommerce_form_field")){
		function woocommerce_form_field(){}
	}
	if(!function_exists("wc_cart_totals_subtotal_html")){
		function wc_cart_totals_subtotal_html(){}
	}
	
	if(!function_exists("wc_cart_totals_coupon_html")){
		function wc_cart_totals_coupon_html(){}
	}
	
	if(!function_exists("wc_cart_totals_coupon_label")){
		function wc_cart_totals_coupon_label(){}
	}
	
	if(!function_exists("wc_cart_totals_shipping_html")){
		function wc_cart_totals_shipping_html(){}
	}
	
	if(!function_exists("wc_cart_totals_order_total_html")){
		function wc_cart_totals_order_total_html(){}
	}
	
	if(!function_exists("wc_cart_totals_fee_html")){
		function wc_cart_totals_fee_html(){}
	}
	
	if(!function_exists("wc_print_notice")){
		function wc_print_notice(){}
	}
	if(!function_exists("woocommerce_login_form")){
		function woocommerce_login_form(){}
	}
	if(!function_exists("wc_print_notice")){
		function wc_print_notice(){}
	}
	if(!function_exists("woocommerce_product_subcategories")){
		function woocommerce_product_subcategories(){}
	}
	if(!function_exists("woocommerce_product_loop_start")){
		function woocommerce_product_loop_start(){}
	}
	if(!function_exists("woocommerce_product_loop_end")){
		function woocommerce_product_loop_end(){}
	}
	if(!function_exists("woocommerce_page_title")){
		function woocommerce_page_title(){}
	}
	if(!function_exists("woocommerce_products_will_display")){
		function woocommerce_products_will_display(){}
	}
	if(!function_exists("woocommerce_template_loop_add_to_cart")){
		function woocommerce_template_loop_add_to_cart(){}
	}
	if(!function_exists("woocommerce_quantity_input")){
		function woocommerce_quantity_input(){}
	}
	if(!function_exists("woocommerce_show_product_sale_flash")){
		function woocommerce_show_product_sale_flash(){}
	}
	if(!function_exists("woocommerce_template_loop_rating")){
		function woocommerce_template_loop_rating(){}
	}
	if(!function_exists("woocommerce_template_loop_price")){
		function woocommerce_template_loop_price(){}
	}
	if(!function_exists("woocommerce_get_product_schema")){
		function woocommerce_get_product_schema(){}
	}
	if(!function_exists("woocommerce_cart_totals")){
		function woocommerce_cart_totals(){}
	}
	
	if(!function_exists("woocommerce_shipping_calculator")){
		function woocommerce_shipping_calculator(){}
	}
	if(!function_exists("wc_cart_totals_shipping_method_label")){
		function wc_cart_totals_shipping_method_label(){}
	}
	if(!function_exists("wc_cart_totals_taxes_total_html")){
		function wc_cart_totals_taxes_total_html(){}
	}
	/*-allinone-*/
	if(!function_exists("pdfprnt_show_buttons_for_bws_portfolio")){
		function pdfprnt_show_buttons_for_bws_portfolio(){}
	}
	if(!function_exists("pdfprnt_show_buttons_for_bws_portfolio_post")){
		function pdfprnt_show_buttons_for_bws_portfolio_post(){}
	}
	if(!function_exists("get_the_image")){
		function get_the_image(){}
	}
	if(!function_exists("delete_user_attribute")){
		function delete_user_attribute(){}
	}
	/*---more from theme-data----*/
	if(!function_exists("vc_shortcode_custom_css_class")){
		function vc_shortcode_custom_css_class(){}
	}
	if(!function_exists("wpb_js_remove_wpautop")){
		function wpb_js_remove_wpautop(){}
	}
	if(!function_exists("rpwe_resize")){
		function rpwe_resize(){}
	}
	if(!function_exists("wc_lostpassword_url")){
		function wc_lostpassword_url(){}
	}
	if(!function_exists("is_woocommerce")){
		function is_woocommerce(){}
	}
	if(!function_exists("wc_get_template")){
		function wc_get_template(){}
	}
	if(!function_exists("wc_clean")){
		function wc_clean(){}
	}
	if(!function_exists("wc_clean")){
		function wc_clean(){}
	}
	if(!function_exists("wc_get_order_types")){
		function wc_get_order_types(){}
	}
	if(!function_exists("wc_get_order_statuses")){
		function wc_get_order_statuses(){}
	}
	if(!function_exists("wc_get_order")){
		function wc_get_order(){}
	}
	if(!function_exists("wc_get_order_status_name")){
		function wc_get_order_status_name(){}
	}
	if(!function_exists("wc_get_page_id")){
		function wc_get_page_id(){}
	}
	if(!function_exists("WC")){
		function WC(){}
	}
	if(!function_exists("wc_ship_to_billing_address_only")){
		function wc_ship_to_billing_address_only(){}
	}
	if(!function_exists("wc_get_endpoint_url")){
		function wc_get_endpoint_url(){}
	}
	if(!function_exists("wc_customer_bought_product")){
		function wc_customer_bought_product(){}
	}
	if(!function_exists("wc_get_product_terms")){
		function wc_get_product_terms(){}
	}
	if(!function_exists("wc_price")){
		function wc_price(){}
	}if(!function_exists("is_ajax")){
		function is_ajax(){}
	}if(!function_exists("is_product_category")){
		function is_product_category(){}
	}
	if(!function_exists("get_woocommerce_term_meta")){
		function get_woocommerce_term_meta(){}
	}
	if(!function_exists("wc_get_template_part")){
		function wc_get_template_part(){}
	}
	if(!function_exists("wc_get_product")){
		function wc_get_product(){}
	}
	if(!function_exists("wc_attribute_label")){
		function wc_attribute_label(){}
	}
	if(!function_exists("wc_attribute_orderby")){
		function wc_attribute_orderby(){}
	}
	if(!function_exists("wc_placeholder_img_src")){
		function wc_placeholder_img_src(){}
	}
	if(!function_exists("get_woocommerce_currency")){
		function get_woocommerce_currency(){}
	}