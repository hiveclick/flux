<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	if (trim($lead->getKeywords()) == '') {
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
		<div class="form-group">
			<label>Only export leads with the following fields set: </label>
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
		<div class="form-group">
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
		<div class="form-group">
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
		<div class="form-group">
			<label>How many leads do you want to export</label>
			<select class="form-control selectize" name="items_per_page" id="items_per_page" placeholder="How many leads do you want to export...">
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
        $('#headers').selectize()[0].selectize.addItem('<?php echo $header_col; ?>');
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

	$('#items_per_page','#lead_export_form').selectize();
});
//-->
</script>