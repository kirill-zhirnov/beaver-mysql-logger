<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="/public/assets/libs/twitterBootstrap/css/bootstrap.min.css">
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
		<style type="text/css">
			#loading {
				width: 100px;
				height: 100px;
				background: #ccc;
				position: fixed;
				top: 50%;
				left: 50%;
				margin: -50px 0 0 -50px;
				display: none;
				visibility: hidden;
			}

			.modal-msg {
				background: #fff;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				border-radius: 5px;
				padding: 10px;
				text-align: center;
				width: 250px;
				position: absolute;
				top: 50%;
				left: 50%;
				margin: -30px 0 0 -130px;
				font-size: 140%;
			}
		</style>
	</head>
	<body>
		<div id="modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog"></div>
		</div>
		<div id="modal-msg" class="modal" tabindex="-1">
			<div class="modal-msg"></div>
		</div>
		<?=$content?>
		<div id="loading">Loading</div>
	</body>
</html>