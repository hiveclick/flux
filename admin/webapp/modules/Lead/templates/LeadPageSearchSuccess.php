<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="page-header">
	<h1>Lead Pages <small>Pages visited by lead #<?php echo $lead->getId() ?></small></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/lead/lead-search">Leads</a></li>
	<li><a href="/lead/lead?_id=<?php echo $lead->getId() ?>">Lead #<?php echo $lead->getId() ?></a></li>
	<li class="active">Lead Pages</li>
</ol>

<!-- Page Content -->
<div class="help-block">You can view a lead on this screen and see how it was tracked</div>
<div class="panel panel-primary">
	<div id='lead_page-header' class='grid-header panel-heading clearfix'>
		<form id="lead_page_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/lead/lead-page">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" name="lead[lead_id]" value="<?php echo $lead->getId() ?>">
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="pull-right">
				<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="name" value="" />
			</div>
		</form>
	</div>
	<div id="lead_page-grid"></div>
	<div id="lead_page-pager" class="panel-footer"></div>
</div>

<!-- View lead page preview modal -->
<div class="modal fade" id="lead_page_view_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'href', name:'url', field:'href', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'page', name:'page', field:'page', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var offer_page_name = (dataContext.offer_page.offer_page_name == '') ? '' : ('<a href="/offer/offer-page?_id=' + dataContext.offer_page.offer_page_id + '">' + dataContext.offer_page.offer_page_name + '</a>');
			var offer_name = (dataContext.offer.offer_name == '') ? '' : ' on <a href="/offer/offer?_id=' + dataContext.offer.offer_id + '">' + dataContext.offer.offer_name + '</a>';
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += value;
				ret_val += '<div class="small text-muted">';
				ret_val += ' (' + offer_page_name + offer_name + ')';
				ret_val += '</div>';
				ret_val += '</div>';
				return ret_val;
			return value;
		}},
		{id:'domain', name:'domain', field:'domain', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'entrance_time', name:'enter time', field:'entrance_time', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return moment.unix(value.sec).calendar();
		}},
		{id:'exit_time', name:'exit time', field:'exit_time', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return moment.unix(value.sec).calendar();
		}},
		{id:'lead', name:'lead', field:'lead.lead_id', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var email = (dataContext.lead.email == '') ? '' : 'E: ' + dataContext.lead.email;
			var phone = (dataContext.lead.phone == '') ? '' : ', P: ' + dataContext.lead.phone;
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/lead/lead?_id=' + dataContext.lead.lead_id + '">' + dataContext.lead.lead_id + '</a>';
				ret_val += '<div class="small text-muted">';
				ret_val += ' (' + email + phone + ')';
				ret_val += '</div>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'offer', name:'offer', field:'offer.offer_id', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/offer/offer?_id=' + dataContext.offer.offer_id + '">' + dataContext.offer.offer_name + '</a>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'load_count', name:'load #', field:'load_count', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'_id', name:'cookie', field:'_id', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a href="/lead/lead-pane-page?_id=' + dataContext._id + '" data-toggle="modal" data-target="#lead_page_view_modal">view cookie</a>';
		}}
	];

	slick_grid = $('#lead_page-grid').slickGrid({
		pager: $('#lead_page-pager'),
		form: $('#lead_page_search_form'),
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

 	$("#txtSearch").keyup(function(e) {
		// clear on Esc
		if (e.which == 27) {
			this.value = "";
		} else if (e.which == 13) {
			$('#lead_page_search_form').trigger('submit');
		}
	});
	 	
	$('#lead_page_search_form').trigger('submit');

	
	$('body').on('hidden.bs.modal', '.modal', function () {
		  $(this).removeData('bs.modal');
	});
});
//-->
</script>