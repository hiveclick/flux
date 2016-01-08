<?php
	/* @var $revenue_report \Flux\ReportLead */
	$report_lead = $this->getContext()->getRequest()->getAttribute('report_lead', array());
?>
<div class="page-header">
   <h2>Lead Report</h2>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/default/index">Reports</a></li>
	<li class="active">Lead Report</li>
</ol>
<div class="help-block">View the leads you have submitted and their current disposition</div>
<div class="panel panel-primary">
	<div id='lead_report-header' class='grid-header panel-heading clearfix'>
		<form id="lead_report_search_form" method="GET" class="form-inline" action="/api">
			<input type="hidden" name="func" value="/report/report-lead">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="client_id" name="client_id_array[]" value="<?php echo $this->getContext()->getUser()->getUserDetails()->getClient()->getClientId() ?>" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="pull-right">
				<div class="form-group text-left">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
				</div>
				<div class="form-group text-left">
					<select class="form-control selectize" name="disposition_array[]" id="disposition_array" multiple placeholder="Filter by disposition">
						<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_PENDING, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Pending</options>
						<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Accepted</options>
						<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Disqualified</options>
						<option value="<?php echo \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE ?>" <?php echo in_array(\Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE, $report_lead->getDispositionArray()) ? "selected" : "" ?>>Duplicate</options>
					</select>
				</div>
				<div class="form-group text-left">
					<select id="report_date" name="report_date" class="form-control" style="width:200px;">
						<option value="<?php echo date('m/01/Y') ?>" <?php echo $report_lead->getReportDate()->sec == strtotime(date('m/01/Y')) ? 'selected' : '' ?>><?php echo date('F Y') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						<?php for ($i=1;$i<14;$i++) { ?>
							<option value="<?php echo date('m/01/Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months')) ?>" <?php echo $report_lead->getReportDate()->sec == strtotime(date('m/01/Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months'))) ? 'selected' : '' ?>><?php echo date('F Y', strtotime(date('m/01/Y') . ' - ' . $i . ' months')) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						<?php } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
	<div id="lead_report-grid"></div>
	<div id="lead_report-pager" class="panel-footer"></div>
</div>

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
			var offer_id = (dataContext.lead.offer.offer_id == undefined) ? 0 : dataContext.lead.offer.offer_id;
			var offer_name = (dataContext.lead.offer.offer_name == undefined) ? 0 : dataContext.lead.offer.offer_name;
			var client_name = (dataContext.lead.client.client_name == undefined) ? 0 : dataContext.lead.client.client_name;
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += value.lead_name;
			ret_val += '<div class="small text-muted">';
			ret_val += ' (' + offer_name + ')';
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'payout', name:'payout', field:'payout', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '$' + $.formatNumber(value, {format:"#,##0.00", locale:"us"});
		}},
		{id:'disposition', name:'disposition', field:'disposition', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				if (value == <?php echo \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED ?>) {
					ret_val += '<span class="text-success">Accepted</span>';
				} else if (value == <?php echo \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED ?>) {
					ret_val += '<span class="text-danger">Disqualified</span>';
				} else if (value == <?php echo \Flux\ReportLead::LEAD_DISPOSITION_PENDING ?>) {
					ret_val += '<span class="text-muted">Pending</span>';
				}
				ret_val += '<div class="small text-muted">';
				ret_val += dataContext.disposition_message;
				ret_val += '</div>';
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

  	slick_grid = $('#lead_report-grid').slickGrid({
		pager: $('#lead_report-pager'),
		form: $('#lead_report_search_form'),
		columns: columns,
		useFilter: false,
		cookie: '<?php echo $_SERVER['PHP_SELF'] ?>',
		pagingOptions: {
			pageSize: 25,
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

	$('#disposition_array,#report_date').selectize().on('change', function(e) {
		$('#lead_report_search_form').trigger('submit');
	});

  	$('#lead_report_search_form').trigger('submit');
});
//-->
</script>