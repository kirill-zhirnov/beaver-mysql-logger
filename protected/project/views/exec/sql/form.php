<?php
	/** @var \KZ\view\helpers\Html $html */
	/** @var \models\ExecSqlForm $model */
	$html = $this->helper('html');
?>
<form action="<?=$this->helper('link')->get('exec/sql')?>" method="post" role="form" data-form="">
	<?php if (!empty($error)):?>
		<p class="alert alert-danger"><?=$error?></p>
	<?php endif?>

	<?php
		if (isset($result) && $result !== false)
			echo $this->renderPartial('exec/sql/result');
	?>

	<?=$html->hidden($model, 'threadId')?>
	<?=$html->hidden($model, 'commandType')?>
	<?=$html->hidden($model, 'db')?>
	<?=$html->hidden($model, 'run')?>

	<?=$html->formGroup($model, 'sql')?>
		<?=$html->label($model, 'sql', 'SQL:')?>
		<?=$html->textArea($model, 'sql', [
			'class' => 'form-control',
			'rows' => 8,
			'value' => $this->helper('sqlFormatter')->format($model->sql, false)
		])?>
		<?=$html->errors($model, 'sql')?>
		<?=$html->errors($model, 'threadId')?>
		<?=$html->errors($model, 'commandType')?>
		<?=$html->errors($model, 'run')?>
	</div>
	<div class="text-center">
		<button type="submit" class="btn btn-primary">Execute</button>
	</div>
</form>