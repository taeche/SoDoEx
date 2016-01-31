<?php
  /**
 * WC_Shortcodes class.
 *
 * @class         WC_Shortcodes
 * @version        2.1.0
 * @package        WooCommerce/Classes
 * @category    Class
 * @author         WooThemes
 */
class JV_WCShortcodes {
    /**
     * Display a single product
     *
     * @param array $atts
     * @return string
     */
    public static function product( $atts ) {
        if ( empty( $atts ) ) {
            return '';
        }

        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'no_found_rows'  => 1,
            'post_status'    => 'publish',
            'meta_query'     => $meta_query
        );

        if ( isset( $atts['sku'] ) ) {
            $args['meta_query'][] = array(
                'key'         => '_sku',
                'value'     => $atts['sku'],
                'compare'     => '='
            );
        }

        if ( isset( $atts['id'] ) ) {
            $args['p'] = $atts['id'];
        }

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce">' . ob_get_clean() . '</div>';
    }
    /**
     * Show a single product page
     *
     * @param array $atts
     * @return string
     */
    public static function product_page( $atts ) {
        if ( empty( $atts ) ) {
            return '';
        }

        if ( ! isset( $atts['id'] ) && ! isset( $atts['sku'] ) ) {
            return '';
        }

        $args = array(
            'posts_per_page'      => 1,
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => 1
        );

        if ( isset( $atts['sku'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_sku',
                'value'   => $atts['sku'],
                'compare' => '='
            );
        }

        if ( isset( $atts['id'] ) ) {
            $args['p'] = $atts['id'];
        }

        $single_product = new WP_Query( $args );

        ob_start();

        while ( $single_product->have_posts() ) : $single_product->the_post(); wp_enqueue_script( 'wc-single-product' ); ?>

            <div class="single-product">

                <?php wc_get_template_part( self::getSlug( $atts ), 'single-product' ); ?>

            </div>

        <?php endwhile; // end of the loop.

        wp_reset_postdata();

        return '<div class="woocommerce">' . ob_get_clean() . '</div>';
    }
    /**
     * List products in a category shortcode
     *
     * @param array $atts
     * @return string
     */
    public static function product_category( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '4',
            'orderby'  => 'title',
            'order'    => 'desc',
            'category' => '',  // Slugs
            'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
            'slug'     => 'content'
        ), $atts );

        if ( ! $atts['category'] ) {
            return '';
        }

        // Default ordering args
        $ordering_args = WC()->query->get_catalog_ordering_args( $atts['orderby'], $atts['order'] );
        $meta_query    = WC()->query->get_meta_query();

        $args = array(
            'post_type'                => 'product',
            'post_status'             => 'publish',
            'ignore_sticky_posts'    => 1,
            'orderby'                 => $ordering_args['orderby'],
            'order'                 => $ordering_args['order'],
            'posts_per_page'         => $atts['per_page'],
            'meta_query'             => $meta_query,
            'tax_query'             => array(
                array(
                    'taxonomy'         => 'product_cat',
                    'terms'         => array_map( 'sanitize_title', explode( ',', $atts['category'] ) ),
                    'field'         => 'slug',
                    'operator'         => $atts['operator']
                )
            )
        );

        if ( isset( $ordering_args['meta_key'] ) ) {
            $args['meta_key'] = $ordering_args['meta_key'];
        }

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        woocommerce_reset_loop();
        wp_reset_postdata();

        $return = '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';

        // Remove ordering query arguments
        WC()->query->remove_ordering_args();

        return $return;
    }
    /**
     * List all (or limited) product categories
     *
     * @param array $atts
     * @return string
     */
    public static function product_categories( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'number'     => null,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'columns'    => '4',
            'hide_empty' => 1,
            'parent'     => '',
            'ids'        => '',
            'slug'       => 'content'
        ), $atts );

        if ( isset( $atts['ids'] ) ) {
            $ids = explode( ',', $atts['ids'] );
            $ids = array_map( 'trim', $ids );
        } else {
            $ids = array();
        }

        $hide_empty = ( $atts['hide_empty'] == true || $atts['hide_empty'] == 1 ) ? 1 : 0;

        // get terms and workaround WP bug with parents/pad counts
        $args = array(
            'orderby'    => $atts['orderby'],
            'order'      => $atts['order'],
            'hide_empty' => $hide_empty,
            'include'    => $ids,
            'pad_counts' => true,
            'child_of'   => $atts['parent']
        );

        $product_categories = get_terms( 'product_cat', $args );

        if ( '' !== $atts['parent'] ) {
            $product_categories = wp_list_filter( $product_categories, array( 'parent' => $atts['parent'] ) );
        }

        if ( $hide_empty ) {
            foreach ( $product_categories as $key => $category ) {
                if ( $category->count == 0 ) {
                    unset( $product_categories[ $key ] );
                }
            }
        }

        if ( $atts['number'] ) {
            $product_categories = array_slice( $product_categories, 0, $atts['number'] );
        }

        $woocommerce_loop['columns'] = $atts['columns'];

        ob_start();

        // Reset loop/columns globals when starting a new loop
        $woocommerce_loop['loop'] = $woocommerce_loop['column'] = '';

        if ( $product_categories ) {

            woocommerce_product_loop_start();
            $slug = self::getSlug( $atts );

            foreach ( $product_categories as $category ) {

                wc_get_template( "{$slug}-product_cat.php", array(
                    'category' => $category
                ) );

            }

            woocommerce_product_loop_end();

        }

        woocommerce_reset_loop();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * List multiple products shortcode
     *
     * @param array $atts
     * @return string
     */
    public static function products( $atts ) {
        global $woocommerce_loop;

        if ( empty( $atts ) ) {
            return '';
        }

        $atts = shortcode_atts( array(
            'columns' => '4',
            'orderby' => 'title',
            'order'   => 'asc',
            'ids'     => '',
            'skus'    => '',
            'slug'    => 'content'
        ), $atts );

        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'posts_per_page'      => -1,
            'meta_query'          => $meta_query
        );

        if ( ! empty( $atts['skus'] ) ) {
            $skus = explode( ',', $atts['skus'] );
            $skus = array_map( 'trim', $skus );
            $args['meta_query'][] = array(
                'key'         => '_sku',
                'value'     => $skus,
                'compare'     => 'IN'
            );
        }

        if ( ! empty( $atts['ids'] ) ) {
            $ids = explode( ',', $atts['ids'] );
            $ids = array_map( 'trim', $ids );
            $args['post__in'] = $ids;
        }

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * Recent Products shortcode
     *
     * @param array $atts
     * @return string
     */
    public static function recent_products( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'per_page'     => '12',
            'columns'     => '4',
            'orderby'     => 'date',
            'order'     => 'desc',
            'slug'    => 'content'
        ), $atts );

        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'post_type'                => 'product',
            'post_status'            => 'publish',
            'ignore_sticky_posts'    => 1,
            'posts_per_page'         => $atts['per_page'],
            'orderby'                 => $atts['orderby'],
            'order'                 => $atts['order'],
            'meta_query'             => $meta_query
        );

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * List all products on sale
     *
     * @param array $atts
     * @return string
     */
    public static function sale_products( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '4',
            'orderby'  => 'title',
            'order'    => 'asc',
            'slug'    => 'content'
        ), $atts );

        // Get products on sale
        $product_ids_on_sale = wc_get_product_ids_on_sale();

        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'posts_per_page'    => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'             => $atts['order'],
            'no_found_rows'     => 1,
            'post_status'         => 'publish',
            'post_type'         => 'product',
            'meta_query'         => $meta_query,
            'post__in'            => array_merge( array( 0 ), $product_ids_on_sale )
        );

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * List best selling products on sale
     *
     * @param array $atts
     * @return string
     */
    public static function best_selling_products( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '4',
            'slug'    => 'content'
        ), $atts );

        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'meta_key'            => 'total_sales',
            'orderby'             => 'meta_value_num',
            'meta_query'          => $meta_query
        );

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * List top rated products on sale
     *
     * @param array $atts
     * @return string
     */
    public static function top_rated_products( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '4',
            'orderby'  => 'title',
            'order'    => 'asc',
            'slug'    => 'content'
        ), $atts );

        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'posts_per_page'      => $atts['per_page'],
            'meta_query'          => $meta_query
        );

        ob_start();

        add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * Output featured products
     *
     * @param array $atts
     * @return string
     */
    public static function featured_products( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'per_page' => '12',
            'columns'  => '4',
            'orderby'  => 'date',
            'order'    => 'desc',
            'slug'    => 'content'
        ), $atts );

        $meta_query   = WC()->query->get_meta_query();
        $meta_query[] = array(
            'key'   => '_featured',
            'value' => 'yes'
        );

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'meta_query'          => $meta_query
        );

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * List products with an attribute shortcode
     * Example [product_attribute attribute='color' filter='black']
     *
     * @param array $atts
     * @return string
     */
    public static function product_attribute( $atts ) {
        global $woocommerce_loop;

        $atts = shortcode_atts( array(
            'per_page'  => '12',
            'columns'   => '4',
            'orderby'   => 'title',
            'order'     => 'asc',
            'attribute' => '',
            'filter'    => ''
        ), $atts );

        $attribute  = strstr( $atts['attribute'], 'pa_' ) ? sanitize_title( $atts['attribute'] ) : 'pa_' . sanitize_title( $atts['attribute'] );
        $meta_query = WC()->query->get_meta_query();

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'meta_query'          => $meta_query,
            'tax_query'           => array(
                array(
                    'taxonomy' => $attribute,
                    'terms'    => array_map( 'sanitize_title', explode( ',', $atts['filter'] ) ),
                    'field'    => 'slug'
                )
            )
        );

        ob_start();

        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $atts['columns'];

        if ( $products->have_posts() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( self::getSlug( $atts ), 'product' ); ?>

                <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php endif;

        wp_reset_postdata();

        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }
    /**
     * @param array $atts
     * @return string
     */
    public static function related_products( $atts ) {

        $atts = shortcode_atts( array(
            'posts_per_page' => '2',
            'columns'        => '2',
            'orderby'        => 'rand',
            'per_page'       => '',
            'slug'    => 'content'
        ), $atts );

        if ( ! empty( $atts['per_page'] ) ) {
            _deprecated_argument( __CLASS__ . '->' . __FUNCTION__, '2.1', __( 'Use $args["posts_per_page"] instead. Deprecated argument will be removed in WC 2.2.', 'woocommerce' ) );
            $atts['posts_per_page'] = $atts['per_page'];
            unset( $atts['per_page'] );
        }

        ob_start();

        if ( ! is_array( $args ) ) {
            _deprecated_argument( __FUNCTION__, '2.1', __( 'Use $args argument as an array instead. Deprecated argument will be removed in WC 2.2.', 'woocommerce' ) );

            $argsvalue = $args;

            $args = array(
                'posts_per_page' => $argsvalue,
                'columns'        => $columns,
                'orderby'        => $orderby,
            );
        }

        $defaults = array(
            'posts_per_page' => 2,
            'columns'        => 2,
            'orderby'        => 'rand'
        );

        $args = wp_parse_args( $args, $defaults );
        $slug = self::getSlug($atts, 'related');

        wc_get_template( "single-product/{$slug}.php", $args );

        return ob_get_clean();
    }
    /**
     * @param array $attr
     * @param string $default
     * @return string
     */
    public static function getSlug( $attr = array(), $default = 'content' ) {
        return isset( $attr[ 'slug' ] ) && !empty( $attr[ 'slug' ] ) ? $attr[ 'slug' ] : $default;         
    }    
}
?>
