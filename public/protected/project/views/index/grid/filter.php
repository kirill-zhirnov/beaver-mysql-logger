<?php
	/** @var \grids\GeneralLog $grid */
	/** @var \KZ\view\helpers\Link $link */
	/** @var \KZ\view\helpers\Html $html */

	$html = $this->helper('html');
	$link = $this->helper('link');
	$model = $grid->getFilter();
?>
<form action="<?=$link->get('index/index')?>" method="get" class=" grid-filter" role="form">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td class="thread-id">
						<?=$html->label($model, 'threadId', 'Thread ID', ['class' => 'sr-only'])?>
						<?=$html->text($model, 'threadId', [
							'class' => 'form-control',
							'placeholder' => 'Thread ID'
						])?>
						<?=$html->errors($model, 'threadId')?>
				</td>
				<td class="command-type">
					<?=$html->label($model, 'commandType', 'Command type', ['class' => 'sr-only'])?>
					<?=$html->dropDownList(
						$model,
						'commandType',
						$model->getCommandTypeOptions(['' => 'Command type']),
						['class' => 'form-control']
					)?>
					<?=$html->errors($model, 'commandType')?>
				</td>
				<td class="argument">
					<?=$html->label($model, 'argument', 'Argument', ['class' => 'sr-only'])?>
					<?=$html->text($model, 'argument', [
						'class' => 'form-control',
						'placeholder' => 'argument'
					])?>
					<?=$html->errors($model, 'argument')?>
				</td>
				<td class="event-time">
					<?=$html->label($model, 'eventTime', 'Event time', ['class' => 'sr-only'])?>
					<?=$html->text($model, 'eventTime', [
						'class' => 'form-control',
						'placeholder' => 'Event time'
					])?>
					<?=$html->errors($model, 'eventTime')?>
				</td>
				<td class="server-id">
					<?=$html->label($model, 'serverId', 'Event time', ['class' => 'sr-only'])?>
					<?=$html->text($model, 'serverId', [
						'class' => 'form-control',
						'placeholder' => 'Server Id'
					])?>
					<?=$html->errors($model, 'serverId')?>
				</td>
				<td class="user-host">
					<?=$html->label($model, 'userHost', 'Event time', ['class' => 'sr-only'])?>
					<?=$html->text($model, 'userHost', [
						'class' => 'form-control',
						'placeholder' => 'User host'
					])?>
					<?=$html->errors($model, 'userHost')?>
				</td>
			</tr>
			<tr>
				<td colspan="6" class="submit-row">
					<?=$html->label($model, 'sortBy', 'Sort by', [])?>
					<?=$html->dropDownList(
						$model,
						'sortBy',
						$model->getSortByOptions(),
						['class' => 'form-control sort-by']
					)?>
					<?=$html->errors($model, 'sortBy')?>
					<button type="submit" class="btn btn-default">Search</button>
					<button type="button" class="btn btn-default grid-reset">Reset</button>
				</td>
			</tr>
		</tbody>
	</table>
</form>