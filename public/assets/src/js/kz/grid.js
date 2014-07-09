if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.grid = function(el, config)
	{
		kz.grid.superclass.constructor.call(this, el, $.extend({
			paginationSelector : '.pagination a'
		}, config));
	}

	extend(kz.grid, kz.widget);

	kz.grid.prototype.onSetup = function()
	{
		if (this.config.bindEvents)
			this.bindEvents();
	}

	kz.grid.prototype.bindEvents = function()
	{
		this.el.on('click', this.config.paginationSelector, $.proxy(this.onPaginationClick, this));
	}

	kz.grid.prototype.onPaginationClick = function(e)
	{
		e.preventDefault();

		var that = this;
		$.post($(e.target).attr('href'), {}, function(data) {
			if (typeof(data.html) != 'undefined')
				that.replace(data.html);
		}, 'json');
	}
}) (jQuery);