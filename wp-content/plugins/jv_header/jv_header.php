<?php
/*
Plugin Name: JV Header
Plugin URI: http://www.joomlavi.com
Description: Custom layout header.
Version: 1.8.0
Author: Joomlavi
Author URI: http://www.joomlavi.com
*/

if(!class_exists('jvheader')) {
	class jvheader {
		
		public static $prefix = "jvheader";
		
		public static function addmeta(){
			
			$screens = array( 'page' );

			foreach ( $screens as $screen ) {
				add_meta_box(
					'jvheader_optionmeta'
					,esc_html__( 'Header options', 'jvheader' )
					,'jvheader::formmeta'
					,$screen
					,'side'
					,'default'
				);
			}
		}
		
		public static function getDefaults() {
			
			$data = array( '' => 'Style-1' );
			                                       
			foreach( glob( TEMPLATEPATH . "/header-*.php" ) as $path ) {
			    
                $i 			= pathinfo( $path );
                $name 		= str_replace( 'header-', '', $i[ 'filename' ] );
                $data[ $name ] 	= $name;
                
			}
            
			return array_unique( $data );
		}
		
		public static function get_meta( $post_id = 0 ) {
            
            return get_post_meta( $post_id, self::$prefix . $post_id, true );
			
        }
		
		/* Display the post meta box. */
		public static function formmeta( $object, $box ) { 
			
            ob_start();
            
            $header = self::get_meta( $object->ID );
			$data	= self::getDefaults();
            
			require_once( dirname( __FILE__ ) . '/inc/form.php' );
            
			echo ob_get_clean();
		}
        
        public static function getPathTheme( $post_id = 0 ) {
            
            return dirname( __FILE__ ) . '/' . md5( $post_id ) . '.php';
                
        }
		
		/* Save the meta box's post metadata. */
		public static function savemeta( $post_id, $post ){
			
			/* Get the post type object. */
			$post_type = get_post_type_object( $post->post_type );

			/* Check if the current user has permission to edit the post. */
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;

            /* Get the meta key. */
            $meta_key   = self::$prefix . $post_id;   
            
            /* Get the posted data and sanitize it for use as an HTML class. */
            $new_meta_value = ( isset( $_POST[ self::$prefix ] ) ? $_POST[ self::$prefix ] : '' );

            /* Get the meta value of the custom field key. */
            $meta_value     = get_post_meta( $post_id, $meta_key, true );

            /* If a new meta value was added and there was no previous value, add it. */
            if ( $new_meta_value && '' == $meta_value )
                add_post_meta( $post_id, $meta_key, $new_meta_value, true );

            /* If the new meta value does not match the old value, update it. */
            elseif ( $new_meta_value && $new_meta_value != $meta_value )
                update_post_meta( $post_id, $meta_key, $new_meta_value );

            /* If there is no new meta value but an old value exists, delete it. */
            elseif ( '' == $new_meta_value && $meta_value ) {
                    
                delete_post_meta( $post_id, $meta_key, $meta_value );
                
                if( ( $ptemplate = self::getPathTheme( $post_id ) ) && file_exists( $ptemplate ) ) {
                    
                    unlink( $ptemplate );
                }
            }   
		} 
        
        public static function template_include( $template ){
            
            if( !in_array( get_post_type(), array( 'page' ) ) ) { return $template; }
            
            if( function_exists( 'get_the_ID' ) && ( $header = self::get_meta( get_the_ID() ) ) ) {
                
                $pcontent = file_get_contents( $template );
                
                if( !preg_match( '/get_header.+(?:(\)))/', $pcontent, $rsearch ) ) {
                    
                    return $template;
                }
                
                $ntemplate  = self::getPathTheme( get_the_ID() );
                $rsearch    = array_shift( $rsearch );
                $pcontent   = str_replace( $rsearch, "get_header('{$header}')", $pcontent );
                
                file_put_contents( $ntemplate, $pcontent );
                
                return $ntemplate;
                    
            }         
            
            return $template;
                
        }
        
        public static function delete_post( $post_id = 0 ) {
            
            if( ( $ptemplate = self::getPathTheme( $post_id ) ) && file_exists( $ptemplate ) ) {
                    
                unlink( $ptemplate );
            }    
        }
	}
}
/* Add meta boxes on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'jvheader::addmeta' );

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', 'jvheader::savemeta', 10, 2 );        
                                                                  
/* Save post meta on the 'template_include' hook. */
add_filter( 'template_include', 'jvheader::template_include', 10000000, 1 );

/* hook event delete post */
add_action( 'after_delete_post', 'jvheader::delete_post', 10, 1 );