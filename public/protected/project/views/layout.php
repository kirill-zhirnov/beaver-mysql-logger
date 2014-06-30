<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

<!--		<link rel="stylesheet" href="/public/assets/libs/twitterBootstrap/css/bootstrap.min.css">-->
		<link rel="stylesheet" href="/public/compiled-assets/css/styles.css" />

		<?php //@todo: fixme!?>
		<script type="text/javascript" src="/public/assets/libs/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="/public/assets/libs/extend.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/util.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/app.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/widget.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/form.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/loading.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/ajaxResponse.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/modal.js"></script>
		<script type="text/javascript" src="/public/assets/libs/kz/modalMsg.js"></script>
		<script type="text/javascript" src="/public/assets/libs/jquery/jquery.form.js"></script>
		<script src="/public/assets/libs/twitterBootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			var app = null;
			$(function() {
				app = new kz.app();
				app.setup();
			});
		</script>
	</head>
	<body>
		<div id="modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog"></div>
		</div>
		<div id="modal-msg" class="modal" tabindex="-1">
			<div class="modal-msg"></div>
		</div>
		<?=$content?>
		<div id="loading" class="ajax-loading">Loading</div>
	</body>
</html>