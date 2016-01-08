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
	<input type="hidden" name="fulfillment[fulfillment_id]" value="<?php echo $fulfillment->getId() ?>" />
	<input type="hidden" name="func" value="/admin/fulfillment-test" />
	<div class="modal-body">
		<div class="help-block">Enter a lead id to test this fulfillment and see what would be submitted</div>
		<div class="form-group">
			<input type="text" id="lead_id" name="lead[lead_id]" class="form-control" value="" placeholder="enter a lead to use as a test" />
		</div>
		<div style="display:none;" id="fulfillment_log_div">
			<hr />
			<div id="fulfillment_result_debug" style="display:none;">
				<div class="help-block">This is what would be sent to the fulfillment if it was not a test</div>
				<div role="tabpanel">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#qs" role="tab" data-toggle="tab">Request</a></li>
						<li role="presentation"><a href="#request" role="tab" data-toggle="tab">Raw Request</a></li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="qs">
							<div class="help-block">This is what will be posted to the advertiser at <b id="debug_url"></b></div>
							<div style="height:400px;overflow:auto;">
								<table class="table">
									<thead>
										<th>Parameter</th>
										<th>Value</th>
									</thead>
									<tbody id="debug_qs"></tbody>
								</table>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="request">
							<div class="help-block">This is the raw post url that will be sent to the advertiser</div>
							<textarea id="debug_request" rows="10" class="form-control" readonly></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Test Fulfillment</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#fulfillment_test_form').form(function(data) {
		if (data.record) {
			$('#debug_request').val(data.record.debug.request);
			if (data.record.debug.url != '') {
				$('#debug_url').html(data.record.debug.url);
				$('#debug_qs').html('');
				$.each(data.record.debug.params, function(i, item) {
					var tr = $('<tr />').appendTo($('#debug_qs'));
					$('<td />').html(i).appendTo(tr);
					$('<td />').html(item).appendTo(tr);
				});
			} else {
				$('#debug_qs').html('');
				var tr = $('<tr />').appendTo($('#debug_qs'));
				$('<td colspan="2" />').html('<pre>' + data.record.debug.request + '</pre>').appendTo(tr);
			}
			$('#fulfillment_result_debug').show();
			$.rad.notify('Test Complete', 'The test was sent and you can see the results above');
		}
	}, {
	   keep_form: true,
	   prepare: function() {
		$('#fulfillment_result_debug').hide();
		$('#fulfillment_log_div').show();
	}});
});
//-->
</script>