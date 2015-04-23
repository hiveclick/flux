<?php
	/* @var $client \Flux\Client */
	$client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_client_modal" href="/client/client-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Client</a>
	</div>
   <h2>Clients</h2>
</div>
<div class="help-block">Clients are owners of exports, offers, and campaigns</div>
<div class="panel panel-primary">
    <div id='client-header' class='grid-header panel-heading clearfix'>
		<form id="client_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/client/client">
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
	<div id="client-grid"></div>
	<div id="client-pager" class="panel-footer"></div>
</div>

<!-- edit client modal -->
<div class="modal fade" id="edit_client_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'account_user_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a data-toggle="modal" data-target="#edit_client_modal" href="/client/client-wizard?_id=' + dataContext._id + '">' + value + '</a>';
		}},
		{id:'email', name:'email', field:'email', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a data-toggle="modal" data-target="#edit_client_modal" href="/client/client-wizard?_id=' + dataContext._id + '">' + value + '</a>';
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:68, width:68, minWidth:68, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Client::CLIENT_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\Client::CLIENT_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			} else if (value == '<?php echo \Flux\Client::CLIENT_STATUS_DELETED ?>') {
				return '<span class="text-muted">Deleted</span>';
			}
		}},
		{id:'client_type', name:'type', field:'client_type', def_value: ' ', cssClass: 'text-center', maxWidth:150, width:150, minWidth:150, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN ?>') {
				return '<span class="">Primary Admin</span>';
			} else if (value == '<?php echo \Flux\Client::CLIENT_TYPE_AFFILIATE ?>') {
				return '<span class="">Affiliate</span>';
			}
		}},
		{id:'actions', name:'actions', field:'actions', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			ret_val = '<a class="btn btn-sm btn-default" href="/admin/user-search?client_id_array[]=' + dataContext._id + '">Users</a> ';
			ret_val += '<a class="btn btn-sm btn-default" href="/offer/offer-search?client_id_array[]=' + dataContext._id + '">Offers</a> ';
			ret_val += '<a class="btn btn-sm btn-default" href="/campaign/campaign-search?client_id_array[]=' + dataContext._id + '">Campaigns</a> ';
			ret_val += '<a class="btn btn-sm btn-default" href="/admin/fulfillment-search?client_id_array[]=' + dataContext._id + '">Fulfillment</a>'
			return ret_val;
		}}
	];

	slick_grid = $('#client-grid').slickGrid({
		pager: $('#client-pager'),
	    form: $('#client_search_form'),
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
			$('#client_search_form').trigger('submit');
		}
	});
	
	$('#client_search_form').trigger('submit');

	$('#edit_client_modal').on('hide.bs.modal', function(e) {
  		$(this).removeData('bs.modal');
    });
});
//-->
</script>