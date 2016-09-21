<?php
	/* @var $traffic_source \Flux\TrafficSource */
	$traffic_source = $this->getContext()->getRequest()->getAttribute("traffice_source", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/traffic-source-search">Traffic Sources</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#edit_traffic_source_modal" href="/admin/traffic-source-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Traffic Source</a>
		</div>
		<h1>Traffic Sources</h1>
	</div>
	<div class="help-block">Traffic sources categorize where incoming leads originate from.  Sample traffic sources are Google Adwords, Facebook, and TrafficVance</div>
	<br/>
	<div class="panel panel-primary">
		<div id='traffic-source-header' class='grid-header panel-heading clearfix'>
			<form id="traffic-source_search_form" method="GET" action="/admin/traffic-source">
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
		<div id="traffic-source-grid"></div>
		<div id="traffic-source-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit traffic-source modal -->
<div class="modal fade" id="edit_traffic_source_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'icon', name:'icon', field:'icon', def_value: ' ', sortable:true, minWidth: 64, width: 64, maxWidth: 64, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value != '') {
				var ret_val = '<div>'
					ret_val += '<img src="/images/traffic-sources/' + value + '_48.png" border="0" class="img-thumbnail" />';
					ret_val += '</div>';
					return ret_val;
			} else {
				var ret_val = '<div>'
					ret_val += '<img src="/images/traffic-sources/unknown_48.png" border="0" class="img-thumbnail" />';
					ret_val += '</div>';
					return ret_val;
			}
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a data-toggle="modal" data-target="#edit_traffic_source_modal" href="/admin/traffic-source-wizard?_id=' + dataContext._id + '">' + value + '</a>';
				ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'url', name:'url', field:'url', def_value: ' ', cssClass: 'text-center', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="' + value + '" target="_blank">' + value + '</a>';
				ret_val += '<div class="small text-muted">' + dataContext.username + ' / ' + dataContext.password + '</div>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'password', name:'password', field:'password', def_value: ' ', cssClass: 'text-center', hidden: true, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}}
	];

	slick_grid = $('#traffic-source-grid').slickGrid({
  		pager: $('#traffic-source-pager'),
  		form: $('#traffic-source_search_form'),
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
  			rowHeight: 66
  		}
  	});

	$("#txtSearch").keyup(function(e) {
  		// clear on Esc
  		if (e.which == 27) {
  			this.value = "";
  		} else if (e.which == 13) {
  			$('#traffic-source_search_form').trigger('submit');
		}
  	});
	
  	$('#traffic-source_search_form').trigger('submit');

	$('#edit_traffic_source_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
});
//-->
</script>