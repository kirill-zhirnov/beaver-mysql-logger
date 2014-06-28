if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.modal = (function() {
		var instances = {};

		return function modalSingletone (config) {

			config = $.extend({
				wrapperSelector : '#modal',
				bsConfig : {
					show : false
				},
				closeOnFormSuccess : true
			}, config);

			//singleton part
			if (typeof(instances[config.wrapperSelector]) != 'undefined')
				return instances[config.wrapperSelector];

			if (this && this.constructor === modalSingletone)
				instances[config.wrapperSelector] = this;
			else
				return new modalSingletone(name);

			//prepare args and call parent constructor
			this.wrapperEl = $(config.wrapperSelector).modal(config.bsConfig);
			el = this.wrapperEl.find('.modal-dialog');

			kz.modal.superclass.constructor.call(this, el, config);

			this.setupInstance();
		};
	} ());

	extend(kz.modal, kz.widget);

	kz.modal.prototype.setupInstance = function()
	{
		this.setupFormListeners();
	}

	kz.modal.prototype.setupFormListeners = function()
	{
		var that = this;
		this.wrapperEl.on('afterSubmit.widget', 'form', function(e, formWidget, data) {
			if (that.config.closeOnFormSuccess && typeof(data.result) != 'undefined' && data.result)
				that.hide();
		});
	}

	kz.modal.prototype.show = function()
	{
		this.wrapperEl.modal('show');
	}

	kz.modal.prototype.hide = function()
	{
		this.wrapperEl.modal('hide');
	}

	kz.modal.prototype.load = function(url)
	{
		var that = this;
		$.post(url, {}, function(data) {
			if (typeof(data.html) != 'undefined')
				that.replace(data.html);

			that.show();
		}, 'json');
	}

	kz.modal.prototype.makeReplaceSelector = function()
	{
		return 'div.modal-dialog';
	}
}) (jQuery);
