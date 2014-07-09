<?php
/** @var \tables\GeneralLog $generalLog */
/** @var \grids\GeneralLog $grid */
/** @var \KZ\view\helpers\Link $link */

$link = $this->helper('link');
$threadId = null;
?>
<div class="table-responsive general-log">
	<table class="table table-hover table-bordered">
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
				<?php if ($threadId != $row['thread_id']):?>
					<?php if ($threadId):?>
						<tr>
							<td colspan="6" class="another-thread">another thread</td>
						</tr>
					<?php endif?>
					<?php $threadId = $row['thread_id']?>
				<?php endif?>
				<tr>
					<td><?=$row['thread_id']?></td>
					<td class="command-type">
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
					<td><?=$row['argument']?></td>
					<td><?=$row['event_time']?></td>
					<td><?=$row['server_id']?></td>
					<td><?=$row['user_host']?></td>
				</tr>
			<?php endforeach?>
		</tbody>
	</table>
	<?php
		$pager = $grid->getPager();
		$pagesInRange = $pager->getPagesInRange();
	?>
	<ul class="pagination pagination-sm">
		<?php
			$classes = [];
			if (in_array(1, $pagesInRange))
				$classes[] = 'disabled';
		?>
		<li <?php if ($classes):?>class="<?=implode(' ', $classes)?>"<?php endif?>>
			<a href="<?=$link->get('index/index', ['p' => 1])?>">
				<span>&laquo;</span> First
			</a>
		</li>
		<?php foreach ($pagesInRange as $page):?>
			<?php
				$classes = [];
				if ($page == $pager->getCurrentPage())
					$classes[] = 'active';

				$url = $link->get('index/index', ['p' => $page]);
			?>
			<li <?php if ($classes):?>class="<?=implode(' ', $classes)?>"<?php endif?>>
				<a href="<?=$url?>"><?=$page?></a>
			</li>
		<?php endforeach?>
		<?php
			$last = $pager->getPageCount();
			$classes = [];
			if (in_array($last, $pagesInRange))
				$classes[] = 'disabled';
		?>
		<li <?php if ($classes):?>class="<?=implode(' ', $classes)?>"<?php endif?>>
			<a href="<?=$link->get('index/index', ['p' => $last])?>">
				Last <span>&raquo;</span>
			</a>
		</li>
	</ul>
</div>
<script type="text/javascript">
	$(function() {
		var grid = new kz.grid($('.general-log'));
		grid.setup();
	});
</script>