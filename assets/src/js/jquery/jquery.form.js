/**
 * How to use:
 *
 * $('form').form(); - will setup kz.form with default config.
 *
 * $('form').form({custom : 'val'}); - will setup form with custom config.
 *
 * $('form').form('myClass', {custom : 'val'}); - will setup kz.myCalss with custom config.
 */
(function($) {
	$.fn.form = function()
	{
		var config, name;
		if (arguments.length > 1) {
			name = arguments[0];
			config = arguments[1];
		} else if (arguments.length == 1) {
			if (typeof(arguments[0]) == 'object') {
				config = arguments[0];
			} else if (typeof(arguments[0]) == 'string') {
				name = arguments[0];
			}
		}

		if (typeof(name) == 'undefined')
			name = 'form';

		if (typeof(kz[name]) != 'function')
			throw new Error('Form "' + name + '" does not exist!');

		$(this).each(function() {
			var obj = new kz[name]($(this), config);
			obj.setup();
		});
	}
}) (jQuery);