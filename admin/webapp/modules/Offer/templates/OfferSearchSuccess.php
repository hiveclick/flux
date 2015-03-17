<?php
	/* @var $offer \Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a href="/offer/offer-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Offer</a>
	</div>
   <h1>Offers</h1>
</div>
<div class="help-block">These are all the offers in the system.  Choose one to change settings on it and view reports for it</div>
<div class="panel panel-primary">
	<div id='offer-header' class='grid-header panel-heading clearfix'>
		<form id="offer_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/offer/offer">
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
	<div id="offer-grid"></div>
	<div id="offer-pager" class="panel-footer"></div>
</div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', hidden:true, formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'name', name:'name', field:'name', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/offer/offer?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			if (dataContext.status == '<?php echo \Flux\Offer::OFFER_STATUS_ACTIVE ?>') {
				ret_val += ' <span class="label label-success">active</span> ';
			} else if (dataContext.status == '<?php echo \Flux\Offer::OFFER_STATUS_INACTIVE ?>') {
				ret_val += ' <span class="label label-danger">inactive</span> ';
			}
			ret_val += dataContext.client.client_name + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'payout', name:'payout', field:'payout', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '$' + $.number(value, 2);
		}},
		{id:'verticals', name:'verticals', field:'verticals', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var cell_html = '';
			if (value instanceof Array) {
				$.each(value, function(i,item) {
					cell_html += '<span class="badge alert-info">' + item + '</span> ';
				});
			}
			return cell_html;
		}},
		{id:'clicks', name:'clicks', field:'clicks', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '0') {
				return '<span class="text-muted">' + $.number(value) + '</span>';
			} else {
				return $.number(value);
			}
		}},
		{id:'conversions', name:'conversions', field:'conversions', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '0') {
				return '<span class="text-muted">' + $.number(value) + '</span>';
			} else {
				return $.number(value);
			}
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:120, width:120, minWidth:120, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Offer::OFFER_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\Offer::OFFER_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			} else if (value == '<?php echo \Flux\Offer::OFFER_STATUS_DELETED ?>') {
				return '<span class="text-muted">Deleted</span>';
			}
		}}
	];

 	slick_grid = $('#offer-grid').slickGrid({
		pager: $('#offer-pager'),
		form: $('#offer_search_form'),
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
  			$('#offer_search_form').trigger('submit');
  		}
  	});
  	
  	$('#offer_search_form').trigger('submit');
	
	$('#status_array,#client_id_array').selectize();
});
//-->
</script>