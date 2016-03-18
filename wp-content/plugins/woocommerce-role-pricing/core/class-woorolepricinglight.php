<?php
/**
 * class-woorolepricinglight.php
 *
 * Copyright (c) Antonio Blanco http://www.blancoleon.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Antonio Blanco
 * @package woorolepricinglight
 * @since woorolepricing 1.0.0
 */

/**
 * WooRolePricingLight class
 */
class WooRolePricingLight {

	public static function init() {
	
		add_filter('woocommerce_get_price', array( __CLASS__, 'woocommerce_get_price' ), 10, 2);
		
		// filter <del> tags for variable products
		add_filter('woocommerce_variable_sale_price_html', array ( __CLASS__, 'woocommerce_variable_sale_price_html' ), 10, 2 );
		
	}

	public static function woocommerce_get_price($price, $product)
	{

		global $post, $woocommerce;

		$baseprice = $price;
		$result = $baseprice;

		if (($post == null) || !is_admin()) {


			$roleprice = self::get_role_price($product);
			if (empty($roleprice)) {
				$regularprice = $product->get_regular_price();
				if(!empty($regularprice)){
					$baseprice=$regularprice;
				}
				if ($product->get_sale_price() != $product->get_regular_price()
					&& $product->get_sale_price() == $product->price
				) {
					if (get_option("wrp-baseprice", "regular") == "sale") {
						$baseprice = $product->get_sale_price();
					}
				}
				$product_price = $baseprice;
			}else{
				$baseprice=$roleprice;
				$product_price=$roleprice;
			}


			$result = 0;

			if ($product->is_taxable() && get_option('woocommerce_prices_include_tax') == 'yes') {
				$_tax = new WC_Tax();
				$tax_rates = $_tax->get_shop_base_rate($product->tax_class);
				$taxes = $_tax->calc_tax($baseprice, $tax_rates, true);
				$product_price = $_tax->round($baseprice - array_sum($taxes));
			}

			$result = $product_price;

			if ($product->is_taxable() && get_option('woocommerce_prices_include_tax') == 'yes') {
				$_tax = new WC_Tax();
				$tax_rates = $_tax->get_shop_base_rate($product->tax_class);
				$taxes = $_tax->calc_tax($result, $tax_rates, false); // important false
				$result = $_tax->round($result + array_sum($taxes));
			}

		}


		return $result;
	}
	
		
	/**
	 * Filter <del> tabs for variable products
	 * @param String $pricehtml
	 * @param Object $product
	 * @return String
	 */
	public static function woocommerce_variable_sale_price_html ( $pricehtml, $product ) {
		$string = $pricehtml;
	
		global $post, $woocommerce;
	
		if ( ($post == null) || !is_admin() ) {
			$commission = self::get_commission( $product );
			if ( $commission ) {
				$string=preg_replace('/<del[^>]*>.+?<\/del>/i', '', $string);
			}
		}
		return $string;
	}
	
	// extra functions
	public static function get_role_price($product){
		global $post, $woocommerce;


		if(is_admin()){
			//admin screen
			if(!empty($_POST['selected_user_id'])) {
				$user = get_user_by('id', $_POST['selected_user_id']);
			}else{
				//!empty($_POST['order_id'])
				$order = new WC_Order( $_POST['order_id'] );
				$user_id=$order->get_user_id();
				$user = get_user_by('id', $user_id);
			}
		}else{
			// user screen
			$user = wp_get_current_user();
		}

		$user_roles = $user->roles;
		if($user_roles==null)return null;

		$user_role = array_shift($user_roles);

		if ( $user_role !== null ) {
			$role_price=get_post_meta( $product->variation_id, $user_role, true );
			if (!empty($role_price)) {
				return $role_price;
			}
		}
		return null;

	}

	
}
WooRolePricingLight::init();
