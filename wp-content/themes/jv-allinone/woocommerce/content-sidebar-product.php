<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Change number or products per row to 4

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();

if ( 0 != ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 != $woocommerce_loop['columns'] )
	$classes[] = 'item  ';

if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'item';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'item ';
?>




<div <?php post_class( $classes ); ?> >
	<div class="inner-item">

        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>


		<?php

			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
		
		
		
		?>
        
		<a class="product-img"  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php
			echo apply_filters( 'esc_html', get_the_post_thumbnail($product->post->ID,array(80,80)) );
		?>
        </a>
       

    
    <div class="content-item-description">
    

    
    <h3 class="product-title">    
        <a  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
        </a>

    </h3>      
    <?php woocommerce_template_loop_rating();	?>  
	<div class="product-price"><?php	woocommerce_template_loop_price();	?></div>
    

    
        
    </div>    


	</div>
    <div class="bottom-border"> </div>
</div>
    