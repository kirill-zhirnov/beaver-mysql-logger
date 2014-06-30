if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.util = {
		redirect : function(url)
		{
			if (window.location.href == url) {
				this.reload();
				return;
			}

			var modalMsg = new kz.modalMsg();
			modalMsg.show('You are being redirected...');

			window.location = url;
		},

		reload : function()
		{
			var modalMsg = new kz.modalMsg();
			modalMsg.show('You are being redirected...');

			window.location.reload();
		}
	};
}) (jQuery);