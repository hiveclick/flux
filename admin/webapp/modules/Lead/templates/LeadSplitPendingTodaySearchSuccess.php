<?php
	/* @var $lead_split \Flux\LeadSplit */
	$lead_split = $this->getContext()->getRequest()->getAttribute("lead_split", array());
	$splits = $this->getContext()->getRequest()->getAttribute("splits", array());
?>
<ol class="breadcrumb">
	<li><a href="/lead/lead-search">Leads</a></li>
	<li class="active">Pending Leads for Today &amp; Yesterday</li>
</ol>

<div class="container-fluid">
	<div class="page-header">
	   <h1>Pending Leads for Today &amp; Yesterday</h1>
	</div>
	<div class="help-block">These leads have been found by a split and are queued to be sent for fulfillment.</div>
	<p />
	<div class="panel panel-primary">
		<div id='split-header' class='grid-header panel-heading clearfix'>
			<form id="split_search_form" method="GET" class="form-inline" action="/api">
				<input type="hidden" name="func" value="/lead/lead-split">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="_id" />
				<input type="hidden" id="sord" name="sord" value="desc" />
				<input type="hidden" id="sord" name="hide_catch_all" value="1" />
				<input type="hidden" id="sord" name="hide_unfulfillable" value="1" />
				<div class="text-right">
					<div class="form-group text-left">
						<select class="form-control selectize" name="split_id_array[]" id="split_queue_spy_split_id" multiple placeholder="Filter by split">
							<optgroup label="Normal Splits">
							<?php
								/* @var $split \Flux\Split */ 
								foreach ($splits as $split) { 
							?>
								<?php if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_NORMAL) { ?>
									<option value="<?php echo $split->getId() ?>" <?php echo in_array($split->getId(), $lead_split->getSplitIdArray()) ? "selected" : "" ?>><?php echo $split->getName() ?></option>
								<?php } ?>
							<?php } ?>
							</optgroup>
							<optgroup label="Host & Post Splits">
							<?php
								/* @var $split \Flux\Split */ 
								foreach ($splits as $split) { 
							?>
								<?php if ($split->getSplitType() == \Flux\Split::SPLIT_TYPE_HOST_POST) { ?>
									<option value="<?php echo $split->getId() ?>" <?php echo in_array($split->getId(), $lead_split->getSplitIdArray()) ? "selected" : "" ?>><?php echo $split->getName() ?></option>
								<?php } ?>
							<?php } ?>
							</optgroup>
						</select>
					</div>
					<div class="form-group text-left">
						<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
					</div>
					<div class="form-group text-left">
						<select class="form-control selectize" name="date_range" id="date_range" placeholder="Filter by date range">
							<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_2_DAYS ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_2_DAYS ? 'SELECTED' : '' ?>>Yesterday&nbsp;&amp;&nbsp;Today&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
							<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_TODAY ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_TODAY ? 'SELECTED' : '' ?>>Today&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
							<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_YESTERDAY ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_YESTERDAY ? 'SELECTED' : '' ?>>Yesterday&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
							<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ? 'SELECTED' : '' ?>>Last 7 days&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
							<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_MTD ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_MTD ? 'SELECTED' : '' ?>>Month To Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
							<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_30_DAYS ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_30_DAYS ? 'SELECTED' : '' ?>>Last 30 Days&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
							<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_CUSTOM ?>" <?php echo $lead_split->getDateRange() == \Mojavi\Form\DateRangeForm::DATE_RANGE_CUSTOM ? 'SELECTED' : '' ?>>No Filter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</options>
						</select>
					</div>
					<div class="form-group text-left">
						<select class="form-control selectize" name="disposition_array[]" id="disposition_array" multiple placeholder="Filter by disposition">
							<option value="<?php echo \Flux\SplitQueue::DISPOSITION_UNFULFILLED ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_UNFULFILLED, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Unfulfilled</options>
							<option value="<?php echo \Flux\SplitQueue::DISPOSITION_PENDING ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_PENDING, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Pending</options>
							<option value="<?php echo \Flux\SplitQueue::DISPOSITION_PROCESSING ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_PROCESSING, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Processing</options>
							<option value="<?php echo \Flux\SplitQueue::DISPOSITION_FULFILLED ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_FULFILLED, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Fulfilled</options>
							<option value="<?php echo \Flux\SplitQueue::DISPOSITION_UNFULFILLABLE ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_UNFULFILLABLE, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Unfulfillable</options>
							<option value="<?php echo \Flux\SplitQueue::DISPOSITION_ALREADY_FULFILLED ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_ALREADY_FULFILLED, $lead_split->getDispositionArray()) ? "selected" : "" ?>>Already Fulfilled</options>
						</select>
					</div>
				</div>
			</form>
		</div>
		<div id="split-grid"></div>
		<div id="split-pager" class="panel-footer"></div>
	</div>
</div>
<script>
//<!--
$(document).ready(function() {

	$('#disposition_array,#date_range').selectize().on('change', function($val) {
		$('#split_search_form').trigger('submit');
	});
	
	var columns = [
		{id:'_id', name:'Item #', field:'_id', sort_field:'_id', def_value: ' ', width:175, sortable:true, hidden:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var lead_id = (dataContext.lead._id == undefined) ? '' : dataContext.lead._id;
			var offer_id = (dataContext.lead.offer._id == undefined) ? '' : dataContext.lead.offer._id;
			var offer_name = (dataContext.lead.offer.name == undefined) ? '' : dataContext.lead.offer.name;
			var client_name = (dataContext.lead.client.name == undefined) ? '' : dataContext.lead.client.name;
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/lead/lead?_id=' + lead_id + '&tab=attempts">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += ' (Lead #<a href="/lead/lead?_id=' + lead_id + '&tab=attempts">' + lead_id + '</a> - <a href="/offer/offer?_id=' + offer_id + '">' + offer_name + '</a> on ' + client_name + ')';
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'lead_id', name:'Lead #', field:'lead._id', sort_field:'lead._id', def_value: ' ', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/lead/lead?_id=' + dataContext.lead._id + '&tab=attempts">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += ' (Item #:<a href="/lead/lead?_id=' + dataContext.lead._id + '&tab=attempts">' + dataContext.lead._id + '</a>)';
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'split_id', name:'Split', field:'split._id', sort_field:'split._id', def_value: ' ', cssClass: 'text-center', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var split_id = (dataContext.split._id == undefined) ? '' : dataContext.split._id;
			var split_name = (dataContext.split.name == undefined) ? '' : dataContext.split.name;
			var offer_id = (dataContext.lead.offer._id == undefined) ? '' : dataContext.lead.offer._id;
			var offer_name = (dataContext.lead.offer.name == undefined) ? '' : dataContext.lead.offer.name;
			var client_name = (dataContext.lead.client.name == undefined) ? '' : dataContext.lead.client.name;
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/export/split?_id=' + split_id + '">' + split_name + '</a>';
				ret_val += '<div class="small text-muted">';
				ret_val += ' (<a href="/offer/offer?_id=' + offer_id + '">' + offer_name + '</a> on ' + client_name + ')';
				ret_val += '</div>';
				ret_val += '</div>';
			return ret_val;
		}},
		{id:'name', name:'Name', field:'lead_name', sort_field:'lead_name', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var lead_id = (dataContext.lead._id == undefined) ? 0 : dataContext.lead._id;
			var email = (dataContext.lead.email == '') ? '' : 'E: ' + dataContext.lead.email;
			var phone = (dataContext.lead.phone == '') ? '' : ', P: ' + dataContext.lead.phone;
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/lead/lead?_id=' + lead_id + '&tab=attempts">' + dataContext.lead.name + '</a>';
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
		{id:'error_message', name:'Errors', field:'error_message', def_value: ' ', sortable:true, hidden: true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value != '') {
				return '<span class="text-danger">' + value + '</span>';
			} else {
				return '<i class="text-muted">no errors</i>';
			}
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
		{id:'actions', name:'Flag Item', field:'_id', def_value: ' ', sortable:false, hidden: false, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div class="btn-group" role="group">';
			ret_val += '<a href="javascript:changeDisposition({&quot;_id&quot;:&quot;' + dataContext._id + '&quot;,&quot;disposition&quot;:<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLED ?>});" class="btn btn-default"><span class="fa fa-flag" aria-hidden="true"></span></a>';
			ret_val += '<a href="javascript:changeDisposition({&quot;_id&quot;:&quot;' + dataContext._id + '&quot;,&quot;disposition&quot;:<?php echo \Flux\LeadSplit::DISPOSITION_PENDING ?>});" class="btn btn-warning"><span class="fa fa-flag" aria-hidden="true"></span></a>';
			ret_val += '<a href="javascript:changeDisposition({&quot;_id&quot;:&quot;' + dataContext._id + '&quot;,&quot;disposition&quot;:<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLABLE ?>});" class="btn btn-danger"><span class="fa fa-flag" aria-hidden="true"></span></a>';
			ret_val += '<a href="javascript:changeDisposition({&quot;_id&quot;:&quot;' + dataContext._id + '&quot;,&quot;disposition&quot;:<?php echo \Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED ?>});" class="btn btn-info"><span class="fa fa-flag" aria-hidden="true"></span></a>';
			ret_val += '<a href="javascript:changeDisposition({&quot;_id&quot;:&quot;' + dataContext._id + '&quot;,&quot;disposition&quot;:<?php echo \Flux\LeadSplit::DISPOSITION_FULFILLED ?>});" class="btn btn-success"><span class="fa fa-flag" aria-hidden="true"></span></a>';
			ret_val += '</div>';
			return ret_val;
		}}
	];

	slick_grid = $('#split-grid').slickGrid({
		pager: $('#split-pager'),
		form: $('#split_search_form'),
		columns: columns,
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

	$("#txtSearch").keyup(function(e) {
		// clear on Esc
		if (e.which == 27) {
			this.value = "";
		} else if (e.which == 13) {
			$('#split_search_form').trigger('submit');
		}
	});
	
	$('#split_queue_spy_split_id').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	}).on('change', function(e) {
		$('#split_search_form').trigger('submit');
	});

	// submit the form to initially fill in the grid
	$('#split_search_form').trigger('submit');
	
});

function changeDisposition(obj) {
	$.rad.put('/api', {func: '/lead/lead-split', disposition: obj.disposition, _id: obj._id}, function(data) {
		$('#split_search_form').trigger('submit');
	});
}
//-->
</script>
