<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/public/assets/compiled/css/styles.css" />
		<script type="text/javascript" src="/public/assets/compiled/js/scripts.js"></script>
		<script type="text/javascript">
			var app = null;
			$(function() {
				app = new kz.app();
				app.setup();
			});
		</script>
		<title><?=isset($pageTitle) ? $pageTitle : 'Mysql debug'?></title>
	</head>
	<body>
		<div class="container">
			<?=$this->renderPartial('layout/navbar')?>
			<?=$this->renderPartial('layout/pageHeader')?>
			<?=$this->renderPartial('layout/flashMessenger')?>
			<?=$content?>
		</div>
		<div id="loading" class="ajax-loading"><div>Loading...</div></div>
		<div id="modal" class="modal fade" tabindex="-1">
			<div class="modal-dialog"></div>
		</div>
		<div id="modal-msg" class="modal" tabindex="-1">
			<div class="modal-msg"></div>
		</div>
	</body>
</html>