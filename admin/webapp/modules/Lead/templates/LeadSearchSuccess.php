<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute("lead", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	$selected_columns = array(\Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME);
?>
<div class="page-header">
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
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="form-group text-left col-md-6">
				<label>Search by lead name or id: </label>
				<input type="text" class="form-control" placeholder="search by name or id" size="35" id="txtSearch" name="keywords" value="<?php echo $lead->getKeywords() ?>" />
			</div>
			<div class="form-group text-left col-md-6">
				<label>Only show leads with the following fields set: </label>
				<select class="form-control selectize" name="required_fields[]" id="required_fields" multiple placeholder="No Fields">
					<?php
						/* @var $data_field \Flux\DataField */ 
						foreach($data_fields AS $data_field) { 
					?>
						<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
							<option value="<?php echo $data_field->getKeyName() ?>"<?php echo in_array($data_field->getKeyName(), $selected_columns) ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
						<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
							<option value="<?php echo $data_field->getKeyName() ?>"<?php echo in_array($data_field->getKeyName(), $selected_columns) ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
						<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
							<option value="<?php echo $data_field->getKeyName() ?>"<?php echo in_array($data_field->getKeyName(), $selected_columns) ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
			<div class="form-group text-left col-md-6">
				<label>Filter leads by offer: </label>
				<select class="form-control selectize" name="offer_id_array[]" id="offer_id_array" multiple placeholder="Filter by offer">
					<?php 
						/* @var $offer \Flux\Offer */
						foreach($offers as $offer) {
					?>
						<option value="<?php echo $offer->getId() ?>" <?php echo in_array($offer->getId(), $lead->getOfferIdArray()) ? "selected" : "" ?>><?php echo $offer->getName() ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group text-left col-md-6">
				<label>Filter leads by campaign: </label>
				<select class="form-control selectize" name="campaign_id_array[]" id="campaign_id_array" multiple placeholder="Filter by campaign">
					<?php
						/* @var $campaign \Flux\Campaign */ 
						foreach ($campaigns as $campaign) { 
					?>
						<option value="<?php echo $campaign->getId() ?>" <?php echo in_array($campaign->getId(), $lead->getCampaignIdArray()) ? "selected" : "" ?> data-data="<?php echo htmlentities(json_encode(array('campaign_key' => $campaign->getKey(), 'description' => $campaign->getDescription(), 'client_name' => $campaign->getClient()->getClientName()))) ?>"><?php echo $campaign->getId() ?></option>
					<?php } ?>
				</select>
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
		{id:'name', name:'Name', field:'_d.fn', sort_field:'_d.fn', def_value: ' ', sortable:true, cssClass:'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			var name = (dataContext._d.fn == undefined) ? '' : dataContext._d.fn;
			name += (dataContext._d.ln == undefined) ? '' : (' ' + dataContext._d.ln);
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
			<?php if (trim($data_field->getKeyName()) != '' && ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT || $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING || $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT)) { ?>
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
									ret_val += ' (Fired ' + moment.unix(item.t.sec).calendar() + ')';
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

	$("#txtSearch").keyup(function(e) {
		// clear on Esc
		if (e.which == 27) {
			this.value = "";
		} else if (e.which == 13) {
			$('#lead_search_form').trigger('submit');
		}
	});

	
	$('#required_fields').selectize({
		valueField: 'key_name',
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				var label = item.name || item.key;            
	            return '<div">' + escape(label) + '</div>';
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

	$('#offer_id_array').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	});

	$('#campaign_id_array').selectize({
		valueField: 'campaign_key',
		labelField: 'description',
		searchField: ['client_name', 'description', 'campaign_key'],
		dropdownWidthOffset: 150,
		create: true,
		render: {
			item: function(item, escape) {
	            return '<div>' + escape(item.campaign_key) + '</div>';
			},
			option: function(item, escape) {
				return '<div style="padding-right:25px;">' +
	                '<b>' + escape(item.campaign_key) + '</b>' +
	                '<span class="pull-right label label-success">' + (item.client_name ? escape(item.client_name) : 'Unknown') + '</span>' + 
	    	        '<br />' +
	                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
	            '</div>';
			}
		}
	});

	<?php if (trim($lead->getKeywords()) != '') { ?>
	   $('#lead_search_form').trigger('submit');
	<?php } ?>
});
//-->
</script>