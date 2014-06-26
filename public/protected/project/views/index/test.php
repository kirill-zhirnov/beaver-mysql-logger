<div class="widget1 class2  super-class" id="widget1<?php if (!empty($content)) echo rand(1,1000)?>">
	<?php if (!empty($content)):?>
		<?=$content?>
	<?php else:?>
		this is pure content
	<?php endif?>
</div>
<input type="submit" value="update" id="update" />
<script type="text/javascript">
	$(function() {
		$('#widget1').bind('afterSetup.widget', function(e, widget) {
			console.log(arguments);
		});

		var widget = new kz.widget($('#widget1'), {});
		widget.setup();

		$('#update').click(function(e) {
			e.preventDefault();

			$.post('<?=$this->helper('link')->get('index/update')?>', {}, function(data) {
				widget.replace($(data.html));
			}, 'json');
		});
	});
</script>