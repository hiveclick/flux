<?php
	/* @var $split \Flux\Export */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("datafields", array());
?>
<div class="page-header">
   <h1>Queued Leads</h1>
</div>
	
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/export/split-search">Splits</a></li>
	<li><a href="/export/split?_id=<?php echo $split->getId() ?>"><?php echo $split->getName() ?></a></li>
	<li class="active">View Queued Leads</li>
</ol>
<div class="panel panel-primary">
	<div id='split-header' class='grid-header panel-heading clearfix'>
		<form id="split_search_form" method="GET" class="form-inline" action="/api">
			<input type="hidden" name="func" value="/export/split-queue">
			<input type="hidden" name="split[split_id]" value="<?php echo $split->getId() ?>">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="_id" />
			<input type="hidden" id="sord" name="sord" value="desc" />
			<div class="text-right">
				<div class="form-group text-left">
					<select class="form-control selectize" name="offer_id_array[]" id="split_queue_spy_offer_id" multiple placeholder="Filter by offer">
						<?php
							/* @var $offer \Flux\Offer */ 
							foreach ($offers as $offer) { 
						?>
							<option value="<?php echo $offer->getId() ?>"><?php echo $offer->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group text-left">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="name" value="" />
				</div>
			</div>
		</form>
	</div>
	<div id="split-grid"></div>
	<div id="split-pager" class="panel-footer"></div>
</div>

<script>
//<!--
$(document).ready(function() {

	var columns = [
		{id:'lead.lead_id', name:'Lead #', field:'lead.lead_id', sort_field:'lead.lead_id', def_value: ' ', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var offer_id = (dataContext.lead.offer.offer_id == undefined) ? 0 : dataContext.lead.offer.offer_id;
			var offer_name = (dataContext.lead.offer.offer_name == undefined) ? 0 : dataContext.lead.offer.offer_name;
			var client_name = (dataContext.lead.client.client_name == undefined) ? 0 : dataContext.lead.client.client_name;
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/lead/lead?_id=' + value + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += ' (<a href="/offer/offer?_id=' + offer_id + '">' + offer_name + '</a> on ' + client_name + ')';
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'name', name:'Name', field:'lead_name', sort_field:'lead_name', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var email = (dataContext.lead.email == '') ? '' : 'E: ' + dataContext.lead.email;
			var phone = (dataContext.lead.phone == '') ? '' : ', P: ' + dataContext.lead.phone;
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/lead/lead?_id=' + dataContext.lead.lead_id + '">' + dataContext.lead.lead_name + '</a>';
				ret_val += '<div class="small text-muted">';
				ret_val += ' (' + email + phone + ')';
				ret_val += '</div>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'is_fulfilled', name:'Fulfilled', field:'is_fulfilled', def_value: ' ', width:60, sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
				return '<span class="text-success">Yes</span>';
			} else {
				return '<span class="text-danger">No</span>';
			}
		}},
		{id:'error_message', name:'Errors', field:'error_message', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
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
		}}
	];

	slick_grid = $('#split-grid').slickGrid({
		pager: $('#split-pager'),
		form: $('#split_search_form'),
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
			$('#split_search_form').trigger('submit');
		}
	});
	
	$('#split_queue_spy_offer_id').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	}).on('change', function(e) {
		$('#split_search_form').trigger('submit');
	});

	// submit the form to initially fill in the grid
	$('#split_search_form').trigger('submit');
});
//-->
</script>
