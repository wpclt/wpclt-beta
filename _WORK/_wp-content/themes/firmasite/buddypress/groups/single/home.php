<?php global $firmasite_settings; ?>
<div id="content">

	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

	<?php do_action( 'bp_before_group_home_content' ); ?>

	<div id="item-header" class="well well-sm media clearfix" role="complementary">

		<?php bp_get_template_part( 'groups/single/group-header' ); ?>

	</div><!-- #item-header -->

    <?php do_action( 'template_notices' ); ?>

    <nav id="item-nav" class="navbar <?php if (isset($firmasite_settings["menu-style"]) && "alternative" == $firmasite_settings["menu-style"]){ echo " navbar-default";} else { echo "  navbar-inverse"; } ?>" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#object-nav">
            <span class="sr-only"><?php _e( 'Toggle navigation', 'firmasite' ); ?></span>
            <i class="icon-bar"></i>
            <i class="icon-bar"></i>
            <i class="icon-bar"></i>
          </button>
        </div>
    
        <div class="item-list-tabs no-ajax collapse navbar-collapse tabs-top" id="object-nav">
          <ul class="nav navbar-nav">

				<?php bp_get_options_nav(); ?>

				<?php do_action( 'bp_group_options_nav' ); ?>

          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav><!-- #item-nav -->
    
    

	<div id="item-body">

		<?php do_action( 'bp_before_group_body' );

		/**
		 * Does this next bit look familiar? If not, go check out WordPress's
		 * /wp-includes/template-loader.php file.
		 *
		 * @todo A real template hierarchy? Gasp!
		 */

			// Looking at home location
			if ( bp_is_group_home() ) :

				if ( bp_group_is_visible() ) {

					// Use custom front if one exists
					$custom_front = bp_locate_template( array( 'groups/single/front.php' ), false, true );
					if     ( ! empty( $custom_front   ) ) : load_template( $custom_front, true );

					// Default to activity
					elseif ( bp_is_active( 'activity' ) ) : bp_get_template_part( 'groups/single/activity' );

					// Otherwise show members
					elseif ( bp_is_active( 'members'  ) ) : firmasite_bp_groups_members_template_part();

					endif;

				} else {

					do_action( 'bp_before_group_status_message' ); ?>

					<div class="clearfix"></div><div id="message" class="info alert alert-info">
						<p><?php bp_group_status_message(); ?></p>
					</div>

					<?php do_action( 'bp_after_group_status_message' );

				}

			// Not looking at home
			else :

				// Group Admin
				if     ( bp_is_group_admin_page() ) : bp_get_template_part( 'groups/single/admin'        );

				// Group Activity
				elseif ( bp_is_group_activity()   ) : bp_get_template_part( 'groups/single/activity'     );

				// Group Members
				elseif ( bp_is_group_members()    ) : firmasite_bp_groups_members_template_part();

				// Group Invitations
				elseif ( bp_is_group_invites()    ) : bp_get_template_part( 'groups/single/send-invites' );

				// Old group forums
				elseif ( bp_is_group_forum()      ) : bp_get_template_part( 'groups/single/forum'        );

				// Membership request
				elseif ( bp_is_group_membership_request() ) : bp_get_template_part( 'groups/single/request-membership' );

				// Anything else (plugins mostly)
				else                                : bp_get_template_part( 'groups/single/plugins'      );

				endif;

			endif;

		do_action( 'bp_after_group_body' ); ?>

	</div><!-- #item-body -->

	<?php do_action( 'bp_after_group_home_content' ); ?>

	<?php endwhile; endif; ?>

</div><!-- #buddypress -->
