<?php
/**
 *
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template. 
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
         <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php the_breadcrumb(); ?>
        </div>
</section>

<div id="layoutPage" class="container">
    <div id="content" class="site-content" role="main">
        <?php while ( have_posts() ) : the_post(); ?>
             <?php  the_content(); ?>
        <?php endwhile; // end of the loop. ?>
    </div><!-- #content -->
</div>

<?php get_footer();