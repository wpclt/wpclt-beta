Local SEO
=========
Requires at least: 3.4<br/>
Tested up to: 3.8.1<br/>
Stable tag: 1.2.2.2

Description
-----------

Local Search module for WordPress SEO plugin of yoast.com.

Changelog
=========

1.2.2.2
-----
* Bugfixes:
    * Video sitemap was breaking after update 1.2.2.1. We fixed that now.

1.2.2.1
-----
* Bugfixes:
    * Due to changes in sitemaps to be more in line with other WordPress SEO sitemaps, geo_sitemap.xml was not working anymore. Added now a redirect to redirect geo_sitemap.xml to geo-sitemap.xml

1.2.2
-----
* Bugfixes:
    * Fixes fatal error in metabox when having no internet connection
    * Updates lat.long coordinates after changing address of location
    * Force slug for locations CPT, even when blank in admin bug
    * Notice fix in widget when location has no lat/long coordinates
* Enhancements:
    * Possibility to add default country to imporve searches from store locator (it adds the country to the search query)
    * Show meesage when route cannot be calculated
    * Pre-select location when adding short codes via popup
    * Add filter to time-frame in Opening Hours
    * Added parameter to shortcode that prevents mouse scrolling

1.2.1
-----
* Bugfixes:
    * Fixed: Store locator routing function was broke
    * Some addresses were not reverse geocoded well by the route planner.
* Enhancements:
    * Load text domain through filter now, so you can overrule standard translations. (Thanks to Timo Leiniö and http://geertdedeckere.be/article/loading-wordpress-language-files-the-right-way)


1.2.1
-----
* Bugfixes:
    * Fixed: Store locator gave unexpected results with a lot of locations
    * Store locator popup checkboxes didn't work correctly. Now they do. The scrollbar is gone too.
    * Fixed: When some locations don't have geo locations, map with all locations fails
    * Fixed: Map failed when some locations don't have lat/long coordinates
* Enhancements:
    * Added documentation for CSV import
    * Routeplanner on mobile phones opens now in maps.google.com, which results in opening in the Google Maps app (if installed)
* i18n
    * Updated .pot file
    * Updated ru_RU translation


1.2
-----
* Bugfixes:
    * Checkbox 'Hide closed days' in widget-admin now works.
    * Added filter 'wpseo_local_location_route_title_name' for title 'route' of widget and shortcode
    * Added esc_html to filter 'wpseo_local_location_title_tag_name'
    * Replaced WPSEO_LOCAL_URL constants by using plugins_url() so that it can be filtered (where needed)
* Enhancements:
    * Added a store locator. Gives you the possibility to let people search for the neirest store/office
    * Added a custom taxonomy for categorizing your locations
    * You can enter custom URL's for your locations now
    * Better icons for adding shortcodes
    * Better UI for selecting the map style when adding a map shortcode
    * Added possibility to add comma separated ID's to wpseo_map shortcode for selectively showing locations on a map
    * Added a second field for a phone number (office, mobile etc.)
    * Allow HTML in the "Extra comment" field in the Address and Opening Hours widgets
* i18n:
    * Updated .pot file

1.1.7
-----
* Bugfixes:
    * When outputting opening hours on its own, don't add schema.org
    * When using the "insert address" button it inserts the entire address with phone, country, fax, ect whether or not it's checked.
    * When "hide closing days" isn't checked it still hides them.
    * Added page layout options for Genesis themes
    * Added quarters for the opening hours
    * Added shortcode ( [wpseo_all_locations] ) to display all your locations at once.
* Enhancements:
    * Added icons to shortcode buttons
    * Added opening hours shortcode button
		* Allow license key to be set by constant WPSEO_LOCAL_LICENSE. Key will be hidden if valid.
    * Created option to show URL in address detail and in info-box in Google Map
    * Deleted unnecessary files
* i18n:
    * Updated hu_HU & ru_RU
    * Updated .pot file

1.1.6
-----
* Bugfixes:
    * Apostrophe in company name created issues. Not anymore
    * Setting Unit system works again
    * When specifying a business type and saving, the chosen business type is now selected.
    * Opening hours now display correctly if the opening hours are set to two sets, and only one set is used
* Enhancements:
    * Shortcodes can now be inserted visually (button opens popup with settings)
    * Google Maps is now responsive (fluid width)
    * Hide link in popup box (Google Map) when there's just one location
    * Added comment box in the address and opening hours widgets, for extra (optional) comments.

1.1.5
-----
* Bugfixes:
	* Make sure maps work on https.
	* Improve JS output.
	* Fix several widget bugs.
* Enhancements:
	* Remove jQuery dependency.
	* Move JS to external file.
* i18n:
	* Updated ru_RU translation.
	* Added Swedish and Polish.

1.1.4
-----
* Bugfixes:
    * Allow more values in shortcodes to set stuff to false.
    * Fix bounds for Maps.
* Enhancements:
    * Make maps output search engine indexable links too.
* i18n:
    * Added ru_RU translation.

1.1.3
-----
* Bugfixes:
    * Fix activation hook to work on add_option instead of just update_option, so activation works immediately.
    * Multiple maps embedded on one page now work properly.
    * Dropdowns with chosen script now line out properly.
    * Google Maps geocoder script + maps embed scripts now properly enqueued and outputted in footer instead of within content.
    * Maps shortcode output bug fixed.
* Inline documentation:
    * Added link to FAQ entry about schema.org business types.
* Enhancements:
    * Added back LocalBusiness business type to top of business type select.
    * You can now use "Current location" for widgets, so you can use them on the locations pages. They'll output nothing outside of locations.

1.1.2
-----
* i18n
    * Added da_DK, hu_HU, it_IT and nl_NL translations.
* Bugfixes
    * Fix `class_exists` check to actually check for the right class (props [Ryan McCue](http://ryanmccue.info/)).
    * Make both front and backend classes global so methods can be used outside the plugin (props [Ryan McCue](http://ryanmccue.info/)).
    * Fix overwriting of `$args` variable which broke widgets.

1.1.1
-----
* Bugfixes:
    * Make updater actually work...

1.1
---
* Enhancements:
    * Added hide_closed option to opening hours shortcode and widgets.
    * Added option to show fax number and email address in both shortcode and widget.
    * Improved UI for opening hours.
    * Switched to a better endpoint for Google Maps Geocode API.
    * Added state to KML file output.
* Bugfixes:
    * "undefined" URL in maps shortcode and widgets.
    * Fixed several notices.
    * Values "off" and "no" now properly work for shortcodes.

1.0
---
* Initial version.

