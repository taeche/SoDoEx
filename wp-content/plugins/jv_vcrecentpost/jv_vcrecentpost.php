<?php
/*
Plugin Name: JV VC Recent Post
Plugin URI: http://www.joomlavi.com
Description: Include recent post to Visual Composer
Version: 1.8.0
Author: Joomlavi
Author URI: http://www.joomlavi.com
*/

if( !class_exists( 'Jvrecent' ) ) {

	class Jvrecent {

		public static function addParams() {

			$cates = array();

			foreach( get_terms( 'category' ) as $item ) {
				
				$cates[ $item->name ] = $item->term_id;
					
			}
			$tags = array();

			foreach( get_terms( 'post_tag' ) as $item ) {
				
				$tags[ $item->name ] = $item->term_id;
					
			}
			$tmpls = array( __( 'Default', 'jv-recent' ) => '' );
            
            foreach( glob( get_template_directory() . '/recent-post/*.php' ) as $item ) {
                
                $name = basename( $item, '.php' ); 
                
                $tmpls[ __( $name, 'jv-recent' ) ] = "recent-post/{$name}";
                
            }

			vc_map( array(
		      	"name" 					=> __( "JV Recent Post", "jv-recent" ),
		      	"base" 					=> "rpwe",
		      	"category" 				=> __( "JV Widget", "jv-recent"),
		      	"params" 				=> array(
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Limit to Category", "jvrecent" ),
		            	"param_name" 	=> "cat",
		            	"group" 		=> __( "Filter", "jvrecent" ),
		            	"value" 		=> $cates
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Number of posts to show", "jvrecent" ),
		            	"param_name" 	=> "limit",
		            	"value"			=> 5,
		            	"group" 		=> __( "Filter", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Offset", "jvrecent" ),
		            	"param_name" 	=> "offset",
		            	"group" 		=> __( "Filter", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Order", "jvrecent" ),
		            	"param_name" 	=> "order",
		            	"group" 		=> __( "Filter", "jvrecent" ),
		            	"value" 		=> array(
	            			__( "Desending", "jvrecent" ) => 'DESC',
	            			__( "Ascending", "jvrecent" ) => 'ASC',
		            	)
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Orderby", "jvrecent" ),
		            	"param_name" 	=> "orderby",
		            	"group" 		=> __( "Filter", "jvrecent" ),
		            	"value" 		=> array(
	            			__( "ID", "jvrecent" ) 				=> 'ID',
	            			__( "Author", "jvrecent" ) 			=> 'author',
	            			__( "Title", "jvrecent" ) 			=> 'title',
	            			__( "Date", "jvrecent" ) 			=> 'date',
	            			__( "Modified", "jvrecent" ) 		=> 'modified',
	            			__( "Random", "jvrecent" ) 			=> 'rand',
	            			__( "Comment Count", "jvrecent" ) 	=> 'comment_count',
	            			__( "Menu Order", "jvrecent" ) 		=> 'menu_order',
		            	)
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Limit to Taxonomy", "jvrecent" ),
		            	"param_name" 	=> "taxonomy",
		            	"description" 	=> "Ex: category=1,2,4&post_tag=6,12 Available: category, post_tag, post_format, product_cat, product_tag, product_shipping_class, portfolio_executor_profile, portfolio_technologies",
		            	"group" 		=> __( "Filter", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Limit to Tag", "jvrecent" ),
		            	"param_name" 	=> "tag",
		            	"group" 		=> __( "Filter", "jvrecent" ),
		            	"value" 		=> $tags
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Display Thumbnail", "jvrecent" ),
		            	"param_name" 	=> "thumb",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) => '1',	
		            	)
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Thumbnail height", "jvrecent" ),
		            	"param_name" 	=> "thumb_height",
		            	"group" 		=> __( "Display", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Thumbnail width", "jvrecent" ),
		            	"param_name" 	=> "thumb_width",
		            	"group" 		=> __( "Display", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Thumbnail align", "jvrecent" ),
		            	"param_name" 	=> "thumb_align",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "Left", "jvrecent" ) 	=> 'rpwe-alignleft',
		            		__( "Right", "jvrecent" ) 	=> 'rpwe-alignright',
		            		__( "Center", "jvrecent" ) 	=> 'rpwe-aligncenter',
		            	)
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Default Thumbnail", "jvrecent" ),
		            	"param_name" 	=> "thumb_default",
		            	"description" 	=> "Leave it blank to disable.",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> 'http://placehold.it/45x45/f0f0f0/ccc'
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Display Excerpt", "jvrecent" ),
		            	"param_name" 	=> "excerpt",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) 	=> '1',	
		            	)
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Excerpt Length", "jvrecent" ),
		            	"param_name" 	=> "length",
		            	"group" 		=> __( "Display", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Display Title", "jvrecent" ),
		            	"param_name" 	=> "dtitle",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) 	=> '1',	
		            	)
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Display Date", "jvrecent" ),
		            	"param_name" 	=> "date",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) 	=> '1',	
		            	)
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Display Comment", "jvrecent" ),
		            	"param_name" 	=> "dcomment",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) 	=> '1',	
		            	)
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Display author", "jvrecent" ),
		            	"param_name" 	=> "dauthor",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) 	=> '1',	
		            	)
		         	),
		         	array(
                        "type"             => "checkbox",
                        "heading"         => __( "Display category", "jvrecent" ),
                        "param_name"     => "dcategory",
                        "group"         => __( "Display", "jvrecent" ),
                        "value"         => array(
                            __( "True", "jvrecent" )     => '1',    
                        )
                     ),
                     array(
                        "type"             => "checkbox",
                        "heading"         => __( "Display tags", "jvrecent" ),
                        "param_name"     => "dtag",
                        "group"         => __( "Display", "jvrecent" ),
                        "value"         => array(
                            __( "True", "jvrecent" )     => '1',    
                        )
                     ),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Use Relative Date. eg: 5 days ago", "jvrecent" ),
		            	"param_name" 	=> "date_relative",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) 	=> '1',	
		            	)
		         	),
		         	array(
		            	"type" 			=> "checkbox",
		            	"heading" 		=> __( "Display Readmore", "jvrecent" ),
		            	"param_name" 	=> "readmore",
		            	"group" 		=> __( "Display", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "True", "jvrecent" ) 	=> '1',	
		            	)
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Readmore Text", "jvrecent" ),
		            	"param_name" 	=> "readmore_text",
		            	"group" 		=> __( "Display", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Date", "jvrecent" ),
		            	"param_name" 	=> "idate",
		            	"description"	=> "Leave it blank to default value. Else if disable then 0",
		            	"group" 		=> __( "Icon", "jvrecent" ),
                        "value"         => "icon-calendar3"
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Comment", "jvrecent" ),
		            	"param_name" 	=> "icomment",
		            	"description"	=> "Leave it blank to default value. Else if disable then 0",
		            	"group" 		=> __( "Icon", "jvrecent" ),
                        "value"         => "fa fa-comments"
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Author", "jvrecent" ),
		            	"param_name" 	=> "iauthor",
		            	"description"	=> "Leave it blank to default value. Else if disable then 0",
		            	"group" 		=> __( "Icon", "jvrecent" ),
                        "value"         => "icon-user22"
		         	),
		         	array(
                        "type"             => "textfield",
                        "heading"         => __( "Category", "jvrecent" ),
                        "param_name"     => "icate",
                        "description"    => "Leave it blank to default value. Else if disable then 0",
                        "group"         => __( "Icon", "jvrecent" ),
                        "value"         => "icon-folder2"
                     ), 
                     array(
                        "type"             => "textfield",
                        "heading"         => __( "Tag", "jvrecent" ),
                        "param_name"     => "itag",
                        "description"    => "Leave it blank to default value. Else if disable then 0",
                        "group"         => __( "Icon", "jvrecent" ),
                        "value"         => "icon-tags2"
                     ), 
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Title Widget", "jvrecent" ),
		            	"param_name" 	=> "title",
		            	"group" 		=> __( "Advance", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Sub Title Widget", "jvrecent" ),
		            	"param_name" 	=> "subtitle",
		            	"group" 		=> __( "Advance", "jvrecent" )
		         	),
					
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Template", "jvrecent" ),
		            	"param_name" 	=> "tmpl",
		            	"group" 		=> __( "Advance", "jvrecent" ),
		            	"value" 		=> $tmpls
		         	),
		         	array(
		            	"type" 			=> "dropdown",
		            	"heading" 		=> __( "Number of column", "jvrecent" ),
		            	"param_name" 	=> "col",
		            	"group" 		=> __( "Advance", "jvrecent" ),
		            	"value" 		=> array(
		            		__( "One", "jvrecent" ) 	=> 1,
		            		__( "Two", "jvrecent" ) 	=> 2,
		            		__( "Three", "jvrecent" ) 	=> 3,
		            		__( "Four", "jvrecent" ) 	=> 4,
		            		__( "Six", "jvrecent" ) 	=> 6,
		            	)
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "Title URL", "jvrecent" ),
		            	"param_name" 	=> "title_url",
		            	"group" 		=> __( "Advance", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "CSS ID", "jvrecent" ),
		            	"param_name" 	=> "cssID",
		            	"group" 		=> __( "Advance", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "textfield",
		            	"heading" 		=> __( "CSS Class", "jvrecent" ),
		            	"param_name" 	=> "css_class",
		            	"group" 		=> __( "Advance", "jvrecent" )
		         	),

					
		         	array(
		            	"type" 			=> "textarea",
		            	"heading" 		=> __( "HTML or text before the recent posts", "jvrecent" ),
		            	"param_name" 	=> "before",
		            	"group" 		=> __( "Advance", "jvrecent" )
		         	),
		         	array(
		            	"type" 			=> "textarea",
		            	"heading" 		=> __( "HTML or text after the recent posts", "jvrecent" ),
		            	"param_name" 	=> "after",
		            	"group" 		=> __( "Advance", "jvrecent" )
		         	),
		      	)
		   ) );

		}
	}

	add_action( 'vc_before_init', 'Jvrecent::addParams' );
}