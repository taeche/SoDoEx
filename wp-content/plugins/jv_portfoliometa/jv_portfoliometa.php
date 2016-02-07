<?php

/*

Plugin Name: Add option portfolio

Plugin URI: http://www.joomlavi.com

Description: Add more option to Portfolio.

Version: 1.1.0

Author: Joomlavi

Author URI: http://www.joomlavi.com

*/



if( !class_exists( 'jvportfolio' ) ) {

	

	class jvportfolio {

        

        public static $prefix = "jvportfolio_";
		 
        

        public static function get_meta( $post_id = 0 ) {

            

            $defaults =  array(

                'jvportfolio_col'       => '3',

                'jvportfolio_limit'     => get_option( 'posts_per_page' ),

                'jvportfolio_filter'    => '0',
                'jvportfolio_sort'      => array(),

                'jvportfolio_tags'      => array(),

                'jvportfolio_mfetch'    => 'scroll',

                'jvportfolio_stitle'    => '0',

                'jvportfolio_stag'      => '0',

                'jvportfolio_sdate'     => '0',

                'jvportfolio_slink'     => '0',

                'jvportfolio_sdesc'     => '0'

            );

            

            if( !$post_id ) { return $defaults; }

            

            $rs = get_post_meta( $post_id, self::$prefix . $post_id, true );

            

            return !$rs ? $defaults : array_merge( $defaults, $rs );

        }

        

        public static function get_categories( $args = array() ) {

            $args = array_merge( array( 'hide_empty' => 0 ), $args );

            return get_terms( array( 'portfolio_technologies' ), $args );    

        }
        

        public static function getTimeSort( $t ) {

            return strtotime( $t );    

        } 

        

        public static function getTermsSort( $terms ) {

            

            if( !is_array( $terms ) || !count( $terms ) ) { return false; }

            

            $rs = array();

            

            foreach( $terms as $term ) {

                

                array_push( $rs, $term->slug );

                

            }

            return json_encode( $rs );    

        }

        

        public static function getColumn( $cols = 3 ) {

            

            if( !intval( $cols ) ) { return ""; }

            

            $cols = 12 / $cols;

            

            $sm = ( $cols !=6 ? 4 : 6 ); 

            

            return sprintf( "col-sm-%d col-md-%d", $sm, $cols );

            

        }

        

        public static function getId( $id ) {

            

            return "pfo-item-{$id}";

        }

        

        public static function qview( $id, $cols ) {

            

            $id = self::getId( $id );

            $cols = 12 / $cols;

            $ncol = 2 * $cols;

            $qview = array(

                'view' => "#{$id}",

                'c' => array(

                    array(

                        'removeClass' => "col-md-{$cols}",

                        'addClass' => "col-md-{$ncol}",

                    ),

                    array(

                        'removeClass' => "col-md-{$ncol}",

                        'addClass' => "col-md-{$cols}",

                    )

                )

            );

            

            return json_encode( $qview );

        }

        

        public static function getAssets( $path = '' ) {

            

            return rtrim( site_url(), '/' ) . '/wp-content/plugins/jv_portfoliometa/assets/' . $path;

        }

        

        public static function ajax( $cw = 'content', $postmeta = array(), $count_all_albums = 0, $link = '' ) {

            

            $scripts = array(

                'modernizr-custom-min'  => 'js/modernizr.custom.min.js',

                'jquery-imagesloaded'  => 'js/jquery.imagesloaded.js',

                'jquery-shuffle'        => 'js/jquery.shuffle.js',

                'jquery-infinitescroll' => 'js/jquery.infinitescroll.js',

                'frontend-jvportfolio'  => 'js/frontend.jvportfolio.js',

            );

            $skins = array(

                'pfo' => 'css/pfo.css'

            );

            $inline_js = array(

                'cw' => $cw,

                'limit' => $postmeta[ 'jvportfolio_limit' ],

                'link' => $link,

                'pageTotal' => ceil( $count_all_albums / $postmeta[ 'jvportfolio_limit' ] )

            );

            

            $mfetch = isset( $postmeta[ 'jvportfolio_mfetch' ] ) ? $postmeta[ 'jvportfolio_mfetch' ] : 'scroll';

            $inline_js[ 'mfetch' ] = $mfetch;

            

            if( isset( $postmeta[ 'jvportfolio_filter' ] ) && intval( $postmeta[ 'jvportfolio_filter' ] ) ) {

                

                $scripts[ 'extend-filter-portfolio' ] = 'js/extend.filter.portfolio.js';    

                $inline_js[ 'cfilter' ] = 1;     

            }

            if( isset( $postmeta[ 'jvportfolio_filter' ] ) && count( $postmeta[ 'jvportfolio_sort' ] ) ) { 

                $inline_js[ 'csort' ] = 1;

            }

            switch($mfetch){

                case 'button':

                    $scripts[ 'extend-scrf' ] = 'js/extend.scrf.js';                

                break;

                case 'nav':

                $scripts[ 'jquery-simplePagination' ] = 'js/jquery.simplePagination.js';

                $scripts[ 'extend-scrf' ] = 'js/extend.scrf.js';                

                break;    

            }

            

            $citems = "#{$cw} .pfo-item"; 

            

            

            foreach( $scripts as $js_handle => $script ) {

                

                $script = self::getAssets( $script );

                

                wp_enqueue_script( $js_handle, $script, array(), '1.0', true );    

            }

            

            wp_register_script( 'jv-pfo', self::getAssets( 'js/dump.js' ) );

            wp_localize_script( 'jv-pfo', 'JV', $inline_js );

            wp_enqueue_script( 'jv-pfo' );

            

            foreach( $skins as $shandle => $skin ) {

                

                $skin = self::getAssets( $skin );

                

                wp_enqueue_style( $shandle, $skin, array(), '1.0' );

            }

        }

        

        public static function nextpage( $url = '' ) {

            

            if( !preg_match( '/format=hfetch/', $url ) ) {

                

                $url = !preg_match( '/\?/', $url ) ? "{$url}?format=hfetch" : "{$url}&format=hfetch";

            }

            return $url;

        }

        

        public static function toArray( $v ) {

            

            return $v ? explode( ",", $v ) : array();

            

        }

        

        public static function jvpfo( $atts ){

            

            $defaults   = self::get_meta(); 

            $tmpl       = dirname( __FILE__ ). "/inc/shortcode.php";

            $maps       = array(

                'col'       => 'jvportfolio_col',

                'limit'     => 'jvportfolio_limit',

                'filter'    => 'jvportfolio_filter',
                'sort'      => 'jvportfolio_sort',

                'tags'      => 'jvportfolio_tags',

                'mfetch'    => 'jvportfolio_mfetch',

                'stitle'    => 'jvportfolio_stitle',

                'stag'      => 'jvportfolio_stag',

                'sdate'     => 'jvportfolio_sdate',

                'slink'     => 'jvportfolio_slink',

                'sdesc'     => 'jvportfolio_sdesc'

            );

            $types      = array(   

                'jvportfolio_sort' => 'jvportfolio::toArray',

                'jvportfolio_tags' => 'jvportfolio::toArray'    

            );

            $data       = array();

            

            if( is_array( $atts ) ) { 

                

                

                if( isset( $atts[ 'tmpl' ] ) ) {

                    

                    $template_name = "{$atts[ 'tmpl' ]}.php";

                    

                    if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {

                        

                        $tmpl = STYLESHEETPATH . '/' . $template_name; 

                        

                    } else if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {

                        

                        $tmpl = TEMPLATEPATH . '/' . $template_name;

                        

                    }                                              

                    

                }

                

                foreach( $maps as $k => $kv ) {

                

                    if( !isset( $atts[ $k ] ) ) { continue; }

                    

                    $data[ $kv ] = $atts[ $k ];

                    

                    if( !isset( $types[ $kv ] ) ) { continue; }

                    

                    $data[ $kv ] = call_user_func( $types[ $kv ], $data[ $kv ] );

                    

                }

                

            }

                    

            

            $postmeta   = $data;

            $postmeta   = shortcode_atts( $defaults, $postmeta );

            

            ob_start();

            

            require_once( $tmpl );

            

            return ob_get_clean();    
        }    
	}
    

    /* get post meta */

    add_filter( 'jvportoflio_get_meta', 'jvportfolio::get_meta', 10, 1 ); 

    

    /* get categories */

    add_filter( 'jvportoflio_getcategories', 'jvportfolio::get_categories', 10, 1 ); 

    

    /* get time sort */

    add_filter( 'jvportoflio_getTimeSort', 'jvportfolio::getTimeSort', 10, 1 ); 

    

    /* get terms sort */

    add_filter( 'jvportoflio_getTermsSort', 'jvportfolio::getTermsSort', 10, 1 );

    

    /* get gird width */

    add_filter( 'jvportfolio_col', 'jvportfolio::getColumn', 10, 1 );

    

    /* get id portfolio */

    add_filter( 'jvportfolio_id', 'jvportfolio::getId', 10, 1 );

    

    /* get quick view */

    add_filter( 'jvportfolio_qview', 'jvportfolio::qview', 10, 2 );

    

    /* get assets */

    add_filter( 'jvportfolio_getassets', 'jvportfolio::getAssets', 10, 2 );

    

    /* add LIB JS & CSS */

    add_action( 'jvportfolio_ajax', 'jvportfolio::ajax', 10, 4 ); 

    

    /* get link next page*/

    add_action( 'jvportfolio_nextpage', 'jvportfolio::nextpage', 10, 1 ); 

    

    /* add shortcode */

    add_shortcode( 'jvpfo', 'jvportfolio::jvpfo' );


}