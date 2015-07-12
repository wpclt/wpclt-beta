<?php
/**
 * @package wpclt
 */

    $presenterId = get_post_meta( $post->ID, 'wpclt_presentation_presenter', true );
    $presenter = get_user_meta( $presenterId, null, true);
    $presenter_avatar = get_avatar( $presenterId );
    $presenter_name = $presenter['first_name'][0] . " " . $presenter['last_name'][0];

    $meetup_link = get_post_meta( $post->ID, 'wpclt_presentation_meetup_com_event_link', true );
    $date_time = get_post_meta( $post->ID, 'wpclt_presentation_date_time', true );

    $presentation_file_1 = get_post_meta( $post->ID, 'wpclt_presentation_presentation_file_1', true );
    $presentation_file_2 = get_post_meta( $post->ID, 'wpclt_presentation_presentation_file_2', true );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_post_thumbnail( ); ?>
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <?php echo $date_time; ?>
        <?php echo $presenter_name; ?>
    </header><!-- .entry-header -->
    <div class="presentation-meta">
        <a href="<?php echo $meetup_link; ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/meetup-logo.png" /></a>

        <?php echo $presenter_avatar; ?>
        <?php echo $presenter_name; ?>
    </div>
    <div class="entry-content">
        <?php the_content(); ?>
        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'wpclt' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php wpclt_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
