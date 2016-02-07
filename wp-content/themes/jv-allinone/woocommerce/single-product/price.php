<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="divPrice product-price">

	<p class="price"><?php echo apply_filters( 'esc_html', $product->get_price_html() ); ?></p>

	<meta itemprop="price" content="<?php echo esc_attr($product->get_price()); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo apply_filters( 'esc_html', get_woocommerce_currency() ); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo esc_attr( $product->is_in_stock() ? 'InStock' : 'OutOfStock' ); ?>" />

</div>