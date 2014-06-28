if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.modal = function(el, config)
	{
		config = $.extend({
			wrapperSelector : '#modal',
			bsConfig : {
				show : false
			},
			closeOnFormSuccess : true
		}, config);

		this.wrapperEl = $(config.wrapperSelector).modal(config.bsConfig);

		if (!(el instanceof jQuery))
			el = this.wrapperEl.find('.modal-dialog');

		kz.form.superclass.constructor.call(this, el, config);

		this.setupFormListeners();
	}

	extend(kz.modal, kz.widget);

	kz.modal.prototype.setupFormListeners = function()
	{
		var that = this;
		this.wrapperEl.on('afterSubmit.widget', 'form', function(e, formWidget, data) {
			console.log(that.wrapperEl.find('form'));
//			console.log(data);
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
