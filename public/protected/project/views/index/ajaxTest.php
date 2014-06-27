<a href="<?=$this->helper('link')->get('index/submitForm')?>" id="test-test">test</a>
<br/>
<a href="<?=$this->helper('link')->get('index/error')?>" id="test-err">err</a>
<br/>
<a href="<?=$this->helper('link')->get('index/popup')?>" id="popup-trigger">test</a>
<script type="text/javascript">
	$(function() {
		$('#popup-trigger').click(function(e) {
			e.preventDefault();

			var popup = new kz.modal();
			popup.load($(this).attr('href'));
		});
	});
</script>
