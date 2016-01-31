<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $wp_query, $author;
$wrap_before = "<ul id='breadcrumb' class='breadcrumb'>";$wrap_after = "</ul>";
$before = "<li>";$after = "</li>";
$delimiter = "";

$prepend      = '';
$permalinks   = get_option( 'woocommerce_permalinks' );
$shop_page_id = wc_get_page_id( 'shop' );
$shop_page    = get_post( $shop_page_id );

// If permalinks contain the shop page in the URI prepend the breadcrumb with shop
if ( $shop_page_id && $shop_page && strstr( $permalinks['product_base'], '/' . $shop_page->post_name ) && get_option( 'page_on_front' ) != $shop_page_id ) {
	$prepend = $before . '<a href="' . get_permalink( $shop_page ) . '">' . $shop_page->post_title . '</a> ' . $after . $delimiter;
}

if ( ( ! is_front_page() && ! ( is_post_type_archive() && get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) ) || is_paged() ) {

	echo $wrap_before;

	if ( ! empty( $home ) ) {
		echo $before . '<a class="home " href="' . esc_url(apply_filters( 'woocommerce_breadcrumb_home_url', home_url() )) . '">' . esc_html($home) . '</a>' . $after . $delimiter;
	}

	if ( is_home() ) {

		echo $before . single_post_title('', false) . $after;

	} elseif ( is_category() ) {

		$cat_obj = $wp_query->get_queried_object();
		$this_category = get_category( $cat_obj->term_id );

		if ( 0 != $this_category->parent ) {
			$parent_category = get_category( $this_category->parent );
			if ( ( $parents = get_category_parents( $parent_category, TRUE, $after . $delimiter . $before ) ) && ! is_wp_error( $parents ) ) {
				echo $before . rtrim( $parents, $after . $delimiter . $before ) . $after . $delimiter;
			}
		}

		echo $before . single_cat_title( '', false ) . $after;

	} elseif ( is_tax( 'product_cat' ) ) {

		echo $prepend;

		$current_term = $wp_query->get_queried_object();

		$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'product_cat' ) );

		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'product_cat' );

			echo $before .  '<a href="' . esc_url(get_term_link( $ancestor )) . '">' . esc_html( $ancestor->name ) . '</a>' . $after . $delimiter;
		}

		echo $before . esc_html( $current_term->name ) . $after;

	} elseif ( is_tax( 'product_tag' ) ) {

		$queried_object = $wp_query->get_queried_object();
		echo $prepend . $before . __( 'Products tagged &ldquo;', 'jv_allinone' ) . esc_html($queried_object->name) . '&rdquo;' . $after;

	} elseif ( is_day() ) {
	//get_the_time ==date

		echo $before . '<a href="' . esc_url(get_year_link( date( 'Y' ) )) . '">' . esc_html(date( 'Y' )) . '</a>' . $after . $delimiter;
		echo $before . '<a href="' . esc_url(get_month_link( date( 'Y' ), date( 'm' ) )) . '">' . esc_html(date( 'F' )) . '</a>' . $after . $delimiter;
		echo $before . date( 'd' ) . $after;

	} elseif ( is_month() ) {

		echo $before . '<a href="' . esc_url(get_year_link( date( 'Y' ) )) . '">' . esc_html(date( 'Y' )) . '</a>' . $after . $delimiter;
		echo $before . date( 'F' ) . $after;

	} elseif ( is_year() ) {

		echo $before . date( 'Y' ) . $after;

	} elseif ( is_post_type_archive( 'product' ) && get_option( 'page_on_front' ) !== $shop_page_id ) {

		$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';

		if ( ! $_name ) {
			$product_post_type = get_post_type_object( 'product' );
			$_name = $product_post_type->labels->singular_name;
		}

		if ( is_search() ) {

			echo $before . '<a href="' . esc_url(get_post_type_archive_link( 'product' )) . '">' . esc_html($_name) . '</a>' . $delimiter . __( 'Search results for &ldquo;', 'jv_allinone' ) . get_search_query() . '&rdquo;' . $after;

		} elseif ( is_paged() ) {

			echo $before . '<a href="' . esc_url(get_post_type_archive_link( 'product' )) . '">' . esc_html($_name) . '</a>' . $after;

		} else {

			echo $before . $_name . $after;

		}

	} elseif ( is_single() && ! is_attachment() ) {



		if ( 'product' == get_post_type() ) {

			echo $prepend;

			if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
				$main_term = $terms[0];
				$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor ) {
					$ancestor = get_term( $ancestor, 'product_cat' );

					if ( ! is_wp_error( $ancestor ) && $ancestor ) {
						echo $before . '<a href="' . esc_url(get_term_link( $ancestor )) . '">' . esc_html($ancestor->name) . '</a>' . $after . $delimiter;
					}
				}

				echo $before . '<a href="' . esc_url(get_term_link( $main_term )) . '">' . esc_html($main_term->name) . '</a>' . $after . $delimiter;

			}

			echo $before . get_the_title() . $after;

		} elseif ( 'post' != get_post_type() ) {

			$post_type = get_post_type_object( get_post_type() );
			$slug = $post_type->rewrite;
			echo $before . '<a href="' . esc_url(get_post_type_archive_link( get_post_type() )) . '">' . esc_html($post_type->labels->singular_name) . '</a>' . $after . $delimiter;
			echo $before . get_the_title() . $after;

		} else {

			$cat = current( get_the_category() );
			if ( ( $parents = get_category_parents( $cat, TRUE, $after . $delimiter . $before ) ) && ! is_wp_error( $parents ) ) {
				echo $before . rtrim( $parents, $after . $delimiter . $before ) . $after . $delimiter;
			}
			echo $before . get_the_title() . $after;

		}

	} elseif ( is_404() ) {

		echo $before . __( 'Error 404', 'jv_allinone' ) . $after;

	} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' ) {

		$post_type = get_post_type_object( get_post_type() );

		if ( $post_type ) {
			echo $before . $post_type->labels->singular_name . $after;
		}

	} elseif ( is_attachment() ) {

		$parent = get_post( $post->post_parent );
		$cat = get_the_category( $parent->ID );
		$cat = $cat[0];
		if ( ( $parents = get_category_parents( $cat, TRUE, $after . $delimiter . $before ) ) && ! is_wp_error( $parents ) ) {
			echo $before . rtrim( $parents, $after . $delimiter . $before ) . $after . $delimiter;
		}
		echo $before . '<a href="' . get_permalink( $parent ) . '">' . esc_html($parent->post_title) . '</a>' . $after . $delimiter;
		echo $before . get_the_title() . $after;

	} elseif ( is_page() && ! $post->post_parent ) {

		echo $before . get_the_title() . $after;

	} elseif ( is_page() && $post->post_parent ) {

		$parent_id  = $post->post_parent;
		$breadcrumbs = array();

		while ( $parent_id ) {
			$page          = get_post( $parent_id );
			$breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
			$parent_id     = $page->post_parent;
		}

		$breadcrumbs = array_reverse( $breadcrumbs );

		foreach ( $breadcrumbs as $crumb ) {
			echo $before . $crumb . $after . $delimiter;
		}

		echo $before . get_the_title() . $after;

	} elseif ( is_search() ) {

		echo $before . __( 'Search results for &ldquo;', 'jv_allinone' ) . get_search_query() . '&rdquo;' . $after;

	} elseif ( is_tag() ) {

			echo $before . __( 'Posts tagged &ldquo;', 'jv_allinone' ) . single_tag_title( '', false ) . '&rdquo;' . $after;

	} elseif ( is_author() ) {

		$userdata = get_userdata( $author );
		echo $before . __( 'Author:', 'jv_allinone' ) . ' ' . $userdata->display_name . $after;

	}

	if ( get_query_var( 'paged' ) ) {
		echo ' (' . __( 'Page', 'jv_allinone' ) . ' ' . get_query_var( 'paged' ) . ')';
	}

	echo $wrap_after;

}
