<?php
/*
Plugin Name: JV VC Portfolio
Plugin URI: http://www.joomlavi.com
Description: Include shortcode portfolio to Visual Composer
Version: 1.8.0
Author: Joomlavi
Author URI: http://www.joomlavi.com
*/

if( !class_exists( 'Jvpfo' ) ) {

	class Jvpfo {

		public static function addParams() {

			$args = array( 'hide_empty' => 0 );
			$tags = array();
            global $wpdb;
            $terms = $wpdb->get_results( "
            SELECT p.term_id, p.`name` 
            FROM {$wpdb->terms} as  p 
            INNER JOIN {$wpdb->term_taxonomy} as c on p.term_id = c.term_id 
            WHERE c.taxonomy = 'portfolio_technologies'
            " );
            if( !is_wp_error( $terms ) )  {
                
                foreach( $terms as $item ) {
                    
                    $tags[ $item->name ] = $item->term_id;
                    
                }   
                
            }
            
            $tmpls = array( __( 'Default', 'jvpfo' ) => '' );
            
            foreach( glob( get_template_directory() . '/portfolio/*.php' ) as $item ) {
                
                $name = basename( $item, '.php' ); 
                
                $tmpls[ __( $name, 'jvpfo' ) ] = "portfolio/{$name}";
                
            }   

			vc_map( array(
		      	"name" 		=> __( "JV Portfolio", "jvpfo" ),
		      	"base" 		=> "jvpfo",
		      	"category" 	=> __( "JV Widget", "jvpfo"),
		      	"params" 	=> array(
		         	array(
                        "type"             => "dropdown",
                        "heading"         => __( "Template", "jvpfo" ),
                        "param_name"     => "tmpl",
                        "group"         => __( 'General', 'jvpfo' ),
                        "value"         => $tmpls
                     ),
                     array(
                        "type"             => "dropdown",
                        "heading"         => __( "Column", "jvpfo" ),
                        "param_name"     => "col",
                        "group"         => __( 'General', 'jvpfo' ),
                        "value"         => array(
                            __( "One column", "jvpfo" )     => "1",
                            __( "Two column", "jvpfo" )     => "2",
                            __( "Three column", "jvpfo" )     => "3",
                            __( "Four column", "jvpfo" )     => "4",
                            __( "Six column", "jvpfo" )     => "6",
                        )
                     ),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Tags", "jvpfo" ),
		            	"param_name" 	=> "tags",
		            	"group" 		=> __( 'General', 'jvpfo' ),
		            	"value" 		=> $tags
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Limit", "jvpfo" ),
		            	"param_name" 	=> "limit",
		            	"group" 		=> __( 'General', 'jvpfo' ),
		            	"value" 		=> get_option( 'posts_per_page' )
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Filter", "jvpfo" ),
		            	"param_name" 	=> "filter",
		            	"group" 		=> __( 'General', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "False", "jvpfo" ) 	=> "0",
		            		__( "True", "jvpfo" ) 	=> "1",
		            	)
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Sort", "jvpfo" ),
		            	"param_name" 	=> "sort",
		            	"group" 		=> __( 'General', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "Name", "jvpfo" ) => "name",
		            		__( "Date", "jvpfo" ) => "date",
		            	)
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Method fetch", "jvpfo" ),
		            	"param_name" 	=> "mfetch",
		            	"group" 		=> __( 'General', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "Scroll", "jvpfo" ) 	=> "scroll",
		            		__( "Button", "jvpfo" ) 	=> "button",
		            		__( "Navigation", "jvpfo" ) => "nav",
		            	)
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Show title", "jvpfo" ),
		            	"param_name" 	=> "stitle",
		            	"group" 		=> __( 'Element options', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "False", "jvpfo" ) 	=> "0",
		            		__( "True", "jvpfo" ) 	=> "1",
		            	)
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Show tag", "jvpfo" ),
		            	"param_name" 	=> "stag",
		            	"group" 		=> __( 'Element options', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "False", "jvpfo" ) 	=> "0",
		            		__( "True", "jvpfo" ) 	=> "1",
		            	)
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Show date", "jvpfo" ),
		            	"param_name" 	=> "sdate",
		            	"group" 		=> __( 'Element options', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "False", "jvpfo" ) 	=> "0",
		            		__( "True", "jvpfo" ) 	=> "1",
		            	)
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Show link", "jvpfo" ),
		            	"param_name" 	=> "slink",
		            	"group" 		=> __( 'Element options', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "False", "jvpfo" ) 	=> "0",
		            		__( "True", "jvpfo" ) 	=> "1",
		            	)
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Show description", "jvpfo" ),
		            	"param_name" 	=> "sdesc",
		            	"group" 		=> __( 'Element options', 'jvpfo' ),
		            	"value" 		=> array(
		            		__( "False", "jvpfo" ) 	=> "0",
		            		__( "True", "jvpfo" ) 	=> "1",
		            	)
		         	)
		      	)
		   ) );

		}
	}

	add_action( 'vc_before_init', 'Jvpfo::addParams' );
}