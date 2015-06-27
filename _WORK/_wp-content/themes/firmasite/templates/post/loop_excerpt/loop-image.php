<?php
/**
 * @package firmasite
 */
 global $post, $firmasite_settings;

	$caption_shortcode = firmasite_extract_from_string('[caption', ']', $post->post_content);	
 ?>
 <article id="post-<?php the_ID(); ?>" <?php post_class("loop_list format_image"); ?>>
 <div class="panel panel-default">
    <div class="entry-content col-xs-12 col-md-12 fs-content-thumbnail">
		<?php if ( has_post_thumbnail() && !$caption_shortcode){?>
          <?php  the_post_thumbnail("large"); ?>
        <?php } ?>
    </div>
   <div class="panel-body">
   	<div class="entry-content">
       	<h4 class="entry-title"><strong><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'firmasite' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></strong></h4>
		 <?php if ( !$caption_shortcode){?>
         <?php the_excerpt(); ?>
         <?php } else {?>
         <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) ); ?>
         <?php } ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links"><ul class="pagination pagination-sm">', 'after' => '</ul></div>' ) ); ?>
        <?php if (empty($post->post_title)){ ?>
        <a class="" href="<?php the_permalink(); ?>" rel="bookmark">
            <small><i class="icon-bookmark"></i><?php  _e( 'Permalink', 'firmasite' ); ?></small>
        </a>
        <?php } ?>
		<div class="clearfix"></div>
    </div>
 </div>
</article><!-- #post-<?php the_ID(); ?> -->