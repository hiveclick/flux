<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute("split_queue", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$splits = $this->getContext()->getRequest()->getAttribute("splits", array());
?>
<div class="page-header">
   <h1>Queued Leads</h1>
</div>
	
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/export/split-search">Splits</a></li>
	<?php if ($split_queue->getSplit()->getSplitId() > 0) { ?>
	<li><a href="/export/split?_id=<?php echo $split_queue->getSplit()->getSplitId() ?>"><?php echo $split_queue->getSplit()->getSplitName() ?></a></li>
	<?php } ?>
	<li class="active">View Queued Leads</li>
</ol>
<div class="panel panel-primary">
	<div id='split-header' class='grid-header panel-heading clearfix'>
		<form id="split_search_form" method="GET" class="form-inline" action="/api">
			<input type="hidden" name="func" value="/export/split-queue">
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
						<?php
							/* @var $split \Flux\Split */ 
							foreach ($splits as $split) { 
						?>
							<option value="<?php echo $split->getId() ?>" <?php echo in_array($split->getId(), $split_queue->getSplitIdArray()) ? "selected" : "" ?>><?php echo $split->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control selectize" name="offer_id_array[]" id="split_queue_spy_offer_id" multiple placeholder="Filter by offer">
						<?php
							/* @var $offer \Flux\Offer */ 
							foreach ($offers as $offer) { 
						?>
							<option value="<?php echo $offer->getId() ?>" <?php echo in_array($offer->getId(), $split_queue->getOfferIdArray()) ? "selected" : "" ?>><?php echo $offer->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group text-left">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
				</div>
				<div class="form-group text-left">
				    <select class="form-control selectize" name="disposition_array[]" id="disposition_array" multiple placeholder="Filter by disposition">
				        <option value="<?php echo \Flux\SplitQueue::DISPOSITION_UNFULFILLED ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_UNFULFILLED, $split_queue->getDispositionArray()) ? "selected" : "" ?>>Unfulfilled</options>
				        <option value="<?php echo \Flux\SplitQueue::DISPOSITION_PENDING ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_PENDING, $split_queue->getDispositionArray()) ? "selected" : "" ?>>Pending</options>
				        <option value="<?php echo \Flux\SplitQueue::DISPOSITION_FULFILLED ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_FULFILLED, $split_queue->getDispositionArray()) ? "selected" : "" ?>>Fulfilled</options>
				        <option value="<?php echo \Flux\SplitQueue::DISPOSITION_UNFULFILLABLE ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_UNFULFILLABLE, $split_queue->getDispositionArray()) ? "selected" : "" ?>>Unfulfillable</options>
				        <option value="<?php echo \Flux\SplitQueue::DISPOSITION_ALREADY_FULFILLED ?>" <?php echo in_array(\Flux\SplitQueue::DISPOSITION_ALREADY_FULFILLED, $split_queue->getDispositionArray()) ? "selected" : "" ?>>Already Fulfilled</options>
					</select>
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

    $('#disposition_array').selectize().on('change', function($val) {
    	$('#split_search_form').trigger('submit');
    });
	
	var columns = [
        {id:'_id', name:'Item #', field:'_id', sort_field:'_id', def_value: ' ', width:175, sortable:true, hidden:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
        	var lead_id = (dataContext.lead.lead_id == undefined) ? 0 : dataContext.lead.lead_id;
        	var offer_id = (dataContext.lead.offer.offer_id == undefined) ? 0 : dataContext.lead.offer.offer_id;
        	var offer_name = (dataContext.lead.offer.offer_name == undefined) ? 0 : dataContext.lead.offer.offer_name;
        	var client_name = (dataContext.lead.client.client_name == undefined) ? 0 : dataContext.lead.client.client_name;
        	var ret_val = '<div style="line-height:16pt;">'
        	ret_val += '<a href="/export/split-queue?_id=' + value + '">' + value + '</a>';
        	ret_val += '<div class="small text-muted">';
        	ret_val += ' (Lead #<a href="/lead/lead?_id=' + lead_id + '">' + lead_id + '</a> - <a href="/offer/offer?_id=' + offer_id + '">' + offer_name + '</a> on ' + client_name + ')';
        	ret_val += '</div>';
        	ret_val += '</div>';
        	return ret_val;
        }},
		{id:'lead_id', name:'Lead #', field:'lead.lead_id', sort_field:'lead.lead_id', def_value: ' ', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var offer_id = (dataContext.lead.offer.offer_id == undefined) ? 0 : dataContext.lead.offer.offer_id;
			var offer_name = (dataContext.lead.offer.offer_name == undefined) ? 0 : dataContext.lead.offer.offer_name;
			var client_name = (dataContext.lead.client.client_name == undefined) ? 0 : dataContext.lead.client.client_name;
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/export/split-queue?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += ' (<a href="/offer/offer?_id=' + offer_id + '">' + offer_name + '</a> on ' + client_name + ')';
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'split_id', name:'Split', field:'split.split_id', sort_field:'split.split_id', def_value: ' ', cssClass: 'text-center', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var split_id = (dataContext.split.split_id == undefined) ? 0 : dataContext.split.split_id;
			var split_name = (dataContext.split.split_name == undefined) ? 0 : dataContext.split.split_name;
			ret_val = '<a href="/export/split?_id=' + split_id + '">' + split_name + '</a>';
			return ret_val;
		}},
		{id:'name', name:'Name', field:'lead_name', sort_field:'lead_name', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
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
				} else {
					ret_val += '<div class="text-muted">Unknown Disposition (' + value + ')</div>';
				}
    			if (dataContext.error_message != '') {
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
	
	$('#split_queue_spy_offer_id,#split_queue_spy_split_id').selectize({
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
