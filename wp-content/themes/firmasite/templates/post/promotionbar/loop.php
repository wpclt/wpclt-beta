<div class="real_item panel panel-default <?php echo get_post_format(); ?>">
	<?php if ( has_post_thumbnail()){ ?>
        <?php the_post_thumbnail("medium"); ?>
    <?php } ?>
    <div class="panel-body">
        <h4>
            <a href="<?php the_permalink(); ?>">
                <?php the_title_attribute(); ?>
            </a>
        </h4>
        <?php 
        // bug fix for single pages.. more tag was not working
        global $more; $more = 0;
        if ( !preg_match('/<!--more(.*?)?-->/', $post->post_content) ){
            the_excerpt();
        } else {
            the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) );
        }
        if (empty($post->post_title)){ ?>
        <a class="pull-right" href="<?php the_permalink(); ?>" rel="bookmark">
            <small><i class="icon-bookmark"></i><?php  _e( 'Permalink', 'firmasite' ); ?></small>
        </a>
         <?php } ?>
     </div>
</div>

