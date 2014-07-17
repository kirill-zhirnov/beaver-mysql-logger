if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.loading = function(config)
	{
		this.config = $.extend({
			selector : '#loading'
		}, config);

		this.el = $(this.config.selector);
	}

	kz.loading.prototype.show = function()
	{
		this.el.show();
	}

	kz.loading.prototype.hide = function()
	{
		this.el.hide();
	}
}) (jQuery);