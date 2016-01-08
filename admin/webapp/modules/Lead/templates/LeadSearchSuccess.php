<?php
	/* @var $lead_search \Flux\Lead */
	$lead_search = $this->getContext()->getRequest()->getAttribute("lead_search", array());
	
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<link href="/scripts/datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="/scripts/datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/lead/lead-search">Leads</a></li>
</ol>

<!-- Page Content -->
<div class="container-fluid">
	<div class="page-header">
		<div class="pull-right">
			<a id="save_search_btn" data-toggle="modal" data-target="#edit_saved_search_modal" href="/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>" class="btn btn-info">Save Search</a>
			<?php if ($this->getContext()->getUser()->getUserDetails()->getUserType() == \Flux\User::USER_TYPE_ADMIN) { ?>
			<a data-toggle="modal" data-target="#export_lead_modal" href="/lead/lead-export-wizard" class="btn btn-success"><span class="glyphicon glyphicon-export"></span> Export Leads</a>
			<?php } ?>
		</div>
	   <h1>Search Leads</h1>
	</div>
	<div class="help-block">These are all the leads in the system.  Search by offer, campaign, lead name or id.</div>
	<br/>
	<form id="lead_search_form" method="GET" action="/api">
		<input type="hidden" name="func" value="/lead/lead-search">
		<input type="hidden" name="format" value="json" />
		<input type="hidden" id="page" name="page" value="1" />
		<input type="hidden" id="items_per_page" name="items_per_page" value="100" />
		<input type="hidden" id="sort" name="sort" value="_id" />
		<input type="hidden" id="sord" name="sord" value="desc" />
		<div>
			<ul class="nav nav-tabs nav-tabs-box" role="tablist">
				<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Basic Search</a></li>
				<li role="presentation"><a href="#campaigns" aria-controls="campaigns" role="tab" data-toggle="tab">Campaign Options</a></li>
				<li role="presentation"><a href="#date" aria-controls="date" role="tab" data-toggle="tab">Date Options</a></li>
				<li role="presentation"><a href="#advanced" aria-controls="advanced" role="tab" data-toggle="tab">Advanced Options</a></li>
			</ul>
	
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane tab-pane-box active" id="home">
					<div class="row">
						<div class="form-group text-left col-md-4">
							<label>Name or Lead #: </label>
							<div class="input-group">
								<span class="input-group-addon">like</span>
								<input type="text" class="form-control" placeholder="search by name, email or lead #" size="35" id="txtSearch" name="keywords" value="<?php echo $lead_search->getKeywords() ?>" />
							</div>
						</div>
						<div class="form-group text-left col-md-4">
							<label>Recent Conversions: </label>
							<div class="input-group">
								<span class="input-group-addon">since</span>
								<select name="conversion_date_range" id="conversion_date_range" placeholder="only show leads that have converted since...">
									<option value="1">Today</option>
									<option value="2">Yesterday</option>
									<option value="3">Today &amp; Yesterday</option>
									<option value="4">Past 7 Days</option>
									<option value="5">Past Month</option>
									<option value="6">Past 3 Months</option>
									<option value="0">All Time</option>
								</select>
							</div>
						</div>
						<div class="form-group text-left col-md-4">
							<label>Offer: </label>
							<div class="input-group">
								<span class="input-group-addon">in</span>
								<select class="selectize" name="offer_id_array[]" id="offer_id_array" multiple placeholder="Filter by offer..."></select>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane tab-pane-box" id="campaigns">
					<div class="row">
						<div class="form-group text-left col-md-12">
							<label>Campaign: </label>
							<div class="input-group">
								<span class="input-group-addon">in</span>
								<select class="selectize" name="campaign_id_array[]" id="campaign_id_array" multiple placeholder="Filter by campaign..."></select>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane tab-pane-box" id="date">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="created_date_start">Created Date:</label>
								<div class="input-group">
									<span class="input-group-addon">between</span>
									<input type="text" class="form-control" id="created_start_date" name="created_start_date" placeholder="start date" value="">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="created_end_date" name="created_end_date" placeholder="end date" value="">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="click_date_start">Click Date:</label>
								<div class="input-group">
									<span class="input-group-addon">between</span>
									<input type="text" class="form-control" id="click_start_date" name="click_start_date" placeholder="start date" value="">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="click_end_date" name="click_end_date" placeholder="end date" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="conversion_date_start">Conversion Date:</label>
								<div class="input-group">
									<span class="input-group-addon">between</span>
									<input type="text" class="form-control" id="conversion_start_date" name="conversion_start_date" placeholder="start date" value="">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="conversion_end_date" name="conversion_end_date" placeholder="end date" value="">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="fulfillment_date_start">Fulfillment Date:</label>
								<div class="input-group">
									<span class="input-group-addon">between</span>
									<input type="text" class="form-control" id="fulfillment_start_date" name="fulfillment_start_date" placeholder="start date" value="">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="fulfillment_end_date" name="fulfillment_end_date" placeholder="end date" value="">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane tab-pane-box" id="advanced">
					<div class="row">
						<div class="form-group col-md-12">
							<label>Required Fields: </label>
							<div class="input-group">
								<span class="input-group-addon">in</span>
								<select class="selectize" name="required_fields[]" id="required_fields" multiple placeholder="Add required fields..."></select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<p />
		<div class="text-center">
			<input type="submit" value="search leads" class="btn btn-primary" />
		</div>
		<p />
	</form>
	<div class="panel panel-primary">
		<div id='lead-header' class='grid-header panel-heading clearfix'>&nbsp;</div>
		<div id="lead-grid"></div>
		<div id="lead-pager" class="panel-footer"></div>
	</div>
</div>

<!-- Add data field modal -->
<div class="modal fade" id="export_lead_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- edit saved-search modal -->
<div class="modal fade" id="edit_saved_search_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {

	var columns = [
		{id:'_id', name:'Lead #', field:'_id', sort_field:'_id', def_value: ' ', width:175, sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/lead/lead?_id=' + value + '">' + value + '</a>';
			ret_val += '<div class="small text-muted">';
			ret_val += ' (<a href="/offer/offer?_id=' + dataContext._t.offer.offer_id + '">' + dataContext._t.offer.offer_name + '</a> on ' + dataContext._t.client.client_name + ')';
			ret_val += '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'contact_name', name:'Lead Name', field:'_id', sort_field:'_d.fn', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var name = (dataContext._d.name == undefined) ? '' : dataContext._d.name;
			if (name == '') {
				var name = (dataContext._d.fn == undefined) ? '' : dataContext._d.fn;
				name += (dataContext._d.ln == undefined) ? '' : (' ' + dataContext._d.ln);
			}			
			var email = (dataContext._d.em == undefined) ? '' : 'E: ' + dataContext._d.em;
			var phone = (dataContext._d.ph1 == undefined) ? '' : ', P: ' + dataContext._d.ph1;
			var ret_val = '<div style="line-height:16pt;">'
				ret_val += '<a href="/lead/lead?_id=' + value + '">' + name + '</a>';
				ret_val += '<div class="small text-muted">';
				ret_val += ' (' + email + phone + ')';
				ret_val += '</div>';
				ret_val += '</div>';
				return ret_val;
		}}
		<?php
			/* @var $data_field \Flux\DataField */
			foreach ($data_fields as $key => $data_field) {
		?>
			<?php if (trim($data_field->getKeyName()) != '' && trim($data_field->getKeyName()) != 'fn' && ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT || $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING || $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT)) { ?>
				,
				<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
					{id:'<?php echo $data_field->getKeyName() ?>', name:'<?php echo ucfirst(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", "", $data_field->getName()))) ?>', field:'_d.<?php echo $data_field->getKeyName() ?>', hidden: true, def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
						return value;
					}}
				<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
					{id:'<?php echo $data_field->getKeyName() ?>', name:'<?php echo ucfirst(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", "", $data_field->getName()))) ?>', field:'_t.<?php echo $data_field->getKeyName() ?>', hidden: true, def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
						<?php if ($data_field->getKeyName() == \Flux\DataField::DATA_FIELD_REF_CLIENT_ID) { ?>
							if (value.client_name) {
								return '<a href="/client/client?_id=' + value.client_id + '">' + value.client_name + '</a>';
							} else {
								return '<em class="text-muted">-- not set --</em>';
							}
						<?php } else if ($data_field->getKeyName() == \Flux\DataField::DATA_FIELD_REF_OFFER_ID) { ?>
							if (value.offer_name) {
								return '<a href="/offer/offer?_id=' + value.offer_id + '">' + value.offer_name + '</a>';
							} else {
								return '<em class="text-muted">-- not set --</em>';
							}
						<?php } else { ?>
							return value;
						<?php } ?>
					}}
				<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
					{id:'<?php echo $data_field->getKeyName() ?>', name:'<?php echo ucfirst(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", "", $data_field->getName()))) ?>', field:'_e.<?php echo $data_field->getKeyName() ?>', hidden: true, def_value: ' ', sortable:false, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
						var ret_val;
						$.each(dataContext._e, function(i, item) {
							if (item.data_field.data_field_id == '<?php echo $data_field->getId() ?>') {
								// This is our event
									ret_val = '<div style="line-height:16pt;">'
									if (item.v == '1') {
										ret_val += 'Yes';
									} else {
										ret_val += 'No';
									}
									ret_val += '<div class="small text-muted">';
									if (moment.unix(item.t.sec).isBefore(moment().startOf('day'), 'day')) {
										ret_val += ' (' + moment.unix(item.t.sec).format("MMM Do [at] LT") + ')';
									} else {
										ret_val += ' (' + moment.unix(item.t.sec).format("LT") + ')';
									}
									ret_val += '</div>';
									ret_val += '</div>';
							}
						});
						return ret_val;
					}}
				<?php } ?>
			<?php } ?>
		<?php } ?>
	];

	slick_grid = $('#lead-grid').slickGrid({
		pager: $('#lead-pager'),
		form: $('#lead_search_form'),
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

	$("#txtSearch",'#lead_search_form').keyup(function(e) {
		// clear on Esc
		if (e.which == 27) {
			this.value = "";
		} else if (e.which == 13) {
			$('#lead_search_form').trigger('submit');
		}
	});

	
	$('#required_fields','#lead_search_form').selectize({
		valueField: 'key_name',
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		options: [
			<?php foreach ($data_fields as $data_field) { ?>
				<?php echo json_encode($data_field->toArray()) ?>,
			<?php } ?>		
		],
		render: {
			item: function(item, escape) {
				var label = item.name || item.key;			
				return '<div class="item">' + escape(label) + '</div>';
			},
			option: function(item, escape) {
				var label = item.name || item.key;
				var caption = item.description ? item.description : null;
				var keyname = item.key_name ? item.key_name : null;
				var tags = item.tags ? item.tags : null;
				var tag_span = '';
				$.each(tags, function(j, tag_item) {
					tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
				});				
				return '<div style="border-bottom: 1px dotted #C8C8C8;">' +
					'<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
					(caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
					'<div>' + tag_span + '</div>' +
				'</div>';
			}
		}
	});
	// Preload any required fields from a saved search
	<?php foreach ($lead_search->getRequiredFields() as $required_field) { ?>
	$('#required_fields','#lead_search_form').selectize()[0].selectize.addItem('<?php echo $required_field ?>');
	<?php } ?>	

	$('#offer_id_array','#lead_search_form').selectize({
		valueField: '_id',
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
				return '<div class="item">' + escape(item.name) + '</div>';
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
	});

	// Preload any offers from a saved search
	<?php foreach ($lead_search->getOfferIdArray() as $offer_id) { ?>
	$('#offer_id_array','#lead_search_form').selectize()[0].selectize.addItem("<?php echo $offer_id ?>");
	<?php } ?>
	
	/*
	$('#offer_id_array','#lead_search_form').selectize()[0].selectize.load(function (callback) {
		$.ajax({
			url: '/api',
			type: 'GET',
			dataType: 'json',
			data: {
				func: '/offer/offer',
				ignore_pagination: true,
				sort: 'name',
				sord: 'asc'
			},
			error: function() {
				callback();
			},
			success: function(res) {
				callback(res.entries);
				<?php foreach ($lead_search->getOfferIdArray() as $offer_id) { ?>
				$('#offer_id_array','#lead_search_form').selectize()[0].selectize.addItem(<?php echo $offer_id ?>);
				<?php } ?>
			}
		});
	});
	*/

	$('#campaign_id_array','#lead_search_form').selectize({
		valueField: '_id',
		labelField: 'description',
		searchField: ['client.client_name', 'description', '_id', 'offer.offer_name'],
		dropdownWidthOffset: 150,
		options: [
		   <?php foreach ($campaigns as $campaign) { ?>
			<?php echo json_encode($campaign->toArray()) ?>,
		   <?php } ?>
		],
		render: {
			item: function(item, escape) {
				return '<div class="item">' + escape(item._id) + '</div>';
			},
			option: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="/images/traffic-sources/' + (item.traffic_source.traffic_source_icon ? escape(item.traffic_source.traffic_source_icon) : 'unknown') + '_48.png" width="32" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<span class="pull-right text-right"><div class="label label-info">' + (item.offer.offer_name ? escape(item.offer.offer_name) : 'Unknown') + '</div><br /><div class="label label-success">' + (item.client.client_name ? escape(item.client.client_name) : 'Unknown') + '</div></span>';
				ret_val += '<h5 class="media-heading">' + escape(item._id) + '</h5>';
				
				ret_val += '<div class="text-muted small">' + escape(item.description) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		}
	});

	// Preload any saved campaigns from a saved search
	<?php foreach ($lead_search->getCampaignIdArray() as $campaign_id) { ?>
	$('#campaign_id_array','#lead_search_form').selectize()[0].selectize.addItem("<?php echo $campaign_id ?>");
	<?php } ?>

	// Preload the campaigns
	/*
	$('#campaign_id_array','#lead_search_form').selectize()[0].selectize.load(function (callback) {
		$.ajax({
			url: '/api',
			type: 'GET',
			dataType: 'json',
			data: {
				func: '/campaign/campaign',
				items_per_page: 100,
				sort: '_id',
				sord: 'desc'
			},
			error: function() {
				callback();
			},
			success: function(res) {
				callback(res.entries);
				<?php foreach ($lead_search->getCampaignIdArray() as $campaign_id) { ?>
				$('#campaign_id_array','#lead_search_form').selectize()[0].selectize.addItem('<?php echo $campaign_id ?>');
				<?php } ?>
			}
		});
	});
	*/

	$('#click_start_date,#click_end_date,#created_start_date,#created_end_date,#conversion_start_date,#conversion_end_date,#fulfillment_start_date,#fulfillment_end_date').datepicker();

	$('#conversion_date_range').selectize();
	
	$('#save_search_btn').on('click', function() {
		$(this).attr('href', '/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>&query_string=' + encodeURIComponent($('#lead_search_form').serialize()));
	});

	<?php if (trim($lead_search->getKeywords()) != '') { ?>
	   $('#lead_search_form').trigger('submit');
	<?php } ?>
});
//-->
</script>