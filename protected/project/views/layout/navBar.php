<?php
	/** @var \KZ\view\helpers\Link $link */
	$link = $this->helper('link');
	$links = [
		'generalLog' => ['General log', $link->get('index/index')],
	];

    if (!\eventHandlers\Setup::isMySQLDSNSpecified()) {
        $links['setup'] = ['Setup', $link->get('setup/index')];
    }

	$curLink = isset($curLink) ? $curLink : null;
?>
<div class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?=$link->get('index/index')?>">
				Beaver Mysql Logger
			</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<?php foreach ($links as $key => $props):?>
					<li <?php if ($key == $curLink):?>class="active"<?php endif?>>
						<a href="<?=$props[1]?>"><?=$props[0]?></a>
					</li>
				<?php endforeach?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="https://github.com/kirill-zhirnov/beaver-mysql-logger" target="_blank">Homepage</a>
				</li>
			</ul>
		</div>
	</div>
</div>