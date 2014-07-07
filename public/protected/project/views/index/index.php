<?php
/** @var \tables\GeneralLog $mysqlLog */
/** @var \KZ\view\helpers\Link $link */
$link = $this->helper('link');
?>
<?php if ($mysqlLog->isLogActive()):?>
	<p class="text-right text-muted">
		Logger is active.
		<a
			href="<?=$link->get('index/setLoggerActive', ['value' => 0])?>"
			class="btn btn-default btn-xs"
			data-ajax-link=""
		>
			<span class="glyphicon glyphicon-stop"></span>
			Turn off
		</a>
	</p>
<?php else:?>
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
<?php endif?>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text </p>
<p>123</p>
<p>123</p>
<p>123</p>
<p>123</p>
<p>123</p>
<p>123</p>
<p>123</p>
<p>123</p>