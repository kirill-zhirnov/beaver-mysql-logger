if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.ajaxLink = function(el, config)
	{
		if (!(el instanceof jQuery))
			throw new Error('Element must be instance of JQuery.');

		this.el = el;
		this.config = $.extend({
			/**
			 * Bind initial events
			 */
			bindEvents : true,

			confirm : false
		}, config);
	}

	kz.ajaxLink.prototype.setup = function()
	{
		if (this.config.bindEvents)
			this.el.on('click', $.proxy(this.onClick, this));
	}

	kz.ajaxLink.prototype.onClick = function(e)
	{
		e.preventDefault();

		if (this.config.confirm && !confirm(this.config.confirm))
			return;

		var that = this;
		$.post(this.el.attr('href'), {}, function(data) {
			that.el.trigger('afterPost.ajaxLink', [this, data]);
		}, 'json');
	}
}) (jQuery);