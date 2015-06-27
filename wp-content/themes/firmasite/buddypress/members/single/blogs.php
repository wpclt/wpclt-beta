<?php

/**
 * BuddyPress - Users Blogs
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs" id="subnav" role="navigation">
	<ul class="nav nav-pills">

		<?php bp_get_options_nav(); ?>

		<li id="blogs-order-select" class="pull-right form-inline last filter">

			<label for="blogs-order-by"><?php _e( 'Order By:', 'firmasite' ); ?></label>
			<select class="form-control input-sm" id="blogs-order-by">
				<option value="active"><?php _e( 'Last Active', 'firmasite' ); ?></option>
				<option value="newest"><?php _e( 'Newest', 'firmasite' ); ?></option>
				<option value="alphabetical"><?php _e( 'Alphabetical', 'firmasite' ); ?></option>

				<?php do_action( 'bp_member_blog_order_options' ); ?>

			</select>
		</li>
	</ul>
</div><!-- .item-list-tabs -->

<?php
switch ( bp_current_action() ) :

	// Home/My Blogs
	case 'my-sites' :
		do_action( 'bp_before_member_blogs_content' ); ?>

		<div class="blogs myblogs margin-top" role="main">

			<?php bp_get_template_part( 'blogs/blogs-loop' ) ?>

		</div><!-- .blogs.myblogs -->

		<?php do_action( 'bp_after_member_blogs_content' );
		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;