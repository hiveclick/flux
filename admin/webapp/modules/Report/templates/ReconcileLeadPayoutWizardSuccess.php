<?php
	/* @var $report_lead Flux\ReportLead */
	$report_lead = $this->getContext()->getRequest()->getAttribute("report_lead", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Reconcile Lead Payout</h4>
</div>
<form class="" id="report_lead_form_<?php echo $report_lead->getId() ?>" method="PUT" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/report/report-lead" />
	<input type="hidden" name="_id" value="<?php echo $report_lead->getId() ?>" />
	<input type="hidden" name="lead[lead_id]" value="<?php echo $report_lead->getLead()->getLeadId() ?>" />
	<div class="modal-body">
		<div class="help-block">Manage if this lead is paid out to the publisher</div>
		<div class="form-group">
			<label class="control-label hidden-xs" for="name">Affiliate</label>
			<select name="client[client_id]" class="form-control" id="client_id_<?php echo $report_lead->getId() ?>">
                <optgroup label="Administrators">
			        <?php
    					/* @var $client \Flux\Client */
    					foreach ($clients AS $client) { 
    				?>
    				    <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
    					    <option value="<?php echo $client->getId(); ?>"<?php echo $report_lead->getClient()->getClientId() == $client->getId() ? ' selected' : ''; ?>><?php echo $client->getName() ?></option>
    					<?php } ?>
    				<?php } ?>
			    </optgroup>
				<optgroup label="Affiliates">
			        <?php
    					/* @var $client \Flux\Client */
    					foreach ($clients AS $client) { 
    				?>
    				    <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
    					    <option value="<?php echo $client->getId(); ?>"<?php echo $report_lead->getClient()->getClientId() == $client->getId() ? ' selected' : ''; ?>><?php echo $client->getName() ?></option>
    					<?php } ?>
    				<?php } ?>
			    </optgroup>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label hidden-xs" for="revenue">Bounty</label>
			<div class="input-group">
                <div class="input-group-addon">$</div>
                <input type="text" class="form-control" name="revenue" placeholder="Enter bounty..." value="<?php echo number_format($report_lead->getRevenue(), 2) ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label hidden-xs" for="payout">Payout</label>
			<div class="input-group">
                <div class="input-group-addon">$</div>
                <input type="text" class="form-control" name="payout" placeholder="Enter payout..." value="<?php echo number_format($report_lead->getPayout(), 2) ?>" />
			</div>
		</div>
		<hr />
		<div class="help-block">Assign a disposition to this payout</div>
		<div class="form-group">
    		<select name="disposition" id="disposition" class="form-control">
                <option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>" <?php echo $report_lead->getDisposition() == \Flux\ReportLead::LEAD_DISPOSITION_PENDING ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('value' => \Flux\ReportLead::LEAD_DISPOSITION_PENDING, 'className' => 'text-muted', 'name' => 'Pending', 'description' => 'Flag this lead as pending review.  It may be accepted in the future.'))) ?>">Pending</option>
                <option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>" <?php echo $report_lead->getDisposition() == \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('value' => \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED, 'className' => 'text-success', 'name' => 'Accepted', 'description' => 'Flag this lead as accepted for payment to the publisher'))) ?>">Accepted</option>
                <option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>" <?php echo $report_lead->getDisposition() == \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('value' => \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED, 'className' => 'text-danger', 'name' => 'Disqualified', 'description' => 'Flag this lead as disqualified for payment.  An optional reason can be entered below.'))) ?>">Disqualified</option>
                <option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE ?>" <?php echo $report_lead->getDisposition() == \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('value' => \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE, 'className' => 'text-warning', 'name' => 'Duplicate', 'description' => 'Flag this lead as a duplicate of another lead.  No payment will be made.'))) ?>">Duplicate</option>
    		</select>
		</div>
		<div class="form-group">
            <textarea class="form-control <?php echo $report_lead->getDisposition() == \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ? 'hidden' : '' ?>" id="disposition_message" name="disposition_message" rows="2" placeholder="Enter an optional message if this payout is flagged as disqualified..."><?php echo $report_lead->getDispositionMessage() ?></textarea>
		</div>
		<hr />
		<div class="form-group row">
			<div class="col-md-6">
                <label class="control-label" for="accepted">Approve this payout for payment to the publisher</label>
			</div>
			<div class="col-md-6 text-right">
			    <input type="hidden" name="accepted" value="0" />  
                <input type="checkbox" class="form-control" id="accepted" name="accepted" placeholder="Select if this payment is accepted" value="1" <?php echo $report_lead->getAccepted() ? 'CHECKED' : '' ?> />
            </div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="button" class="btn btn-danger" value="Delete Payout" class="small" onclick="javascript:confirmDelete();" />
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#report_lead_form_<?php echo $report_lead->getId() ?>').form(function(data) {
		$.rad.notify('Payout Updated', 'The payout has been added/updated in the system');
		$('#reconcile_lead_payout_search_form').trigger('submit');
		$('#edit_report_lead_modal').modal('hide');
	}, {keep_form:1});

	$('#client_id_<?php echo $report_lead->getId() ?>').selectize();

    $('#accepted').bootstrapSwitch({
        onText: 'Yes',
        offText: 'No'
    });
	
	$('#disposition').selectize({
		valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
	            return '<div style="width:100%;padding-right:25px;">' +
	                '<b class="' + escape(item.className) + '">' + escape(item.name) + ' <span class="glyphicon glyphicon-flag"></b></span><br />' +
	                '<div>' + item.description + '</div>' +   
	            '</div>';
			},
			option: function(item, escape) {
				return '<div style="width:100%;padding-right:25px;">' +
                '<b class="' + escape(item.className) + '">' + escape(item.name) + ' <span class="glyphicon glyphicon-flag"></span></b><br />' +
                '<div>' + item.description + '</div>' +   
                '</div>';
			}
		},
		onChange: function(val) {
		    if (val == <?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>) {
		        $('#accepted').bootstrapSwitch('state',true, true);
		        $('#disposition_message').addClass('hidden');
		    } else {
		    	$('#accepted').bootstrapSwitch('state',false, false);
		    	$('#disposition_message').removeClass('hidden');
		    }
		}
	});
});

function confirmDelete() {
	if (confirm('Are you sure you want to delete this payout from the system?\n\nIt is better to flag it as unpayable.')) {
		$.rad.del({ func: '/report/report-lead/<?php echo $report_lead->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this payout', 'You have deleted this payout.  You will need to refresh this page to see your changes.');
			$('#reconcile_lead_payout_search_form').trigger('submit');
			$('#edit_report_lead_modal').modal('hide');
		});
	}
}
//-->
</script>