<?php
	/** @var \KZ\view\helpers\Html $html */
	$html = $this->helper('html');
?>
<form action="<?=$this->helper('link')->get('setup/index')?>" method="post">
	<div>
		<label>DSN:</label>
		<?=$html->text($model, 'dsn')?>
		<?=$html->errors($model, 'dsn')?>
	</div>
	<div>
		<label>Username:</label>
		<?=$html->text($model, 'username')?>
		<?=$html->errors($model, 'username')?>
	</div>
	<div>
		<label>Password:</label>
		<?=$html->text($model, 'password', ['type' => 'password'])?>
		<?=$html->errors($model, 'password')?>
	</div>
	<div>
		<label>Options (json):</label>
		<?=$html->textArea($model, 'options')?>
		<?=$html->errors($model, 'options')?>
	</div>
	<div>
		<button class="submit">Save</button>
	</div>
</form>