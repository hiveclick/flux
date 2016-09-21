<?php
	/* @var $rando \Flux\Rando */
	$rando = $this->getContext()->getRequest()->getAttribute("rando", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/rando-search">Random Data</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a class="btn btn-danger" id="flush-queue"><span class="fa fa-trash"></span> Flush Random Data</a>
			<a data-toggle="modal" data-target="#edit_rando_modal" href="/admin/rando-wizard" class="btn btn-success"><span class="fa fa-plus"></span> Import Data</a>
		</div>
	   <h1>Random Data</h1>
	</div>
	<div class="help-block">This is the random data are used to post comments on forums and blogs.  They are also used for pingbacks.</div>

	<div class="panel panel-primary">
		<div id='rando-header' class='grid-header panel-heading clearfix'>
			<form id="rando_search_form" method="GET" action="/admin/rando">
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
		<div id="rando-grid"></div>
		<div id="rando-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit domain group modal -->
<div class="modal fade" id="edit_rando_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'fname', name:'fname', field:'fname', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'lname', name:'lname', field:'lname', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'email', name:'email', field:'email', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'phone', name:'phone', field:'phone', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'city', name:'city', field:'city', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'state', name:'state', field:'state', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'zip', name:'zip', field:'zip', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'ssn', name:'ssn', field:'ssn', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}}
	];

  	slick_grid = $('#rando-grid').slickGrid({
		pager: $('#rando-pager'),
		form: $('#rando_search_form'),
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
   			$('#rando_search_form').trigger('submit');
   		}
   	});
		  	
	$('#rando_search_form').trigger('submit');

	$('#edit_rando_modal').on('hide.bs.modal', function(e) {
 		$(this).removeData('bs.modal');
	});

	$('#flush-queue').on('click', function() {
		if (confirm('This will remove everything from the random data table.  Are you sure you want to do this?')) {
			$.rad.post('/admin/rando-flush', { }, function() {
				$.rad.notify('Random Data Cleared', 'The items in the random data table have been removed.');
				$('#rando_search_form').trigger('submit');
			});
		}
	});
});
//-->
</script>