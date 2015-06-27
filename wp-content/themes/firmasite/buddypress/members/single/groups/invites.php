<?php do_action( 'bp_before_group_invites_content' ); ?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_loggedin_user_id() ) ) : ?>

	<ul id="group-list" class="invites item-list list-unstyled margin-top" role="main">

		<?php while ( bp_groups() ) : bp_the_group(); ?>

			<li>
             <div class="panel panel-default"><div class="panel-body">
				<div class="item-avatar">
					<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
				</div>

				<h4 class="page-header"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a><span class="small"> - <?php printf( _nx( '1 member', '%d members', bp_get_group_total_members( false ),'Group member count', 'firmasite' ), bp_get_group_total_members( false )  ); ?></span></h4>

				<p class="desc">
					<?php bp_group_description_excerpt(); ?>
				</p>

				<?php do_action( 'bp_group_invites_item' ); ?>

				<div class="action">
					<a class="btn btn-default btn-xs button accept" href="<?php bp_group_accept_invite_link(); ?>"><?php _e( 'Accept', 'firmasite' ); ?></a> &nbsp;
					<a class="btn btn-default btn-xs button reject confirm" href="<?php bp_group_reject_invite_link(); ?>"><?php _e( 'Reject', 'firmasite' ); ?></a>

					<?php do_action( 'bp_group_invites_item_action' ); ?>

				</div>
			 </div></div>
            </li>

		<?php endwhile; ?>
	</ul>

<?php else: ?>

	<div class="clearfix"></div><div id="message" class="info alert alert-info margin-top" role="main">
		<p><?php _e( 'You have no outstanding group invites.', 'firmasite' ); ?></p>
	</div>

<?php endif;?>

<?php do_action( 'bp_after_group_invites_content' ); ?>