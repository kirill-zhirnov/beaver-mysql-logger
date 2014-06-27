<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">Modal title</h4>
		</div>
		<div class="modal-body">
			this is content <?=time()?>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="test2">Save changes</button>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			console.log('triggered!');
			$('#test2').click(function(e) {
				e.preventDefault();

				var popup = new kz.modal();
				popup.load('<?=$this->helper('link')->get('index/popup2')?>');
			});
		});
	</script>
</div>