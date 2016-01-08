<?php
	/* @var $split \Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/export/split-search">Splits</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#add_split_modal" href="/export/split-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Split</a>
		</div>
	   <h1>Splits</h1>
	</div>
	<div class="help-block">Splits are used to automate lead fulfillment</div>
	<div class="panel panel-primary">
		<div id='split-header' class='grid-header panel-heading clearfix'>
			<form id="split_search_form" method="GET" class="form-inline" action="/api">
				<input type="hidden" name="func" value="/export/split">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="created_date" />
				<input type="hidden" id="sord" name="sord" value="desc" />
				<div class="text-right">
					<div class="form-group text-left">
						<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="name" value="" />
					</div>
					<div class="form-group text-left">
						<select name="split_type" id="split_type" style="width:200px;">
						   <optgroup label="Show All Split Types">
							   <option value="0">Show all split types</option>
						   </optgroup>
						   <optgroup label="Show Selected Split Type">
							   <option value="<?php echo \Flux\Split::SPLIT_TYPE_NORMAL ?>">Show normal splits</option>
							   <option value="<?php echo \Flux\Split::SPLIT_TYPE_HOST_POST ?>">Show post splits</option>
							   <option value="<?php echo \Flux\Split::SPLIT_TYPE_CATCH_ALL ?>">Show catch-all splits</option>
						   </optgroup>
						</select>
					</div>
				</div>
			</form>
		</div>
		<div id="split-grid"></div>
		<div id="split-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit split modal -->
<div class="modal fade" id="add_split_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
 		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
 			var ret_val = '<div style="line-height:16pt;">'
 	 		if (dataContext.status == '<?php echo \Flux\Split::SPLIT_STATUS_INACTIVE ?>') {
 	 			ret_val += '<a class="text-muted" href="/export/split?_id=' + dataContext._id + '"><i><span class="glyphicon glyphicon-exclamation-sign"></span> ' + value + ' <span class="small text-danger">(inactive)</span></i></a>';
 	 		} else {
 	 			ret_val += '<a href="/export/split?_id=' + dataContext._id + '">' + value + '</a>';
 	 		}
 			ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
 			ret_val += '</div>';
 			return ret_val;
 		}},
 		{id:'queue_count', name:'# Queued', field:'queue_count', def_value: ' ', cssClass:'text-center', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
 			var ret_val = '<div style="line-height:16pt;">'
 	 			ret_val += $.number(value, 0);
 				if (dataContext.last_queue_time == null) {
 					ret_val += '<div class="small text-muted">nothing queued yet</div>';
 				} else {
  					ret_val += '<div class="small text-muted">' + moment.unix(dataContext.last_queue_time.sec).calendar() + '</div>';
 				}
 	 			ret_val += '</div>';
 	 			return ret_val;
 		}},
 		{id:'schedule', name:'Schedule', field:'scheduling.days', def_value: ' ', cssClass:'text-center', hidden:true, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 	 		var ret_val = $('<div class="btn-group"><div class="btn btn-default disabled sunday">S</div><div class="btn btn-default disabled monday">M</div><div class="btn btn-default disabled tuesday">T</div><div class="btn btn-default disabled wednesday">W</div><div class="btn btn-default disabled thursday">T</div><div class="btn btn-default disabled friday">F</div><div class="btn btn-default disabled saturday">S</div></div>');
 			$.each(value, function(i, item) {
 	 			if (item == 0) {
 	 	 			$('div.sunday', ret_val).removeClass('btn-default').addClass('active btn-success');
 	 			} else if (item == 1) {
 	 				$('div.monday', ret_val).removeClass('btn-default').addClass('active btn-success');
 	 			} else if (item == 2) {
 	 				$('div.tuesday', ret_val).removeClass('btn-default').addClass('active btn-success');
 	 			} else if (item == 3) {
 	 				$('div.wednesday',ret_val).removeClass('btn-default').addClass('active btn-success');
 	 			} else if (item == 4) {
 	 				$('div.thursday', ret_val).removeClass('btn-default').addClass('active btn-success');
 	 			} else if (item == 5) {
 	 				$('div.friday', ret_val).removeClass('btn-default').addClass('active btn-success');
 	 			} else if (item == 6) {
 	 				$('div.saturday', ret_val).removeClass('btn-default').addClass('active btn-success');
 	 			}
 			});
 			return '<div class="btn-group">' + ret_val.html() + '</div>';
 		}},
 		{id:'last_run', name:'Last Run', field:'pid_time_split', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
 			var ret_val = '<div style="line-height:16pt;">'
 			ret_val += moment.unix(value.sec).fromNow();
 			if (dataContext.last_run_time && dataContext.last_run_time.sec) {
 				ret_val += '<div class="small text-muted">Last Fulfilled Lead: ' + moment.unix(dataContext.last_run_time.sec).calendar() + '</div>';
 			}
 			ret_val += '</div>';
 			return ret_val;
 		}},
 		{id:'status', name:'Status', field:'status', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
 			if (value == '<?php echo \Flux\Split::SPLIT_STATUS_INACTIVE ?>') {
 				return '<i class="text-danger">inactive</i>';
 			} else {
 				return '<div class="text-muted">active</div>';
 			}
 		}},
 		{id:'fulfill_immediately', name:'Auto-Fulfillment', field:'fulfill_immediately', def_value: ' ', cssClass:'text-center', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
 			var ret_val = '<div style="line-height:16pt;">'
 	 		if (value) {
 	 			ret_val += '<span class="text-success">Yes</span>';
 	 			if (dataContext.fulfill_delay > 0) {
 	 				ret_val += '<div class="small text-muted">Delayed for ' + dataContext.fulfill_delay + ' minutes</div>';
 	 			}
 	 		} else {
 	 			ret_val += '<span class="text-danger">No</span>';
 	 		}
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

	$('#split_type').selectize().on('change', function() {
		$('#split_search_form').trigger('submit');
	});
 		  	
	$('#split_search_form').trigger('submit');
});
//-->
</script>