<?php
/**
 * Variable product add to cart
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post;
?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart borderBottom" method="post" enctype='multipart/form-data' data-product_id="<?php echo esc_attr($post->ID); ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php if ( ! empty( $available_variations ) ) : ?>
		<div class="variations">

				<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
					<div class="item">
						<label for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label( $name ); ?></label>
						<div class="css-select"><select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>">
							<option value=""><?php echo __( 'Choose an option', 'jv_allinone' ) ?>&hellip;</option>
							<?php
								if ( is_array( $options ) ) {

									
									
									if ( isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
										$selected_value = $_POST[ 'attribute_' . sanitize_title( $name ) ];
									}
									else{
											if( isset( $_GET[ 'attribute_' . sanitize_title( $name ) ] ) ){
												$selected_value = $_GET[ 'attribute_' . sanitize_title( $name ) ];
											}
											else{
													if( isset( $selected_attributes[ sanitize_title( $name ) ] )){
															$selected_value = $selected_attributes[ sanitize_title( $name ) ];
													}
													else{
															$selected_value = '';
													}
											}
									}

									// Get terms if this is a taxonomy - ordered
									if ( taxonomy_exists( sanitize_title( $name ) ) ) {

										$orderby = wc_attribute_orderby( sanitize_title( $name ) );

										switch ( $orderby ) {
											case 'name' :
												$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
											break;
											case 'id' :
												$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false, 'hide_empty' => false );
											break;
											case 'menu_order' :
												$args = array( 'menu_order' => 'ASC', 'hide_empty' => false );
											break;
										}

										$terms = get_terms( sanitize_title( $name ), $args );

										foreach ( $terms as $term ) {
											if ( ! in_array( $term->slug, $options ) )
												continue;

											echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
										}
									} else {

										foreach ( $options as $option ) {
											echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
										}

									}
								}
							?>
						</select> </div>
                        <div class="bottom-border"></div>
					</div>
                    
                    
                    <?php
							if ( sizeof( $attributes ) == $loop )
								echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'jv_allinone' ) . '</a>';
						?>
		        <?php endforeach;?>

		</div>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap" style="display:none;">
			<?php do_action( 'woocommerce_before_single_variation' ); ?>

			<div class="single_variation"></div>

			<div class="variations_button">
				<?php woocommerce_quantity_input(); ?>
				<button type="submit" class="single_add_to_cart_button btn btn-primary alt"><i class="icon-cart42"></i> <?php echo esc_html($product->single_add_to_cart_text()); ?></button>
			</div>
		   	<?php if(shortcode_exists( 'yith_wcwl_add_to_wishlist' )){?><div class="item-btn"> <?php  echo do_shortcode("[yith_wcwl_add_to_wishlist]"); ?></div><?php } ?> 
		    <?php if(shortcode_exists( 'yith_compare_button' )){?><div class="item-btn"> <?php  echo do_shortcode("[yith_compare_button]"); ?></div><?php } ?> 


			
			<input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->id); ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			<input type="hidden" name="variation_id" value="" />

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php else : ?>

		<p class="stock out-of-stock"><?php esc_attr_e( 'This product is currently out of stock and unavailable.', 'jv_allinone' ); ?></p>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' );
