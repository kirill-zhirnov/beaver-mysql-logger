if (typeof(kz) == 'undefined' || !kz) {
	var kz = {};
}

(function($) {
	kz.modalMsg = function(config) {
		config = $.extend({
			wrapperSelector : '#modal-msg',
			bsConfig : {
				keyboard : false,
				show : false,
				backdrop : 'static'
			},
			elSelector : '.modal-msg'
		}, config);

		/**
		 * @type kz.modal
		 */
		this.modal = new kz.modal(config);
	};

	kz.modalMsg.prototype.show = function(message)
	{
		this.modal.el.text(message);
		this.modal.show();
	}

	kz.modalMsg.prototype.hide = function(message)
	{
		this.modal.hide();
	}
}) (jQuery);