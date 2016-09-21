<?php
	/* @var $pingback_keyword_queue \Flux\PingbackKeywordQueue */
	$pingback_keyword_queue = $this->getContext()->getRequest()->getAttribute("pingback_keyword_queue", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/tools/pingback-keyword-queue-search">Tools</a></li>
	<li><a href="/tools/pingback-keyword-queue-search">Pingback Keyword Queue</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a class="btn btn-danger" id="flush-queue"><span class="fa fa-trash"></span> Flush Queue</a>
			<a data-toggle="modal" data-target="#generate_pingback_keyword_modal" href="/tools/generate-pingback-keywords-wizard" class="btn btn-success"><span class="fa fa-plus"></span> Generate New Keywords</a>
		</div>
		<h1>Pingback Keyword Queue</h1>
	</div>
	<div class="help-block">Below is the list of keywords that are pending pingbacks.  As each one is processed, it will be updated with the results</div>
	<div class="panel panel-primary">
		<div id='pingback_keyword-header' class='grid-header panel-heading clearfix'>
			<form id="pingback_keyword_search_form" method="GET" action="/admin/pingback-keyword-queue">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				<div class="pull-right">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="name" value="" />
				</div>
			</form>
		</div>
		<div id="pingback_keyword-grid"></div>
		<div id="pingback_keyword-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit vertical modal -->
<div class="modal fade" id="generate_pingback_keyword_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'keyword', name:'keyword', field:'keyword', def_value: ' ', sortable:true, cssClass:'text-center', width:90,  type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'url', name:'url', field:'url', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'pingback', name:'pingback', field:'pingback', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			if (value && value.name) {
				ret_val += value.name;
				ret_val += '<div><a href="' + value.url + '">' + value.url + '</a></div>';
			} else {
				ret_val += '<i class="text-muted">Unknown</i>';
			}
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'is_error', name:'error', field:'is_error', def_value: ' ', sortable:true, width:90, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
				return '<i class="text-danger">Yes</i>';
			} else {
				return '<i class="text-muted">No</i>';
			}
		}},
		{id:'error_message', name:'error message', field:'error_message', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'is_processed', name:'processed', field:'is_processed', def_value: ' ', width:90, cssClass: 'text-center', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			if (value) {
				ret_val += '<i class="text-success">Yes</i>';
			} else {
				ret_val += '<i class="text-muted">No</i>';
				ret_val += '<div class="text-muted small">Next Attempt: ' + moment.unix(dataContext.next_pingback_time.sec).calendar() + '</div>';
			}
			ret_val += '</div>';
			return ret_val;
		}}
	];

  	slick_grid = $('#pingback_keyword-grid').slickGrid({
		pager: $('#pingback_keyword-pager'),
		form: $('#pingback_keyword_search_form'),
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
   			$('#pingback_keyword_search_form').trigger('submit');
   		}
   	});
		  	
   	$('#pingback_keyword_search_form').trigger('submit');
	
	$('#edit_pingback_keyword_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#flush-queue').on('click', function() {
		if (confirm('This will remove everything from the queue.  Are you sure you want to do this?')) {
			$.rad.post('/admin/pingback-keyword-queue-flush', { }, function() {
				$.rad.notify('Queue Flushed', 'The items in the queue have been removed.');
				$('#pingback_keyword_search_form').trigger('submit');
			});
		}
	});
});
//-->
</script>