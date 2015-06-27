<?php do_action( 'bp_before_directory_groups_page' ); ?>

<?php global $firmasite_settings; ?>
<div id="content">

	<?php do_action( 'bp_before_directory_groups' ); ?>

	<?php do_action( 'bp_before_directory_groups_content' ); ?>

	<div id="group-dir-search" class="dir-search" role="search">
		<?php bp_directory_groups_search_form(); ?>
	</div><!-- #group-dir-search -->

	<form action="" method="post" id="groups-directory-form" class="dir-form">

		<?php do_action( 'template_notices' ); ?>

		<div class="item-list-tabs" role="navigation">
			<ul class="nav nav-pills">
				<li class="selected" id="groups-all"><a href="<?php bp_groups_directory_permalink(); ?>"><?php printf( __( 'All Groups <span>%s</span>', 'firmasite' ), bp_get_total_group_count() ); ?></a></li>

				<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
					<li id="groups-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups/'; ?>"><?php printf( __( 'My Groups <span>%s</span>', 'firmasite' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>
				<?php endif; ?>

				<?php do_action( 'bp_groups_directory_group_filter' ); ?>

			</ul>
		</div><!-- .item-list-tabs -->

		<div class="item-list-tabs" id="subnav" role="navigation">
			<ul class="nav nav-pills">
				<?php do_action( 'bp_groups_directory_group_types' ); ?>

				<li id="groups-order-select" class="pull-right form-inline last filter">

					<label for="groups-order-by"><?php _e( 'Order By:', 'firmasite' ); ?></label>

					<select class="form-control input-sm" id="groups-order-by">
						<option value="active"><?php _e( 'Last Active', 'firmasite' ); ?></option>
						<option value="popular"><?php _e( 'Most Members', 'firmasite' ); ?></option>
						<option value="newest"><?php _e( 'Newly Created', 'firmasite' ); ?></option>
						<option value="alphabetical"><?php _e( 'Alphabetical', 'firmasite' ); ?></option>

						<?php do_action( 'bp_groups_directory_order_options' ); ?>
					</select>
				</li>
			</ul>
		</div>

		<div id="groups-dir-list" class="groups dir-list margin-top">
			<?php bp_get_template_part( 'groups/groups-loop' ); ?>
		</div><!-- #groups-dir-list -->

		<?php do_action( 'bp_directory_groups_content' ); ?>

		<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

		<?php do_action( 'bp_after_directory_groups_content' ); ?>

	</form><!-- #groups-directory-form -->

	<?php do_action( 'bp_after_directory_groups' ); ?>

</div><!-- #buddypress -->

<?php do_action( 'bp_after_directory_groups_page' ); ?>