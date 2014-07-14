<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/public/assets/compiled/css/styles.css" />
		<title>Error!</title>
	</head>
	<body>
		<div class="container">
			<?=$this->renderPartial('layout/navbar')?>
			<p class="alert alert-danger"><?=$error?></p>
		</div>
	</body>
</html>