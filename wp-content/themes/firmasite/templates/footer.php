<?php global $firmasite_settings; ?>
<footer id="footer" class="site-footer clearfix" role="contentinfo">
    <div class="site-info <?php echo $firmasite_settings["layout_container_class"]; ?>">
        <?php do_action( 'open_footer' ); ?>
            <div class="row">
             <?php if ( ! dynamic_sidebar( 'footer-middle' ) ) : ?>
             <?php endif; // end sidebar widget area ?>
             </div>
               <?php if (has_nav_menu('footer_menu')) : 
               switch ($firmasite_settings["footer-menu-style"]) {
                    case "simple":
               ?>
                      <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-footer">
                                <span class="sr-only"><?php _e("Toggle navigation", 'firmasite' );?></span>
                                <span class="icon-reorder"></span>
                                <b class="caret"></b>
                            </button>                    
                      </div> 
                      <nav id="nav-footer" class="collapse navbar-collapse simple-menu-collapse" role="navigation">
                        <?php  wp_nav_menu(array('theme_location' => 'footer_menu', 'menu_class' => 'nav nav-pills')); ?>
                      </nav>
               <?php
                    break;
                   
                    case "default":
                    case "alternative":
                    default:
               ?>
               <div class="clearfix"></div>
                <nav id="footermenu" role="navigation"  class="site-navigation footer-navigation navbar dropup <?php if ((isset($firmasite_settings["alternative"]) && !empty($firmasite_settings["alternative"])) || "alternative" == $firmasite_settings["footer-menu-style"]){ echo " navbar-inverse";} else { echo " navbar-default"; } ?>">          
                  <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-footer">
                        <span class="sr-only"><?php _e("Toggle navigation", 'firmasite' );?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                  </div>               
                  <div id="nav-footer" class="collapse navbar-collapse" role="navigation">
                    <?php  wp_nav_menu(array('theme_location' => 'footer_menu', 'menu_class' => 'nav navbar-nav')); ?>
                  </div>
                </nav>    <!-- .site-navigation .footer-navigation --> 
               <?php 
                    break;
               }
               endif; ?>


        <?php do_action( 'close_footer' ); ?>
         <?php if($firmasite_settings["poweredby"]) { ?>
         <small class="text-muted generator"><a href="<?php echo esc_url( __( 'http://wordpress.org/', 'firmasite' ) ); ?>" title="<?php printf( esc_attr__( 'Proudly powered by %s', 'firmasite' ), 'WordPress' ); ?>"><i id="wplogo" class="icon-wordpress"></i></a></small>
         <?php } ?>
         <?php if($firmasite_settings["designer"]) { ?>
         <small class="text-muted designer"><?php _e("Theme:",'firmasite'); ?> <a href="//firmasite.com/" class="text-muted" rel="designer">FirmaSite</a></small>
         <?php } ?>
    </div><!-- .site-info -->
</footer><!-- #colophon .site-footer -->
