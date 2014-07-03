<?php
	/** @var \KZ\view\helpers\Html $html */
	$html = $this->helper('html');
?>
<form action="<?=$this->helper('link')->get('setup/index')?>" method="post" role="form" data-form="">
	<?=$html->formGroup($model, 'dsn')?>
		<?=$html->label($model, 'dsn', 'DSN:')?>
		<?=$html->text($model, 'dsn', ['class' => 'form-control'])?>
		<?=$html->errors($model, 'dsn')?>
	</div>
	<?=$html->formGroup($model, 'username')?>
		<?=$html->label($model, 'username', 'Username:')?>
		<?=$html->text($model, 'username', ['class' => 'form-control'])?>
		<?=$html->errors($model, 'username')?>
	</div>
	<?=$html->formGroup($model, 'password')?>
		<?=$html->label($model, 'password', 'Password:')?>
		<?=$html->text($model, 'password', [
			'type' => 'password',
			'class' => 'form-control'
		])?>
		<?=$html->errors($model, 'password')?>
	</div>
	<?=$html->formGroup($model, 'options')?>
		<?=$html->label($model, 'options', 'Options (json):')?>
		<?=$html->textArea($model, 'options', ['class' => 'form-control'])?>
		<?=$html->errors($model, 'options')?>
	</div>
	<div class="text-right form-group">
		<button type="submit" class="btn btn-success">Save</button>
	</div>
</form>