<?php
	/** @var \grids\GeneralLog $grid */
	/** @var \tables\GeneralLog $table */
	/** @var \KZ\view\helpers\Link $link */

	$table = $grid->getTable();
	$link = $this->helper('link');

	$db = $table->getQueryDb($row['db_command_type'], $row['db_argument']);

	$explainLink = $link->get('index/explain', [
		'threadId' => $row['thread_id'],
		'commandType' => $row['command_type'],
		'sql' => $row['argument'],
	]);
?>
<div class="actions">
	<?php if ($table->isAllowExplain($row['command_type'], $row['argument'])):?>
		<div class="btn-group-vertical">
			<?php if ($db):?>
				<div class="btn-group">
					<a href="<?=$explainLink->setParams(['db' => $db])?>" class="btn btn-default btn-xs" data-modal="">
						<span class="glyphicon glyphicon-search"></span>
						Explain on DB "<u><?=$db?></u>"
					</a>
				</div>
			<?php endif?>
			<div class="btn-group">
				<a href="#" class="btn btn-default btn-xs" data-toggle="dropdown">
					<span class="glyphicon glyphicon-search"></span>
					Select DB and Explain
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu" role="menu">
					<?php foreach ($databases as $database):?>
						<li>
							<a href="<?=$explainLink->setParams(['db' => $database])?>" data-modal="">
								<?=$database?>
							</a>
						</li>
					<?php endforeach?>
				</ul>
			</div>
		</div>
	<?php endif?>
</div>