<?php

if( wpseo_has_multiple_locations() ) {
	add_action( 'widgets_init', create_function( '', 'return register_widget("WPSEO_Storelocator_Form");' ) );
}

class WPSEO_Storelocator_Form extends WP_Widget {
	/** constructor */
	function WPSEO_Storelocator_Form() {
		$widget_options = array(
			'classname'   => 'WPSEO_Storelocator_Form',
			'description' => __( 'Shows form to search the nearest store. Will submit to the page which contains the store locator.', 'yoast-local-seo' )
		);
		parent::WP_Widget( false, $name = __( 'WP SEO - Storelocator form', 'yoast-local-seo' ), $widget_options );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		$title              = apply_filters( 'widget_title', $instance['title'] );
		$page_id	        = !empty( $instance['page_id'] ) ? $instance['page_id'] : '';

		if ( empty( $page_id ) )
			return '';

		if ( isset( $args['before_widget'] ) )
			echo $args['before_widget'];

		if ( !empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$search_string = isset( $_REQUEST['wpseo-sl-search'] ) ? esc_attr( $_REQUEST['wpseo-sl-search'] ) : '';
		
		?>
		<form action="<?php echo get_permalink( $page_id ); ?>" method="post" id="wpseo-storelocator-form">
			<fieldset>
				<p>
					<label for="wpseo-sl-search"><?php _e('Enter your postal code or city', 'yoast-local-seo'); ?></label>
					<input type="text" name="wpseo-sl-search" id="wpseo-sl-search" value="<?php echo $search_string; ?>">
				</p>
				<p class="sl-submit">
					<input type="submit" value="<?php _e('Search', 'yoast-local-seo'); ?>">
				</p>
			</fieldset>
		</form>

		<?php

		if ( isset( $args['after_widget'] ) )
			echo $args['after_widget'];
	}


	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance                       = $old_instance;
		$instance['title']              = esc_attr( $new_instance['title'] );
		$instance['page_id']        = esc_attr( $new_instance['page_id'] );

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		$title              = !empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$page_id	        = !empty( $instance['page_id'] ) ? esc_attr( $instance['page_id'] ) : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'yoast-local-seo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'page_id' ); ?>"><?php _e( 'Page', 'yoast-local-seo' ); ?>:</label>
			<?php
				$args = array(
					'name' => $this->get_field_name( 'page_id' ),
					'id' => $this->get_field_id( 'page_id' ),
					'class' => 'widefat',
					'selected' => $page_id,
					'show_option_none' => __('Select a page', 'yoast-local-seo')
				);	
				wp_dropdown_pages($args);
			?>
		</p>
	<?php
	}

}
