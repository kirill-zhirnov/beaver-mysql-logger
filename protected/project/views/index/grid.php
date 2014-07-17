<?php
	/** @var \tables\GeneralLog $generalLog */
	/** @var \grids\GeneralLog $grid */
	/** @var \KZ\view\helpers\Link $link */

	$link = $this->helper('link');
	$threadId = null;
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
					<td class="thread-id"><?=$row['thread_id']?></td>
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
						<?=$this->helper('sqlFormatter')->format($row['argument'])?>
						<?php if ($grid->getTable()->isAllowExplain($row['command_type'], $row['argument'])):?>
							<?php
								$explain = $link->get('index/explain', [
									'event_time' => $row['event_time'],
									'user_host' => $row['user_host'],
									'thread_id' => $row['thread_id'],
									'server_id' => $row['server_id'],
									'command_type' => $row['command_type'],
									'argument' => $row['argument'],
								]);
							?>
							<p class="actions">
								<a href="<?=$explain?>" class="btn btn-default btn-xs" data-modal="">
									<span class="glyphicon glyphicon-search"></span>
									Explain
								</a>
							</p>
						<?php endif?>
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
		var grid = new kz.grid($('.general-log'), {
			afterSetup : function() {
				var maxHeight = null;

				//set cursor pointer:
				this.el.find('.argument pre').each(function() {
					$el = $(this);

					if ($el.innerHeight() > 100)
						$el.addClass('pointer');
				});

				this.el.on('click', '.argument pre.pointer', function(e) {
					e.preventDefault();

					$el = $(this);

					if ($el.css('max-height') != 'none') {
						maxHeight = $el.css('max-height');
						$el.css('max-height', 'none');
					} else {
						$el.css('max-height', maxHeight);
					}
				});

				//date picker:
				this.el.find('.grid-filter .event-time input').datetimepicker({
					format : 'Y-m-d H:i:s',
					validateOnBlur : false
				});
			}
		});
		grid.setup();
	});
</script>