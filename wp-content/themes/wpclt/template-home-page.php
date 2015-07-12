<?php
/*
 * Template Name: Home Page
 */
get_header(); ?>
<div class="container-full bg-pic-wordcamp">

    <div class="wide-text-bar text-bar-grey">
        <h2>WPCLT  is an easy way to learn, network and expand your knowledge of WordPress.</h2>
    </div>
</div>
<div class="container">
    <div class="row" style="height: 75px; clear: both;"></div>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'content', 'page' ); ?>
            <?php endwhile; // end of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
