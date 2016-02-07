<?php
  $order_by_values = array(
        '',
        __( 'Date', 'js_composer' ) => 'date',
        __( 'ID', 'js_composer' ) => 'ID',
        __( 'Author', 'js_composer' ) => 'author',
        __( 'Title', 'js_composer' ) => 'title',
        __( 'Modified', 'js_composer' ) => 'modified',
        __( 'Random', 'js_composer' ) => 'rand',
        __( 'Comment count', 'js_composer' ) => 'comment_count',
        __( 'Menu order', 'js_composer' ) => 'menu_order',
    );
    
    $order_way_values = array(
        '',
        __( 'Descending', 'js_composer' ) => 'DESC',
        __( 'Ascending', 'js_composer' ) => 'ASC',
    );
    
    /**
     * @shortcode product
     * @description Show a single product by ID or SKU.
     *
     * @param id integer
     * @param sku string
     * If the product isn’t showing, make sure it isn’t set to Hidden in the Catalog Visibility.
     * To find the Product ID, go to the Product > Edit screen and look in the URL for the postid= .
     */
    vc_map( array(
        'name' => __( 'Product', 'js_composer' ),
        'base' => 'product',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'Show a single product by ID or SKU', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'autocomplete',
                'heading' => __( 'Select identificator', 'js_composer' ),
                'param_name' => 'id',
                'description' => __( 'Input product ID or product SKU or product title to see suggestions', 'js_composer' ),
            ),
            array(
                'type' => 'hidden',
                // This will not show on render, but will be used when defining value for autocomplete
                'param_name' => 'sku',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    
    /**
     * @shortcode product_page
     * @description Show a full single product page by ID or SKU.
     *
     * @param id integer
     * @param sku string
     */
    vc_map( array(
        'name' => __( 'Product page', 'js_composer' ),
        'base' => 'product_page',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'Show single product by ID or SKU', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'autocomplete',
                'heading' => __( 'Select identificator', 'js_composer' ),
                'param_name' => 'id',
                'description' => __( 'Input product ID or product SKU or product title to see suggestions', 'js_composer' ),
            ),
            array(
                'type' => 'hidden',
                'param_name' => 'sku',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    
    /**
     * @shortcode product_category
     * @description Show multiple products in a category by slug.
     *
     * @param per_page integer
     * @param columns integer
     * @param orderby array
     * @param order array
     * @param category string
     * Go to: WooCommerce > Products > Categories to find the slug column.
     */
     $args = array(
        'type' => 'post',
        'child_of' => 0,
        'parent' => '',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
        'hierarchical' => 1,
        'exclude' => '',
        'include' => '',
        'number' => '',
        'taxonomy' => 'product_cat',
        'pad_counts' => false,

    );
    $categories = get_categories( $args );

    $product_categories_dropdown = array();
    self::getCategoryChilds( 0, 0, $categories, 0, $product_categories_dropdown );
    vc_map( array(
        'name' => __( 'Product category', 'js_composer' ),
        'base' => 'product_category',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'Show multiple products in a category', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'How much items per page to show', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'How much columns grid', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Category', 'js_composer' ),
                'value' => $product_categories_dropdown,
                'param_name' => 'category',
                'description' => __( 'Product category list', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) ); 
    
    /**
     * @shortcode product_categories
     * @description Display product categories loop.
     *
     * @param number integer
     * @param columns integer
     * @param orderby array
     * @param order array
     * @param hide_empty bool
     * @param parent integer
     * @param ids string
     * The `number` field is used to display the number of products and the `ids` field is to tell the shortcode which categories to display.
     * Set the parent paramater to 0 to only display top level categories. Set ids to a comma separated list of category ids to only show those.
     */
    vc_map( array(
        'name' => __( 'Product categories', 'js_composer' ),
        'base' => 'product_categories',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'Display product categories loop', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Number', 'js_composer' ),
                'param_name' => 'number',
                'description' => __( 'The `number` field is used to display the number of products.', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'How much columns grid', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Number', 'js_composer' ),
                'param_name' => 'hide_empty',
                'description' => __( 'Hide empty', 'js_composer' ),
            ),
            array(
                'type' => 'autocomplete',
                'heading' => __( 'Categories', 'js_composer' ),
                'param_name' => 'ids',
                'settings' => array(
                    'multiple' => true,
                    'sortable' => true,
                ),
                'description' => __( 'List of product categories', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    
    /**
     * @shortcode products
     * @description Show multiple products by ID or SKU. Make note of the plural ‘products’.
     *
     * @param columns integer
     * @param orderby array
     * @param order array
     * @param slug strong
     * If the product isn’t showing, make sure it isn’t set to Hidden in the Catalog Visibility.
     */   
    vc_map( array(
        'name' => __( 'Products', 'js_composer' ),
        'base' => 'products',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'Show multiple products by ID or SKU.', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
            ),  
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'std' => 'title',
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s. Default by Title', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s. Default by ASC', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'autocomplete',
                'heading' => __( 'Products', 'js_composer' ),
                'param_name' => 'ids',
                'settings' => array(
                    'multiple' => true,
                    'sortable' => true,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
                'description' => __( 'Enter List of Products', 'js_composer' ),
            ),
            array(
                'type' => 'hidden',
                'param_name' => 'skus',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    
    /**
     * @shortcode recent_products
     * @description Lists recent products – useful on the homepage. The ‘per_page’ shortcode determines how many products
     * to show on the page and the columns attribute controls how many columns wide the products should be before wrapping.
     * To learn more about the default ‘orderby’ parameters please reference the WordPress Codex: http://codex.wordpress.org/Class_Reference/WP_Query
     *
     * @param per_page integer
     * @param columns integer
     * @param orderby array
     * @param order array
     */
    vc_map( array(
        'name' => __( 'Recent products', 'js_composer' ),
        'base' => 'recent_products',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'Lists recent products', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'The columns attribute controls how many columns wide the products should be before wrapping.', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    /**
     * @shortcode sale_products
     * @description List all products on sale.
     *
     * @param per_page integer
     * @param columns integer
     * @param orderby array
     * @param order array
     */
    vc_map( array(
        'name' => __( 'Sale products', 'js_composer' ),
        'base' => 'sale_products',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'List all products on sale', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'How much items per page to show', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'How much columns grid', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    /**
     * @shortcode best_selling_products
     * @description List best selling products on sale.
     *
     * @param per_page integer
     * @param columns integer
     */
    vc_map( array(
        'name' => __( 'Best Selling Products', 'js_composer' ),
        'base' => 'best_selling_products',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'List best selling products on sale', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'How much items per page to show', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'How much columns grid', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    /**
     * @shortcode top_rated_products
     * @description List top rated products on sale.
     *
     * @param per_page integer
     * @param columns integer
     * @param orderby array
     * @param order array
     */
    vc_map( array(
        'name' => __( 'Top Rated Products', 'js_composer' ),
        'base' => 'top_rated_products',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'List all products on sale', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'How much items per page to show', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'How much columns grid', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    /**
     * @shortcode featured_products
     * @description Works exactly the same as recent products but displays products which have been set as “featured”.
     *
     * @param per_page integer
     * @param columns integer
     * @param orderby array
     * @param order array
     */
    vc_map( array(
        'name' => __( 'Featured products', 'js_composer' ),
        'base' => 'featured_products',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'Display products set as "featured"', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'The columns attribute controls how many columns wide the products should be before wrapping.', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    /**
     * @shortcode product_attribute
     * @description List products with an attribute shortcode.
     *
     * @param per_page integer
     * @param columns integer
     * @param orderby array
     * @param order array
     * @param attribute string
     * @param filter string
     */
    $attributes_tax = wc_get_attribute_taxonomies();
    $attributes = array();
    foreach ( $attributes_tax as $attribute ) {
        $attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
    }
    vc_map( array(
        'name' => __( 'Product Attribute', 'js_composer' ),
        'base' => 'product_attribute',
        'icon' => 'icon-wpb-woocommerce',
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'List products with an attribute shortcode', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'How much items per page to show', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'How much columns grid', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Attribute', 'js_composer' ),
                'param_name' => 'attribute',
                'value' => $attributes,
                'description' => __( 'List of product taxonomy attribute', 'js_composer' ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Filter', 'js_composer' ),
                'param_name' => 'filter',
                'value' => array( 'empty' => 'empty' ),
                'description' => __( 'Taxonomy values', 'js_composer' ),
                'dependency' => array(
                    'element' => 'attribute',
                    'is_empty' => true,
                    'callback' => 'vc_woocommerce_product_attribute_filter_dependency_callback',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
    /**
     * @shortcode related_products
     * @description List related products.
     *
     * @param per_page integer
     * @param columns integer
     * @param orderby array
     * @param order array
     */
    /* we need to detect post type to show this shortcode */
    global $post, $typenow, $current_screen;
    $post_type = "";

    if ( $post && $post->post_type ) {
        //we have a post so we can just get the post type from that
        $post_type = $post->post_type;
    } elseif ( $typenow ) {
        //check the global $typenow - set in admin.php
        $post_type = $typenow;
    } elseif ( $current_screen && $current_screen->post_type ) {
        //check the global $current_screen object - set in sceen.php
        $post_type = $current_screen->post_type;

    } elseif ( isset( $_REQUEST['post_type'] ) ) {
        //lastly check the post_type querystring
        $post_type = sanitize_key( $_REQUEST['post_type'] );
        //we do not know the post type!
    }

    vc_map( array(
        'name' => __( 'Related Products', 'js_composer' ),
        'base' => 'related_products',
        'icon' => 'icon-wpb-woocommerce',
        'content_element' => $post_type == 'product',
        // disable showing if not product type
        'category' => __( 'WooCommerce', 'js_composer' ),
        'description' => __( 'List related products', 'js_composer' ),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Per page', 'js_composer' ),
                'value' => 12,
                'param_name' => 'per_page',
                'description' => __( 'Please note: the "per_page" shortcode argument will determine how many products are shown on a page. This will not add pagination to the shortcode. ', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Columns', 'js_composer' ),
                'value' => 4,
                'param_name' => 'columns',
                'description' => __( 'How much columns grid', 'js_composer' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => $order_by_values,
                'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order way', 'js_composer' ),
                'param_name' => 'order',
                'value' => $order_way_values,
                'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Layout content', 'js_composer' ),
                'value' => 'content',
                'param_name' => 'slug',
            )
        )
    ) );
?>
