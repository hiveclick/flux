<?php
	/* @var $campaign \Flux\Campaign */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$traffic_sources = $this->getContext()->getRequest()->getAttribute("traffic_sources", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<div class="page-header">
	<div class="pull-right">
	    <a id="save_search_btn" data-toggle="modal" data-target="#edit_saved_search_modal" href="/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>" class="btn btn-info">Save Search</a>
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
						
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control selectize" name="client_id_array[]" id="client_id_array" multiple placeholder="Filter by client">
					    <optgroup label="Administrators">
						<?php
							/* @var $client \Flux\Client */ 
							foreach ($clients as $client) { 
						?>
                            <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
                                <option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $campaign->getClientIdArray()) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
                            <?php } ?>
						<?php } ?>
						</optgroup>
						<optgroup label="Affiliates">
						<?php
							/* @var $client \Flux\Client */ 
							foreach ($clients as $client) { 
						?>
                            <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
                                <option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $campaign->getClientIdArray()) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
                            <?php } ?>
						<?php } ?>
						</optgroup>
					</select>
				</div>
				<div class="form-group text-left">
					<select class="form-control selectize" name="traffic_source_id_array[]" id="traffic_source_id_array" multiple placeholder="Filter by traffic source"></select>
				</div>
				<div class="form-group text-left">
					<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="keywords" value="<?php echo $campaign->getKeywords() ?>" />
				</div>
			</div>
		</form>
	</div>
	<div id="campaign-grid"></div>
	<div id="campaign-pager" class="panel-footer"></div>
</div>

<!-- edit saved-search modal -->
<div class="modal fade" id="edit_saved_search_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'name', field:'_id', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/campaign/campaign?_id=' + dataContext._id + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += dataContext.description;
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'traffic_source', name:'&nbsp;', field:'traffic_source.traffic_source_icon', sort_field:'traffic_source.name', def_value: ' ', maxWidth:64, width:64, minWidth:64, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			
        	return '<img class="img-thumbnail" src="/images/traffic-sources/' + (value != null ? value : 'unknown') + '_48.png" width="32" border="0" />';
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
		{id:'payout', name:'payout', field:'payout', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value > 0) {
			    return '$' + $.number(value, 2);
			} else {
				var ret_val = '<div style="line-height:16pt;">'
					ret_val += '<i class="text-muted">$0.00</i>';
					ret_val += '<div class="small text-muted">Use offer default payout</div>';
					ret_val += '</div>';
					return ret_val;
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

 	slick_grid.slickGetDataView().onRowsChanged.subscribe(function() {
 		$('[data-toggle="tooltip"]').tooltip({
 			delay: { "show": 100, "hide": 10000 },
 			template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
 		});
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

    $('#client_id_array').selectize({
    	dropdownWidthOffset: 200,
		allowEmptyOption: true
    }).on('change', function(e) {
		$('#campaign_search_form').trigger('submit');
	});
	
	$('#offer_id_array').selectize({
    	valueField: '_id',
    	dropdownWidthOffset: 200,
		allowEmptyOption: true,
		labelField: 'name',
		searchField: ['name'],
		options: [
		    <?php foreach ($offers as $offer) { ?>
		    <?php echo json_encode($offer->toArray()) ?>,
		    <?php } ?>
		],
		optgroups: [
		    <?php foreach ($verticals as $vertical) { ?>
		    { label: '<?php echo $vertical->getName() ?>', value: '<?php echo $vertical->getName() ?>'},
            <?php } ?>
		],
		lockOptgroupOrder: true,
		render: {
			item: function(item, escape) {
				return '<div>' + escape(item.name) + '</div>';
			},
			option: function(item, escape) {
				var landing_page = item.landing_pages.shift();
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				if (landing_page != undefined) {
				    ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(landing_page.url) + '" width="64" border="0" />';
				} else {
					ret_val += '<img class="media-object img-thumbnail" src="/images/no_preview.png" width="64" border="0" />';
				}
				ret_val += '</div><div class="media-body">';
				ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
				ret_val += '<div class="text-muted small">' + (landing_page ? escape(landing_page.url) : '') + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		}
	}).on('change', function(e) {
		$('#campaign_search_form').trigger('submit');
	});

	// Preload the offers
	<?php foreach ($campaign->getOfferIdArray() as $offer_id) { ?>
    $('#offer_id_array').selectize()[0].selectize.addItem(<?php echo $offer_id ?>);
	<?php } ?>

	$('#traffic_source_id_array').selectize({
    	allowEmptyOption: true,
    	dropdownWidthOffset: 200,
    	valueField: '_id',
		labelField: 'name',
		searchField: ['name', 'description'],
		options: [
		    <?php foreach ($traffic_sources as $traffic_source) { ?>
		    <?php echo json_encode($traffic_source->toArray()) ?>,
		    <?php } ?>
		],
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

	// Preload the traffic sources
	<?php foreach ($campaign->getTrafficSourceIdArray() as $traffic_source_id) { ?>
    $('#traffic_source_id_array').selectize()[0].selectize.addItem(<?php echo $traffic_source_id ?>);
	<?php } ?>

    $('#save_search_btn').on('click', function() {
        $(this).attr('href', '/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>&query_string=' + encodeURIComponent($('#campaign_search_form').serialize()));
    });

    
});
//-->
</script>