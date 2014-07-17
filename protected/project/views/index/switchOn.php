<?php
/** @var \tables\GeneralLog $generalLog */
/** @var \KZ\view\helpers\Link $link */
$link = $this->helper('link');
?>
<div class="bs-callout bs-callout-danger bs-callout-bg">
	<h4>Logger is disabled!</h4>
	<p class="text-center">
		<a
			href="<?=$link->get('index/setLoggerActive', ['value' => 1])?>"
			class="btn btn-success btn-lg"
			data-ajax-link=""
			>
			<span class="glyphicon glyphicon-play"></span>
			Turn on!
		</a>
	</p>
</div>