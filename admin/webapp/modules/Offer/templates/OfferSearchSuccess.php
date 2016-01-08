<?php
	/* @var $offer \Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/offer/offer-search">Offers</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a id="save_search_btn" data-toggle="modal" data-target="#edit_saved_search_modal" href="/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ?>" class="btn btn-info">Save Search</a>
			<a href="/offer/offer-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Offer</a>
		</div>
	   <h1>Offers</h1>
	</div>
	<div class="help-block">These are all the offers in the system.  Choose one to change settings on it and view reports for it</div>
	<div class="panel panel-primary">
		<div id='offer-header' class='grid-header panel-heading clearfix'>
			<form id="offer_search_form" method="GET" class="form-inline" action="/api">
				<input type="hidden" name="func" value="/offer/offer">
				<input type="hidden" name="format" value="json" />
				<input type="hidden" id="page" name="page" value="1" />
				<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
				<input type="hidden" id="sort" name="sort" value="name" />
				<input type="hidden" id="sord" name="sord" value="asc" />
				
				<div class="text-right">
					<div class="form-group text-left">
						<select class="form-control selectize" name="vertical_id_array[]" id="vertical_id_array" multiple placeholder="Filter by vertical"></select>
					</div>
					<div class="form-group text-left">
						<select class="form-control selectize" name="client_id_array[]" id="client_id_array" multiple placeholder="Filter by client">
							<optgroup label="Administrators">
							<?php 
								/* @var $client \Flux\Client */
								foreach($clients as $client) { 
							?>
								<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
								<option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $offer->getClientIdArray()) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
								<?php } ?>
							<?php } ?>
							</optgroup>
							<optgroup label="Affiliates">
							<?php 
								/* @var $client \Flux\Client */
								foreach($clients as $client) { 
							?>
								<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
								<option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $offer->getClientIdArray()) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
								<?php } ?>
							<?php } ?>
							</optgroup>
						</select>
					</div>
					<div class="form-group text-left">
						<input type="text" class="form-control" placeholder="filter by name" size="35" id="txtSearch" name="name" value="<?php echo $offer->getName() ?>" />
					</div>
				</div>
			</form>
		</div>
		<div id="offer-grid"></div>
		<div id="offer-pager" class="panel-footer"></div>
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
		{id:'landing_pages', name:'&nbsp;', field:'landing_pages', sort_field:'traffic_source.name', def_value: ' ', maxWidth:64, width:64, minWidth:64, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '';
			$.each(value, function(i, item) {
				ret_val = '<div><img class="img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + encodeURIComponent(item.url) + '" width="48" border="0" /></div>';
				return;
			});
			return ret_val;
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
			ret_val += dataContext.client.name + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'payout', name:'payout', field:'payout', def_value: ' ', sortable:true, hidden:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '$' + $.number(value, 2);
		}},
		{id:'vertical', name:'vertical', field:'vertical.name', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<span class="badge alert-info">' + value + '</span>';
		}},
		{id:'daily_clicks', name:'clicks', field:'daily_clicks', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '0') {
				return '<span class="text-muted">' + $.number(value) + '</span>';
			} else {
				return '<a href="/lead/lead-search?required_fields[0]=_cr&offer_id_array[0]=' + dataContext._id + '">' + $.number(value) + '</a>';
			}
		}},
		{id:'daily_conversions', name:'conversions', field:'daily_conversions', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == '0') {
				return '<span class="text-muted">' + $.number(value) + '</span>';
			} else {
				return '<a href="/lead/lead-search?required_fields[0]=conv&offer_id_array[0]=' + dataContext._id + '">' + $.number(value) + '</a>';
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
  			$('#offer_search_form').trigger('submit');
  		}
  	});

	$('#vertical_id_array').selectize({
		valueField: '_id',
		dropdownWidthOffset: 200,
		allowEmptyOption: true,
		labelField: 'name',
		searchField: ['name','description'],
		options: [
			<?php foreach ($verticals as $vertical) { ?>
				<?php echo json_encode($vertical->toArray()) ?>,
			<?php } ?>	
		],
		render: {
			item: function(item, escape) {
				return '<div>' + escape(item.name) + '</div>';
			},
			option: function(item, escape) {
				var ret_val = '<div style="border-bottom: 1px dotted #C8C8C8;">' +
				'<b>' + escape(item.name) + '</b><br />' +
				(item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
				'</div>';
				return ret_val;
			}
		}
	}).on('change', function(e) {
		$('#offer_search_form').trigger('submit');
	});

	// Preload the verticals
	<?php foreach ($offer->getVerticalIdArray() as $vertical_id) { ?>
	$('#vertical_id_array').selectize()[0].selectize.addItem('<?php echo $vertical_id ?>');
	<?php } ?>
	
	$('#client_id_array').selectize({
		allowEmptyOption: true,
		dropdownWidthOffset: 200
	}).on('change', function(e) {
		$('#offer_search_form').trigger('submit');
	});

	$('#save_search_btn').on('click', function() {
		$(this).attr('href', '/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ?>&query_string=' + encodeURIComponent($('#offer_search_form').serialize()));
	});

	$('#offer_search_form').trigger('submit');
});
//-->
</script>