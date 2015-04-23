<?php
	/* @var $campaign \Flux\Campaign */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$traffic_sources = $this->getContext()->getRequest()->getAttribute("traffic_sources", array());
?>
<div class="page-header">
	<div class="pull-right">
		<a href="/campaign/campaign-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Campaign</a>
	</div>
	<h1>Campaigns</h1>
</div>
<div class="help-block">These are all the campaigns assigned to the clients and offers</div>
<div class="panel panel-primary">
	<div id='campaign-header' class='grid-header panel-heading clearfix'>
		<form id="campaign_search_form" class="form-inline" method="GET" action="/api">
			<input type="hidden" name="func" value="/campaign/campaign">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="text-right">
				<div class="form-group text-left">
					<select class="form-control selectize" name="offer_id_array[]" id="offer_id_array" multiple placeholder="Filter by offer">
						<?php 
							/* @var $offer \Flux\Offer */
							foreach($offers as $offer) { 
						?>
							<option value="<?php echo $offer->getId() ?>" <?php echo in_array($offer->getId(), $campaign->getOfferIdArray()) ? "selected" : "" ?>><?php echo $offer->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control selectize" name="client_id_array[]" id="client_id_array" multiple placeholder="Filter by client">
						<?php
							/* @var $client \Flux\Client */ 
							foreach ($clients as $client) { 
						?>
							<option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $campaign->getClientIdArray()) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control selectize" name="traffic_source_id_array[]" id="traffic_source_id_array" multiple placeholder="Filter by traffic source">
						<?php
							/* @var $traffic_source \Flux\TrafficSource */ 
							foreach ($traffic_sources as $traffic_source) { 
						?>
							<option value="<?php echo $traffic_source->getId() ?>" <?php echo in_array($traffic_source->getId(), $campaign->getTrafficSourceIdArray()) ? "selected" : "" ?> data-data="<?php echo htmlentities(json_encode(array('name' => $traffic_source->getName(), 'description' => $traffic_source->getDescription(), 'icon' => $traffic_source->getIcon(), 'username' => $traffic_source->getUsername()))) ?>"><?php echo $traffic_source->getName() ?></option>
						<?php } ?>
					</select>
				</div>
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
		{id:'traffic_source', name:'&nbsp;', field:'traffic_source.traffic_source_icon', sort_field:'traffic_source.name', def_value: ' ', maxWidth:64, width:64, minWidth:64, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
        	return '<img class="img-thumbnail" src="/images/traffic-sources/' + (value != '' ? value : 'unknown') + '_48.png" width="32" border="0" />';
        }},
		{id:'client_name', name:'client', field:'client.client_name', sort_field:'_id', def_value: ' ', hidden: true, sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a href="/client/client?_id=' + dataContext.client.client_id + '">' + value + '</a>';
		}},
		{id:'offer_name', name:'offer', field:'offer.offer_name', sort_field:'_id', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/offer/offer?_id=' + dataContext.offer.offer_id + '">' + value + '</a>';
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
				return $.number(value);
			}
		}},
		{id:'daily_conversions', name:'# conversions', field:'daily_conversions', sort_field:'daily_conversions', cssClass: 'text-center', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '0') {
				return '<span class="text-muted">' + $.number(value) + '</span>';
			} else {
				return $.number(value);
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
	
	$('#offer_id_array,#client_id_array').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	}).on('change', function(e) {
		$('#campaign_search_form').trigger('submit');
	});

	$('#traffic_source_id_array').selectize({
    	allowEmptyOption: true,
    	dropdownWidthOffset: 150,
    	valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		render: {
			
			option: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="/images/traffic-sources/' + escape(item.icon) + '_48.png" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h4 class="media-heading small">' + escape(item.name) + '</h4>';
				ret_val += '<div class="text-muted small">' + escape(item.description) + '</div>';
				ret_val += '<div class="text-muted small">(' + escape(item.username) + ')</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		}
    }).on('change', function(e) {
    	$('#campaign_search_form').trigger('submit');
    });
});
//-->
</script>