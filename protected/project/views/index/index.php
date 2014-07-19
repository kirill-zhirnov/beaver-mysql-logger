<?php
	/** @var \tables\GeneralLog $generalLog */

	if (!$generalLog->isLogActive()) {
		echo $this->renderPartial('index/switchOn');
	}

	echo $this->renderPartial('index/controls');
	echo $this->renderPartial('index/grid');