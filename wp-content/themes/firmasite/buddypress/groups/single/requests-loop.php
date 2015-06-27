<?php if ( bp_group_has_membership_requests( bp_ajax_querystring( 'membership_requests' ) ) ) : ?>

	<ul id="request-list" class="item-list list-unstyled">
		<?php while ( bp_group_membership_requests() ) : bp_group_the_membership_request(); ?>

			<li>
             <div class="panel panel-default"><div class="panel-body">
				<?php bp_group_request_user_avatar_thumb(); ?>
				<h4 class="page-header"><?php bp_group_request_user_link(); ?> <span class="comments"><?php bp_group_request_comment(); ?></span></h4>
				<span class="label label-default activity"><?php bp_group_request_time_since_requested(); ?></span>

				<?php do_action( 'bp_group_membership_requests_admin_item' ); ?>

				<div class="action">

					<?php bp_button( array( 'id' => 'group_membership_accept', 'component' => 'groups', 'wrapper_class' => 'accept', 'link_class' => 'btn btn-default', 'link_href' => bp_get_group_request_accept_link(), 'link_title' => __( 'Accept', 'firmasite' ), 'link_text' => __( 'Accept', 'firmasite' ) ) ); ?>

					<?php bp_button( array( 'id' => 'group_membership_reject', 'component' => 'groups', 'wrapper_class' => 'reject', 'link_class' => 'btn btn-default', 'link_href' => bp_get_group_request_reject_link(), 'link_title' => __( 'Reject', 'firmasite' ), 'link_text' => __( 'Reject', 'firmasite' ) ) ); ?>

					<?php do_action( 'bp_group_membership_requests_admin_item_action' ); ?>

				</div>
             </div></div>     
			</li>

		<?php endwhile; ?>
	</ul>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-mem-requests-count-bottom">

			<?php bp_group_requests_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-mem-requests-pag-bottom">

			<?php bp_group_requests_pagination_links(); ?>

		</div>

	</div>

	<?php else: ?>

		<div class="clearfix"></div><div id="message" class="info alert alert-info">
			<p><?php _e( 'There are no pending membership requests.', 'firmasite' ); ?></p>
		</div>

	<?php endif; ?>
