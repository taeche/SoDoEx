<?php
/**
 * The template for displaying 404 pages (Not Found) 
 *
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/ 
get_header(); ?>

<section id="block-breadcrumb">
    <div class="container">
         <h1 class="entry-title"><?php esc_attr_e( 'Page 404', 'jv_allinone' ); ?></h1>
        <?php the_breadcrumb(); ?>
        </div>
</section>

    <div id="maincontent" role="main" class="container page-404">
        <article id="post-0" class="post  no-results not-found">
        		<h2><?php esc_attr_e( '404', 'jv_allinone' ); ?></h2>
                <h3><?php esc_attr_e( 'Oops! Page not found', 'jv_allinone' ); ?></h3>
                <p class="sorry"><?php esc_attr_e( 'Sorry&#44; but the page you are looking for is not found. Please, make sure you have typed the current URL.', 'jv_allinone' ); ?></p>
                <?php get_search_form(); ?>
        </article><!-- #post-0 -->
    </div><!-- #content -->


<?php get_footer();