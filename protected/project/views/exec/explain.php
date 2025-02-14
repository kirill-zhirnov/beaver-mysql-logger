<div class="modal-dialog modal-lg modal-execute execute-explain">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title">Explain query</h4>
		</div>
		<div class="modal-body">
			<div class="result-wrapper ">
				<table class="table table-hover table-bordered table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Select type</th>
							<th>Table</th>
							<th>Type</th>
							<th>Possible keys</th>
							<th>Key</th>
							<th>Key len</th>
							<th>Ref</th>
							<th>Rows</th>
							<th>Extra</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($result as $row):?>
							<tr>
								<td><?=$row['id']?></td>
								<td><?=$row['select_type']?></td>
								<td><?=$row['table']?></td>
								<td><?=$row['type']?></td>
								<td class="possible-keys">
									<?=implode('<br/>', explode(',', strval($row['possible_keys'])))?>
								</td>
								<td><?=$row['key']?></td>
								<td><?=$row['key_len']?></td>
								<td class="ref">
									<?=implode('<br/>', explode(',', strval($row['ref'])))?>
								</td>
								<td><?=$row['rows']?></td>
								<td>
									<?=implode('<br/>', explode(';', strval($row['Extra'])))?>
								</td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
			<div class="query">
				<?=$this->helper('sqlFormatter')->format($query)?>
			</div>
			<p class="info">
				Queries in tread: <?=$queriesInThread?>
			</p>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>