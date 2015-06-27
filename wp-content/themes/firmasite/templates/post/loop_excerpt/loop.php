<?php
/**
 * @package firmasite
 */
 global $firmasite_settings;
 ?>
 
<article id="post-<?php the_ID(); ?>" <?php post_class("loop_excerpt"); ?>>
 <div class="panel panel-default">
   <div class="panel-body clearfix">
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="entry-thumbnail col-xs-4 col-md-4 pull-left fs-content-thumbnail">
      <?php the_post_thumbnail('medium'); ?>
	</div>
	<?php } ?>
    <div class="fs-have-thumbnail">
        <header class="entry-header">
            <h4 class="entry-title"><strong><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'firmasite' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></strong></h4>
        </header>
        <div class="entry-content">
            <?php 	
            if ( !preg_match('/<!--more(.*?)?-->/', $post->post_content) ){
                the_excerpt();
            } else {
                the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) );
            }
             ?>
            <?php wp_link_pages( array( 'before' => '<div class="page-links"><ul class="pagination pagination-sm"><li><span>' . __( 'Pages:', 'firmasite' ) . '</span></li>', 'after' => '</ul></div>','link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
            <?php if (empty($post->post_title)){ ?>
            <a class="pull-right" href="<?php the_permalink(); ?>" rel="bookmark">
                <small><i class="icon-bookmark"></i><?php  _e( 'Permalink', 'firmasite' ); ?></small>
            </a>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>
   </div>
 </div>
</article><!-- #post-<?php the_ID(); ?> -->