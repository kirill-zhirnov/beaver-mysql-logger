<form action="<?=$this->helper('link')->get('index/submitForm')?>" method="post" data-form="">
	<input type="text" value="<?=isset($val) ? $val : 'no value'?>" />
	<input type="submit" value="1" />
	<input type="button" value="2" />
	<button type="submit">3</button>
	<button type="button">4</button>
	<button>5</button>
</form>