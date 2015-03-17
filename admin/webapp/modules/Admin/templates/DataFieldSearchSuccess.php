<?php
	/* @var $datafield \Flux\DataField */
	$datafield = $this->getContext()->getRequest()->getAttribute("datafield", array());
	$tags = $this->getContext()->getRequest()->getAttribute("tags", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_datafield_modal" href="/admin/data-field-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Data Field</a>
	</div>
	<h1>Data Fields</h1>
</div>
<div class="help-block">Data Fields set what data can be collected on the offer pages via request names</div>
<div class="panel panel-primary">
	<div id='datafield-header' class='grid-header panel-heading clearfix'>
		<form id="datafield_search_form" class="form-inline" method="GET" action="/api">
			<input type="hidden" name="func" value="/admin/data-field">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="text-right">
				<div class="form-group text-left">
					<select class="form-control" name="storage_type_array[]" id="storage_type_array" placeholder="only display selected storage types" multiple>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT ?>" <?php echo in_array(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT, $datafield->getStorageTypeArray()) ? 'selected' : '' ?>>Default</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT ?>" <?php echo in_array(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT, $datafield->getStorageTypeArray()) ? 'selected' : '' ?>>Events</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING ?>" <?php echo in_array(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING, $datafield->getStorageTypeArray()) ? 'selected' : '' ?>>Tracking</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED ?>" <?php echo in_array(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED, $datafield->getStorageTypeArray()) ? 'selected' : '' ?>>Derived</option>
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control" name="tags[]" id="tags" placeholder="only display selected tags" multiple></select>
				</div>
				<div class="form-group text-left">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
				</div>				
			</div>
		</form>
	</div>
	<div id="datafield-grid"></div>
	<div id="datafield-pager" class="panel-footer"></div>
</div>

<!-- edit datafield modal -->
<div class="modal fade" id="edit_datafield_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a data-toggle="modal" data-target="#edit_datafield_modal" href="/admin/data-field-wizard?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'field_type', name:'field type', field:'field_type', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\DataField::DATA_FIELD_TYPE_STRING ?>') {
				return '<span class="text-muted">String</span>';
			} else if (value == '<?php echo \Flux\DataField::DATA_FIELD_TYPE_ARRAY ?>') {
				return '<span class="text-muted">Array[]</span>';
			} else if (value == '<?php echo \Flux\DataField::DATA_FIELD_TYPE_OBJECT ?>') {
				return '<span class="text-muted">Object</span>';
			} else {
				return '<span class="text-muted">' + value + '</span>';
			}
		}},
		{id:'storage_type', name:'storage', field:'storage_type', def_value: ' ', hidden: true, sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT ?>') {
				return '<span class="text-muted">Default</span>';
			} else if (value == '<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT ?>') {
				return '<span class="text-muted">Event</span>';
			} else if (value == '<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING ?>') {
				return '<span class="text-muted">Tracking</span>';
			} else if (value == '<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED ?>') {
				return '<span class="text-muted">Derived</span>';
			}
		}},
		{id:'tags', name:'tags', field:'tags', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var cell_html = '';
			if (value instanceof Array) {
				$.each(value, function(i,item) {
					cell_html += '<span class="badge alert-info">' + item + '</span> ';
				});
			}
			return cell_html;
		}},
		{id:'key_name', name:'key', field:'key_name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<i class="badge alert-success">' + value + '</i>';
		}},
		{id:'request_name', name:'request names', field:'request_name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var cell_html = '';
			if (value instanceof Array) {
				$.each(value, function(i,item) {
					cell_html += '<span class="badge alert-warning">' + item + '</span> ';
				});
			}
			return cell_html;
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:120, width:120, minWidth:120, hidden:true, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\DataField::DATA_FIELD_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\DataField::DATA_FIELD_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			} else if (value == '<?php echo \Flux\DataField::DATA_FIELD_STATUS_DELETED ?>') {
				return '<span class="text-muted">Deleted</span>';
			}
		}}
	];

 	slick_grid = $('#datafield-grid').slickGrid({
		pager: $('#datafield-pager'),
		form: $('#datafield_search_form'),
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
  			$('#datafield_search_form').trigger('submit');
  		}
  	});
		  	
  	$('#datafield_search_form').trigger('submit');

	$('#storage_type_array').selectize();

	$('#tags').selectize({
	    delimiter: ',',
	    persist: true,
	    searchField: ['name'],
	    valueField: 'name',
	    labelField: 'name',
	    options: [
			{ name: "<?php echo implode('"}, {name: "', $tags) ?>" }
		],
	    create: function(input) {
	        return {name: input}
	    }
	});

	$('#edit_datafield_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
});
//-->
</script>