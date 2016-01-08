<?php
	/* @var $revenue_report \Flux\ReportLead */
	$report_lead = $this->getContext()->getRequest()->getAttribute('report_lead', array());
	$clients = $this->getContext()->getRequest()->getAttribute('clients', array());
?>
<div class="page-header">
   <h2>Import Lead Payouts</h2>
</div>
<div class="help-block">Import subid reports from advertisers to flag leads as accepted and paid</div>
<div class="help-block"><b>1)</b> Import file containing lead ids (and optionally payouts)</div>
<form id="import_lead_payout_form" method="POST" action="/api" enctype="multipart/form-data">
	<input type="hidden" name="func" value="/report/import-lead-payout-file">
	<input type="hidden" name="format" value="json" />
	<div class="form-group text-left">
		<input type="file" name="filename" value="" />
	</div>
	<input class="btn btn-info" type="submit" name="btn_submit" value="upload file" />
</form>
<form id="import_lead_payout_confirm_form" method="GET" class="form-inline" action="/api">
	<input type="hidden" name="func" value="/report/import-lead-payout">
	<input type="hidden" name="format" value="json" />
	<div class="help-block"><b>2)</b> Leads below have been verified and payouts will be imported</div>
	<table class="table">
		<thead>
			<tr>
				<th>Lead</th>
				<th>Payout</th>
			</tr>
		</thead>
		<tbody id="payout_table"></tbody>
	</table>
</form>


<!-- edit report lead modal -->
<div class="modal fade" id="edit_report_lead_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>


<script>
//<!--
$('#import_lead_payout_form').form(function(data) {
	console.log(data);
	if (data.entries) {
		data.entries.each(function(item) {
			tr = $('<tr />');
			$('<td><input type="text" name="lead_payout_array[][lead_id]" value="' + item.lead_id + '" /></td>').appendTo(tr);
			$('<td><input type="text" name="lead_payout_array[][payout]" value="' + item.payout + '" /></td>').appendTo(tr);
			tr.appendTo($('#payout_table'));
		});
	}
});
//-->
</script>