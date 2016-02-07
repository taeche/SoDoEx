<?php
/**
 * Template Name: Content with comment
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in JV Allinone consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns. 
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

<div id="content-sidebar" class="container">
        <div id="content" class="site-content col-md-9" role="main">
            <?php while ( have_posts() ) : the_post(); ?>
                 <?php  the_content(); ?>
                 <?php comments_template(); ?>
            <?php endwhile; // end of the loop. ?>
        </div><!-- #content -->
</div>

<?php get_footer();