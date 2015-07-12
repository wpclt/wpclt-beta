jQuery ->
	if (jQuery('.nm-postbox-container').length > 0)
		jQuery('body').addClass('nm-admin-page');


	jQuery('.nm-postbox-container .handlediv, .nm-postbox-container .hndle').on 'click', (e) ->
		e.preventDefault()
		jQuery(this).parent().toggleClass('closed')
