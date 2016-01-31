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
?>
<form role="search" method="get" id="searchform-wp" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" 
		placeholder="<?php esc_attr_e( 'Search here and press enter', 'jv_allinone' ); ?>"
		
		value="<?php echo esc_attr(get_search_query()); ?>" name="s" id="s" />
		<!-- placeholder="<?php //echo esc_attr_x( 'Search here and press enter...', 'submit button' ); ?>"  -->
        <button type="submit" class="btn" id="searchsubmit"><i class="icon-search8"></i></button>
</form>