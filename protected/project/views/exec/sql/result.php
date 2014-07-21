<?php
	/** @var \KZ\view\helpers\Html $html */
	$html = $this->helper('html');
?>
<?php if (empty($result)):?>
	<p class="alert alert-warning">Empty result</p>
<?php else:?>
	<div class="result-wrapper">
		<table class="table table-hover table-bordered table-striped">
			<thead>
				<tr>
					<?php foreach ($result[0] as $column => $value):?>
						<th><?=$column?></th>
					<?php endforeach?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($result as $row):?>
					<tr>
						<?php foreach ($row as $column => $value):?>
							<td><?=$html->encode($value)?></td>
						<?php endforeach?>
					</tr>
				<?php endforeach?>
			</tbody>
		</table>
	</div>
<?php endif?>