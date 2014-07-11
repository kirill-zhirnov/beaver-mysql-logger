if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.widget = function(el, config)
	{
		if (!(el instanceof jQuery))
			throw new Error('Element must be instance of JQuery.');

		this.el = el;
		this.config = $.extend({
			/**
			 * Bind initial events
			 */
			bindEvents : true,

			/**
			 * Selector will be used to find new element during replacement.
			 */
			replaceSelector : 'div:first',

			/**
			 * Callback which will be called after setup.
			 */
			afterSetup : null
		}, config);
	}

	kz.widget.prototype.setup = function()
	{
		this.config.replaceSelector = this.makeReplaceSelector();

		this.onSetup();

		this.afterSetup();
	}

	/**
	 * This method for children: redefine it to have additional logic.
	 */
	kz.widget.prototype.onSetup = function()
	{}

	kz.widget.prototype.afterSetup = function()
	{
		if (typeof(this.config.afterSetup) == 'function')
			this.config.afterSetup.call(this);

		this.el.trigger('afterSetup.widget', [this]);
	}

	kz.widget.prototype.makeReplaceSelector = function()
	{
		var selector = this.el.prop('tagName').toLowerCase(),
			classes = this.el.attr('class');

		if (typeof(classes) == 'string')
			$.each(classes.split(/\s+/), function(key, val) {
				selector += '.' + val;
			});

		selector += ':first';

		return selector;
	}

	kz.widget.prototype.replace = function(html)
	{
		html = (html instanceof jQuery) ? html : $(html);

		var wrapper = $('<div></div>').append(html),
			newEl = wrapper.find(this.config.replaceSelector);

		if (newEl.size() == 0)
			throw new Error('Cannot find new element');

		this.el.replaceWith(newEl);
		this.el = newEl;

		this.setup();

		return this;
	}
}) (jQuery);