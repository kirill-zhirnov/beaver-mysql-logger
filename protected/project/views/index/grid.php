<?php
	/** @var \tables\GeneralLog $generalLog */
	/** @var \grids\GeneralLog $grid */
	/** @var \KZ\view\helpers\Link $link */
	/** @var \KZ\view\helpers\Html $html */

	$html = $this->helper('html');
	$link = $this->helper('link');
	$filter = $grid->getFilter();
	$threadId = null;

	$databases = $grid->getTable()->showDatabases();
?>
<div class="table-responsive general-log">
	<?=$this->renderPartial('index/grid/filter', ['grid' => $grid])?>
	<br/>
	<?=$this->renderPartial('index/grid/paginationInfo', ['grid' => $grid])?>
	<table class="table table-hover table-bordered grid table-striped">
		<thead>
			<tr>
				<th>Thread ID</th>
				<th>Command type</th>
				<th>Argument</th>
				<th>Event time</th>
				<th>Server ID</th>
				<th>User host</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($grid->getRows() as $row):?>
				<?php if ($grid->getFilter()->sortBy == 'default' && $threadId != $row['thread_id']):?>
					<?php if ($threadId):?>
						<tr>
							<td colspan="6" class="another-thread">another thread</td>
						</tr>
					<?php endif?>
					<?php $threadId = $row['thread_id']?>
				<?php endif?>
				<tr>
					<td class="thread-id">
						<a href="#" data-thread-id="<?=$row['thread_id']?>">
							<?=$row['thread_id']?>
						</a>
					</td>
					<td class="command-type type-<?=strtolower(str_replace(' ', '-', $row['command_type']))?>">
						<?php if ($row['command_type'] == 'Connect'):?>
							<span class="glyphicon glyphicon-flash"></span>
						<?php elseif ($row['command_type'] == 'Quit'):?>
							<span class="glyphicon glyphicon-log-out"></span>
						<?php elseif ($row['command_type'] == 'Query'):?>
							<span class="glyphicon glyphicon-play"></span>
						<?php elseif ($row['command_type'] == 'Init DB'):?>
							<span class="glyphicon glyphicon-ok"></span>
						<?php elseif ($row['command_type'] == 'Field List'):?>
							<span class="glyphicon glyphicon-th-list"></span>
						<?php endif?>
						<?=$row['command_type']?>
					</td>
					<td class="argument">
						<div class="sql">
							<?=$this->helper('sqlFormatter')->format($row['argument'])?>
							<a href="#" class="btn btn-default btn-xs close-top">Roll up</a>
							<a href="#" class="btn btn-default btn-xs close-bottom">Roll up</a>
						</div>
						<?=$this->renderPartial('index/grid/manageSqlButtons', [
							'grid' => $grid,
							'row' => $row,
							'databases' => $databases
						])?>
					</td>
					<td class="event-time">
						<?php
							$date = new \DateTime($row['event_time']);
						?>
						<time class="time"><?=$date->format('H:i:s')?></time>
						<time class="date"><?=$date->format('d.m.Y')?></time>
					</td>
					<td class="server-id"><?=$row['server_id']?></td>
					<td class="user-host"><?=$row['user_host']?></td>
				</tr>
			<?php endforeach?>
		</tbody>
	</table>
	<?=$this->renderPartial('index/grid/paginationInfo', ['grid' => $grid])?>
	<?=$this->renderPartial('index/grid/pagination')?>
</div>
<script type="text/javascript">
	$(function() {
		var grid = new project.sqlGrid($('.general-log'));
		grid.setup();
	});
</script>