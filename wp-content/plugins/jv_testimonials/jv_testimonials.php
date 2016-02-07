<?php
/*
Plugin Name: JV Extended Posts
Plugin URI: http://www.joomlavi.com
Description: It can show use shortcode, or shortcode in visual composer
Version: 1.0
Author: Joomlavi
Author URI: http://www.joomlavi.com
*/

if(!class_exists('Jvtestimonials')) {
	class Jvtestimonials 
    {
        private static $folder_layout    = "jvtestimonials";
        
        static function get_layout()
        {
            $layout = array( __( 'Default', 'jvtestimonials' ) => '' );

            foreach( glob( implode( "/", array( TEMPLATEPATH, self::$folder_layout, "*.php" ) ) ) as $path ) {
                
                $name                 = basename( $path, '.php' );
                $layout[ __( $name, 'jvtestimonials' ) ]     = $name;
                
            }
            return $layout;
        }
        
        static function get_cateories()
        {
            $categories = array( __( 'Default', 'jvtestimonials' ) => '' );
            
            if( $fcats = get_categories( array( 'taxonomy' => 'jvtestimonials_categories' ) ) )
            {   
                foreach( $fcats as $i => $item )
                {
                    $categories[ $item->name ] = $item->term_id;
                }
            }
            
            return $categories;
        }
        static function get_order_by()
        {
            return array(
                __( 'None',     'jvtestimonials' ) => 'none',
                __( 'ID',       'jvtestimonials' ) => 'ID',            
                __( 'Title',    'jvtestimonials' ) => 'title',
                __( 'Date',     'jvtestimonials' ) => 'date',
                __( 'Modified', 'jvtestimonials' ) => 'modified',
                __( 'Rand',     'jvtestimonials' ) => 'rand',
            );
        }
        
        static function get_order()
        {
            return array(
                __( 'Descending',   'jvtestimonials' ) => 'DESC',
                __( 'Ascending',    'jvtestimonials' ) => 'ASC',
            );
        }
        
        static function get_bool()
        {
            return array(
                __( 'False' ) => '',
                __( 'True' ) => '1'
            );
        }
        
		static function mapOption() 
        {   
            
			vc_map( array(
				'name'          => __( 'JV Extended Posts', 'jvtestimonials' ),
				'base'          => 'jvtestimonials', 
				'category'      => __( 'JV Widget', 'jvtestimonials' ),        
				'description'   => __( 'Form config to display testimonials', 'jvtestimonials' ),
				'params'        => array(
					array(
                        'type'          => 'textfield',
                        'heading'       => __( 'Title', 'jvtestimonials' ),
                        'param_name'    => 'xtitle',
                        'value'         => "",
                        'group'         => __( 'Design options', 'jvtestimonials' )
                    ),
					array(
                        'type'          => 'textfield',
                        'heading'       => __( 'Subtitle', 'jvtestimonials' ),
                        'param_name'    => 'xsubtitle',
                        'value'         => "",
                        'group'         => __( 'Design options', 'jvtestimonials' )
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Alternative Layout', 'jvtestimonials' ),
                        'param_name'    => 'layout',
                        'value'         => self::get_layout(),
                        'group'         => __( 'Design options', 'jvtestimonials' )
                    ),     
                    array(
                        'type'          => 'textfield',
                        'heading'       => __( 'Limit', 'jvtestimonials' ),
                        'param_name'    => 'posts_per_page',
                        'value'         => '5',
                        'group'         => __( 'Design options', 'jvtestimonials' )
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Filter with categories', 'jvtestimonials' ),
                        'param_name'    => 'fcats',
                        'value'         => self::get_cateories(),
                        'group'         => __( 'Design options', 'jvtestimonials' )
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Order by', 'jvtestimonials' ),
                        'param_name'    => 'orderby',
                        'value'         => self::get_order_by(),
                        'group'         => __( 'Design options', 'jvtestimonials' )
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Order by', 'jvtestimonials' ),
                        'param_name'    => 'order',
                        'value'         => self::get_order(),
                        'group'         => __( 'Design options', 'jvtestimonials' )
                    ),     
                    array(
						'type'          => 'textfield',
						'heading'       => __( 'Class Suffix', 'jvtestimonials' ),
						'param_name'    => 'class_sfx',
						'group'         => __( 'Design options', 'jvtestimonials' )
					),
                    
                    /* Tab Advanced */
					array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Show title', 'jvtestimonials' ),
                        'param_name'    => 'stitle',
                        'value'         => self::get_bool(),
                        'group'         => __( 'Advanced', 'jvtestimonials' )
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Show content', 'jvtestimonials' ),
                        'param_name'    => 'scontent',
                        'value'         => self::get_bool(),
                        'group'         => __( 'Advanced', 'jvtestimonials' )
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Show Excerpt', 'jvtestimonials' ),
                        'param_name'    => 'sexcerpt',
                        'value'         => self::get_bool(),
                        'group'         => __( 'Advanced', 'jvtestimonials' )
                    ),
/*                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Show Category', 'jvtestimonials' ),
                        'param_name'    => 'scate',
                        'value'         => self::get_bool(),
                        'group'         => __( 'Advanced', 'jvtestimonials' )
                    ), */                                                     
                    array(
                        'type'          => 'dropdown',
                        'heading'       => __( 'Show Featured Image', 'jvtestimonials' ),
                        'param_name'    => 'simage',
                        'value'         => self::get_bool(),
                        'group'         => __( 'Advanced', 'jvtestimonials' )
                    ),
				)
			) );                
		}
	
		static function register_post_type()
        {
            $labels = array(
                'name'                       => _x( 'Categories', 'Taxonomy General Name', 'jvtestimonials_categories' ),
                'singular_name'              => _x( 'Categories', 'Taxonomy Singular Name', 'jvtestimonials_categories' ),
                'menu_name'                  => __( 'Categories', 'jvtestimonials_categories' ),
                'all_items'                  => __( 'All Items', 'jvtestimonials_categories' ),
                'parent_item'                => __( 'Parent Item', 'jvtestimonials_categories' ),
                'parent_item_colon'          => __( 'Parent Item:', 'jvtestimonials_categories' ),
                'new_item_name'              => __( 'New Item Name', 'jvtestimonials_categories' ),
                'add_new_item'               => __( 'Add New Item', 'jvtestimonials_categories' ),
                'edit_item'                  => __( 'Edit Item', 'jvtestimonials_categories' ),
                'update_item'                => __( 'Update Item', 'jvtestimonials_categories' ),
                'view_item'                  => __( 'View Item', 'jvtestimonials_categories' ),
                'separate_items_with_commas' => __( 'Separate items with commas', 'jvtestimonials_categories' ),
                'add_or_remove_items'        => __( 'Add or remove items', 'jvtestimonials_categories' ),
                'choose_from_most_used'      => __( 'Choose from the most used', 'jvtestimonials_categories' ),
                'popular_items'              => __( 'Popular Items', 'jvtestimonials_categories' ),
                'search_items'               => __( 'Search Items', 'jvtestimonials_categories' ),
                'not_found'                  => __( 'Not Found', 'jvtestimonials_categories' ),
            );
            $args = array(
                'labels'                     => $labels,
                'hierarchical'               => true,
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => false,
            );
            register_taxonomy( 'jvtestimonials_categories', array( 'jvtestimonials' ), $args );
            
			$labels = array(
                'name'                => _x( 'JV Extended Posts', 'Post Type General Name', 'jvtestimonials' ),
                'singular_name'       => _x( 'JV Extended Posts', 'Post Type Singular Name', 'jvtestimonials' ),
                'menu_name'           => __( 'JV Extended Posts', 'jvtestimonials' ),
                'name_admin_bar'      => __( 'Categories', 'jvtestimonials' ),
                'parent_item_colon'   => __( 'Parent Item:', 'jvtestimonials' ),
                'all_items'           => __( 'All Items', 'jvtestimonials' ),
                'add_new_item'        => __( 'Add New Item', 'jvtestimonials' ),
                'add_new'             => __( 'Add New', 'jvtestimonials' ),
                'new_item'            => __( 'New Item', 'jvtestimonials' ),
                'edit_item'           => __( 'Edit Item', 'jvtestimonials' ),
                'update_item'         => __( 'Update Item', 'jvtestimonials' ),
                'view_item'           => __( 'View Item', 'jvtestimonials' ),
                'search_items'        => __( 'Search Item', 'jvtestimonials' ),
                'not_found'           => __( 'Not found', 'jvtestimonials' ),
                'not_found_in_trash'  => __( 'Not found in Trash', 'jvtestimonials' ),
            );
            $args = array(
                'label'               => __( 'jvtestimonials', 'jvtestimonials' ),
                'description'         => __( 'It can show use shortcode, or shortcode in visual composer', 'jvtestimonials' ),
                'labels'              => $labels,
                'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
                'taxonomies'          => array( 'jvtestimonials_categories' ),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'menu_position'       => 5,
                'menu_icon'           => 'dashicons-media-text',
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'can_export'          => true,
                'has_archive'         => true,        
                'exclude_from_search' => true,
                'publicly_queryable'  => true,
                'capability_type'     => 'post',
            );
            register_post_type( 'jvtestimonials', $args );

		}
        
        public static function form( $atts ) {
            
            $atts = shortcode_atts( array(
                "xtitle"            => "",
				"xsubtitle"         => "",
                "layout"            => "",
                "posts_per_page"    => 5,
                "fcats"             => "",
                "orderby"           => "date",
                "order"             => "DESC",
                "class_sfx"         => "",
                "stitle"            => "",
                "scontent"          => "",
                "sexcerpt"          => "",
                "scate"             => "",
                "simage"            => "",
            ), $atts );
            
            extract( $atts );
            
            $args = array( 'post_type' => 'jvtestimonials' );
            
            foreach( array( 'fcats' => 'tax_query', 'orderby' => 'orderby', 'order' => 'order', 'posts_per_page' => 'posts_per_page' ) as $k => $v )
            {
                if( $qk = $atts[ $k ] )
                {
                    if( $v == 'tax_query' )
                    {
                        $args[ $v ] = array(
                            array(
                                'taxonomy' => 'jvtestimonials_categories',
                                'field'    => 'term_id',
                                'terms'    => $qk,
                            ),
                        );
                        continue;
                    }
                    $args[ $v ] = $qk;
                }    
            }              
            
            $posts = new WP_Query( $args );
                      
            ob_start();
            
            $tmpl = __DIR__ . "/templates/default.php";

            if( isset( $atts[ 'layout' ] ) ) {
                    
                $template_name = implode( "/", array( TEMPLATEPATH, self::$folder_layout, "{$atts[ 'layout' ]}.php" ) );
                
                if ( file_exists( $template_name ) ) {
                    
                    $tmpl = $template_name;
                    
                }                                              
                
            }
            
            require( $tmpl );
            
            return ob_get_clean();
        }
	}
}
add_action(     'vc_before_init'    , 'Jvtestimonials::mapOption'               );
add_action(     'init'              , 'Jvtestimonials::register_post_type'  , 0 );              
add_shortcode(  'jvtestimonials'    , 'Jvtestimonials::form'                    );