<?php
/**
 *
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements. 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 
?>


	<?php if ( is_active_sidebar( 'footer' ) ) : ?>
	<footer id="Footer" role="contentinfo">
        <div class="container">
        <div class="row">
            	<?php dynamic_sidebar( 'footer' ); ?>
         </div>       
        </div>
	</footer>
    <?php endif; ?>
  <!-- #colophon -->
  
</div></div>
<!-- #page -->
<div id="searchtop" >
<div class="container">
	<?php echo apply_filters( 'render_form_search', '' ); ?>
    <span id="search-beack"></span>
</div>
</div>


<?php echo JVLibrary::showDemo(); ?>
<?php  wp_footer(); ?>
</body></html>