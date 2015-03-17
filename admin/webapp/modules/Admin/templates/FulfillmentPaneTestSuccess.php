<?php
	/* @var $export \Flux\Export */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$export_handlers = $this->getContext()->getRequest()->getAttribute("export_handlers", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Test Fulfillment</h4>
</div>
<form id="fulfillment_test_form" name="fulfillment_test_form" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="_id" value="<?php echo $fulfillment->getId() ?>" />
	<input type="hidden" name="func" value="/admin/fulfillment-test" />
	<div class="modal-body">
		<div class="help-block">Enter a lead id to test this fulfillment and see what would be submitted</div>
		<div class="form-group">
			<input type="text" id="lead_id" name="lead_id" class="form-control" value="" placeholder="enter a lead to use as a test" />
		</div>
		<hr />
		
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Test Fulfillment</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#fulfillment_form').form(function(data) {
		$.rad.notify('Testing fulfillment', 'Please wait while we test this fulfillment');
	}, {keep_form: true});
});
//-->
</script>