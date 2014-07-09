<?php
	/** @var \tables\GeneralLog $generalLog */
	/** @var \KZ\view\helpers\Link $link */
	$link = $this->helper('link');

	echo $this->renderPartial('index/switcher');
	echo $this->renderPartial('index/grid');
?>