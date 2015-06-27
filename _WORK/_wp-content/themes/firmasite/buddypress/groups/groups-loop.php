<?php

/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_groups_loop' ); ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<?php do_action( 'bp_before_directory_groups_list' ); ?>

	<ul id="groups-list" class="item-list list-unstyled margin-top" role="main">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li <?php bp_group_class(); ?>>
         <div class="panel panel-default"><div class="panel-body">
			<div class="item-avatar">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
			</div>

			<div class="action pull-right">

				<?php do_action( 'bp_directory_groups_actions' ); ?>

				<div class="meta label label-info">

					<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>

				</div>

			</div>
            
			<div class="item">
				<div class="item-title"><a href="<?php bp_group_permalink(); ?>" class="lead"><?php bp_group_name(); ?></a></div>
				<div class="item-meta"><span class="label label-default activity"><?php printf( __( 'active %s', 'firmasite' ), bp_get_group_last_active() ); ?></span></div>

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

				<?php do_action( 'bp_directory_groups_item' ); ?>

			</div>

			<div class="clear clearfix"></div>
         </div></div>    
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-bottom">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div class="clearfix"></div><div id="message" class="info alert alert-info">
		<p><?php _e( 'There were no groups found.', 'firmasite' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_groups_loop' ); ?>
