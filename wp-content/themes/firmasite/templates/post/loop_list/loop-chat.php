<?php
/**
 * @package firmasite
 */
 global $firmasite_settings,$post;
 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class("loop_list format_chat"); ?>>
 <div class="panel panel-default clearfix">
   <div class="panel-body">
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="entry-thumbnail col-xs-4 col-md-4 pull-left fs-content-thumbnail">
      <?php the_post_thumbnail('medium'); ?>
	</div>
	<?php } ?>
    <div class="entry-content fs-have-thumbnail">
 		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><ul class="pagination pagination-sm"><li><span>' . __( 'Pages:', 'firmasite' ) . '</span></li>', 'after' => '</ul></div>','link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
        <a class="pull-right" href="<?php the_permalink(); ?>" rel="bookmark">
			<small class="entry-title"><i class="icon-bookmark"></i><?php if (!empty($post->post_title)){ the_title(); } else { _e( 'Permalink', 'firmasite' ); } ?></small>
        </a>
		<div class="clearfix"></div>
    </div>
   </div>
 </div>
</article><!-- #post-<?php the_ID(); ?> -->
