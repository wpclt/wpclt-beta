/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Theme Style
	/*wp.customize( 'firmasite_settings[style]', function( value ) {
		value.bind( function( newval ) {
			// we got link href
			var href = $('link#bootstrap-css').attr('href');
			// we split it to parts
			var oldstyle = href.split('/');
			// we got style name part
			var oldstylename = oldstyle[oldstyle.length - 2];
			// we replaced old style name with new value			
			href = href.replace(oldstylename,newval);
			// we giving proper class to #page
			$('#page').removeClass( oldstylename + '-theme' );	
			$('#page').addClass(  newval + '-theme' );	

			// we replace style css.. styles_url comes with wp_localize_script
			$('link#bootstrap-css').attr('href', firmasite_settings_array["styles_url"][newval]+'/bootstrap.min.css');

		} );
	} );*/

	
	//Google font
	/*wp.customize( 'firmasite_settings[font]', function( value ) {
		value.bind( function( newval ) {
			$('#firmasite-font').remove();
			if(newval){
				$('head').append('<link id="firmasite-font" href="//fonts.googleapis.com/css?family=' + newval + ':300,400,700&subset=latin,' + firmasite_settings_array["subsets"] + '" rel="stylesheet" type="text/css">');
				$('head').append('<style type="text/css"> body, h1, h2, h3, h4, h5, h6, .btn, .navbar { font-family: '+ newval + ',sans-serif !important; } </style>');
			} else {
				$('head').append('<style type="text/css"> body, h1, h2, h3, h4, h5, h6, .btn, .navbar { font-family: sans-serif !important; } </style>');		
			}

		} );
	} );*/
		
} )( jQuery );