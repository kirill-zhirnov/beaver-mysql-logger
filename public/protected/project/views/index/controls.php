<?php
	/** @var \tables\GeneralLog $generalLog */
	/** @var \KZ\view\helpers\Link $link */
	$link = $this->helper('link');
?>
<?php if ($generalLog->isLogActive()):?>
	<?php if (!$generalLog->isKeysCreated()):?>
		<div class="alert alert-warning">
			<strong>Warning!</strong> General_log table does not have indexes. Selecting from this table can be slow.
			<a href="<?=$link->get('index/createKeys')?>" class="btn btn-default" data-ajax-link="">Create keys</a>
		</div>
	<?php endif?>
<?php endif?>
<div class="pull-left">
	<p>
		<a
			href="<?=$link->get('index/clearLogs')?>"
			class="btn btn-warning btn-xs"
			data-ajax-link=""
			onclick="return confirm('Are you sure?');"
		>
			<span class="glyphicon glyphicon-minus"></span>
			Clear logs
		</a>
	</p>
</div>
<div class="pull-right">
	<?php if ($generalLog->isLogActive()):?>
		<p class="text-muted">
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
	<?php endif?>
</div>
<div class="clearfix"></div>