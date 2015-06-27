<?php
/**
 * @package firmasite
 */
 global $firmasite_loop_tile, $firmasite_settings, $firmasite_loop_tile_count;
 ?>
<li id="post-<?php the_ID(); ?>" <?php post_class("col-xs-12 col-sm-6 col-md-" . round(12 / $firmasite_settings["loop_tile_row"]) . " loop_tile_item loop_tile_" .$firmasite_loop_tile_count. "_item"); ?>>
<div class="panel panel-default">
    <?php if (has_post_thumbnail() && !(isset($firmasite_settings["loop-thumbnail"]) && !empty($firmasite_settings["loop-thumbnail"]))  ) {	
        the_post_thumbnail('medium',array(
            'alt'	=> trim(strip_tags( $post->post_title )),
            'title'	=> trim(strip_tags( $post->post_title )),
            ) ); 
    } ?>					
     <div class="caption panel-body">
        <h4 class="entry-title"><strong><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'firmasite' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></strong></h4>

		<?php 	
        if ( !preg_match('/<!--more(.*?)?-->/', $post->post_content) ){
            the_excerpt();
        } else {
            the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) );
        }
         ?>		

		<?php if (empty($post->post_title)){ ?>
		<a class="pull-right" href="<?php the_permalink(); ?>" rel="bookmark">
			<small><i class="icon-bookmark"></i><?php  _e( 'Permalink', 'firmasite' ); ?></small>
		</a>
		<?php } ?>
        <?php if(is_object_in_term($post->ID,'category')){ ?>
        <small>
                <?php the_terms($post->ID,'category', '<span class="icon-folder-open"></span> &nbsp;  ', ', ',''  ); ?>
        </small>
        <?php } ?> 
     </div>
</div>
</li>