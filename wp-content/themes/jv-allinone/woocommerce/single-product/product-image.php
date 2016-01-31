<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

if( class_exists( 'JVLibrary' ) && $config = JVLibrary::getConfig() ) {
	$imageWoo = @$config->imageWoo; 
}else{
	$imageWoo = 'default';
}

if ($imageWoo=='woocommerce core'  ){ ?>
<div class="shopImages col-md-4 col-sm-4  ">

	<?php
		if ( has_post_thumbnail() ) {

			$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	=> $image_title,
				'alt'	=> $image_title
				) );

			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_caption, $image ), $post->ID );

		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'jv_allinone' ) ), $post->ID );

		}
	?>

	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>
<?php 
}else { ?>
<div class="shopImages col-md-4 col-sm-4  ">





<?php

			$attachment_ids =  $product->get_gallery_attachment_ids() ;
?>

    <div class="imgMainProduct  owl-carousel">
    <?php
            if ( has_post_thumbnail() ) { ?>
                <div class="item rzoom images">
	<?php
		if ( has_post_thumbnail() ) {

			$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	=> $image_title,
				'alt'	=> $image_title
				) );

			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '[product-gallery]';
			}

			echo apply_filters( 'woocommerce_single_product_image_html', $image, $post->ID );

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="zoom zoom-item" class="woocommerce-main-image " title="%s" data-rel="prettyPhoto' . $gallery . '"><i class="icon-expand4"></i></a>', $image_link, $image_caption, $image ), $post->ID );
		
		
		
		
		
		
		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'jv_allinone' ) ), $post->ID );

		}
	?>
                    
                    
                    
                    

                </div>
            <?php
            }
    
            foreach( $attachment_ids as $attachment_id ) { ?>
                <div class="item rzoom">
                    <img align="" src="<?php echo esc_attr( wp_get_attachment_url( $attachment_id ) ); ?>" />
                    <a class="zoom zoom-item" href="<?php echo esc_url( wp_get_attachment_url( $attachment_id ) ); ?>" itemprop="image" data-rel="prettyPhoto[product-gallery]" ><i class="icon-expand4"></i></a>
                </div>
            <?php
            }
        ?>
    </div>    
    <div class="bottom-border"></div> <br />
    
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
			do_action( 'woocommerce_before_shop_loop_item_title' );//for woocommerce-new-product-badge
		} 
	?>
    
    <?php if( count( $attachment_ids ) >= 1 ) { ?>

    <div class="imgsubproduct  owl-carousel space10">    
<?php
		if ( has_post_thumbnail() ) { ?>
			<div class="item"> <img src="<?php echo esc_url($image_link);  ?>"  /></div>		
<?php
		}
		foreach( $attachment_ids as $attachment_id ) 
		{
			echo apply_filters( 'esc_html', "<div class='item'><img  src='".wp_get_attachment_url( $attachment_id )."' /></div>" );
		}
    ?>
    </div>
    
    <?php } ?>
	

</div>
<?php 
}
