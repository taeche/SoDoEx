<?php
/**
 *
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely. 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 
?>

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="secondary" class="widget-area sidebar col-md-3" role="complementary">
		<div class="inner-sidebar">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->
	</div>
<?php endif;