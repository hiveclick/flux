<?php
	/* @var $server \Flux\Server */
	$server = $this->getContext()->getRequest()->getAttribute("server", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/server-search">Servers</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#edit_server_modal" href="/admin/server-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Server</a>
		</div>
	   <h1>Servers</h1>
	</div>
	<div class="help-block">Servers host offers and paths.  A server is used when deploying a new offer</div>
	<div class="panel panel-primary">
		<div id='server-header' class='grid-header panel-heading clearfix'>
			<form id="server_search_form" method="GET" action="/admin/server">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				<div class="pull-right">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="hostname" value="" />
				</div>
			</form>
		</div>
		<div id="server-grid"></div>
		<div id="server-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit server modal -->
<div class="modal fade" id="edit_server_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<!-- server explorer modal -->
<div class="modal fade" id="server_explorer_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
 		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'hostname', name:'hostname', field:'hostname', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return '<a data-toggle="modal" data-target="#edit_server_modal" href="/admin/server-wizard?_id=' + dataContext._id + '">' + value + '</a>';
 		}},
 		{id:'ip_address', name:'ip', field:'ip_address', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return '<a data-toggle="modal" data-target="#edit_server_modal" href="/admin/server-wizard?_id=' + dataContext._id + '">' + value + '</a>';
 		}},
 		{id:'root_username', name:'username', field:'root_username', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'root_password', name:'password', field:'root_password', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'ftp_username', name:'ftp username', field:'ftp_username', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'ftp_password', name:'ftp password', field:'ftp_password', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'mysql_username', name:'mysql username', field:'mysql_username', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'mysql_password', name:'mysql password', field:'mysql_password', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:120, width:120, minWidth:120, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			if (value == '<?php echo \Flux\Server::SERVER_STATUS_ACTIVE ?>') {
 				return '<span class="text-success">Active</span>';
 			} else if (value == '<?php echo \Flux\Server::SERVER_STATUS_INACTIVE ?>') {
 				return '<span class="text-danger">Inactive</span>';
 			} else if (value == '<?php echo \Flux\Server::SERVER_STATUS_DELETED ?>') {
 				return '<span class="text-muted">Deleted</span>';
 			}
 		}},
 		{id:'fluxfe_lib_dir', name:'lib folder', field:'fluxfe_lib_dir', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'docroot_dir', name:'docroot', field:'docroot_dir', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'web_user', name:'user', field:'web_user', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'web_group', name:'group', field:'web_group', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'browse', name:'&nbsp;', field:'browse', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			ret_val = '<a class="btn btn-sm btn-default" data-toggle="modal" data-target="#server_explorer_modal" href="/admin/server-explorer-form?_id=' + dataContext._id + '">Browse</a> ';
 			return ret_val;
 		}}
 	];

   	slick_grid = $('#server-grid').slickGrid({
 		pager: $('#server-pager'),
 		form: $('#server_search_form'),
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
 			rowHeight: 68
 		}
 	});

   	$("#txtSearch").keyup(function(e) {
		// clear on Esc
		if (e.which == 27) {
			this.value = "";
		} else if (e.which == 13) {
			$('#server_search_form').trigger('submit');
		}
	});
   	
	$('#server_search_form').trigger('submit');

	$('#edit_server_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
});
//-->
</script>