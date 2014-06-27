if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.ajaxResponse = function(app)
	{
		this.app = app;
	}

	kz.ajaxResponse.prototype.response = function(responseJSON)
	{
		if (typeof(responseJSON.redirect) == 'string')
			alert('redirect must be here!');

		if (typeof(responseJSON.reload) == 'boolean' && responseJSON.reload)
			alert('reload must be here!');

		if (typeof(responseJSON.openPopup) == 'string')
			alert('open popup must be here!');
	}
}) (jQuery);
