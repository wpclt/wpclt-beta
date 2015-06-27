<?php

/**
 * User Details
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

	<?php do_action( 'bbp_template_before_user_details' ); ?>

<div id="bbp-single-user-details" class="media margin-bot">

    <div id="bbp-user-avatar" class="media-left">

        <span class='vcard'>
            <a class="url fn n" href="<?php bbp_user_profile_url(); ?>" title="<?php bbp_displayed_user_field( 'display_name' ); ?>" rel="me">
                <?php echo get_avatar( bbp_get_displayed_user_field( 'user_email', 'raw' ), apply_filters( 'bbp_single_user_details_avatar_size', 150 ) ); ?>
            </a>
        </span>

    </div><!-- #author-avatar -->
    
    <div class="media-body media-bottom">
		
		<?php do_action( 'firmasite_bbp_template_before_user_menu' ); ?>

        <nav id="bbp-user-navigation" class="navbar no-margin-bot <?php if (isset($firmasite_settings["menu-style"]) && "alternative" == $firmasite_settings["menu-style"]){ echo " navbar-default";} else { echo "  navbar-inverse"; } ?>" role="navigation">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#object-nav">
                <span class="sr-only"><?php _e( 'Toggle navigation', 'firmasite' ); ?></span>
                <i class="icon-bar"></i>
                <i class="icon-bar"></i>
                <i class="icon-bar"></i>
              </button>
            </div>
        
            <div class="item-list-tabs no-ajax collapse navbar-collapse" id="object-nav">
              <ul class="nav navbar-nav">
    
                    <li class="<?php if ( bbp_is_single_user_profile() ) :?>current active<?php endif; ?>">
                            <a class="url fn n" href="<?php bbp_user_profile_url(); ?>" title="<?php printf( esc_attr__( "%s's Profile", 'firmasite' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>" rel="me"><?php _e( 'Profile', 'firmasite' ); ?></a>
                    </li>
    
                    <li class="<?php if ( bbp_is_single_user_topics() ) :?>current active<?php endif; ?>">
                            <a href="<?php bbp_user_topics_created_url(); ?>" title="<?php printf( esc_attr__( "%s's Topics Started", 'firmasite' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Topics Started', 'firmasite' ); ?></a>
                    </li>
    
                    <li class="<?php if ( bbp_is_single_user_replies() ) :?>current active<?php endif; ?>">
                            <a href="<?php bbp_user_replies_created_url(); ?>" title="<?php printf( esc_attr__( "%s's Replies Created", 'firmasite' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Replies Created', 'firmasite' ); ?></a>
                    </li>
    
                    <?php if ( bbp_is_favorites_active() ) : ?>
                        <li class="<?php if ( bbp_is_favorites() ) :?>current active<?php endif; ?>">
                                <a href="<?php bbp_favorites_permalink(); ?>" title="<?php printf( esc_attr__( "%s's Favorites", 'firmasite' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Favorites', 'firmasite' ); ?></a>
                        </li>
                    <?php endif; ?>
    
                    <?php if ( bbp_is_user_home() || current_user_can( 'edit_users' ) ) : ?>
    
                        <?php if ( bbp_is_subscriptions_active() ) : ?>
                            <li class="<?php if ( bbp_is_subscriptions() ) :?>current active<?php endif; ?>">
                                    <a href="<?php bbp_subscriptions_permalink(); ?>" title="<?php printf( esc_attr__( "%s's Subscriptions", 'firmasite' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Subscriptions', 'firmasite' ); ?></a>
                            </li>
                        <?php endif; ?>
    
                        <li class="<?php if ( bbp_is_single_user_edit() ) :?>current active<?php endif; ?>">
                                <a href="<?php bbp_user_profile_edit_url(); ?>" title="<?php printf( esc_attr__( "Edit %s's Profile", 'firmasite' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Edit', 'firmasite' ); ?></a>
                        </li>
    
                    <?php endif; ?>
    
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav><!-- #item-nav -->
	</div>

</div><!-- #bbp-single-user-details -->

	<?php do_action( 'bbp_template_after_user_details' ); ?>
