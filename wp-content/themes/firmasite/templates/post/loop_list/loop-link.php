<?php
/**
 * @package firmasite
 */
 global $firmasite_settings,$post;
 
	$has_url = get_url_in_content( get_the_content() );
 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class("loop_list format_link"); ?>>
 <div class="panel panel-default">
   <div class="panel-body clearfix">
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="entry-thumbnail col-xs-4 col-md-4 pull-left fs-content-thumbnail">
      <?php the_post_thumbnail('medium'); ?>
	</div>
	<?php } ?>
    <div class="fs-have-thumbnail">
		<?php if ( $has_url ) { ?>
        <header class="entry-header">
            <h4 class="entry-link"><strong><a href="<?php echo $has_url; ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'firmasite' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></strong></h4>
        </header>
        <?php } ?>   
        <div class="entry-content">
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) ); ?>
            <?php wp_link_pages( array( 'before' => '<div class="page-links"><ul class="pagination pagination-sm"><li><span>' . __( 'Pages:', 'firmasite' ) . '</span></li>', 'after' => '</ul></div>','link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
            <a class="pull-right" href="<?php the_permalink(); ?>" rel="bookmark">
                <small class="entry-title"><i class="icon-bookmark"></i><?php if (!empty($post->post_title)){ the_title(); } else { _e( 'Permalink', 'firmasite' ); } ?></small>
            </a>
            <div class="clearfix"></div>
        </div>
     </div>
   </div>
 </div>
</article><!-- #post-<?php the_ID(); ?> -->
