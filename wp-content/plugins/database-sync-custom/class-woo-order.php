<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-12-30
 * Time: 오전 6:31
 */
class class_woo_order
{
    public $body = array(
        'ID' => '',
        'old_id' => '',
        'post_type' => '',
        'post_status' => '',
        'post_title' => '',
        'post_name' => '',
        'post_date' => '',
        'post_date_gmt' => '',
        'post_content' => '',
        'post_excerpt' => '',
        'post_parent' => 0,
        'post_password' => '',
        'comment_status' => '',
        'ping_status' => '',
        'menu_order' => 0,
        'post_author' => '',
    );

    public $meta = array();

    public function save()
    {

        global $wpdb;
        //save the post
        $post_id = wp_insert_post($this->body, true);


        if (is_wp_error($post_id)) {

            return;
        } else {
            $this->body['ID'] = $post_id;
        }


        //save the meta
        foreach ($this->meta as $key => $value) {
            update_post_meta($post_id, $key, $value);
            // update order_id(post_id) in woocommerce_order_items table
            $args=array( 'order_id' => $post_id);
            $update = $wpdb->update( $wpdb->prefix . 'woocommerce_order_items', $args, array( 'order_id' => $this->body['old_id']  ) );

            if ( false === $update ) {
                return false;
            }
        }


        //and return the ID
        return $post_id;
    }
}