<?php
	/* @var DomainGroup \Flux\DomainGroup */
	$domain_group = $this->getContext()->getRequest()->getAttribute("domain_group", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_domain_group_modal" href="/admin/domain-group-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Domain Group</a>
	</div>
   <h1>Domain Groups</h1>
</div>
<div class="help-block">Domain Groups allow you to categorize email addresses by domains</div>
<div class="panel panel-primary">
	<div id='domain_group-header' class='grid-header panel-heading clearfix'>
		<form id="domain_group_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/admin/domain_group">
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
	<div id="domain_group-grid"></div>
	<div id="domain_group-pager" class="panel-footer"></div>
</div>

<!-- edit domain group modal -->
<div class="modal fade" id="edit_domain_group_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a data-toggle="modal" data-target="#edit_domain_group_modal" href="/admin/domain_group-wizard?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'domains', name:'domains', field:'domains', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value.join(", ");
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', width:120, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\DomainGroup::DOMAIN_GROUP_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\DomainGroup::DOMAIN_GROUP_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			} else if (value == '<?php echo \Flux\DomainGroup::DOMAIN_GROUP_STATUS_DELETED ?>') {
				return '<span class="text-muted">Deleted</span>';
			}
		}}
	];

  	slick_grid = $('#domain_group-grid').slickGrid({
		pager: $('#domain_group-pager'),
		form: $('#domain_group_search_form'),
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
   			$('#domain_group_search_form').trigger('submit');
   		}
   	});
		  	
	$('#domain_group_search_form').trigger('submit');

	$('#edit_domain_group_modal').on('hide.bs.modal', function(e) {
 		$(this).removeData('bs.modal');
	});
});
//-->
</script>