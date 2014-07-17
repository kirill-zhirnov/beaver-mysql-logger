<?php
	/** @var \grids\GeneralLog $grid */
	/** @var \KZ\view\helpers\Link $link */
	/** @var \KZ\view\helpers\Html $html */

	$html = $this->helper('html');
	$link = $this->helper('link');

	$pager = $grid->getPager();
	$pagesInRange = $pager->getPagesInRange();
	$filter = $grid->getFilter();

	$attrName = $html->name($filter, 'p');

	$url = $link->get('index/index');
	$url->appendModelAttrs($filter);
?>
<ul class="pagination pagination-sm">
	<?php
		$classes = [];
		if (in_array(1, $pagesInRange))
			$classes[] = 'disabled';
	?>
	<li <?php if ($classes):?>class="<?=implode(' ', $classes)?>"<?php endif?>>
		<a href="<?=$url->setParams([$attrName => 1])?>">
			<span>&laquo;</span> First
		</a>
	</li>
	<?php foreach ($pagesInRange as $page):?>
		<?php
			$classes = [];
			if ($page == $pager->getCurrentPage())
				$classes[] = 'active';
		?>
		<li <?php if ($classes):?>class="<?=implode(' ', $classes)?>"<?php endif?>>
			<a href="<?=$url->setParams([$attrName => $page])?>"><?=$page?></a>
		</li>
	<?php endforeach?>
	<?php
		$last = $pager->getPageCount();
		$classes = [];
		if (in_array($last, $pagesInRange))
			$classes[] = 'disabled';
	?>
	<li <?php if ($classes):?>class="<?=implode(' ', $classes)?>"<?php endif?>>
		<a href="<?=$url->setParams([$attrName => $last])?>">
			Last <span>&raquo;</span>
		</a>
	</li>
</ul>