<?php
	/* @var $pingback \Flux\Pingback */
	$pingback = $this->getContext()->getRequest()->getAttribute("pingback", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/pingback-search">Pingback URLs</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#edit_pingback_modal" href="/admin/pingback-wizard" class="btn btn-success"><span class="fa fa-plus"></span> Add New Pingback</a>
		</div>
	   <h1>Pingbacks</h1>
	</div>
	<div class="help-block">Pingbacks are used to generate backlinks which increases your SEO score on your domain</div>
	<div class="panel panel-primary">
		<div id='pingback-header' class='grid-header panel-heading clearfix'>
			<form id="pingback_search_form" method="GET" action="/admin/pingback">
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
		<div id="pingback-grid"></div>
		<div id="pingback-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit domain group modal -->
<div class="modal fade" id="edit_pingback_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a data-toggle="modal" data-target="#edit_pingback_modal" href="/admin/pingback-wizard?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'url', name:'url', field:'url', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'rpc_url', name:'rpc url', field:'rpc_url', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'active', name:'active', field:'active', def_value: ' ', cssClass: 'text-center', width:120, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
				return '<span class="text-success">Active</span>';
			} else {
				return '<span class="text-danger">Inactive</span>';
			}
		}}
	];

  	slick_grid = $('#pingback-grid').slickGrid({
		pager: $('#pingback-pager'),
		form: $('#pingback_search_form'),
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
   			$('#pingback_search_form').trigger('submit');
   		}
   	});
		  	
	$('#pingback_search_form').trigger('submit');

	$('#edit_pingback_modal').on('hide.bs.modal', function(e) {
 		$(this).removeData('bs.modal');
	});
});
//-->
</script>