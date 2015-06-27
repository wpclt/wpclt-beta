<?php

do_action( 'bp_before_group_header' );

?>

<div id="item-actions" class="pull-right">

	<?php if ( bp_group_is_visible() ) : ?>

		<strong><?php _e( 'Group Admins', 'firmasite' ); ?></strong>

		<?php bp_group_list_admins();

		do_action( 'bp_after_group_menu_admins' );

		if ( bp_group_has_moderators() ) :
			do_action( 'bp_before_group_menu_mods' ); ?>

			<strong><?php _e( 'Group Mods' , 'firmasite' ); ?></strong>

			<?php bp_group_list_mods();

			do_action( 'bp_after_group_menu_mods' );

		endif;

	endif; ?>

</div><!-- #item-actions -->

<div id="item-header-avatar" class="col-xs-4 col-md-4 fs-content-thumbnail">
	<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">

		<?php bp_group_avatar(); ?>

	</a>
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="fs-have-thumbnail">
	<h2><a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>"><?php bp_group_name(); ?></a></h2>
	<span class="highlight label label-default"><?php bp_group_type(); ?></span>
	<span class="label label-default activity"><?php printf( __( 'active %s', 'firmasite' ), bp_get_group_last_active() ); ?></span>

	<?php do_action( 'bp_before_group_header_meta' ); ?>

	<div id="item-meta">

		<?php bp_group_description(); ?>

		<div id="item-buttons" class="clearfix">

			<?php do_action( 'bp_group_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php do_action( 'bp_group_header_meta' ); ?>

	</div>
    
</div><!-- #item-header-content -->

<?php
do_action( 'bp_after_group_header' );
?>