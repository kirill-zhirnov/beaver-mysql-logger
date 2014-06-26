<!DOCTYPE html>
<html>
	<head>
		<?php //@todo: fixme!?>
		<script type="text/javascript" src="/public/assets/libs/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="/public/assets/libs/extend.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/app.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/widget.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/form.js"></script>
		<script type="text/javascript" src="/public/assets/libs/jquery/jquery.form.js"></script>
	</head>
	<body>
		<?=$content?>
		<script type="text/javascript">
			var app = null;
			$(function() {
				app = new kz.app();
				app.setup();
			});
		</script>
	</body>
</html>