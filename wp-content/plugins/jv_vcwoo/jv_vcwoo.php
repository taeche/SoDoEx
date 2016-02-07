<?php
/*
Plugin Name: JV layout composer product woo
Plugin URI: http://www.joomlavi.com
Description: Custom layout composer product woo.
Version: 1.8.0
Author: Joomlavi
Author URI: http://www.joomlavi.com
*/
if(!class_exists('JV_WCShortcodes')) {
    require_once( dirname(__FILE__) . '/includes/shortcodes.php' );
}
if(!class_exists('JVvcwoo')) {
	class JVvcwoo {
        
        /**
         * Get lists of categories.
         * @since 4.4
         *
         * @param $parent_id
         * @param $pos
         * @param array $array
         * @param $level
         * @param array $dropdown - passed by  reference
         */
        public static function getCategoryChilds( $parent_id, $pos, $array, $level, &$dropdown ) {

            for ( $i = $pos; $i < count( $array ); $i ++ ) {
                if ( $array[ $i ]->category_parent == $parent_id ) {
                    $data = array(
                        str_repeat( "- ", $level ) . $array[ $i ]->name => $array[ $i ]->slug,
                    );
                    $dropdown = array_merge( $dropdown, $data );
                    self::getCategoryChilds( $array[ $i ]->term_id, $i, $array, $level + 1, $dropdown );
                }
            }
        }
		public static function mapOption() {
            require_once( dirname(__FILE__) . '/includes/map.php' );
		}
		
		public static function mapTemplate( $content ){  
			// Define shortcodes
			$shortcodes = array(
				'product'                    => 'JV_WCShortcodes::product',
				'product_page'               => 'JV_WCShortcodes::product_page',
				'product_category'           => 'JV_WCShortcodes::product_category',
				'product_categories'         => 'JV_WCShortcodes::product_categories',     
				'products'                   => 'JV_WCShortcodes::products',
				'recent_products'            => 'JV_WCShortcodes::recent_products',
				'sale_products'              => 'JV_WCShortcodes::sale_products',
				'best_selling_products'      => 'JV_WCShortcodes::best_selling_products',
				'top_rated_products'         => 'JV_WCShortcodes::top_rated_products',
				'featured_products'          => 'JV_WCShortcodes::featured_products',
				'product_attribute'          => 'JV_WCShortcodes::product_attribute',
				'related_products'           => 'JV_WCShortcodes::related_products',  
			);

			foreach ( $shortcodes as $shortcode => $function ) {
				remove_shortcode( $shortcode );
				add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
			} 
            
            return $content;  
		} 
	}
}
/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    add_action( 'vc_after_init', 'JVvcwoo::mapOption' );                                            
	add_filter( 'the_content', 'JVvcwoo::mapTemplate', 6 );
}

             