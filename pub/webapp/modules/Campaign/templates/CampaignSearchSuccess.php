<?php
	/* @var $campaign \Flux\Campaign */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$traffic_sources = $this->getContext()->getRequest()->getAttribute("traffic_sources", array());
?>
<div class="page-header">
	<h1>Your Campaigns</h1>
</div>
<div class="help-block">These are all the campaigns assigned to your account.  Each campaign has posting instructions that you can use to submit leads into the system.</div>
<div class="panel panel-primary">
	<div id='campaign-header' class='grid-header panel-heading clearfix'>
		<form id="campaign_search_form" class="form-inline" method="GET" action="/api">
			<input type="hidden" name="func" value="/campaign/campaign">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="client_id_array" name="client_id_array[]" value="<?php echo $this->getContext()->getUser()->getUserDetails()->getClient()->getClientId() ?>" />
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="text-right">
				<div class="form-group text-left">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="" />
				</div>
			</div>
		</form>
	</div>
	<div id="campaign-grid"></div>
	<div id="campaign-pager" class="panel-footer"></div>
</div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'name', field:'_id', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/campaign/campaign?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">' + dataContext.description + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'client_name', name:'client', field:'client.client_name', sort_field:'_id', def_value: ' ', hidden: true, sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'offer_name', name:'offer', field:'offer.offer_name', sort_field:'_id', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += value;
				ret_val += '<div class="small text-muted">' + dataContext.client.client_name + '</div>';
				ret_val += '</div>';
				return ret_val;
		}},
		{id:'status', name:'status', field:'status', def_value: ' ', cssClass: 'text-center', maxWidth:120, width:120, minWidth:120, sortable:false, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '<?php echo \Flux\Campaign::CAMPAIGN_STATUS_ACTIVE ?>') {
				return '<span class="text-success">Active</span>';
			} else if (value == '<?php echo \Flux\Campaign::CAMPAIGN_STATUS_INACTIVE ?>') {
				return '<span class="text-danger">Inactive</span>';
			} else if (value == '<?php echo \Flux\Campaign::CAMPAIGN_STATUS_DELETED ?>') {
				return '<span class="text-muted">Deleted</span>';
			}
		}},
		{id:'daily_clicks', name:'# clicks', field:'daily_clicks', sort_field:'daily_clicks', cssClass: 'text-center', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '0') {
				return '<span class="text-muted">' + $.number(value) + '</span>';
			} else {
				return '<a href="/campaign/campaign-leads?_id=' + dataContext._id + '&start_date=<?php echo date('m/d/Y') ?>">' + $.number(value) + '</a>';
			}
		}},
		{id:'daily_conversions', name:'# conversions', field:'daily_conversions', sort_field:'daily_conversions', cssClass: 'text-center', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '0') {
				return '<span class="text-muted">' + $.number(value) + '</span>';
			} else {
				return '<a href="/campaign/campaign-leads?_id=' + dataContext._id + '&start_date=<?php echo date('m/d/Y') ?>&required_fields[]=<?php echo \Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME ?>">' + $.number(value) + '</a>';
			}
		}}
	];
	
 	slick_grid = $('#campaign-grid').slickGrid({
		pager: $('#campaign-pager'),
		form: $('#campaign_search_form'),
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
  			$('#campaign_search_form').trigger('submit');
  		}
  	});
	  	
  	$('#campaign_search_form').trigger('submit');
});
//-->
</script>