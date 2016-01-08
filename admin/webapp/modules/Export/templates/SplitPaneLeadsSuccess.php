<?php
	/* @var $split \Flux\LeadSplit */
	$lead_split = $this->getContext()->getRequest()->getAttribute('lead_split', array());
?>
<div class="panel panel-primary">
	<div id='split-lead-header' class='grid-header panel-heading clearfix'>
		<form id="split-lead-form" method="GET" action="/api" class="form-inline">
			<input type="hidden" name="func" value="/lead/lead-split" />
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="_id" />
			<input type="hidden" id="sord" name="sord" value="desc" />
			<input type="hidden" name="split_id_array[]" value="<?php echo $lead_split->getSplit()->getSplitId() ?>" />
			<div class="form-group text-left">
				<select class="form-control" name="date_range" id="date_range" placeholder="Filter by date range">
					<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_TODAY ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_TODAY ? 'SELECTED' : '' ?>>Today&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
					<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_YESTERDAY ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_YESTERDAY ? 'SELECTED' : '' ?>>Yesterday&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
					<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ? 'SELECTED' : '' ?>>Last 7 days&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
					<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_MTD ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_MTD ? 'SELECTED' : '' ?>>Month To Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
					<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_30_DAYS ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_30_DAYS ? 'SELECTED' : '' ?>>Last 30 Days&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
					<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_CUSTOM ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_CUSTOM ? 'SELECTED' : '' ?>>No Filter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
				</select>
			</div>
			<div class="form-group text-left">
				<select class="form-control" name="disposition_array[]" id="disposition_array" multiple placeholder="Filter by disposition">
					<option value="<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLED ?>" <?php echo in_array(\Flux\LeadSplit::DISPOSITION_UNFULFILLED, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Unfulfilled</options>
					<option value="<?php echo \Flux\LeadSplit::DISPOSITION_PENDING ?>" <?php echo in_array(\Flux\LeadSplit::DISPOSITION_PENDING, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Pending</options>
					<option value="<?php echo \Flux\LeadSplit::DISPOSITION_PROCESSING ?>" <?php echo in_array(\Flux\LeadSplit::DISPOSITION_PROCESSING, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Processing</options>
					<option value="<?php echo \Flux\LeadSplit::DISPOSITION_FULFILLED ?>" <?php echo in_array(\Flux\LeadSplit::DISPOSITION_FULFILLED, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Fulfilled</options>
					<option value="<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLABLE ?>" <?php echo in_array(\Flux\LeadSplit::DISPOSITION_UNFULFILLABLE, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Unfulfillable</options>
					<option value="<?php echo \Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED ?>" <?php echo in_array(\Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Already Fulfilled</options>
					<option value="<?php echo \Flux\LeadSplit::DISPOSITION_FAILOVER ?>" <?php echo in_array(\Flux\LeadSplit::DISPOSITION_FAILOVER, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Failed, sent to Failover</options>
				</select>
			</div>
		</form>
	</div>
	<div id="split-lead-grid"></div>
	<div id="split-lead-pager" class="panel-footer"></div>
</div>
<!-- Flag Split modal -->
<div class="modal fade" id="flag_lead_split_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var split_lead_columns = [
 		{id:'_id', name:'Lead #', field:'_id', sort_field:'_id', def_value: ' ', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			var split_id = (dataContext.split.split_id == undefined) ? 0 : dataContext.split.split_id;
			var split_name = (dataContext.split.split_name == undefined) ? 0 : dataContext.split.split_name;
			var offer_id = (dataContext.lead.offer.offer_id == undefined) ? 0 : dataContext.lead.offer.offer_id;
			var offer_name = (dataContext.lead.offer.offer_name == undefined) ? 0 : dataContext.lead.offer.offer_name;
			var client_name = (dataContext.lead.client.client_name == undefined) ? 0 : dataContext.lead.client.client_name;
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/lead/lead?_id=' + dataContext.lead.lead_id + '">' + dataContext.lead.lead_id + '</a>';
				ret_val += '<div class="small text-muted">';
				ret_val += ' (<a href="/offer/offer?_id=' + offer_id + '">' + offer_name + '</a> on ' + client_name + ' last updated ' + moment.unix(parseInt(dataContext.lead.lead_id.toString().substring(0,8), 16 )).format("MMM Do [at] LT") + ')';
				ret_val += '</div>';
				ret_val += '</div>';
			return ret_val;
 		}},
 		{id:'contact_name', name:'Lead Name', field:'lead.lead_name', sort_field:'lead.lead_name', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			var email = (dataContext.lead.email == '') ? '' : 'E: ' + dataContext.lead.email;
			var phone = (dataContext.lead.phone == '') ? '' : ', P: ' + dataContext.lead.phone;
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/export/split-queue?_id=' + dataContext._id + '">' + dataContext.lead.lead_name + '</a>';
				ret_val += '<div class="small text-muted">';
				ret_val += ' (' + email + phone + ')';
				ret_val += '</div>';
				ret_val += '</div>';
				return ret_val;
 		}},
 		{id:'is_fulfilled', name:'Fulfilled', field:'is_fulfilled', def_value: ' ', width:60, sortable:true, hidden: true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
				return '<span class="text-success">Yes</span>';
			} else {
				return '<span class="text-danger">No</span>';
			}
		}},
		{id:'disposition', name:'Disposition', field:'disposition', def_value: ' ', width:60, sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				if (value == 0) {
					ret_val += '<div class="text-muted">Unfulfilled</div>';
				} else if (value == 1) {
					ret_val += '<div class="text-success">Fulfilled</div>';
				} else if (value == 2) {
					ret_val += '<div class="text-warning">Pending</div>';
				} else if (value == 3) {
					ret_val += '<div class="text-danger">Unfulfillable</div>';
				} else if (value == 4) {
					ret_val += '<div class="text-info">Already Fulfilled</div>';
				} else if (value == 5) {
					ret_val += '<div class="text-info">Processing</div>';
				} else {
					ret_val += '<div class="text-muted">Unknown Disposition (' + value + ')</div>';
				}
				if (dataContext.error_message != null && dataContext.error_message != '') {
					ret_val +=  '<div class="text-danger small">' + dataContext.error_message + '</div>';
				} else {
					ret_val +=  '<div class="text-muted small">no errors</div>';
				}
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'last_attempt_time', name:'Last Attempt', field:'last_attempt_time', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				if (value != null) {
					ret_val += moment.unix(value.sec).calendar() + ' (' + dataContext.attempt_count + ' attempts)';
				} else {
					ret_val += '<i class="text-muted">Not Attempted Yet</i>';
				}
				ret_val += '<div class="small text-muted">';
				ret_val += (' Next Attempt: ' + moment.unix(dataContext.next_attempt_time.sec).calendar());
				ret_val += '</div>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'actions', name:'actions', field:'_id', def_value: ' ', sortable:true, maxWidth:60, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<a class="btn btn-default" data-toggle="modal" data-target="#flag_lead_split_modal" href="/lead/lead-split-flag-disposition?_id=' + dataContext._id + '"><span class="fa fa-flag"></span></a>';
			return ret_val;
		}}	
 	];

 	slick_grid = $('#split-lead-grid').slickGrid({
 		pager: $('#split-lead-pager'),
 		form: $('#split-lead-form'),
 		columns: split_lead_columns,
 		useFilter: false,
 		cookie: '<?php echo $_SERVER['PHP_SELF'] ?>',
 		pagingOptions: {
 			pageSize: <?php echo \Flux\Preferences::getPreference('items_per_page', 25) ?>,
 			pageNum: 1
 		},
 		slickOptions: {
 			defaultColumnWidth: 150,
 			forceFitColumns: true,
 			enableCellNavigation: false,
 			width: 800,
 			rowHeight: 48
 		}
 	});

 	$('#date_range,#disposition_array').selectize().on('change', function() {
 		$('#split-lead-form').trigger('submit');
 	});

 	$('#flag_lead_split_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
		$('#split-lead-form').trigger('submit');
	});

 	$('#split-lead-form').trigger('submit'); 	
});
//-->
</script>