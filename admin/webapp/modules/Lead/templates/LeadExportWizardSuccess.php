<?php
	/* @var $lead \Flux\Lead */
	$lead_search = $this->getContext()->getRequest()->getAttribute('lead', array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	if (trim($lead_search->getKeywords()) == '') {
		$selected_columns = array(\Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME);
	} else {
		$selected_columns = array();
	}
	$selected_headers = array('fn','ln','em','ph','addr','cy','st','zi');
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Export Leads</h4>
</div>
<form action="/lead/lead-export" id="lead_export_form" method="POST">
	<div class="modal-body">
		<div class="help-block">Export leads from the system to a file by selecting your criteria below</div>
		<div>
			<ul class="nav nav-tabs nav-tabs-box" role="tablist">
				<li role="presentation" class="active"><a href="#export_home" aria-controls="home" role="tab" data-toggle="tab">Basic Search</a></li>
				<li role="presentation"><a href="#export_campaigns" aria-controls="campaigns" role="tab" data-toggle="tab">Campaign Options</a></li>
				<li role="presentation"><a href="#export_date" aria-controls="date" role="tab" data-toggle="tab">Date Options</a></li>
				<li role="presentation"><a href="#export_advanced" aria-controls="advanced" role="tab" data-toggle="tab">Advanced Options</a></li>
			</ul>
	
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane tab-pane-box active" id="export_home">
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
									<option value="1" <?php echo $lead_search->getConversionDateRange() == 1 ? 'selected' : '' ?>>Today</option>
									<option value="2" <?php echo $lead_search->getConversionDateRange() == 2 ? 'selected' : '' ?>>Yesterday</option>
									<option value="3" <?php echo $lead_search->getConversionDateRange() == 3 ? 'selected' : '' ?>>Today &amp; Yesterday</option>
									<option value="4" <?php echo $lead_search->getConversionDateRange() == 4 ? 'selected' : '' ?>>Past 7 Days</option>
									<option value="5" <?php echo $lead_search->getConversionDateRange() == 5 ? 'selected' : '' ?>>Past Month</option>
									<option value="6" <?php echo $lead_search->getConversionDateRange() == 6 ? 'selected' : '' ?>>Past 3 Months</option>
									<option value="0" <?php echo $lead_search->getConversionDateRange() == 7 ? 'selected' : '' ?>>All Time</option>
								</select>
							</div>
						</div>
						<div class="form-group text-left col-md-4">
							<label>Offer: </label>
							<div class="input-group">
								<span class="input-group-addon">in</span>
								<select class="selectize" name="offer_id_array[]" id="offer_id_array" multiple placeholder="Filter by offer...">
									<?php 
										/* @var $offer \Flux\Offer */
										foreach($offers as $offer) {
									?>
										<option value="<?php echo $offer->getId() ?>" <?php echo in_array($offer->getId(), $lead_search->getOfferIdArray()) ? "selected" : "" ?>><?php echo $offer->getName() ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane tab-pane-box" id="export_campaigns">
					<div class="row">
						<div class="form-group text-left col-md-12">
							<label>Campaign: </label>
							<div class="input-group">
								<span class="input-group-addon">in</span>
								<select class="selectize" name="campaign_id_array[]" id="campaign_id_array" multiple placeholder="Filter by campaign...">
									<?php
										/* @var $campaign \Flux\Campaign */ 
										foreach ($campaigns as $campaign) { 
									?>
										<option value="<?php echo $campaign->getId() ?>" <?php echo in_array($campaign->getId(), $lead_search->getCampaignIdArray()) ? "selected" : "" ?> data-data="<?php echo htmlentities(json_encode(array('campaign_key' => $campaign->getKey(), 'description' => $campaign->getDescription(), 'client_name' => $campaign->getClient()->getName()))) ?>"><?php echo $campaign->getId() ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane tab-pane-box" id="export_date">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="created_date_start">Created Date:</label>
								<div class="input-group">
									<span class="input-group-addon">between</span>
									<input type="text" class="form-control" id="created_start_date" name="created_start_date" placeholder="start date" value="<?php echo $lead_search->getCreatedStartDate() != '' ? date('m/d/Y', strtotime($lead_search->getCreatedStartDate())) : "" ?>">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="created_end_date" name="created_end_date" placeholder="end date" value="<?php echo $lead_search->getCreatedEndDate() != '' ? date('m/d/Y', strtotime($lead_search->getCreatedEndDate())) : '' ?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="click_date_start">Click Date:</label>
								<div class="input-group">
									<span class="input-group-addon">between</span>
									<input type="text" class="form-control" id="click_start_date" name="click_start_date" placeholder="start date" value="<?php echo $lead_search->getClickStartDate() != '' ? date('m/d/Y', strtotime($lead_search->getClickStartDate())) : '' ?>">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="click_end_date" name="click_end_date" placeholder="end date" value="<?php echo $lead_search->getClickEndDate() != '' ? date('m/d/Y', strtotime($lead_search->getClickEndDate())) : '' ?>">
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
									<input type="text" class="form-control" id="conversion_start_date" name="conversion_start_date" placeholder="start date" value="<?php echo $lead_search->getConversionStartDate() != '' ? date('m/d/Y', strtotime($lead_search->getConversionStartDate())) : '' ?>">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="conversion_end_date" name="conversion_end_date" placeholder="end date" value="<?php echo $lead_search->getConversionEndDate() != '' ? date('m/d/Y', strtotime($lead_search->getConversionEndDate())) : '' ?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="fulfillment_date_start">Fulfillment Date:</label>
								<div class="input-group">
									<span class="input-group-addon">between</span>
									<input type="text" class="form-control" id="fulfillment_start_date" name="fulfillment_start_date" placeholder="start date" value="<?php echo $lead_search->getFulfillmentStartDate() != '' ? date('m/d/Y', strtotime($lead_search->getFulfillmentStartDate())) : '' ?>">
									<span class="input-group-addon">and</span>
									<input type="text" class="form-control" id="fulfillment_end_date" name="fulfillment_end_date" placeholder="end date" value="<?php echo $lead_search->getFulfillmentEndDate() != '' ? date('m/d/Y', strtotime($lead_search->getFulfillmentEndDate())) : '' ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane tab-pane-box" id="export_advanced">
					<div class="row">
						<div class="form-group col-md-6">
							<label>Required Fields: </label>
							<div class="input-group">
								<span class="input-group-addon">in</span>
								<select class="selectize" name="required_fields[]" id="required_fields" multiple placeholder="Add required fields...">
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
						</div>
						<div class="form-group col-md-6">
							<label>How many leads do you want to export</label>
							<select class="selectize" name="items_per_page" id="items_per_page" placeholder="How many leads do you want to export...">
								<optgroup label="Export all leads">
									<option value="0">Export all leads</option>
								</optgroup>
								<optgroup label="Export selected leads">
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
									<option value="200">200</option>
									<option value="500">500</option>
								</optgroup>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr />
		<div class="form-group">
			<label>Choose the columns you want included in this export</label>
			<select class="form-control selectize" name="headers[]" id="headers" multiple placeholder="Select the column headers">
				<?php
					/* @var $data_field \Flux\DataField */ 
					foreach($data_fields AS $data_field) { 
				?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
					<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
					<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
					<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" class="btn btn-primary" value="Export Leads" />
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	// submit the form
	$('#created_start_date,#created_end_date,#click_start_date,#click_end_date,#conversion_start_date,#conversion_end_date,#fulfillment_start_date,#fulfillment_end_date', '#lead_export_form').datepicker();
	
	$('#headers,#required_fields','#lead_export_form').selectize({
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

	<?php foreach ($selected_headers as $header_col) { ?>
		$('#headers','#lead_export_form').selectize()[0].selectize.addItem('<?php echo $header_col; ?>');
	<?php } ?>

	$('#offer_id_array','#lead_export_form').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	});

	$('#campaign_id_array','#lead_export_form').selectize({
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

	$('#items_per_page,#conversion_date_range','#lead_export_form').selectize();
});
//-->
</script>