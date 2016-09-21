<?php
	/* @var $daemon \Flux\Daemon */
	$daemon = $this->getContext()->getRequest()->getAttribute("daemon", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/daemon-search">Daemons</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#edit_daemon_modal" href="/admin/daemon-wizard" class="btn btn-success"><span class="fa fa-plus"></span> Add New Daemon</a>
		</div>
	   <h1>Daemons</h1>
	</div>
	<div class="help-block">Daemons are scripts that run constantly in the background checking leads, splits and exports</div>
	<div class="panel panel-primary">
		<div id='daemon-header' class='grid-header panel-heading clearfix'>
			<form id="daemon_search_form" method="GET" action="/admin/daemon">
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
		<div id="daemon-grid"></div>
		<div id="daemon-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit daemon modal -->
<div class="modal fade" id="edit_daemon_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a data-toggle="modal" data-target="#edit_daemon_modal" href="/admin/daemon-wizard?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'type', name:'type', field:'type', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'class_name', name:'class', field:'class_name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'threads', name:'# threads', field:'threads', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'pid', name:'pid', field:'pid', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:120, width:120, minWidth:120, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Daemon::DAEMON_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\Daemon::DAEMON_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			}
		}},
		{id:'start_time', name:'last started', field:'start_time', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value !== null && value.sec) {		
				return moment.unix(value.sec).calendar();
			}
			return "";
		}}
	];

  	slick_grid = $('#daemon-grid').slickGrid({
		pager: $('#daemon-pager'),
		form: $('#daemon_search_form'),
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
   			$('#daemon_search_form').trigger('submit');
   		}
   	});
	  	
   	$('#daemon_search_form').trigger('submit');

	$('#edit_daemon_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
});
//-->
</script>