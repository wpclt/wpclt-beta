<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package firmasite
 */
global $firmasite_settings;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 <div class="panel panel-default">
   <div class="panel-body">
    <header class="entry-header">
        <h1 class="page-header page-title entry-title">
            <strong><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'firmasite' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></strong>
			<?php if (!empty($post->post_excerpt)){ ?>
                <small><?php the_excerpt(); ?></small>
            <?php } ?>
        </h1>
    </header>
    <div class="entry-content">
 		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><ul class="pagination pagination-lg"><li><span>' . __( 'Pages:', 'firmasite' ) . '</span></li>', 'after' => '</ul></div>','link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
        <?php if (empty($post->post_title)){ ?>
        <a class="pull-right" href="<?php the_permalink(); ?>" rel="bookmark">
			<small><i class="icon-bookmark"></i><?php  _e( 'Permalink', 'firmasite' ); ?></small>
        </a>
        <?php } ?>
        <?php edit_post_link( __( 'Edit', 'firmasite' ), '<span class="edit-link"><span class="icon-edit"></span> ', '</span>' ); ?>   
    </div>
   </div>
 </div>
</article><!-- #post-<?php the_ID(); ?> -->