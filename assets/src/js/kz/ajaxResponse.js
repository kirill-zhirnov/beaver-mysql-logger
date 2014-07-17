if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.ajaxResponse = function()
	{
	}

	kz.ajaxResponse.prototype.response = function(responseJSON)
	{
		if (typeof(responseJSON.redirect) == 'string') {
			kz.util.redirect(responseJSON.redirect);

			return;
		}

		if (typeof(responseJSON.reload) == 'boolean' && responseJSON.reload) {
			kz.util.reload();

			return;
		}

		if (typeof(responseJSON.openPopup) == 'string') {
			var modal = new kz.modal();
			modal.load(responseJSON.openPopup);

			return;
		}
	}
}) (jQuery);
