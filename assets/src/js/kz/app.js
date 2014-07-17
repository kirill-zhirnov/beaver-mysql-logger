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

		this.config = {
			showLoading : true,
			loadingSelector : '#loading'
		};

		/**
		 * @type kz.loading
		 */
		this.loading = null;

		/**
		 * @type kz.ajaxResponse
		 */
		this.ajaxResponse = null;
	}

	kz.app.prototype.setup = function()
	{
		this.loading = new kz.loading({
			selector: this.config.loadingSelector
		});

		this.ajaxResponse = new kz.ajaxResponse();

		this.setupAjax();
		this.setupGlobalListeners();
	}

	kz.app.prototype.showLoading = function(val)
	{
		this.config.showLoading = Boolean(val);

		return this;
	}

	kz.app.prototype.setupAjax = function()
	{
		var that = this;
		$(document).ajaxStart(function() {
			if (that.config.showLoading)
				that.loading.show();
		});

		$(document).ajaxComplete(function(e, jqXHR, ajaxOptions) {
			that.loading.hide();

			if (typeof(jqXHR.responseJSON) != 'undefined')
				that.ajaxResponse.response(jqXHR.responseJSON);
		});
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

		this.el.on('click', 'a[data-modal],input[data-modal],button[data-modal]', function(e) {
			e.preventDefault();

			var el = $(this),
				className = el.data('modal') ? el.data('modal') : 'modal',
				modal = new kz[className](),
				href = (typeof(el.attr('href')) != 'undefined') ? el.attr('href') : el.data('modal-url'),
				modal = new kz.modal()
			;

			modal.load(href);
		});

		this.el.on('click', 'a[data-ajax-link]', function(e) {
			var $el = $(this);
			if ($el.data('ajax-link-instance') instanceof kz.ajaxLink) {
				var instance = $el.data('ajax-link-instance');
			} else {
				var config = {
					bindEvents: false
				};

				if ($el.data('ajax-link-config'))
					config = $.extend(config, $el.data('ajax-link-config'));

				var instance = new kz.ajaxLink($el, config);
				$el.data('ajax-link-instance', instance);
			}

			instance.onClick(e);
		});
	}

}) (jQuery);