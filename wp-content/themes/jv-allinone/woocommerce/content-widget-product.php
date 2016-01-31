<?php 
/** 
* @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 */
global $product; ?>
<div class="item">
	<div class="inner-item">

        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>   
        
		<a class="product-img"  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php echo apply_filters( 'esc_html', get_the_post_thumbnail($product->post->ID,array(80,80)) ); ?>
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

