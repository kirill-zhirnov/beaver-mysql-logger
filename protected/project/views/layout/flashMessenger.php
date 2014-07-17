<?php foreach ($this->helper('flashMessenger')->get() as $type => $text):?>
	<div class="alert alert-<?=$type?>" role="alert">
		<?=$text?>
	</div>
<?php endforeach ?>