<?php 
global $wpsl_settings, $wpsl;

$output         = $this->get_custom_css(); 
$autoload_class = ( !$wpsl_settings['autoload'] ) ? 'class="wpsl-not-loaded"' : '';

// styles to set store results floating on the map
$listHeight = $wpsl_settings['height'];
$output .= '<style>.wpsl-store-float #wpsl-result-list{overflow: hidden;z-index: 1;position: absolute;top:10px;width: 300px;padding: 0px;border: 7px solid rgba(0, 0, 0, .1);</style>' . "\r\n";
$output .= '<style>.wpsl-store-float #wpsl-gmap{float:none;width:100%;height:100%}</style>' . "\r\n";
$output .= '<style>.wpsl-store-float #wpsl-stores, #wpsl-direction-details{background-color:rgb(240,240,240);}</style>' . "\r\n";
$output .= '<style>.wpsl-store-float #wpsl-result-title{overflow-y:auto;z-index: 1;background-color: rgba(76, 92, 173, .9);height: 30px;font-size: 15px;text-align: center;line-height: 30px;text-wrap: none;padding: 0px;}</style>' . "\r\n";
$output .= '<style>.wpsl-store-float a.titleClose{position: absolute;top: 3px;right: 7px;z-index: 1;padding 7px;padding-right:8px;width: 12px;height: 12px;display: block;}</style>' . "\r\n";
$output .= '<style>.wpsl-store-float #wpsl-direction-details, #wpsl-stores{height:' . ($listHeight - 30 - 10) . 'px !important}</style>' . "\r\n";


$output .= '<div id="wpsl-wrap" class="wpsl-store-float">' . "\r\n";
$output .= "\t" . '<div class="wpsl-search wpsl-clearfix ' . $this->get_css_classes() . '">' . "\r\n";
$output .= "\t\t" . '<div id="wpsl-search-wrap">' . "\r\n";
$output .= "\t\t\t" . '<div class="wpsl-input">' . "\r\n";
$output .= "\t\t\t\t" . '<div><label for="wpsl-search-input">' . esc_html( $wpsl->i18n->get_translation( 'search_label', __( 'Your location', 'wpsl' ) ) ) . '</label></div>' . "\r\n";
$output .= "\t\t\t\t" . '<input autocomplete="off" id="wpsl-search-input" type="text" value="" name="wpsl-search-input" />' . "\r\n";
$output .= "\t\t\t" . '</div>' . "\r\n";

if ( $wpsl_settings['radius_dropdown'] || $wpsl_settings['results_dropdown']  ) {
    $output .= "\t\t\t" . '<div class="wpsl-select-wrap">' . "\r\n";

    if ( $wpsl_settings['radius_dropdown'] ) {
        $output .= "\t\t\t\t" . '<div id="wpsl-radius">' . "\r\n";
        $output .= "\t\t\t\t\t" . '<label for="wpsl-radius-dropdown">' . esc_html( $wpsl->i18n->get_translation( 'radius_label', __( 'Search radius', 'wpsl' ) ) ) . '</label>' . "\r\n";
        $output .= "\t\t\t\t\t" . '<select autocomplete="off" id="wpsl-radius-dropdown" class="wpsl-dropdown" name="wpsl-radius">' . "\r\n";
        $output .= "\t\t\t\t\t\t" . $this->get_dropdown_list( 'search_radius' ) . "\r\n";
        $output .= "\t\t\t\t\t" . '</select>' . "\r\n";
        $output .= "\t\t\t\t" . '</div>' . "\r\n";
    }

    if ( $wpsl_settings['results_dropdown'] ) {
        $output .= "\t\t\t\t" . '<div id="wpsl-results">' . "\r\n";
        $output .= "\t\t\t\t\t" . '<label for="wpsl-results-dropdown">' . esc_html( $wpsl->i18n->get_translation( 'results_label', __( 'Results', 'wpsl' ) ) ) . '</label>' . "\r\n";
        $output .= "\t\t\t\t\t" . '<select autocomplete="off" id="wpsl-results-dropdown" class="wpsl-dropdown" name="wpsl-results">' . "\r\n";
        $output .= "\t\t\t\t\t\t" . $this->get_dropdown_list( 'max_results' ) . "\r\n";
        $output .= "\t\t\t\t\t" . '</select>' . "\r\n";
        $output .= "\t\t\t\t" . '</div>' . "\r\n";
    }
    
    $output .= "\t\t\t" . '</div>' . "\r\n";
}

if ( $wpsl_settings['category_dropdown'] ) {
    $output .= $this->create_category_filter();
}
 
$output .= "\t\t\t\t" . '<div class="wpsl-search-btn-wrap"><input id="wpsl-search-btn" type="submit" value="' . esc_attr( $wpsl->i18n->get_translation( 'search_btn_label', __( 'Search', 'wpsl' ) ) ) . '"></div>' . "\r\n";

$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t" . '</div>' . "\r\n";
    
if ( $wpsl_settings['reset_map'] ) { 
    $output .= "\t" . '<div class="wpsl-gmap-wrap">' . "\r\n";
    $output .= "\t\t" . '<div id="wpsl-gmap" class="wpsl-gmap-canvas"></div>' . "\r\n";
    $output .= "\t" . '</div>' . "\r\n";
} else {
    $output .= "\t" . '<div id="wpsl-gmap" class="wpsl-gmap-canvas"></div>' . "\r\n";
}

$output .= "\t" . '<div id="wpsl-result-list">' . "\r\n";



// title bar for the store results.
$output .= "\t" . '<div id="wpsl-result-title"><a style="color:white;">Store Results</a>' . "\r\n";
$output .= "\t" . '<a class="titleClose" title="Close" id="aClose" href="#">' . "\r\n";
$output .= "\t" . '<svg style="background-image:none;" enable-background="new 0 0 9 9" viewBox="0 0 9 9" focusable="false" xmlns="http://www.w3.org/2000/svg">' . "\r\n";
$output .= "\t" . '<path fill="white" d="M 9 0.6 l -0.6 -0.6 l -3.9 3.9 l -3.9 -3.9 l -0.6 0.6 l 3.9 3.9 l -3.9 3.9 l 0.6 0.6 l 3.9 -3.9 l 3.9 3.9 l 0.6 -0.6 l -3.9 -3.9 Z"></path>' . "\r\n";
$output .= "\t" . '</svg>' . "\r\n";
$output .= "\t" . '</a>' . "\r\n";
$output .= "\t" . '</div>' . "\r\n";
$output .= '<script type="text/javascript">jQuery("#aClose").click(function(){jQuery("#wpsl-result-list").fadeOut("slow")});' . "\r\n";
$output .= 'jQuery("#wpsl-search-btn").click(function(){jQuery("#wpsl-result-list").fadeIn("slow")});' . "\r\n";
$output .= 'jQuery("#wpsl-result-list").css("top", jQuery("#wpsl-gmap").position().top);</script>' . "\r\n";


$output .= "\t\t" . '<div id="wpsl-stores" '. $autoload_class .'>' . "\r\n";
$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";

$output .= "\t\t" . '<div id="wpsl-direction-details">' . "\r\n";
$output .= "\t\t\t" . '<ul></ul>' . "\r\n";
$output .= "\t\t" . '</div>' . "\r\n";
$output .= "\t" . '</div>' . "\r\n";

if ( $wpsl_settings['show_credits'] ) { 
    $output .= "\t" . '<div class="wpsl-provided-by">'. sprintf( __( "Search provided by %sWP Store Locator%s", "wpsl" ), "<a target='_blank' href='http://wpstorelocator.co'>", "</a>" ) .'</div>' . "\r\n";
}

$output .= '</div>' . "\r\n";

return $output;