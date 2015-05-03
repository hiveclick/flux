<?php
	/* @var $user \Flux\User */
	$user = $this->getContext()->getRequest()->getAttribute("user", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_user_modal" href="/admin/user-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New User</a>
	</div>
	<h1>Users</h1>
</div>
<div class="help-block">Users have access to log into the system and make changes</div>
<br/>
<div class="panel panel-primary">
    <div id='user-header' class='grid-header panel-heading clearfix'>
		<form id="user_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/admin/user">
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
	<div id="user-grid"></div>
	<div id="user-pager" class="panel-footer"></div>
</div>

<!-- edit user modal -->
<div class="modal fade" id="edit_user_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'account_user_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'thumbnail', name:'', field:'thumbnail', sort_field:'account_user_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a data-toggle="modal" data-target="#edit_user_modal" href="/admin/user-wizard?_id=' + dataContext._id + '">' + value + '</a>';
		}},
		{id:'email', name:'email', field:'email', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a data-toggle="modal" data-target="#edit_user_modal" href="/admin/user-wizard?_id=' + dataContext._id + '">' + value + '</a>';
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:68, width:68, minWidth:68, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\User::USER_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\User::USER_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			} else if (value == '<?php echo \Flux\User::USER_STATUS_DELETED ?>') {
				return '<span class="text-muted">Deleted</span>';
			}
		}},
		{id:'user_type', name:'type', field:'user_type', def_value: ' ', cssClass: 'text-center', maxWidth:90, width:90, minWidth:90, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\User::USER_TYPE_ADMIN ?>') {
				return '<span class="">Administrator</span>';
			} else if (value == '<?php echo \Flux\User::USER_TYPE_DATA_ENTRY ?>') {
				return '<span class="">Data Entry</span>';
			}
		}},
		{id:'_client_name', name:'client', field:'client.client_name', def_value: ' ', cssClass: 'text-center', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'timezone', name:'timezone', field:'timezone', def_value: ' ', cssClass: 'text-center', hidden: true, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'password', name:'password', field:'password', def_value: ' ', cssClass: 'text-center', hidden: true, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}}
	];

	slick_grid = $('#user-grid').slickGrid({
  		pager: $('#user-pager'),
  	    form: $('#user_search_form'),
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
  			rowHeight: 68
  		}
  	});

	$("#txtSearch").keyup(function(e) {
  		// clear on Esc
  		if (e.which == 27) {
  			this.value = "";
  		} else if (e.which == 13) {
  			$('#user_search_form').trigger('submit');
		}
  	});
	
  	$('#user_search_form').trigger('submit');

	$('#edit_user_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
});
//-->
</script>