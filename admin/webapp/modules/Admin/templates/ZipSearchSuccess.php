<?php
	/* @var $zip \Flux\Vertical */
	$zip = $this->getContext()->getRequest()->getAttribute("zip", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_zip_modal" href="/admin/zip-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Zipcode</a>
		<a data-toggle="modal" data-target="#zip_download_modal" href="/admin/zip-download" class="btn btn-info"><span class="glyphicon glyphicon-import"></span> Download Updates</a>
	</div>
	<h1>Zipcodes</h1>
</div>
<div class="help-block">This is an internal list of zipcodes, cities, and states that was downloaded from <code>http://download.geonames.org/export/zip/</code></div>
<div class="panel panel-primary">
	<div id='zip-header' class='grid-header panel-heading clearfix'>
		<form id="zip_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/admin/zip">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="pull-right">
				<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
			</div>
		</form>
	</div>
	<div id="zip-grid"></div>
	<div id="zip-pager" class="panel-footer"></div>
</div>

<!-- edit zip modal -->
<div class="modal fade" id="edit_zip_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<!-- edit zip download modal -->
<div class="modal fade" id="zip_download_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
        {id:'_id', name:'id', field:'_id', sort_field:'zipcode', def_value: ' ', cssClass: 'text-center', hidden: false, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
        	return '<a data-toggle="modal" data-target="#edit_zip_modal" href="/admin/zip-wizard?_id=' + value + '">' + value + '</a>';
        }},
        {id:'city', name:'City', field:'city', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
        	return value;
        }},
        {id:'state', name:'State', field:'state', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
        	return value;
        }},
		{id:'zipcode', name:'Zipcode', field:'zipcode', sort_field:'zipcode', def_value: ' ', cssClass: 'text-center', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a data-toggle="modal" data-target="#edit_zip_modal" href="/admin/zip-wizard?_id=' + dataContext._id + '">' + value + '</a>';
		}},
		{id:'county', name:'County', field:'county', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'community', name:'Community', field:'community', def_value: ' ', sortable:true, hidden: false, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'country', name:'Country', field:'country', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'latitude', name:'Latitude', field:'latitude', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'longitude', name:'Longitude', field:'longitude', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}}
	];

  	slick_grid = $('#zip-grid').slickGrid({
		pager: $('#zip-pager'),
		form: $('#zip_search_form'),
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
   			$('#zip_search_form').trigger('submit');
   		}
   	});
		  	
   	$('#zip_search_form').trigger('submit');
	
	$('#edit_zip_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
});
//-->
</script>