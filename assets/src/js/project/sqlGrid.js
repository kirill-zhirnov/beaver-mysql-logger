if (typeof(project) == 'undefined' || !project) {
	var project = {};
}

(function($) {
	project.sqlGrid = function(el, config)
	{
		project.sqlGrid.superclass.constructor.call(this, el, config);
	}

	extend(project.sqlGrid, kz.grid);

	project.sqlGrid.prototype.onSetup = function()
	{
		project.sqlGrid.superclass.onSetup.call(this);

		this.setupExpand();
		this.setupDatePicker();
		this.setupThreadLinkFilter()
	}

	project.sqlGrid.prototype.setupThreadLinkFilter = function()
	{
		var that = this;
		this.el.on('click', '.thread-id a', function(e) {
			e.preventDefault();

			that.filter.find('.thread-id input').val($(this).data('thread-id'));
			that.filter.trigger('submit');
		});
	}

	project.sqlGrid.prototype.setupDatePicker = function()
	{
		//date picker:
		this.el.find('.grid-filter .event-time input').datetimepicker({
			format : 'Y-m-d H:i:s',
			validateOnBlur : false
		});
	}

	project.sqlGrid.prototype.setupExpand = function()
	{
		this.el.find('.argument .sql').each(function() {
			$el = $(this);

			if ($el.innerHeight() > 100)
				$el.addClass('pointer');
		});

		this.el.on('click', '.argument .sql.pointer', function(e) {
			e.preventDefault();

			$(this).removeClass('pointer').addClass('opened');
		});

		this.el.on('click', '.argument .close-top,.argument .close-bottom', function(e) {
			e.preventDefault();

			var $sql = $(this).parents('.sql');
			$sql.removeClass('opened').addClass('pointer');
		});
	}
}) (jQuery);
