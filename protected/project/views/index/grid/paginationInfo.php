<?php
/** @var \grids\GeneralLog $grid */
$pager = $grid->getPager();
?>
<p class="text-right">
	Treads count: <?=$grid->getTreadsCount()?>
	Rows: <?=$pager->getItemCount()?>,
	Pages: <?=$pager->getPageCount()?>
</p>