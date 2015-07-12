<?php
if ( !class_exists( 'WPSEO_Frontend_Local' ) ) {

	class WPSEO_Frontend_Local {
		var $options = array();
		var $load_styles = false;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->options = get_option( "wpseo_local" );

			// Create shortcode functionality. Functions are defined in includes/wpseo-local-functions.php because they're also used by some widgets
			add_shortcode( 'wpseo_address', 'wpseo_local_show_address' );
			add_shortcode( 'wpseo_all_locations', 'wpseo_local_show_all_locations' );
			add_shortcode( 'wpseo_map', 'wpseo_local_show_map' );
			add_shortcode( 'wpseo_opening_hours', 'wpseo_local_show_opening_hours' );

			add_action( 'wpseo_opengraph', array( $this, 'opengraph_location' ) );
			add_filter( 'wpseo_opengraph_type', array( $this, 'opengraph_type' ) );
			add_filter( 'wpseo_opengraph_title', array( $this, 'opengraph_title_filter' ) );

			// Genesis 2.0 specific, this filters the Schema.org output Genesis 2.0 comes with.
			add_filter( 'genesis_attr_body', array( $this, 'genesis_contact_page_schema' ), 20, 1 );
			add_filter( 'genesis_attr_entry', array( $this, 'genesis_empty_schema' ), 20, 1 );
			add_filter( 'genesis_attr_entry-title', array( $this, 'genesis_itemprop_name' ), 20, 1 );
		}

		/**
		 * Filter the Genesis page schema and force it to ContactPage for Location pages
		 *
		 * @since 1.1.7
		 *
		 * @link https://yoast.com/schema-org-genesis-2-0/
		 * @link http://schema.org/ContactPage
		 *
		 * @param array $attr The Schema.org attributes
		 * @return array $attr
		 */
		function genesis_contact_page_schema( $attr ) {
			if ( is_singular( 'wpseo_locations' ) ) {
				$attr['itemtype'] = 'http://schema.org/ContactPage';
				$attr['itemprop'] = '';
				$attr['itemscope'] = 'itemscope';
			}
			return $attr;
		}

		/**
		 * Filter the Genesis schema for an attribute and empty them
		 *
		 * @since 1.1.7
		 *
		 * @link https://yoast.com/schema-org-genesis-2-0/
		 *
		 * @param array $attr The Schema.org attributes
		 * @return array $attr
		 */
		function genesis_empty_schema( $attr ) {
			$attr['itemtype'] = '';
			$attr['itemprop'] = '';
			$attr['itemscope'] = '';
			return $attr;
		}

		/**
		 * Filter the Genesis schema for an attribute itemprop and set it to name
		 *
		 * @since 1.1.7
		 *
		 * @link https://yoast.com/schema-org-genesis-2-0/
		 *
		 * @param array $attr The Schema.org attributes
		 * @return array $attr
		 */
		function genesis_itemprop_name( $attr ) {
			$attr['itemprop'] = 'name';
			return $attr;
		}

		/**
		 * Output opengraph location tags.
		 *
		 * @link https://developers.facebook.com/docs/reference/opengraph/object-type/business.business
		 * @link https://developers.facebook.com/docs/reference/opengraph/object-type/restaurant.restaurant
		 *
		 * @since 1.0
		 */
		function opengraph_location() {
			if ( is_singular( 'wpseo_locations' ) || ( "on" == wpseo_get_value( 'opengraph-local' ) && ! wpseo_has_multiple_locations() ) ) {

				$location_data = wpseo_get_location_details( get_the_ID() );

				echo '<meta property="place:location:latitude" content="' . $location_data['business_coords_lat'] . '"/>' . "\n";
				echo '<meta property="place:location:longitude" content="' . $location_data['business_coords_long'] . '"/>' . "\n";
				echo '<meta property="business:contact_data:street_address" content="' . $location_data['business_address'] . '"/>' . "\n";
				echo '<meta property="business:contact_data:locality" content="' . $location_data['business_city'] . '"/>' . "\n";
				echo '<meta property="business:contact_data:country" content="' . WPSEO_Frontend_Local::get_country( $location_data['business_country'] ) . '"/>' . "\n";
				echo '<meta property="business:contact_data:postal_code" content="' . $location_data['business_zipcode'] . '"/>' . "\n";
				echo '<meta property="business:contact_data:street_address" content="' . $location_data['business_address'] . '"/>' . "\n";
				echo '<meta property="business:contact_data:website" content="' . trailingslashit( get_home_url() ) . '"/>' . "\n";

				if ( ! empty( $location_data['business_state'] ) )
					echo '<meta property="business:contact_data:region" content="' . $location_data['business_state'] . '"/>' . "\n";
				if ( ! empty( $location_data['business_email'] ) )
					echo '<meta property="business:contact_data:email" content="' . $location_data['business_email'] . '"/>' . "\n";
				if ( ! empty( $location_data['business_phone'] ) )
					echo '<meta property="business:contact_data:phone_number" content="' . $location_data['business_phone'] . '"/>' . "\n";
				if ( ! empty( $location_data['business_fax'] ) )
					echo '<meta property="business:contact_data:fax_number" content="' . $location_data['business_fax'] . '"/>' . "\n";

				// Opening Hours
				$days = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
				foreach ( $days as $day ) {
					$field_name = '_wpseo_opening_hours_' . $day;

					$start = get_post_meta( get_the_ID(), $field_name . '_from', true );
					if ( ! $start || empty( $start ) )
						continue;

					$end = get_post_meta( get_the_ID(), $field_name . '_to', true );
					echo '<meta property="business:hours:day" content="' . $day . '"/>' . "\n";
					echo '<meta property="business:hours:start" content="' . $start . '"/>' . "\n";
					echo '<meta property="business:hours:end" content="' . $end . '"/>' . "\n";
				}
			}
		}

		/**
		 * Change the OpenGraph type when current post type is a location.
		 *
		 * @link https://developers.facebook.com/docs/reference/opengraph/object-type/business.business
		 * @link https://developers.facebook.com/docs/reference/opengraph/object-type/restaurant.restaurant
		 *
		 * @param $type string The OpenGraph type to be altered
		 *
		 * @return string
		 */
		function opengraph_type( $type ) {
			global $post;

			if ( !empty( $post ) && ( ( isset( $post->post_type ) && $post->post_type == 'wpseo_locations' ) || ( "on" == wpseo_get_value( 'opengraph-local', $post->ID ) && ! wpseo_has_multiple_locations() ) ) ) {
				$business_type = get_post_meta( $post->ID, '_wpseo_business_type', true );
				switch ( $business_type ) {
					case 'BarOrPub':
					case 'Winery':
					case 'Restaurant':
						$type = 'restaurant.restaurant';
						break;
					default:
						$type = 'business.business';
						break;
				}
			}

			return $type;
		}

		/**
		 * Filter the OG title output
		 *
		 * @param string $title
		 *
		 * @return string
		 */
		function opengraph_title_filter( $title ) {
			if ( "on" == wpseo_get_value( 'opengraph-local' ) && ! wpseo_has_multiple_locations() ) {
				return get_bloginfo('name');
			} else if ( wpseo_has_multiple_locations() && is_singular( 'wpseo_locations' ) ) {
				return get_the_title( get_the_ID() );
			}
			return $title;
		}

		/**
		 * Return the country name based on country code
		 *
		 * @since 0.1
		 *
		 * @param string $country_code Two char country code
		 *
		 * @return string Country name
		 */
		public static function get_country( $country_code = '' ) {
			$countries = WPSEO_Frontend_Local::get_country_array();

			return $country_code != '' ? $countries[$country_code] : false;
		}

		public static function get_country_array() {
			$countries = array(
				'AF' => __( 'Afghanistan', 'yoast-local-seo' ),
				'AL' => __( 'Albania', 'yoast-local-seo' ),
				'DZ' => __( 'Algeria', 'yoast-local-seo' ),
				'AS' => __( 'American Samoa', 'yoast-local-seo' ),
				'AD' => __( 'Andorra', 'yoast-local-seo' ),
				'AO' => __( 'Angola', 'yoast-local-seo' ),
				'AI' => __( 'Anguilla', 'yoast-local-seo' ),
				'AQ' => __( 'Antarctica', 'yoast-local-seo' ),
				'AG' => __( 'Antigua And Barbuda', 'yoast-local-seo' ),
				'AR' => __( 'Argentina', 'yoast-local-seo' ),
				'AM' => __( 'Armenia', 'yoast-local-seo' ),
				'AW' => __( 'Aruba', 'yoast-local-seo' ),
				'AU' => __( 'Australia', 'yoast-local-seo' ),
				'AT' => __( 'Austria', 'yoast-local-seo' ),
				'AZ' => __( 'Azerbaijan', 'yoast-local-seo' ),
				'BS' => __( 'Bahamas', 'yoast-local-seo' ),
				'BH' => __( 'Bahrain', 'yoast-local-seo' ),
				'BD' => __( 'Bangladesh', 'yoast-local-seo' ),
				'BB' => __( 'Barbados', 'yoast-local-seo' ),
				'BY' => __( 'Belarus', 'yoast-local-seo' ),
				'BE' => __( 'Belgium', 'yoast-local-seo' ),
				'BZ' => __( 'Belize', 'yoast-local-seo' ),
				'BJ' => __( 'Benin', 'yoast-local-seo' ),
				'BM' => __( 'Bermuda', 'yoast-local-seo' ),
				'BT' => __( 'Bhutan', 'yoast-local-seo' ),
				'BO' => __( 'Bolivia', 'yoast-local-seo' ),
				'BA' => __( 'Bosnia And Herzegovina', 'yoast-local-seo' ),
				'BW' => __( 'Botswana', 'yoast-local-seo' ),
				'BV' => __( 'Bouvet Island', 'yoast-local-seo' ),
				'BR' => __( 'Brazil', 'yoast-local-seo' ),
				'IO' => __( 'British Indian Ocean Territory', 'yoast-local-seo' ),
				'BN' => __( 'Brunei', 'yoast-local-seo' ),
				'BG' => __( 'Bulgaria', 'yoast-local-seo' ),
				'BF' => __( 'Burkina Faso', 'yoast-local-seo' ),
				'BI' => __( 'Burundi', 'yoast-local-seo' ),
				'KH' => __( 'Cambodia', 'yoast-local-seo' ),
				'CM' => __( 'Cameroon', 'yoast-local-seo' ),
				'CA' => __( 'Canada', 'yoast-local-seo' ),
				'CV' => __( 'Cape Verde', 'yoast-local-seo' ),
				'KY' => __( 'Cayman Islands', 'yoast-local-seo' ),
				'CF' => __( 'Central African Republic', 'yoast-local-seo' ),
				'TD' => __( 'Chad', 'yoast-local-seo' ),
				'CL' => __( 'Chile', 'yoast-local-seo' ),
				'CN' => __( 'China', 'yoast-local-seo' ),
				'CX' => __( 'Christmas Island', 'yoast-local-seo' ),
				'CC' => __( 'Cocos (Keeling) Islands', 'yoast-local-seo' ),
				'CO' => __( 'Columbia', 'yoast-local-seo' ),
				'KM' => __( 'Comoros', 'yoast-local-seo' ),
				'CG' => __( 'Congo', 'yoast-local-seo' ),
				'CK' => __( 'Cook Islands', 'yoast-local-seo' ),
				'CR' => __( 'Costa Rica', 'yoast-local-seo' ),
				'CI' => __( 'Cote D\'Ivorie (Ivory Coast)', 'yoast-local-seo' ),
				'HR' => __( 'Croatia (Hrvatska)', 'yoast-local-seo' ),
				'CU' => __( 'Cuba', 'yoast-local-seo' ),
				'CY' => __( 'Cyprus', 'yoast-local-seo' ),
				'CZ' => __( 'Czech Republic', 'yoast-local-seo' ),
				'CD' => __( 'Democratic Republic Of Congo (Zaire)', 'yoast-local-seo' ),
				'DK' => __( 'Denmark', 'yoast-local-seo' ),
				'DJ' => __( 'Djibouti', 'yoast-local-seo' ),
				'DM' => __( 'Dominica', 'yoast-local-seo' ),
				'DO' => __( 'Dominican Republic', 'yoast-local-seo' ),
				'TP' => __( 'East Timor', 'yoast-local-seo' ),
				'EC' => __( 'Ecuador', 'yoast-local-seo' ),
				'EG' => __( 'Egypt', 'yoast-local-seo' ),
				'SV' => __( 'El Salvador', 'yoast-local-seo' ),
				'GQ' => __( 'Equatorial Guinea', 'yoast-local-seo' ),
				'ER' => __( 'Eritrea', 'yoast-local-seo' ),
				'EE' => __( 'Estonia', 'yoast-local-seo' ),
				'ET' => __( 'Ethiopia', 'yoast-local-seo' ),
				'FK' => __( 'Falkland Islands (Malvinas)', 'yoast-local-seo' ),
				'FO' => __( 'Faroe Islands', 'yoast-local-seo' ),
				'FJ' => __( 'Fiji', 'yoast-local-seo' ),
				'FI' => __( 'Finland', 'yoast-local-seo' ),
				'FR' => __( 'France', 'yoast-local-seo' ),
				'FX' => __( 'France, Metropolitan', 'yoast-local-seo' ),
				'GF' => __( 'French Guinea', 'yoast-local-seo' ),
				'PF' => __( 'French Polynesia', 'yoast-local-seo' ),
				'TF' => __( 'French Southern Territories', 'yoast-local-seo' ),
				'GA' => __( 'Gabon', 'yoast-local-seo' ),
				'GM' => __( 'Gambia', 'yoast-local-seo' ),
				'GE' => __( 'Georgia', 'yoast-local-seo' ),
				'DE' => __( 'Germany', 'yoast-local-seo' ),
				'GH' => __( 'Ghana', 'yoast-local-seo' ),
				'GI' => __( 'Gibraltar', 'yoast-local-seo' ),
				'GR' => __( 'Greece', 'yoast-local-seo' ),
				'GL' => __( 'Greenland', 'yoast-local-seo' ),
				'GD' => __( 'Grenada', 'yoast-local-seo' ),
				'GP' => __( 'Guadeloupe', 'yoast-local-seo' ),
				'GU' => __( 'Guam', 'yoast-local-seo' ),
				'GT' => __( 'Guatemala', 'yoast-local-seo' ),
				'GN' => __( 'Guinea', 'yoast-local-seo' ),
				'GW' => __( 'Guinea-Bissau', 'yoast-local-seo' ),
				'GY' => __( 'Guyana', 'yoast-local-seo' ),
				'HT' => __( 'Haiti', 'yoast-local-seo' ),
				'HM' => __( 'Heard And McDonald Islands', 'yoast-local-seo' ),
				'HN' => __( 'Honduras', 'yoast-local-seo' ),
				'HK' => __( 'Hong Kong', 'yoast-local-seo' ),
				'HU' => __( 'Hungary', 'yoast-local-seo' ),
				'IS' => __( 'Iceland', 'yoast-local-seo' ),
				'IN' => __( 'India', 'yoast-local-seo' ),
				'ID' => __( 'Indonesia', 'yoast-local-seo' ),
				'IR' => __( 'Iran', 'yoast-local-seo' ),
				'IQ' => __( 'Iraq', 'yoast-local-seo' ),
				'IE' => __( 'Ireland', 'yoast-local-seo' ),
				'IL' => __( 'Israel', 'yoast-local-seo' ),
				'IT' => __( 'Italy', 'yoast-local-seo' ),
				'JM' => __( 'Jamaica', 'yoast-local-seo' ),
				'JP' => __( 'Japan', 'yoast-local-seo' ),
				'JO' => __( 'Jordan', 'yoast-local-seo' ),
				'KZ' => __( 'Kazakhstan', 'yoast-local-seo' ),
				'KE' => __( 'Kenya', 'yoast-local-seo' ),
				'KI' => __( 'Kiribati', 'yoast-local-seo' ),
				'KW' => __( 'Kuwait', 'yoast-local-seo' ),
				'KG' => __( 'Kyrgyzstan', 'yoast-local-seo' ),
				'LA' => __( 'Laos', 'yoast-local-seo' ),
				'LV' => __( 'Latvia', 'yoast-local-seo' ),
				'LB' => __( 'Lebanon', 'yoast-local-seo' ),
				'LS' => __( 'Lesotho', 'yoast-local-seo' ),
				'LR' => __( 'Liberia', 'yoast-local-seo' ),
				'LY' => __( 'Libya', 'yoast-local-seo' ),
				'LI' => __( 'Liechtenstein', 'yoast-local-seo' ),
				'LT' => __( 'Lithuania', 'yoast-local-seo' ),
				'LU' => __( 'Luxembourg', 'yoast-local-seo' ),
				'MO' => __( 'Macau', 'yoast-local-seo' ),
				'MK' => __( 'Macedonia', 'yoast-local-seo' ),
				'MG' => __( 'Madagascar', 'yoast-local-seo' ),
				'MW' => __( 'Malawi', 'yoast-local-seo' ),
				'MY' => __( 'Malaysia', 'yoast-local-seo' ),
				'MV' => __( 'Maldives', 'yoast-local-seo' ),
				'ML' => __( 'Mali', 'yoast-local-seo' ),
				'MT' => __( 'Malta', 'yoast-local-seo' ),
				'MH' => __( 'Marshall Islands', 'yoast-local-seo' ),
				'MQ' => __( 'Martinique', 'yoast-local-seo' ),
				'MR' => __( 'Mauritania', 'yoast-local-seo' ),
				'MU' => __( 'Mauritius', 'yoast-local-seo' ),
				'YT' => __( 'Mayotte', 'yoast-local-seo' ),
				'MX' => __( 'Mexico', 'yoast-local-seo' ),
				'FM' => __( 'Micronesia', 'yoast-local-seo' ),
				'MD' => __( 'Moldova', 'yoast-local-seo' ),
				'MC' => __( 'Monaco', 'yoast-local-seo' ),
				'MN' => __( 'Mongolia', 'yoast-local-seo' ),
				'MS' => __( 'Montserrat', 'yoast-local-seo' ),
				'MA' => __( 'Morocco', 'yoast-local-seo' ),
				'MZ' => __( 'Mozambique', 'yoast-local-seo' ),
				'MM' => __( 'Myanmar (Burma)', 'yoast-local-seo' ),
				'NA' => __( 'Namibia', 'yoast-local-seo' ),
				'NR' => __( 'Nauru', 'yoast-local-seo' ),
				'NP' => __( 'Nepal', 'yoast-local-seo' ),
				'NL' => __( 'Netherlands', 'yoast-local-seo' ),
				'AN' => __( 'Netherlands Antilles', 'yoast-local-seo' ),
				'NC' => __( 'New Caledonia', 'yoast-local-seo' ),
				'NZ' => __( 'New Zealand', 'yoast-local-seo' ),
				'NI' => __( 'Nicaragua', 'yoast-local-seo' ),
				'NE' => __( 'Niger', 'yoast-local-seo' ),
				'NG' => __( 'Nigeria', 'yoast-local-seo' ),
				'NU' => __( 'Niue', 'yoast-local-seo' ),
				'NF' => __( 'Norfolk Island', 'yoast-local-seo' ),
				'KP' => __( 'North Korea', 'yoast-local-seo' ),
				'MP' => __( 'Northern Mariana Islands', 'yoast-local-seo' ),
				'NO' => __( 'Norway', 'yoast-local-seo' ),
				'OM' => __( 'Oman', 'yoast-local-seo' ),
				'PK' => __( 'Pakistan', 'yoast-local-seo' ),
				'PW' => __( 'Palau', 'yoast-local-seo' ),
				'PA' => __( 'Panama', 'yoast-local-seo' ),
				'PG' => __( 'Papua New Guinea', 'yoast-local-seo' ),
				'PY' => __( 'Paraguay', 'yoast-local-seo' ),
				'PE' => __( 'Peru', 'yoast-local-seo' ),
				'PH' => __( 'Philippines', 'yoast-local-seo' ),
				'PN' => __( 'Pitcairn', 'yoast-local-seo' ),
				'PL' => __( 'Poland', 'yoast-local-seo' ),
				'PT' => __( 'Portugal', 'yoast-local-seo' ),
				'PR' => __( 'Puerto Rico', 'yoast-local-seo' ),
				'QA' => __( 'Qatar', 'yoast-local-seo' ),
				'RE' => __( 'Reunion', 'yoast-local-seo' ),
				'RO' => __( 'Romania', 'yoast-local-seo' ),
				'RU' => __( 'Russia', 'yoast-local-seo' ),
				'RW' => __( 'Rwanda', 'yoast-local-seo' ),
				'BL' => __( 'Saint Barthelemy', 'yoast-local-seo' ),
				'SH' => __( 'Saint Helena', 'yoast-local-seo' ),
				'KN' => __( 'Saint Kitts And Nevis', 'yoast-local-seo' ),
				'LC' => __( 'Saint Lucia', 'yoast-local-seo' ),
				'MF' => __( 'Saint Martin', 'yoast-local-seo' ),
				'PM' => __( 'Saint Pierre And Miquelon', 'yoast-local-seo' ),
				'VC' => __( 'Saint Vincent And The Grenadines', 'yoast-local-seo' ),
				'SM' => __( 'San Marino', 'yoast-local-seo' ),
				'ST' => __( 'Sao Tome And Principe', 'yoast-local-seo' ),
				'SA' => __( 'Saudi Arabia', 'yoast-local-seo' ),
				'SN' => __( 'Senegal', 'yoast-local-seo' ),
				'SC' => __( 'Seychelles', 'yoast-local-seo' ),
				'SL' => __( 'Sierra Leone', 'yoast-local-seo' ),
				'SG' => __( 'Singapore', 'yoast-local-seo' ),
				'SK' => __( 'Slovak Republic', 'yoast-local-seo' ),
				'SI' => __( 'Slovenia', 'yoast-local-seo' ),
				'SB' => __( 'Solomon Islands', 'yoast-local-seo' ),
				'SO' => __( 'Somalia', 'yoast-local-seo' ),
				'ZA' => __( 'South Africa', 'yoast-local-seo' ),
				'GS' => __( 'South Georgia And South Sandwich Islands', 'yoast-local-seo' ),
				'KR' => __( 'South Korea', 'yoast-local-seo' ),
				'ES' => __( 'Spain', 'yoast-local-seo' ),
				'LK' => __( 'Sri Lanka', 'yoast-local-seo' ),
				'SD' => __( 'Sudan', 'yoast-local-seo' ),
				'SR' => __( 'Suriname', 'yoast-local-seo' ),
				'SJ' => __( 'Svalbard And Jan Mayen', 'yoast-local-seo' ),
				'SZ' => __( 'Swaziland', 'yoast-local-seo' ),
				'SE' => __( 'Sweden', 'yoast-local-seo' ),
				'CH' => __( 'Switzerland', 'yoast-local-seo' ),
				'SY' => __( 'Syria', 'yoast-local-seo' ),
				'TW' => __( 'Taiwan', 'yoast-local-seo' ),
				'TJ' => __( 'Tajikistan', 'yoast-local-seo' ),
				'TZ' => __( 'Tanzania', 'yoast-local-seo' ),
				'TH' => __( 'Thailand', 'yoast-local-seo' ),
				'TG' => __( 'Togo', 'yoast-local-seo' ),
				'TK' => __( 'Tokelau', 'yoast-local-seo' ),
				'TO' => __( 'Tonga', 'yoast-local-seo' ),
				'TT' => __( 'Trinidad And Tobago', 'yoast-local-seo' ),
				'TN' => __( 'Tunisia', 'yoast-local-seo' ),
				'TR' => __( 'Turkey', 'yoast-local-seo' ),
				'TM' => __( 'Turkmenistan', 'yoast-local-seo' ),
				'TC' => __( 'Turks And Caicos Islands', 'yoast-local-seo' ),
				'TV' => __( 'Tuvalu', 'yoast-local-seo' ),
				'UG' => __( 'Uganda', 'yoast-local-seo' ),
				'UA' => __( 'Ukraine', 'yoast-local-seo' ),
				'AE' => __( 'United Arab Emirates', 'yoast-local-seo' ),
				'UK' => __( 'United Kingdom', 'yoast-local-seo' ),
				'US' => __( 'United States', 'yoast-local-seo' ),
				'UM' => __( 'United States Minor Outlying Islands', 'yoast-local-seo' ),
				'UY' => __( 'Uruguay', 'yoast-local-seo' ),
				'UZ' => __( 'Uzbekistan', 'yoast-local-seo' ),
				'VU' => __( 'Vanuatu', 'yoast-local-seo' ),
				'VA' => __( 'Vatican City (Holy See)', 'yoast-local-seo' ),
				'VE' => __( 'Venezuela', 'yoast-local-seo' ),
				'VN' => __( 'Vietnam', 'yoast-local-seo' ),
				'VG' => __( 'Virgin Islands (British)', 'yoast-local-seo' ),
				'VI' => __( 'Virgin Islands (US)', 'yoast-local-seo' ),
				'WF' => __( 'Wallis And Futuna Islands', 'yoast-local-seo' ),
				'EH' => __( 'Western Sahara', 'yoast-local-seo' ),
				'WS' => __( 'Western Samoa', 'yoast-local-seo' ),
				'YE' => __( 'Yemen', 'yoast-local-seo' ),
				'YU' => __( 'Yugoslavia', 'yoast-local-seo' ),
				'ZM' => __( 'Zambia', 'yoast-local-seo' ),
				'ZW' => 'Zimbabwe'
			);

			return $countries;
		}
	}
}
