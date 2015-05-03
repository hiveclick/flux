<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute("lead", array());
	
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
?>
<div class="page-header">
    <div class="pull-right">
        <a id="save_search_btn" data-toggle="modal" data-target="#edit_saved_search_modal" href="/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>" class="btn btn-info">Save Search</a>
        <?php if ($this->getContext()->getUser()->getUserDetails()->getUserType() == \Flux\User::USER_TYPE_ADMIN) { ?>
		<a data-toggle="modal" data-target="#export_lead_modal" href="/lead/lead-export-wizard" class="btn btn-success"><span class="glyphicon glyphicon-export"></span> Export Leads</a>
		<?php } ?>
	</div>
   <h2>Search Leads</h2>
</div>
<div class="help-block">These are all the leads in the system.  Search by offer, campaign, lead name or id.</div>
<br/>
<div class="panel panel-info">
	<div id='lead-header' class='grid-header panel-heading clearfix'>Search for leads</div>
	<div class="panel-body">
		<form id="lead_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/lead/lead-search">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="_id" />
			<input type="hidden" id="sord" name="sord" value="desc" />
			<div class="form-group text-left col-md-6">
				<label>Search by lead name or id: </label>
				<input type="text" class="form-control" placeholder="search by name or id" size="35" id="txtSearch" name="keywords" value="<?php echo $lead->getKeywords() ?>" />
			</div>
			<div class="form-group text-left col-md-6">
				<label>Only show leads with the following fields set: </label>
				<select class="form-control selectize" name="required_fields[]" id="required_fields" multiple placeholder="Add required fields..."></select>
			</div>
			<div class="form-group text-left col-md-6">
				<label>Filter leads by offer: </label>
				<select class="form-control selectize" name="offer_id_array[]" id="offer_id_array" multiple placeholder="Filter by offer..."></select>
			</div>
			<div class="form-group text-left col-md-6">
				<label>Filter leads by campaign: </label>
				<select class="form-control selectize" name="campaign_id_array[]" id="campaign_id_array" multiple placeholder="Filter by campaign..."></select>
			</div>
			
			<div class="text-center">
				<input type="submit" value="search" class="btn btn-primary" />
			</div>
		</form>
	</div>
</div>

<div class="panel panel-primary">
	<div id='lead-header' class='grid-header panel-heading clearfix'>&nbsp;</div>
	<div id="lead-grid"></div>
	<div id="lead-pager" class="panel-footer"></div>
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
							if (item.data_field.data_field_id == <?php echo $data_field->getId() ?>) {
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

	$('#required_fields','#lead_search_form').selectize()[0].selectize.load(function (callback) {
        $.ajax({
        	url: '/api',
            type: 'GET',
            dataType: 'json',
            data: {
                func: '/admin/data-field',
                ignore_pagination: true,
                sort: 'name',
                sord: 'asc',
                storage_type_array: [ <?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT ?>,<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT ?>, <?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING ?>]
            },
            error: function() {
                callback();
            },
            success: function(res) {
                callback(res.entries);
                <?php foreach ($lead->getRequiredFields() as $required_field) { ?>
                $('#required_fields','#lead_search_form').selectize()[0].selectize.addItem('<?php echo $required_field ?>');
            	<?php } ?>
            }
        });
    });

	$('#offer_id_array','#lead_search_form').selectize({
    	valueField: '_id',
		allowEmptyOption: true,
		labelField: 'name',
		searchField: ['name'],
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
                <?php foreach ($lead->getOfferIdArray() as $offer_id) { ?>
                $('#offer_id_array','#lead_search_form').selectize()[0].selectize.addItem(<?php echo $offer_id ?>);
            	<?php } ?>
            }
        });
    });

	$('#campaign_id_array','#lead_search_form').selectize({
		valueField: '_id',
		labelField: 'description',
		searchField: ['client.client_name', 'description', '_id', 'offer.offer_name'],
		dropdownWidthOffset: 150,
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

	// Preload the campaigns
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
                <?php foreach ($lead->getCampaignIdArray() as $campaign_id) { ?>
                $('#campaign_id_array','#lead_search_form').selectize()[0].selectize.addItem('<?php echo $campaign_id ?>');
            	<?php } ?>
            }
        });
    });

	$('#save_search_btn').on('click', function() {
        $(this).attr('href', '/admin/saved-search-wizard?search_type=<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>&query_string=' + encodeURIComponent($('#lead_search_form').serialize()));
    });

	<?php if (trim($lead->getKeywords()) != '') { ?>
	   $('#lead_search_form').trigger('submit');
	<?php } ?>
});
//-->
</script>