<?php

/**
 * Search 
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form role="search" method="get" id="bbp-search-form" class="search-form form-inline pull-right" action="<?php bbp_search_url(); ?>">
	<div>
		<label class="screen-reader-text hidden sr-only" for="bbp_search"><?php _e( 'Search for:', 'firmasite' ); ?></label>
		<input type="hidden" name="action" value="bbp-search-request" />
		<div class="input-group">
            <input tabindex="<?php bbp_tab_index(); ?>" class="form-control" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'firmasite' ); ?>" name="bbp_search" id="bbp_search" />
            <div class="input-group-btn">
                <input tabindex="<?php bbp_tab_index(); ?>" class="button btn btn-default" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'firmasite' ); ?>" />
            </div>
		</div>
	</div>
</form>
