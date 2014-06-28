<a href="#" id="trigger-msg">trigger</a>
<script type="text/javascript">
	$(function() {
		$('#trigger-msg').click(function(e) {
			e.preventDefault();

			var modalMsg = new kz.modalMsg();
			modalMsg.show('You are being redirected...');

			window.setTimeout(function() {
				modalMsg.hide();
			}, 1000);
		});
	});
</script>