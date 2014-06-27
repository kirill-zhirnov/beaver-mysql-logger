if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.form = function(el, config)
	{
		kz.form.superclass.constructor.call(this, el, $.extend({
			buttonsSelector : ':button, input[type=submit]'
		}, config));
	}

	extend(kz.form, kz.widget);

	kz.form.prototype.onSetup = function()
	{
		if (this.config.bindEvents)
			this.el.bind('submit', $.proxy(this.onSubmit, this));
	}

	kz.form.prototype.onSubmit = function(e)
	{
		e.preventDefault();

		//disable buttons
		if (this.config.buttonsSelector)
			this.el.find(this.config.buttonsSelector).attr('disabled', true);

		var that = this;
		$.post(this.el.attr('action'), this.el.serializeArray(), function(data) {
			if (typeof(data.html) != 'undefined')
				that.replace(data.html);

			that.afterSubmit(data);
		}, 'json');
	}

	kz.form.prototype.afterSubmit = function(data)
	{
		this.el.trigger('afterSetup.widget', [this, data]);
	}

}) (jQuery);