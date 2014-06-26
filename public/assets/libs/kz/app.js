if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.app = function(el)
	{
		if (typeof(el) == 'undefined')
			el = $('html');

		if (!(el instanceof jQuery))
			throw new Error('Element must be instance of JQuery.');

		this.el = el;
	}

	kz.app.prototype.setup = function()
	{
		this.setupGlobalListeners();
	}

	kz.app.prototype.setupGlobalListeners = function()
	{
		this.el.on('submit', 'form[data-form]', function(e) {
			var el = $(this),
				className = el.data('form') ? el.data('form') : 'form';

			var form = new kz[className](el, {
				bindEvents : false
			});
			form.setup();
			form.onSubmit(e);
		});
	}

}) (jQuery);