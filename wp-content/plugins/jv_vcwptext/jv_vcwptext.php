<?php
/*
Plugin Name: JV VC WP Text Custom
Plugin URI: http://www.joomlavi.com
Description: Custom layout VC WP Text.
Version: 1.8.0
Author: Joomlavi
Author URI: http://www.joomlavi.com
*/

if(!class_exists('JVvcwptext')) {
	class JVvcwptext {
		static function mapOption() {
			vc_map( array(
				'name' => 'WP ' . __( 'Text' ),
				'base' => 'vc_wp_text',
				'icon' => 'icon-wpb-wp',
				'category' => __( 'WordPress Widgets', 'js_composer' ),
				'class' => 'wpb_vc_wp_widget',
				'weight' => - 50,
				'description' => __( 'Arbitrary text or HTML', 'js_composer' ),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Widget title', 'js_composer' ),
						'param_name' => 'title',
						'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' )
					),array(
						'type' => 'textfield',
						'heading' => __( 'Widget sub title', 'js_composer' ),
						'param_name' => 'stitle',
						'description' => __( 'What text use as a widget sub title. Leave blank to use default widget sub title.', 'js_composer' )
					),
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'heading' => __( 'Text', 'js_composer' ),
						'param_name' => 'content',
						// 'admin_label' => true
					),
					/*array(
					'type' => 'checkbox',
					'heading' => __( 'Automatically add paragraphs', 'js_composer' ),
					'param_name' => "filter"
			  ),*/
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'el_class',
						'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
					)
				)
			) );
			
			if( !isset( $vc_add_css_animation ) ) {
                
                $vc_add_css_animation = array(
                    'type' => 'dropdown',
                    'heading' => __( 'CSS Animation', 'js_composer' ),
                    'param_name' => 'css_animation',
                    'admin_label' => true,
                    'value' => array(
                        __( 'No', 'js_composer' ) => '',
                        __( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
                        __( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
                        __( 'Left to right', 'js_composer' ) => 'left-to-right',
                        __( 'Right to left', 'js_composer' ) => 'right-to-left',
                        __( 'Appear from center', 'js_composer' ) => "appear"
                    ),
                    'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
                );
            }
			
			vc_map( array(
                'name' => __( 'Text Block', 'js_composer' ),
                'base' => 'vc_column_text',
                'icon' => 'icon-wpb-layer-shape-text',
                'wrapper_class' => 'clearfix',
                'category' => __( 'Content', 'js_composer' ),
                'description' => __( 'A block of text with WYSIWYG editor', 'js_composer' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Title', 'js_composer' ),
                        'param_name' => 'title',
                        'description' => __( 'What text use as a widget title. Leave blank to use default title.', 'js_composer' )
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Sub title', 'js_composer' ),
                        'param_name' => 'stitle',
                        'description' => __( 'What text use as a widget sub title. Leave blank to use default sub title.', 'js_composer' )
                    ),
                    array(
                        'type' => 'textarea_html',
                        'holder' => 'div',
                        'heading' => __( 'Text', 'js_composer' ),
                        'param_name' => 'content',
                        'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' )
                    ),
                    $vc_add_css_animation,
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Extra class name', 'js_composer' ),
                        'param_name' => 'el_class',
                        'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
                    ),
                    array(
                        'type' => 'css_editor',
                        'heading' => __( 'Css', 'js_composer' ),
                        'param_name' => 'css',
                        // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
                        'group' => __( 'Design options', 'js_composer' )
                    )
                )
            ) );
		}
	
		static function mapTemplate( $title ){
			$args = func_get_args();
			
			if( count( $args ) < 2 ) { return $title; }
			
			if(!isset($args[1]['stitle'])) {return $title;}
			
			return $title. "<span class='sub-title'>{$instance['stitle']}</span>";
		}
	}
}
add_action( 'vc_before_init', 'JVvcwptext::mapOption' );
add_filter( 'widget_title' , 'JVvcwptext::mapTemplate'); 