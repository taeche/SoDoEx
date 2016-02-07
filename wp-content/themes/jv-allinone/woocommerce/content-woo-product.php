<?php
/**
 * @package Allinone
 * @subpackage @subpackage JV_Allinone
 * @since JV Allinone 1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );


$columnsWoo = $woocommerce_loop['columns'];
	
if ($columnsWoo < 1 )
{
	$columnsWoo= 1;
}
elseif ($columnsWoo ==5)
{
	$columnsWoo = 4;
}
elseif ($columnsWoo > 6)
{
	$columnsWoo = 6;		
}
	

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
global $product;

?>

<div class="item  fffffffffffffffffff
<?php 
if($woocommerce_loop['columns'] > 1 ) {
	echo 'col-xs-6 col-md-'.(12/$woocommerce_loop['columns']); 
	if($woocommerce_loop['columns'] == 2 ) { 
		echo '  col-sm-6 '; 
	}else{
		{ echo '  col-sm-4 '; }
	}
} 
?> ">

<div class="inner-item">

        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>



		
	<div class="product-img">
		
        
		<a  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php
			echo apply_filters( 'esc_html', get_the_post_thumbnail($product->post->ID,array(370,370)) );
		?>
        </a>
		
		<?php		
		woocommerce_show_product_sale_flash( );
		?>
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			 if (function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce-new-product-badge/new-badge.php' ) ) {
			  //plugin is activated
				remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
				remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
				do_action( 'woocommerce_before_shop_loop_item_title' );
			} 
		?>
                
        

 		<?php
        $countbtn = 1; 
        if(shortcode_exists( 'yith_wcwl_add_to_wishlist' )) { $countbtn = $countbtn + 1; }
        if(shortcode_exists( 'yith_compare_button' )) { $countbtn = $countbtn + 1; }
        ?>
       <div class="product-action countbtn_<?php echo $countbtn; ?>">
		   	<?php if(shortcode_exists( 'yith_wcwl_add_to_wishlist' )){?><div class="item-btn"> <?php  echo do_shortcode("[yith_wcwl_add_to_wishlist]"); ?></div><?php } ?> 
		    <?php if(shortcode_exists( 'yith_compare_button' )){?><div class="item-btn"> <?php  echo do_shortcode("[yith_compare_button]"); ?></div><?php } ?>  
            <div class="item-btn"><a class="product-detail btn" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><i class="icon-search32"></i></a></div>
        </div> 

	</div>    
    
    <div class="content-item-description">
    

    <?php woocommerce_template_loop_rating();	?>
    <h3 class="product-title">    
        <a  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_title(); ?>
        </a>

    </h3>        
	<div class="product-price"><?php	woocommerce_template_loop_price();	?></div>
    <div class="desc"><?php   echo apply_filters( 'esc_html', $product->post->post_excerpt ); ?></div>
    <?php do_action( 'woocommerce_after_shop_loop_item' ); // add to cart ?>
    
        
    </div>    
    <div class="bottom-border"> </div>

	</div>
</div>    


