<?php
add_action('init', 'ninja_annc_cpt');
function ninja_annc_cpt() {
	register_post_type( 'ninja_annc',
		array(
			'labels' => array(
				'name' => __( 'Ninja Announcements' , 'ninja-announcements'),
				'singular_name' => __( 'Announcement' , 'ninja-announcements'),
				'not_found' => __('No Announcements Found', 'ninja-announcements'),
				'new_item' => __('New Announcement', 'ninja-announcements'),
				'add_new_item' => __('New Announcement', 'ninja-announcements'),
				'edit_item' => __('Edit Announcement', 'ninja-announcements'),
			),
		'public' => true,
		'has_archive' => false,
		'exclude_from_search' => true,
		'menu_icon' => plugins_url( 'images/ninja_announc_icon.png' , dirname(__FILE__) ),
		)
	);
}