<?php
	/* @var $fulfillment \Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_fulfillment_modal" href="/admin/fulfillment-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Handler</a>
	</div>
	<h1>Fulfillment Handlers</h1>
</div>
<div class="help-block">Define how this clients can receive data through various feeds</div>
<div class="panel panel-primary">
    <div id='fulfillment-header' class='grid-header panel-heading clearfix'>
		<form id="fulfillment_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/admin/fulfillment">
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
	<div id="fulfillment-grid"></div>
	<div id="fulfillment-pager" class="panel-footer"></div>
</div>

<!-- edit fulfillment modal -->
<div class="modal fade" id="edit_fulfillment_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<!-- Map Custom Function modal -->
<div class="modal fade" id="map_options_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {	
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'account_user_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/admin/fulfillment?_id=' + dataContext._id + '">' + value + '</a>';
				ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
				ret_val += '</div>';
			return ret_val;
		}},
		{id:'client_name', name:'owner', field:'client.client_name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:90, width:90, minWidth:90, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Fulfillment::FULFILLMENT_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\Fulfillment::FULFILLMENT_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			} else if (value == '<?php echo \Flux\Fulfillment::FULFILLMENT_STATUS_DELETED ?>') {
				return '<span class="text-muted">Deleted</span>';
			}
		}},
		{id:'revenue', name:'payout', field:'bounty', def_value: ' ', cssClass: 'text-center', maxWidth:90, width:90, minWidth:90, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '$' + $.number(value, 2);
		}},
		{id:'export_type', name:'type', field:'export_type', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo json_encode(\Flux\Export\ExportAbstract::FULFILLMENT_TYPE_FTP) ?>') {
				var ret_val = '<div style="line-height:16pt;">'
					ret_val += 'FTP Fulfillment';
					ret_val += '<div class="small text-muted">' + dataContext.ftp_username + '@' + dataContext.ftp_hostname + '</div>';
					ret_val += '</div>';
				return ret_val;
			} else if (value == '<?php echo json_encode(\Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL) ?>') {
				var ret_val = '<div style="line-height:16pt;">'
					ret_val += 'Email Fulfillment';
					ret_val += '<div class="small text-muted">' + dataContext.email_address.join(', ') + '</div>';
					ret_val += '</div>';
				return ret_val;
			} else if (value == '<?php echo json_encode(\Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST) ?>') {
				var ret_val = '<div style="line-height:16pt;">'
					ret_val += 'Post URL';
					ret_val += '<div class="small text-muted">' + dataContext.post_url + '</div>';
					ret_val += '</div>';
				return ret_val;
			} else if (value == '<?php echo json_encode(\Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MULTI_POST) ?>') {
				var ret_val = '<div style="line-height:16pt;">'
					ret_val += 'Form Fill Post';
					ret_val += '<div class="small text-muted">' + dataContext.post_url + '</div>';
					ret_val += '</div>';
				return ret_val;
			} else if (value == '<?php echo json_encode(\Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT) ?>') {
				var ret_val = '<div style="line-height:16pt;">'
					ret_val += 'Infusionsoft API';
					ret_val += '<div class="small text-muted">' + dataContext.infusionsoft_host + '</div>';
					ret_val += '</div>';
				return ret_val;
			} else if (value == '<?php echo json_encode(\Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MANUAL) ?>') {
				var ret_val = '<div style="line-height:16pt;">'
					ret_val += 'Manual Fulfillment';
			        ret_val += '<div class="small text-muted">Leads will just be marked as fulfilled</div>';
					ret_val += '</div>';
				return ret_val;
			}
		}}
	];

	slick_grid = $('#fulfillment-grid').slickGrid({
		pager: $('#fulfillment-pager'),
		form: $('#fulfillment_search_form'),
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
			$('#fulfillment_search_form').trigger('submit');
		}
	});
       	
	$('#fulfillment_search_form').trigger('submit');

	$('#edit_fulfillment_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });

	$('#map_options_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
});
//-->
</script>