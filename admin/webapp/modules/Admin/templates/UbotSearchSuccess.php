<?php
	/* @var $ubot \Flux\Ubot */
	$ubot = $this->getContext()->getRequest()->getAttribute("ubot", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/admin/comment-search">Ubot Scripts</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a data-toggle="modal" data-target="#edit_ubot_modal" href="/admin/ubot-wizard" class="btn btn-success"><span class="fa fa-plus"></span> Add New Ubot Script</a>
		</div>
	   <h1>Ubot Scripts</h1>
	</div>
	<div class="help-block">These ubot scripts are used to automate comment posting and forum posts.</div>
	<div class="panel panel-primary">
		<div id='ubot-header' class='grid-header panel-heading clearfix'>
			<form id="ubot_search_form" method="GET" action="/admin/ubot">
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
		<div id="ubot-grid"></div>
		<div id="ubot-pager" class="panel-footer"></div>
	</div>
</div>

<!-- edit domain group modal -->
<div class="modal fade" id="edit_ubot_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			ret_val = '<div style="line-height:16pt;">';
			ret_val += '<a data-toggle="modal" data-target="#edit_ubot_modal" href="/admin/ubot-wizard?_id=' + dataContext._id + '">' + value + '</a>';
			if (dataContext.type == '<?php echo \Flux\Ubot::TYPE_COMMENT ?>') {
				ret_val += '<div class="small text-muted">Comment Script</div>';
			} else if (dataContext.type == '<?php echo \Flux\Ubot::TYPE_PINGOMATIC ?>') {
				ret_val += '<div class="small text-muted">Pingomatic Script</div>';
			}
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'username', name:'username', field:'username', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'password', name:'password', field:'password', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'script_filename', name:'script', field:'script_filename', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'urls', name:'urls', field:'urls', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value.length + ' urls';
		}},
		{id:'active', name:'active', field:'active', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
				return '<span class="text-success">Yes</span>';
			} else {
				return '<span class="text-muted">No</span>';
			}
		}},
		{id:'use_hma', name:'hma proxy', field:'use_hma', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
				return '<span class="text-success">Yes</span>';
			} else {
				return '<span class="text-muted">No</span>';
			}
		}},
		{id:'type', name:'type', field:'type', def_value: ' ', sortable:true, hidden: true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Ubot::TYPE_COMMENT ?>') {
				return 'Comment Script';
			} else if (value == '<?php echo \Flux\Ubot::TYPE_PINGOMATIC ?>') {
				return 'Pingomatic Script';
			}
			return value;
		}}
	];

  	slick_grid = $('#ubot-grid').slickGrid({
		pager: $('#ubot-pager'),
		form: $('#ubot_search_form'),
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
   			$('#ubot_search_form').trigger('submit');
   		}
   	});
		  	
	$('#ubot_search_form').trigger('submit');

	$('#edit_ubot_modal').on('hide.bs.modal', function(e) {
 		$(this).removeData('bs.modal');
	});
});
//-->
</script>