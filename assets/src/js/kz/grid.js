if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.grid = function(el, config)
	{
		kz.grid.superclass.constructor.call(this, el, $.extend({
			paginationSelector : '.pagination a',
			filterSelector : '.grid-filter',
			filterReset : '.grid-filter .grid-reset'
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
		this.el.on('update.grid', $.proxy(function(e, url, params) {
			this.update(url, params);
		}, this));

		this.el.on('click', this.config.paginationSelector, $.proxy(this.onPaginationClick, this));

		this.el.on('submit', this.config.filterSelector, $.proxy(this.onFilterSubmit, this));
		this.el.on('click', this.config.filterReset, $.proxy(this.onFilterReset, this));
	}

	kz.grid.prototype.update = function(url, params)
	{
		var that = this;
		$.post(url, params, function(data) {
			if (typeof(data.html) != 'undefined')
				that.replace(data.html);
		}, 'json');
	}

	kz.grid.prototype.onFilterReset = function(e)
	{
		e.preventDefault();

		this.update($(e.target).attr('action'), {});
	}

	kz.grid.prototype.onFilterSubmit = function(e)
	{
		e.preventDefault();

		$form = $(e.target);
		this.update($form.attr('action'), $form.serializeArray());
	}

	kz.grid.prototype.onPaginationClick = function(e)
	{
		e.preventDefault();

		this.update($(e.target).attr('href'), {});
	}
}) (jQuery);