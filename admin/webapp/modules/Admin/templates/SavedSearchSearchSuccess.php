<?php
	/* @var $saved_search \Flux\SavedSearch */
	$saved_search = $this->getContext()->getRequest()->getAttribute("saved_search", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/saved-search-search">Saved Searches</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#edit_saved_search_modal" href="/admin/saved-search-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Saved Search</a>
		</div>
	   <h1>Saved Searches</h1>
	</div>
	<div class="help-block">You can save custom searches here and use them throughout the system.</div>
	<div class="panel panel-primary">
		<div id='saved-search-header' class='grid-header panel-heading clearfix'>
			<form id="saved-search_search_form" method="GET" action="/admin/saved-search">
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
		<div id="saved-search-grid"></div>
		<div id="saved-search-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit saved-search modal -->
<div class="modal fade" id="edit_saved_search_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
 		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
 			return value;
 		}},
 		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			return '<a data-toggle="modal" data-target="#edit_saved_search_modal" href="/admin/saved-search-wizard?_id=' + dataContext._id + '">' + value + '</a>';
 		}},
 		{id:'search_type', name:'type', field:'search_type', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 			if (value == '<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>') {
 				return 'Lead';
 			} else if (value == '<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ?>') {
 				return 'Offer';
 			} else if (value == '<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>') {
 				return 'Campaign';
 	 		} else {
 				return '<i class="text-muted">Unknown Type (' + value + ')</i>'
 			}
 		}},
 		{id:'owner', name:'owner', field:'user.user_name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
 	 		if (dataContext.is_global == '1') {
 	 			var ret_val = '<div style="line-height:16pt;">'
 	 	 			ret_val += value;
 	 	 			ret_val += '<div class="small text-muted">This is a shared search and will be shown to everyone</div>';
 	 	 			ret_val += '</div>';
 	 	 			return ret_val;
 	 		} else { 
 	 			return value;
 	 		}
 		}}
 	];

   	slick_grid = $('#saved-search-grid').slickGrid({
 		pager: $('#saved-search-pager'),
 		form: $('#saved-search_search_form'),
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
			$('#saved-search_search_form').trigger('submit');
		}
	});
   	
	$('#saved-search_search_form').trigger('submit');

	$('#edit_saved_search_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
});
//-->
</script>