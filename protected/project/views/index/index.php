<?php
	/** @var \tables\GeneralLog $generalLog */
	/** @var \KZ\view\helpers\Link $link */
	$link = $this->helper('link');

	if (!$generalLog->isLogActive()) {
		echo $this->renderPartial('index/switchOn');
	}

	echo $this->renderPartial('index/controls');

	echo $this->renderPartial('index/grid');
?>