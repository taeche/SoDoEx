<?php
/** 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/
$output = $el_class = $css_animation = '';

call_user_func( JVLibrary::getFnCheat( 'ex'), shortcode_atts(array(
    'el_class' => '',
    'css_animation' => '',
    'css' => '',
    'title' => '',
    'stitle' => ''
), $atts) );

$el_class = $this->getExtraClass($el_class);

$heading = '';
if( $title ) {
    
    $stitle = !$stitle ? "" : "<span class='sub-title'>{$stitle}</span>";
    $heading = "<h2 class='widgettitle'>{$title}{$stitle}</h2>";
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_text_column wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$css_class .= $this->getCSSAnimation($css_animation);
$output .= "\n\t".'<div class="'.esc_attr( $css_class ).'">';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t{$heading}";
$output .= "\n\t\t\t<div class='wpb_container'>" . wpb_js_remove_wpautop( $content , true )."</div>";
$output .= "\n\t\t".'</div> ' . $this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> ' . $this->endBlockComment('.wpb_text_column');

echo $output;