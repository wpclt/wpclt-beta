<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>><div class="modal-dialog"><div class="modal-content"><div class="modal-body clearfix">

<?php if ( has_post_thumbnail()){?>
 <div class="media">
  <div class="media-left">
	   <?php the_post_thumbnail('thumbnail'); ?>
  </div>
  <div class="media-body">
<?php } ?>
	<div class="bbp-forum-info">

		<?php do_action( 'bbp_theme_before_forum_title' ); ?>

		<h3 class="media-heading"><a class="bbp-forum-title" href="<?php bbp_forum_permalink(); ?>" title="<?php bbp_forum_title(); ?>"><?php bbp_forum_title(); ?></a></h3>

		<?php do_action( 'bbp_theme_after_forum_title' ); ?>

		<?php do_action( 'bbp_theme_before_forum_description' ); ?>

		<div class="bbp-forum-content"><?php bbp_forum_content(); ?></div>

		<?php do_action( 'bbp_theme_after_forum_description' ); ?>

	</div>

    <ul class="bbp-topic-meta list-inline text-muted text-right">
        <li class="bbp-forum-topic-count"><span class="badge"><?php bbp_forum_topic_count(); ?></span> <?php _e( 'Topics', 'firmasite' ); ?></li>
    
        <li class="bbp-forum-reply-count"><span class="badge"><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></span> <?php bbp_show_lead_topic() ? _e( 'Replies', 'firmasite' ) : _e( 'Posts', 'firmasite' ); ?></li>
    
        <li class="bbp-forum-freshness">
    
            <?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>
    
            <?php bbp_forum_freshness_link(); ?>
    
            <?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>
    
        </li>
        <li class="bbp-topic-freshness-author">
			<?php do_action( 'bbp_theme_before_topic_author' ); ?>

            <span class="text-muted"><?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 20 ) ); ?></span>

            <?php do_action( 'bbp_theme_after_topic_author' ); ?>
    
        </li>
    </ul>
<?php if ( has_post_thumbnail()){?>
  </div>
 </div>
<?php } ?>
</div></div></div></div><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
