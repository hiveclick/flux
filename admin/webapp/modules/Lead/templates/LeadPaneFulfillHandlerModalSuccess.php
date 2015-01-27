<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
	$clients = $this->getContext()->getRequest()->getAttribute('clients', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Fulfill Lead</h4>
</div>
<form id="lead_fulfill_manual_form" action="/api" method="POST">
	<input type="hidden" name="func" value="/lead/manual-fulfill-custom" />
	<input type="hidden" name="_id" value="<?php echo $lead->getId() ?>" />
	<div class="modal-body">
		<div class="help-block">You can test fulfillment using the data on this lead.</div>
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="type">Export</label>
			<div class="col-sm-10">
				<select id="client_export_id" name="client_export_id" class="form-control selectize">
					<?php
						/* @var $client \Flux\Client */ 
						foreach ($clients as $client) { 
					?>
						<?php if (count($client->getClientExports()) > 0) { ?>
							<optgroup label="<?php echo $client->getName() ?>">
								<?php 
									/* @var $client \Flux\ClientExport */
									foreach ($client->getClientExports() AS $client_export) { 
								?>
									<option value="<?php echo $client_export->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $client_export->getId(), 'name' => $client_export->getName()))) ?>"><?php echo $client_export->getName() ?></option>
								<?php } ?>
							</optgroup>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="clearfix" />
		<p />
		<div style="display:none;" id="fulfillment_log_div">
			<div class="help-block">A log of the fulfillment test will appear here</div>
			<div class="well">
				<div id="fulfill_log_contents" style="width:100%;font-Family:Courier;font-Size:10px;"></div>
			</div>
			<div class="help-block">You can view the export and any error messages on the export page</div>
			<a id="fulfill_export_btn" href="/export/export?_id=" class="btn btn-sm btn-info">view export details</a>
		</div>
		
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary">Fulfill Lead</button>
	</div>
</form>
<script>
//<!--
$('#client_export_id').selectize();

$('#lead_fulfill_manual_form').form(
	function(data) {
		if (data.record) {
			if (data.record.fulfill_log_contents) {
				$('#fulfill_log_contents').html(data.record.fulfill_log_contents);
			}
			if (data.record.fulfill_export_id && data.record.fulfill_export_id > 0) {
				$('#fulfill_export_btn').attr('href', '/export/export?_id=' + data.record.fulfill_export_id);
			} else {
				$('#fulfill_export_btn').attr('href', '/export/export-search?name=<?php echo $lead->getId() ?>');
			}
			$.rad.notify('Lead Submitted', 'The lead has been submitted to the export successfully');
		}
	}, {
	prepare: function() {
		$('#fulfill_log_contents').html('');
		$('#fulfillment_log_div').show();
	}	
});
//-->
</script>