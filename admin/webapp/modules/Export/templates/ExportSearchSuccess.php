<?php
	/* @var $export \Flux\Export */
	$export = $this->getContext()->getRequest()->getAttribute("export", array());
	$fulfillments = $this->getContext()->getRequest()->getAttribute("fulfillments", array());
	$splits = $this->getContext()->getRequest()->getAttribute("splits", array());
?>
<div class="page-header">
   <h1>Exports</h1>
</div>
<div class="help-block">Exports define how a client can receive data from a split</div>
<div class="panel panel-primary">
	<div id='export-header' class='grid-header panel-heading clearfix'>
		<form id="export_search_form" method="GET" class="form-inline" action="/api">
			<input type="hidden" name="func" value="/export/export">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="export_date" />
			<input type="hidden" id="sord" name="sord" value="desc" />
			<div class="text-right">
				<div class="form-group text-left">
					<select class="form-control" name="export_type_array[]" id="export_type" multiple placeholder="Filter by Export Type">
						<option value="<?php echo \Flux\Export::EXPORT_TYPE_BATCH ?>" <?php echo in_array(\Flux\Export::EXPORT_TYPE_BATCH, $export->getExportTypeArray()) ? 'selected' : '' ?>>Batch Exports</option>
						<option value="<?php echo \Flux\Export::EXPORT_TYPE_REALTIME ?>" <?php echo in_array(\Flux\Export::EXPORT_TYPE_REALTIME, $export->getExportTypeArray()) ? 'selected' : '' ?>>Realtime Exports</option>
						<option value="<?php echo \Flux\Export::EXPORT_TYPE_EMAIL_BATCH ?>" <?php echo in_array(\Flux\Export::EXPORT_TYPE_EMAIL_BATCH, $export->getExportTypeArray()) ? 'selected' : '' ?>>Batch Email Exports</option>
						<option value="<?php echo \Flux\Export::EXPORT_TYPE_EMAIL_REALTIME ?>" <?php echo in_array(\Flux\Export::EXPORT_TYPE_EMAIL_REALTIME, $export->getExportTypeArray()) ? 'selected' : '' ?>>Realtime Email Exports</option>
						<option value="<?php echo \Flux\Export::EXPORT_TYPE_TEST ?>" <?php echo in_array(\Flux\Export::EXPORT_TYPE_TEST, $export->getExportTypeArray()) ? 'selected' : '' ?>>Test Exports</option>
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control" name="fulfillment_id_array[]" id="fulfillment_id" multiple placeholder="All Exports">
						<?php
							/* @var $fulfillment \Flux\Fulfillment */ 
							foreach ($fulfillments as $fulfillment) { 
						?>
							<option value="<?php echo $fulfillment->getId() ?>" <?php echo in_array($fulfillment->getId(), $export->getFulfillmentIdArray()) ? 'selected' : '' ?>><?php echo $fulfillment->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control" name="split_id_array[]" id="split_id" multiple placeholder="All Splits">
						<?php
							/* @var $split \Flux\Split */ 
							foreach($splits as $split) { 
						?>
							<option value="<?php echo $split->getId() ?>" <?php echo in_array($split->getId(), $export->getSplitIdArray()) ? 'selected' : '' ?>><?php echo $split->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group text-left">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="name" value="" />
				</div>
			</div>
		</form>
	</div>
	<div id="export-grid"></div>
	<div id="export-pager" class="panel-footer"></div>
</div>

<script>
//<!--
$(document).ready(function() {

	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/export/export?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">Started on ' + moment.unix(dataContext.start_time.sec).calendar() + '.  Completed on ' + moment.unix(dataContext.end_time.sec).calendar() + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'export_type', name:'type', field:'export_type', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Export::EXPORT_TYPE_BATCH ?>') {
				return 'Batch Export';
			} else if (value == '<?php echo \Flux\Export::EXPORT_TYPE_REALTIME ?>') {
				return 'Realtime Export';
			} else if (value == '<?php echo \Flux\Export::EXPORT_TYPE_EMAIL_BATCH ?>') {
				return 'Email Batch Export';
			} else if (value == '<?php echo \Flux\Export::EXPORT_TYPE_EMAIL_REALTIME ?>') {
				return 'Email Realtime Export';
			} else if (value == '<?php echo \Flux\Export::EXPORT_TYPE_TEST ?>') {
				return 'Test';
			}
			return value;
		}},
		{id:'fulfillment', name:'fulfillment', field:'fulfillment.fulfillment_name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'split_id', name:'split', field:'split.split_name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'percent_complete', name:'% complete', field:'percent_complete', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == 100) {
				var div = '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" style="width: ' + ((dataContext.num_records_successful/dataContext.num_records)*100) + '%;"></div><div class="progress-bar progress-bar-danger" role="progressbar" style="width: ' + ((dataContext.num_records_error/dataContext.num_records)*100) + '%;"></div></div>';
			} else {
				var div = '<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' + value + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + value + '%;"></div></div>';
			}
			return div;
		}},
		{id:'export_date', name:'export_date', field:'export_date', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return moment.unix(value.sec).calendar();
		}},
		{id:'num_records', name:'# records', field:'num_records', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return $.number(value);
		}}

	];

  	slick_grid = $('#export-grid').slickGrid({
		pager: $('#export-pager'),
		form: $('#export_search_form'),
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
   			$('#export_search_form').trigger('submit');
   		}
   	});
		  	
   	$('#export_search_form').trigger('submit');
	
	$('#split_id,#fulfillment_id,#export_type').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	}).on('change', function(e) {
		$('#export_search_form').trigger('submit');
	});
});
//-->
</script>