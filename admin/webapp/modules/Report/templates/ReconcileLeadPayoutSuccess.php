<?php
	/* @var $revenue_report \Flux\ReportLead */
	$report_lead = $this->getContext()->getRequest()->getAttribute('report_lead', array());
	$clients = $this->getContext()->getRequest()->getAttribute('clients', array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/report/report-home">Reports</a></li>
	<li><a href="/report/reconcile-lead-payout">Reconcile Lead Payouts</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
	   <h2>Reconcile Lead Payouts</h2>
	</div>
	<div class="help-block">Reconcile leads that need to be paid out to publishers</div>
	<div class="panel panel-primary">
		<div id='reconcile_lead_payout-header' class='grid-header panel-heading clearfix'>
			<form id="reconcile_lead_payout_search_form" method="GET" class="form-inline" action="/report/report-lead">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				<input type="hidden" id="date_range" name="date_range" value="<?php echo \Flux\Report\BaseReport::DATE_RANGE_CUSTOM ?>" />
				<div class="pull-right">
					<div class="form-group text-left">
						<select class="selectize" name="client_id_array[]" id="client_id_array" multiple placeholder="Filter by client">
							<optgroup label="Administrators">
								<?php
									/* @var $client \Flux\Client */
									foreach ($clients AS $client) { 
								?>
									<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
										<option value="<?php echo $client->getId(); ?>"<?php echo in_array($client->getId(), $report_lead->getClientIdArray()) ? ' selected' : ''; ?>><?php echo $client->getName() ?></option>
									<?php } ?>
								<?php } ?>
							</optgroup>
							<optgroup label="Affiliates">
								<?php
									/* @var $client \Flux\Client */
									foreach ($clients AS $client) { 
								?>
									<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
										<option value="<?php echo $client->getId(); ?>"<?php echo in_array($client->getId(), $report_lead->getClientIdArray()) ? ' selected' : ''; ?>><?php echo $client->getName() ?></option>
									<?php } ?>
								<?php } ?>
							</optgroup>
						</select>
					</div>
					<div class="form-group text-left">
						<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
					</div>
					<div class="form-group text-left">
						<select class="selectize" name="disposition_array[]" id="disposition_array" multiple placeholder="Filter by disposition">
							<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_PENDING, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Pending</options>
							<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Accepted</options>
							<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Disqualified</options>
							<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Duplicate</options>
						</select>
					</div>
					<div class="form-group text-left">
						<select id="report_date" name="report_date" class="selectize" style="width:200px;">
							<option value="<?php echo date('m/01/Y') ?>" <?php echo $report_lead->getReportDate()->sec == strtotime(date('m/01/Y')) ? 'selected' : '' ?>><?php echo date('F Y') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<?php for ($i=1;$i<14;$i++) { ?>
								<option value="<?php echo date('m/01/Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months')) ?>" <?php echo $report_lead->getReportDate()->sec == strtotime(date('m/01/Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months'))) ? 'selected' : '' ?>><?php echo date('F Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months')) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</form>
		</div>
		<div id="reconcile_lead_payout-grid"></div>
		<div id="reconcile_lead_payout-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit report lead modal -->
<div class="modal fade" id="edit_report_lead_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'report_date', name:'report date', field:'report_date', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = moment.unix(value.sec).format("MMM D YYYY");
			return ret_val;
		}},
		{id:'lead', name:'lead', field:'lead', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/lead/lead?_id=' + dataContext.lead.lead_id.$id + '">' + value.lead_name + '</a>';
			if (dataContext.lead.email) {
				ret_val += '<div class="small text-muted">' + dataContext.lead.email + '</div>';
			}
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'client', name:'client', field:'client', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var offer_id = (dataContext.lead.offer.offer_id == undefined) ? 0 : dataContext.lead.offer.offer_id;
			var offer_name = (dataContext.lead.offer.offer_name == undefined) ? 'no offer' : dataContext.lead.offer.offer_name;
			var client_name = (dataContext.client && dataContext.client.client_name != undefined) ? dataContext.client.client_name : 'no client';
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a data-toggle="modal" data-target="#edit_report_lead_modal" href="/report/reconcile-lead-payout-wizard?_id=' + dataContext._id.$id + '">' + client_name + '</a>';
			ret_val += '<div class="small text-muted"><a href="/offer/offer?_id=' + offer_id + '">' + offer_name + '</a></div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'revenue', name:'revenue', field:'revenue', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '$' + $.number(value, 2);
		}},
		{id:'payout', name:'payout', field:'payout', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '$' + $.number(value, 2);
		}},
		{id:'disposition', name:'disposition', field:'disposition', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				if (value == <?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>) {
					ret_val += '<a class="text-success" data-toggle="modal" data-target="#edit_report_lead_modal" href="/report/reconcile-lead-payout-wizard?_id=' + dataContext._id.$id + '">Accepted</a>';
				} else if (value == <?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>) {
					ret_val += '<a class="text-danger" data-toggle="modal" data-target="#edit_report_lead_modal" href="/report/reconcile-lead-payout-wizard?_id=' + dataContext._id.$id + '">Disqualified</a>';
				} else if (value == <?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>) {
					ret_val += '<a class="text-muted" data-toggle="modal" data-target="#edit_report_lead_modal" href="/report/reconcile-lead-payout-wizard?_id=' + dataContext._id.$id + '">Pending</a>';
				}
				if (dataContext.disposition_message) {
					ret_val += '<div class="small text-muted">';
					ret_val += dataContext.disposition_message;
					ret_val += '</div>';
				}
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'accepted', name:'paid', field:'accepted', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
				return '<div class="text-success">Yes</div>';
			} else {
				return '<div class="text-danger">No</div>';
			}
			return value;
		}}
	];

  	slick_grid = $('#reconcile_lead_payout-grid').slickGrid({
		pager: $('#reconcile_lead_payout-pager'),
		form: $('#reconcile_lead_payout_search_form'),
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
  			$('#reconcile_lead_payout_search_form').trigger('submit');
  		}
  	});

	$('#client_id_array,#disposition_array,#report_date').selectize().on('change', function(e) {
		$('#reconcile_lead_payout_search_form').trigger('submit');
	});

  	$('#reconcile_lead_payout_search_form').trigger('submit');

  	$('#edit_report_lead_modal').on('hide.bs.modal', function(e) {
 		$(this).removeData('bs.modal');
	});
});
//-->
</script>