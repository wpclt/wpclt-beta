(function ($) {
$( document ).on('ready', function(){
	$inputs = $("input");
	$inputs.filter("[type='submit'], [type='button']").addClass("btn btn-default");
	$inputs.filter("[type='text'], :not([type]), [type='password'], [type='search'], [type='email']").addClass("form-control"); //not([type]) included as browser defaults to text when attribute not present
	$("textarea").addClass("form-control");

	$('input[value=content-sidebar]').after('<span class="content-sidebar"></span>').parent().wrapInner('<div class="radio" />');
	$('input[value=sidebar-content]').after('<span class="sidebar-content"></span>').parent().wrapInner('<div class="radio" />');
	$('input[value=only-content]').after('<span class="only-content"></span>').parent().wrapInner('<div class="radio" />');
	$('input[value=only-content-long]').after('<span class="only-content-long"></span>').parent().wrapInner('<div class="radio" />');

	$('input[value=loop-list]').after('<span class="loop-list"></span>');
	$('input[value=loop-tile]').after('<span class="loop-tile"></span>');
	$('input[value=loop-excerpt]').after('<span class="loop-excerpt"></span>');
	
	$("input[type=text]").addClass("form-control");
	$("select").addClass("form-control");
	$("textarea").addClass("form-control");
	
		// Image Options
	$('.of-radio-img-img').click(firmasite_change_style);
	$('.of-radio-img-label').click(firmasite_change_style);	

	function firmasite_change_style() {
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).parent().parent().find('.of-radio-img-radio').removeAttr('checked');
		$(this).parent().parent().find('.of-radio-img-label').removeClass('selected');
		$(this).parent().parent().find('.of-radio-img-label i').remove();
		$(this).parent().find('.of-radio-img-img').addClass('of-radio-img-selected');		
		$(this).parent().find('input.of-radio-img-radio').click();
		$(this).parent().find('.of-radio-img-label').addClass('selected');
		$(this).parent().find('.of-radio-img-label').prepend('<i class="icon-ok"></i> ');

		// we got link href
		var href = $('link#customizer-bootstrap-css').attr('href');
		var newval = $(this).parent().find('input.of-radio-img-radio').attr("id");
		// we split it to parts
		var oldstyle = href.split('/');
		// we got style name part
		var oldstylename = oldstyle[oldstyle.length - 2];
		// we replaced old style name with new value			
		href = href.replace(oldstylename,newval);
		// we replace style css.. styles_url comes with wp_localize_script
		$('link#customizer-bootstrap-css').attr('href', styles_url[newval]+'/bootstrap.min.css');

	}
	$('.of-radio-img-label').show();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

});

})(jQuery);
