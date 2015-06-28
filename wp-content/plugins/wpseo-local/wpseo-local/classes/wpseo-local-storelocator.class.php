<?php
if ( !class_exists( 'WPSEO_Storelocator' ) ) {

	class WPSEO_Storelocator {
		var $options = array();
		var $load_styles = false;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->options = get_option( "wpseo_local" );

			add_shortcode( 'wpseo_storelocator', array( &$this, 'show_storelocator' ) );

			add_action( 'wp_head', array( &$this, 'load_scripts'), 99 );
		}

		function show_storelocator( $atts ) {
			global $wpseo_enqueue_geocoder, $wpseo_sl_load_scripts;

			$wpseo_sl_load_scripts = true;

			$atts = wpseo_check_falses( shortcode_atts( array(
				'radius'	         => 10,
				'show_radius'		 => false,
				'show_map'			 => true,
				'show_filter'		 => false,
				'map_width'			 => '100%',
				'show_country'       => false,
				'show_state'         => false,
				'show_phone'         => false,
				'show_phone_2'       => false,
				'show_fax'           => false,
				'show_email'         => false,
				'show_url'	         => false,
				'oneline'			 => false,
				'show_opening_hours' => false,
				'hide_closed'		 => false,
				'from_widget'        => false,
				'widget_title'       => '',
				'before_title'       => '',
				'after_title'        => '',
				'echo'               => false
			), $atts ) );

			if( $atts['show_map'] )
				$wpseo_enqueue_geocoder = true;

			ob_start();
			?>
			<form action="" method="post" id="wpseo-storelocator-form">
				<fieldset>
					<?php
						$search_string = isset( $_REQUEST['wpseo-sl-search'] ) ? esc_attr( $_REQUEST['wpseo-sl-search'] ) : '';
						$sl_category_term = !empty( $_REQUEST['wpseo-sl-category'] ) ? $_REQUEST['wpseo-sl-category'] : '';
					?>
					<p>
						<label for="wpseo-sl-search"><?php _e('Enter your postal code or city', 'yoast-local-seo'); ?></label>
						<input type="text" name="wpseo-sl-search" id="wpseo-sl-search" value="<?php echo $search_string; ?>">

						<?php if( $atts['show_radius'] ) { ?>
							<?php _e('within', 'yoast-local-seo'); ?>
							<select name="wpseo-sl-radius" id="wpseo-sl-radius">
								<?php
									$radius_array = array( 1, 5, 10, 25, 50, 100, 250, 500, 1000 );
									$selected_radius = ! empty( $_REQUEST['wpseo-sl-radius'] ) ? esc_attr( $_REQUEST['wpseo-sl-radius'] ) : $atts['radius'];
								?>
								<?php foreach( $radius_array as $radius ) {
									echo '<option value="' . $radius . '" ' . selected( $selected_radius, $radius, false ) . '>' . $radius . ( $this->options['unit_system'] == 'METRIC' ? 'km' : 'mi' ) . '</option>';
								} ?>
							</select>
						<?php } ?>
					</p>

					<?php if( $atts['show_filter'] ) { ?>
						<?php
							$terms = get_terms( 'wpseo_locations_category' );
						?>
						<?php if( count( $terms ) > 0 ) { ?>
						<p class="sl-filter">
							<label for="wpseo-sl-category"><?php _e('Filter by category', 'yoast-local-seo'); ?></label>
							<select name="wpseo-sl-category" id="wpseo-sl-category">
								<option value=""></option>
								<?php foreach( $terms as $term ) {
									echo '<option value="' . $term->term_id . '" ' . selected( $sl_category_term, $term->term_id, false ) . '>' . $term->name . '</option>';
								} ?>
							</select>
						</p>
						<?php } ?>
					<?php } ?>

					<p class="sl-submit">
						<input type="submit" value="<?php _e('Search', 'yoast-local-seo'); ?>">
					</p>

				</fieldset>
			</form>

			<?php
				$results = $this->get_results();

				if( $atts['show_map'] ) {
					echo wpseo_local_show_map( array(
						'id' => count( $results ) > 0 ? implode( ',', $results ) : 'all',
						'width' => $atts['map_width'],
						'from_sl' => true,
						'show_route' =>  true
					));
				}
			?>

			<div id="wpseo-storelocator-results">
				<?php
					if( !is_wp_error( $results ) && count( $results > 0 ) ) {
						echo '<h2>' . sprintf( __('%s results have been found', 'yoast-local-seo'), count( $results ) ) . '</h2>';
						
						foreach( $results as $distance => $location_id ) {
							$coords_lat = get_post_meta( $location_id, '_wpseo_coordinates_lat', true );
							$coords_long = get_post_meta( $location_id, '_wpseo_coordinates_long', true );
							?>
							<div class="wpseo-result">
								<?php 
									$location = wpseo_local_show_address( array(
										'id'                 => $location_id,
										'show_state'         => $atts['show_state'],
										'show_country'       => $atts['show_country'],
										'show_phone'         => $atts['show_phone'],
										'show_phone_2'       => $atts['show_phone_2'],
										'show_fax'           => $atts['show_fax'],
										'show_email'         => $atts['show_email'],
										'show_url'	         => $atts['show_url'],
										'show_opening_hours' => $atts['show_opening_hours'],
										'hide_closed'        => $atts['hide_closed'],
										'oneline'            => $atts['oneline'],
										'from_sl'            => true,
										'echo'               => false
									) );

									echo apply_filters( 'wpseo_local_sl_result', $location, $location_id );
								?>

								<div class="wpseo-sl-route">
									<a href="javascript:;" onclick="wpseo_sl_show_route( this, '<?php echo $coords_lat; ?>', '<?php echo $coords_long; ?>' );"><?php _e('Show route', 'yoast-local-seo'); ?></a>
								</div>
							</div>
							<?php
						}
					}
					else {
						echo '<h2>' . __('No results found', 'yoast-local-seo') . '</h2>';
						echo '<p>' . __('Please try another search.', 'yoast-local-seo') . '</p>';
					}
				?>
			</div>
			<?php
			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		}

		public function get_results() {
			global $wpdb;

			$nr_results = !empty( $this->options['sl_num_results'] ) ? $this->options['sl_num_results'] : 10;
			$metric = $this->options['unit_system'] == 'METRIC' ? 'km' : 'mi';
			$radius = !empty( $_REQUEST['wpseo-sl-radius'] ) ? $_REQUEST['wpseo-sl-radius'] : 99999;
			$sl_category_term = !empty( $_REQUEST['wpseo-sl-category'] ) ? $_REQUEST['wpseo-sl-category'] : '';

			$search_string = isset( $_REQUEST['wpseo-sl-search'] ) ? esc_attr( $_REQUEST['wpseo-sl-search'] ) : '';
			if( $search_string == '' )
				return array();

			// Add country name to search to improve search results
			$default_country = isset( $this->options['default_country'] ) ? $this->options['default_country'] : '';
			if( $default_country != '' ) {
				$search_string .= ' ' . WPSEO_Frontend_Local::get_country( $default_country );
			}

			$response = wpseo_geocode_address( $search_string );
			if ( is_wp_error( $response ) )
				return $response;

			// Get lat/long
			if( empty( $response->results[0] ) )
				return new WP_Error( 'wpseo-get-results-error', __('No valid JSON response. We cannot complete the search.', 'yoast-local-seo' ) );

			$result = $response->results[0];
			$coordinates = !empty( $result->geometry->location ) ? $result->geometry->location : false;
			if( ! $coordinates )
				return new WP_Error( 'wpseo-get-results-error', __('No valid coordinates. We cannot complete the search.', 'yoast-local-seo' ) );

			// Extend SQL with category filter
			$inner_join = '';
			if( $sl_category_term != '' ) {
				$inner_join .= "
					INNER JOIN $wpdb->term_relationships AS term_rel ON p.ID = term_rel.object_id
					INNER JOIN $wpdb->term_taxonomy AS taxo ON term_rel.term_taxonomy_id = taxo.term_taxonomy_id 
						AND taxo.taxonomy = 'wpseo_locations_category'
						AND taxo.term_id = $sl_category_term
				";
			}

			// Get all coordinates from posts
			$sql = $wpdb->prepare( "SELECT p.ID, m1.meta_value as lat, m2.meta_value as lng 
										FROM $wpdb->posts p 
										INNER JOIN $wpdb->postmeta m1 ON p.ID = m1.post_id 
										INNER JOIN $wpdb->postmeta m2 ON p.ID = m2.post_id
										$inner_join
										WHERE
											p.post_type = 'wpseo_locations' AND
										 	p.post_status = 'publish' AND
											m1.meta_key = '_wpseo_coordinates_lat' AND
											m2.meta_key = '_wpseo_coordinates_long'
										GROUP BY p.ID", "" );

			$locations = $wpdb->get_results( $sql );

			// Calculate distance
			$distances = array();
			foreach( $locations as $location ) {
				// Skip locations with empty lat/long coordinates
				if( empty( $location->lat ) || empty( $location->lng ) ) {
					continue;
				}

				$distance = $this->get_distance( $coordinates->lat, $coordinates->lng, $location->lat, $location->lng );

				// Filter on radius
				if( $distance[ $metric ] > $radius )
					continue;

				$distance_key = round( $distance[ $metric ], 4 ) * 10000 ;				
				$distances[ (string)$distance_key ] = $location->ID;
			}

			ksort( $distances, SORT_NUMERIC );
			
			return array_slice( $distances, 0, $nr_results, true );
		}

		/**
		 * Calculates distance between two sets of coordinates. Used code from http://www.inkplant.com/code/calculate-the-distance-between-two-points.php
		 */ 
		public function get_distance( $latitude1, $longitude1, $latitude2, $longitude2 ) {
			$theta = $longitude1 - $longitude2;
			$miles = ( sin( deg2rad( $latitude1 ) ) * sin( deg2rad( $latitude2 ) ) ) + ( cos( deg2rad( $latitude1 ) ) * cos( deg2rad( $latitude2 ) ) * cos( deg2rad( $theta ) ) );
			$miles = acos( $miles );
			$miles = rad2deg( $miles );
			$miles = $miles * 60 * 1.1515;
			$km = $miles * 1.609344;
			
			return array(
				'mi' => $miles,
				'km' => $km
			); 
		}

		public function load_scripts() {
			global $wpseo_sl_load_scripts;

			if( ! wp_script_is('jquery', 'done') )
				wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' );
		}

		
	}
}

if( ! function_exists( 'wpseo_local_storelocator' ) ) {
	function wpseo_local_storelocator( $atts ) {
		global $WPSEO_Storelocator;

		if( null == $WPSEO_Storelocator )
			$WPSEO_Storelocator = new WPSEO_Storelocator(); 

		return $WPSEO_Storelocator->show_storelocator( $atts );
	}
}
$wpseo_sl_load_scripts = false;
?>