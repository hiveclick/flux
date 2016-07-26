<?php
	/* @var $revenue_report \Flux\ReportLead */
	$report_lead = $this->getContext()->getRequest()->getAttribute('report_lead', array());
	$clients = $this->getContext()->getRequest()->getAttribute('clients', array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/report/report-home">Reports</a></li>
	<li><a href="/report/lead-payout-report">Lead Payout Report</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
	   <h2>Lead Payout Report</h2>
	</div>
	<div class="help-block">View a report of how many leads have been received, approved, and paid</div>
	<br/>
	<div class="panel panel-info">
		<div id='lead-header' class='grid-header panel-heading clearfix'>Report Filters</div>
		<div class="panel-body">
			<form id="lead_payout_report_search_form" method="GET" class="" action="/api">
				<input type="hidden" name="func" value="/report/lead-payout-report">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="client_id_array">Filter by clients</label>
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
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="date_range">Date Range:</label>
							<select name="date_range" id="date_range" class="selectize">
								<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_TODAY ?>">Today</option>
								<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_YESTERDAY ?>">Yesterday</option>
								<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>">Last 7 days</option>
								<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_MTD ?>">Month To Date</option>
								<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_MONTH ?>">Last Month</option>
								<option value="<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_CUSTOM ?>">Custom</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="start_date" class="hidden-sm hidden-xs">&nbsp;</label>
							<div class="input-group">
								<input type="text" class="form-control" placeholder="start date" id="start_time" name="start_time" value="" disabled />
								<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="end_date" class="hidden-sm hidden-xs">&nbsp;</label>
							<div class="input-group">
								<input type="text" class="form-control" placeholder="end date" id="end_time" name="end_time" value="" disabled />
								<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="client_id_array">Filter by disposition</label>
							<select class="selectize" name="disposition_array[]" id="disposition_array" multiple placeholder="Filter by disposition">
								<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_PENDING, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Pending</options>
								<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Accepted</options>
								<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Disqualified</options>
								<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Duplicate</options>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="client_id_array">Filter by lead name, email, or id</label>
							<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
						</div>
					</div>
				</div>
				<div class="text-center">
					<input type="submit" value="search" class="btn btn-primary" />
				</div>
			</form>
		</div>
	</div>
	<hr />
	<table class="table table-responsive table-striped">
		<thead>
			<tr>
				<th>Client</th>
				<th class="text-center">Clicks</th>
				<th class="text-center">Conversions</th>
				<th class="text-center">Fulfilled</th>
				<th class="text-center">Pixel</th>
				<th class="text-center">CTR</th>
				<th class="text-center">Pending</th>
				<th class="text-center">Accepted</th>
				<th class="text-center">Disqualified</th>
				<th class="text-center">Duplicate</th>
				<th class="text-center">Revenue</th>
				<th class="text-center">Payout</th>
				<th class="text-center">Conversion</th>
			</tr>
		</thead>
		<tbody id="report_tbody"></tbody>
		<tfoot>
			<tr>
				<th>Totals</th>
				<th id="total_clicks" class="text-center text-muted"></th>
				<th id="total_conversions" class="text-center text-muted"></th>
				<th id="total_fulfilled" class="text-center text-muted"></th>
				<th id="total_pixel" class="text-center text-muted"></th>
				<th id="total_ctr" class="text-center text-muted"></th>
				<th id="total_pending_leads" class="text-center text-muted"></th>
				<th id="total_accepted_leads" class="text-center text-success"></th>
				<th id="total_disqualified_leads" class="text-center text-danger"></th>
				<th id="total_duplicate_leads" class="text-center text-warning"></th>
				<th id="total_revenue" class="text-center"></th>
				<th id="total_payout" class="text-center"></th>
				<th id="total_conversion" class="text-center"></th>
			</tr>
		</tfoot>
	</table>
</div>

<!-- edit report lead modal -->
<div class="modal fade" id="lead_payout_report_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	$('#start_time,#end_time').datepicker();

	$('#date_range').selectize().on('change', function(e) {
		if ($(this).val() == '<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_CUSTOM ?>') {
			$('#start_time').removeAttr('disabled');
			$('#end_time').removeAttr('disabled');
		} else {
			$('#start_time').attr('disabled', 'disabled');
			$('#end_time').attr('disabled', 'disabled');
		}
	});
	
	$('#client_id_array,#disposition_array,#report_date').selectize();

	$('#lead_payout_report_modal').on('hide.bs.modal', function(e) {
 		$(this).removeData('bs.modal');
	});

	$('#lead_payout_report_search_form').form(function(data) {
  		if (data.entries) {
			$('#report_tbody').html('');
			var client_id = 0;
			var client_entries = [];
			$.each(data.entries, function(i, entry) {
				client_found = false;
				$.each(client_entries, function(i, client_entry) {
					if (client_entry.client_id == entry.client_id) {
						client_found = true;
						client_entry.clicks += entry.clicks;
						client_entry.conversions += entry.conversions;
						client_entry.fulfilled += entry.fulfilled;
						client_entry.pixel += entry.pixel;
						client_entry.accepted_leads += entry.accepted_leads;
						client_entry.disqualified_leads += entry.disqualified_leads;
						client_entry.duplicate_leads += entry.duplicate_leads;
						client_entry.pending_leads += entry.pending_leads;
						client_entry.revenue += entry.revenue;
						client_entry.payout += entry.payout;
					}
				});
				if (!client_found) {
					client_entries.push({
						client_id: entry.client_id, 
						client_name: entry.client_name,
						clicks: entry.clicks,
						conversions: entry.conversions,
						fulfilled: entry.fulfilled,
						pixel: entry.pixel,
						accepted_leads: entry.accepted_leads,
						disqualified_leads: entry.disqualified_leads,
						duplicate_leads: entry.duplicate_leads,
						pending_leads: entry.pending_leads,
						revenue: entry.revenue,
						payout: entry.payout
					});
				}
			});

			$.each(client_entries, function(i, client_entry) {
				var row_id = 'client_row_' + client_entry.client_id;
				var tr = $('<tr />').attr('id', row_id).appendTo($('#report_tbody'));
				$('<td class="bg-warning" />').html(client_entry.client_name).appendTo(tr);
				$('<td class="bg-warning text-center text-muted " />').html($.number(client_entry.clicks, 0)).appendTo(tr);
				$('<td class="bg-warning text-center text-muted" />').html($.number(client_entry.conversions, 0)).appendTo(tr);
				$('<td class="bg-warning text-center text-muted" />').html($.number(client_entry.fulfilled, 0)).appendTo(tr);
				$('<td class="bg-warning text-center text-muted" />').html($.number(client_entry.pixel, 0)).appendTo(tr);
				$('<td class="bg-warning text-center text-muted" />').html($.number(client_entry.conversions / client_entry.clicks * 100, 2) + '%').appendTo(tr);
				$('<td class="bg-warning text-center text-muted" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?client_id_array[]=' + client_entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(client_entry.pending_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="bg-warning text-center text-success" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?client_id_array[]=' + client_entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(client_entry.accepted_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="bg-warning text-center text-danger" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?client_id_array[]=' + client_entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(client_entry.disqualified_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="bg-warning text-center text-warning" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?client_id_array[]=' + client_entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(client_entry.duplicate_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="bg-warning text-center" />').html('$' + $.number(client_entry.revenue, 2)).appendTo(tr);
				$('<td class="bg-warning text-center" />').html('$' + $.number(client_entry.payout, 2)).appendTo(tr);
				$('<td class="bg-warning text-center" />').html($.number(((client_entry.revenue - client_entry.payout) / client_entry.revenue) * 100, 1) + '%').appendTo(tr);
			});
			
			var total_clicks = 0, total_conversions = 0, total_pixel = 0, total_fulfilled = 0, total_accepted_leads = 0, total_disqualified_leads = 0, total_duplicate_leads = 0, total_pending_leads = 0, total_revenue = 0, total_payout = 0;
			$.each(data.entries, function(i, entry) {				
				total_clicks += entry.clicks;
				total_conversions += entry.conversions;
				total_fulfilled += entry.fulfilled;
				total_pixel += entry.pixel;
				total_accepted_leads += entry.accepted_leads;
				total_disqualified_leads += entry.disqualified_leads;
				total_duplicate_leads += entry.duplicate_leads;
				total_pending_leads += entry.pending_leads;
				total_revenue += entry.revenue;
				total_payout += entry.payout;

				var row_id = '#client_row_' + entry.client_id;
				var tr = $('<tr class="small" />').insertAfter($(row_id));
				$('<td />').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + (entry.offer_name ? entry.offer_name : '<i>Offer Missing</i>')).appendTo(tr);
				$('<td class="text-center text-muted" />').html($.number(entry.clicks, 0)).appendTo(tr);
				$('<td class="text-center text-muted" />').html($.number(entry.conversions, 0)).appendTo(tr);
				$('<td class="text-center text-muted" />').html($.number(entry.fulfilled, 0)).appendTo(tr);
				$('<td class="text-center text-muted" />').html($.number(entry.pixel, 0)).appendTo(tr);
				$('<td class="text-center text-muted" />').html($.number(entry.conversions / entry.clicks * 100, 2) + '%').appendTo(tr);
				$('<td class="text-center text-muted" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?offer_id_array[]=' + entry.offer_id + '&client_id_array[]=' + entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(entry.pending_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="text-center text-success" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?offer_id_array[]=' + entry.offer_id + '&client_id_array[]=' + entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(entry.accepted_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="text-center text-danger" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?offer_id_array[]=' + entry.offer_id + '&client_id_array[]=' + entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(entry.disqualified_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="text-center text-warning" />').html('<a data-toggle="modal" data-target="#lead_payout_report_modal" href="/report/lead-payout-report-details?offer_id_array[]=' + entry.offer_id + '&client_id_array[]=' + entry.client_id + '&disposition_array[]=<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE ?>&start_date=' + $('#start_time').val() + '&end_date=' + $('#end_time').val() + '&date_range=' + $('#date_range').val() +  '&keywords=' + $('#txtSearch').val() + '">' + $.number(entry.duplicate_leads, {format:'#,##0'}) + '</a>').appendTo(tr);
				$('<td class="text-center" />').html('$' + $.number(entry.revenue, 2)).appendTo(tr);
				$('<td class="text-center" />').html('$' + $.number(entry.payout, 2)).appendTo(tr);
				$('<td class="text-center" />').html($.number(((entry.revenue - entry.payout) / entry.revenue) * 100, 1) + '%').appendTo(tr);
			});

			$('#total_clicks').html($.number(total_clicks, 0));
			$('#total_conversions').html($.number(total_conversions, 0));
			$('#total_fulfilled').html($.number(total_fulfilled, 0));
			$('#total_pixel').html($.number(total_pixel, 0));
			$('#total_ctr').html($.number(total_conversions / total_clicks * 100, 2) + '%');
			$('#total_accepted_leads').html($.number(total_accepted_leads, 0));
			$('#total_disqualified_leads').html($.number(total_disqualified_leads, 0));
			$('#total_duplicate_leads').html($.number(total_duplicate_leads, 0));
			$('#total_pending_leads').html($.number(total_pending_leads, 0));
			$('#total_revenue').html('$' + $.number(total_revenue, 2));
			$('#total_payout').html('$' + $.number(total_payout, 2));
			$('#total_conversion').html($.number(((total_revenue - total_payout) / total_revenue) * 100, 1) + '%');
  		}
  	}, {keep_form: true});
});
//-->
</script>