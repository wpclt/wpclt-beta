<?php

/**
 * Single Topic Lead Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_lead_topic' ); ?>


<?php ob_start(); ?>
<div class="bbp-header">

	<div class="bbp-meta">

		<?php if ( bbp_is_user_keymaster() ) : ?>

			<?php do_action( 'bbp_theme_before_topic_author_admin_details' ); ?>

			<div class="bbp-topic-ip hidden-xs"><?php bbp_author_ip( bbp_get_topic_id() ); ?> &nbsp;</div>

			<?php do_action( 'bbp_theme_after_topic_author_admin_details' ); ?>

		<?php endif; ?>

		<span class="bbp-topic-post-date"><?php bbp_topic_post_date(); ?></span>

		<div>

		<?php do_action( 'bbp_theme_before_topic_admin_links' ); ?>

		<?php bbp_topic_admin_links(); ?>

		<?php do_action( 'bbp_theme_after_topic_admin_links' ); ?>

        </div>

	</div><!-- .bbp-meta -->

</div><!-- #post-<?php bbp_topic_id(); ?> -->

<?php $topic_manage = ob_get_contents(); ob_end_clean(); ?>

<div id="post-<?php bbp_topic_id(); ?>" <?php bbp_reply_class(bbp_get_topic_id(), array("modal firmasite-modal-static")); ?>><div class="modal-dialog no-margin"><div class="modal-content">
<div class="<?php echo firmasite_social_bbp_get_reply_class_modal();?> clearfix">
	<div class="col-xs-4 col-md-3 pull-left bbp-topic-author text-muted fs-content-thumbnail">
    
		<?php do_action( 'bbp_theme_before_topic_author_details' ); ?>

		<?php bbp_topic_author_link( array( 'sep' => '<div class=clearfix></div>', 'show_role' => true ) ); ?>

		<?php do_action( 'bbp_theme_after_topic_author_details' ); ?>
        
		<div>
            <a href="#<?php bbp_topic_id(); ?>" class="" data-container="body" data-toggle="popover" data-rel="popover" data-html="true" data-placement="right" data-original-title="" data-content="<?php echo esc_attr($topic_manage); ?>">
                <span class="edit-link text-muted"><span class="icon-cog"></span> <?php _e( 'Details', 'firmasite' );?></span>
             </a>
         </div>

		 

	</div><!-- .bbp-topic-author -->

	<div class="bbp-topic-content">

		<?php do_action( 'bbp_theme_before_topic_content' ); ?>

		<?php bbp_topic_content(); ?>

		<?php do_action( 'bbp_theme_after_topic_content' ); ?>

	</div><!-- .bbp-topic-content -->
</div>
</div></div></div><!-- #bbp-topic-<?php bbp_topic_id(); ?>-lead -->

<?php do_action( 'bbp_template_after_lead_topic' ); ?>