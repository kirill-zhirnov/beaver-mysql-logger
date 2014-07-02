<?php
	/** @var \KZ\view\helpers\Html $html */
	$html = $this->helper('html');
?>
<form action="<?=$this->helper('link')->get('setup/index')?>" method="post" data-form="" role="form">
	<div class="form-group">
		<?=$html->label($model, 'dsn', 'DSN:')?>
		<?=$html->text($model, 'dsn', ['class' => 'form-control'])?>
		<?=$html->errors($model, 'dsn')?>
	</div>
	<div class="form-group">
		<?=$html->label($model, 'username', 'Username:')?>
		<?=$html->text($model, 'username', ['class' => 'form-control'])?>
		<?=$html->errors($model, 'username')?>
	</div>
	<div class="form-group">
		<?=$html->label($model, 'password', 'Password:')?>
		<?=$html->text($model, 'password', [
			'type' => 'password',
			'class' => 'form-control'
		])?>
		<?=$html->errors($model, 'password')?>
	</div>
	<div class="form-group">
		<?=$html->label($model, 'options', 'Options (json):')?>
		<?=$html->textArea($model, 'options', ['class' => 'form-control'])?>
		<?=$html->errors($model, 'options')?>
	</div>
	<div class="form-group text-right">
		<button type="submit" class="btn btn-success">Save</button>
	</div>
</form>