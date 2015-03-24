<?php
	/* @var $split \Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#add_split_modal" href="/export/split-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Split</a>
	</div>
   <h2>Splits</h2>
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
			</div>
		</form>
	</div>
	<div id="split-grid"></div>
	<div id="split-pager" class="panel-footer"></div>
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
 			ret_val += '<a href="/export/split?_id=' + dataContext._id + '">' + value + '</a>';
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
  		            ret_val += '<div class="small text-muted">' + moment.unix(dataContext.last_queue_time.sec).calendar(); + '</div>';
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
 		  	
	$('#split_search_form').trigger('submit');
});
//-->
</script>