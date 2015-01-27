<?php
	/* @var $datafield Flux\DataField */
	$dataField = $this->getContext()->getRequest()->getAttribute("datafield", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo ($dataField->getId() > 0) ? 'Edit' : 'Add' ?> Data Field</h4>
</div>
<form id="data_field_form_<?php echo $dataField->getId() ?>" method="<?php echo ($dataField->getId() > 0) ? 'PUT' : 'POST' ?>" action="/api" role="form">
	<input type="hidden" name="func" value="/admin/data-field" />
	<?php if ($dataField->getId() > 0) { ?>
		<input type="hidden" name="_id" value="<?php echo $dataField->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#storage" role="tab" data-toggle="tab">Storage</a></li>
			<li role="presentation" class=""><a href="#fields" role="tab" data-toggle="tab">Data Fields</a></li>
			<li role="presentation" class=""><a href="#advanced" role="tab" data-toggle="tab">Advanced Settings</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
				<div class="help-block">Data Fields define which data can be collected on offer pages</div>
				<div class="form-group">
					<label class="control-label hidden-xs" for="name">Name:</label>
					<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $dataField->getName() ?>" />
				</div>
			
				<div class="form-group">
					<label class="control-label hidden-xs" for="description">Description:</label>
					<textarea type="text" id="description" name="description" class="form-control" placeholder="Enter description..."><?php echo $dataField->getDescription() ?></textarea>
				</div>
				<hr />
			
				<div class="form-group">
					<label class="control-label hidden-xs" for="key_name">Unique Key Name:</label>
					<input type="text" id="key_name" name="key_name" class="form-control" placeholder="Enter unique key name" value="<?php echo $dataField->getKeyName() ?>" />
					<div class="small help-block">Set the internal property used to find this field</div>
				</div>
			
			
				<div class="form-group">
					<label class="control-label hidden-xs" for="request_name">Request Fields:</label>
					<input type="text" id="request_name" name="request_name" class="form-control" placeholder="Request Name" value="<?php echo implode(",", $dataField->getRequestName()) ?>" />
					<div class="help-block small">Use this placeholder for any POST url fields matching this list</div>
				</div>
		
				 <div class="form-group">
					<label class="control-label hidden-xs" for="request_name">Tags:</label>
					<input type="text" id="tags" name="tags" class="form-control" placeholder="Tags" value="<?php echo implode(",", $dataField->getTags()) ?>" />
					<div class="help-block small">Enter tags to help identify this data field when selecting it</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="storage">
				<div class="help-block">Set how and where this data field will be stored and accessed</div>
				<div class="form-group">
					<label class="control-label hidden-xs" for="access_type">Access Type</label>
					<select class="form-control" name="access_type" id="access_type" placeholder="Access Type">
						<option value="<?php echo \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM ?>" <?php echo $dataField->getAccessType() == \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'System', 'value' => \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM, 'description' => 'SYSTEM fields cannot be edited nor populate from realtime'))) ?>">System</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_ACCESS_TYPE_RESERVED ?>" <?php echo $dataField->getAccessType() == \Flux\DataField::DATA_FIELD_ACCESS_TYPE_RESERVED ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Reserved', 'value' => \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM, 'description' => 'RESERVED fields can be edited but not populated from realtime'))) ?>">Reserved</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC ?>" <?php echo $dataField->getAccessType() == \Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Public', 'value' => \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM, 'description' => 'PUBLIC are accessible to everything'))) ?>">Public</option>				
					</select>
					<small class="help-block">Select the type of data field and where it is available to be viewed or manipulated</small>
				</div>
				<hr />
				<div class="form-group">
					<label class="control-label hidden-xs" for="storage_type">Storage Type</label>
					<select class="form-control" name="storage_type" id="storage_type" placeholder="Storage Type">
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT ?>" <?php echo $dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Default', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT, 'description' => 'Default storage for most saved data'))) ?>">Default</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING ?>" <?php echo $dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Tracking', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING, 'description' => 'Tracking storage for subids, user agent, and campaign data'))) ?>">Tracking</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT ?>" <?php echo $dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Events', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT, 'description' => 'Event storage for clicks, partials, and conversions'))) ?>">Event</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED ?>" <?php echo $dataField->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Derived', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED, 'description' => 'Data derived from other data fields'))) ?>">Derived</option>
					</select>
					<small class="help-block">Select where this field should be stored.  Tracking storage cannot be changed once set, Default storage changes, Event Storage is added</small>
				</div>
				<hr />
				<div class="form-group">
					<label class="control-label hidden-xs" for="type">Data Type</label>
					<select class="form-control" name="type" id="type" placeholder="Data Type">
						<?php foreach(\Flux\DataField::retrieveSettableTypes() AS $type_id => $type_name) { ?>
						<option value="<?php echo $type_id; ?>"<?php echo $dataField->getFieldType() == $type_id ? ' selected' : ''; ?>><?php echo $type_name; ?></option>
						<?php } ?>
					</select>
					<small class="help-block">Select what type of data should be expected.  This affects validation and how the data is stored</small>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="advanced">
				<div class="help-block">Advanced options allow you to further customize this data field with custom mapping functions and field names</div>
				<div class="form-group">
					<div class="btn <?php echo (trim($dataField->getCustomCode()) == '') ? 'btn-warning' : 'btn-success' ?> text-center" id="show_advanced_options_div_<?php echo $dataField->getId() ?>">Show Advanced Options</div>
					<div class="hidden" id="advanced_options_div_<?php echo $dataField->getId() ?>">
						<div class="help-block">Define a custom function that you can use to convert this field to a value that the API accepts</div>
				        <p />
				        <div class="help-text">
				            <span class="text-success">
				            /**<br />
				            &nbsp;* Custom mapping function<br />
				            &nbsp;* $value - Value from mapping<br />
				            &nbsp;* $record - \Flux\Lead object<br />
				            &nbsp;*/<br />
				            </span>
				            <strong>
				            $mapping_func = function ($value, $lead) {
				            </strong>
				        </div>
				        <div class="col-sm-offset-1">
							<textarea id="custom_code_<?php echo $dataField->getId() ?>" class="form-control" name="custom_code" rows="6" placeholder="return $value;"><?php echo $dataField->getCustomCode() ?></textarea>
						</div>
						<div class="help-text"><strong>}</strong></div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="fields">
				<div class="help-block">These are the values that can be used in this data field.  Use these when adding data fields to a lead</div>
				<div id="data_field_set_groups">
					<?php
						$counter = 0;
						/* @var $data_field_set \Flux\DataFieldSet */
						foreach($dataField->getDataFieldSet() AS $data_field_set) {
					?>
						<div class="form-group data-field-set-group-item">
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<label for="data_field_set_value_<?php echo $counter ?>">#<?php echo $counter + 1 ?></label>
							</div>
							<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
								<input type="text" id="data_field_set_value_<?php echo $counter ?>" name="data_field_set[<?php echo $counter;?>][value]" class="form-control" value="<?php echo $data_field_set->getValue() ?>" placeholder="enter value for data field #<?php echo $counter + 1 ?>" />
								<div class="text-right hidden-xs hidden-sm"><small><?php echo number_format($data_field_set->getLeadTotal(), 0, null, ',') ?> leads total, <?php echo number_format($data_field_set->getDailyTotal(), 0, null, ',') ?> leads today</small></div>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
								<textarea name="data_field_set[<?php echo $counter ?>][name]" class="form-control" placeholder="enter description for data field #<?php echo $counter + 1 ?>"><?php echo $data_field_set->getName() ?></textarea>
							</div>
							<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
								<button type="button" class="form-control btn btn-danger btn-remove-item"><span class="glyphicon glyphicon-remove"></span></button>
							</div>
							<div class="clearfix"></div>
						</div>
						<?php $counter++; ?>
					<?php } ?>
				</div>
				<div class="form-group">
					<div class="col-xs-2 col-xs-offset-10 col-sm-2 col-sm-offset-10 col-md-1 col-md-offset-11 col-lg-1 col-lg-offset-11">
						<button type="button" class="btn btn-info" id="add_set_btn"><span class="glyphicon glyphicon-plus"></span></button>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php if ($dataField->getId() > 0) { ?>
			<input type="button" class="btn btn-danger" value="Delete Data Field" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" id="btn_submit" class="btn btn-primary">Save changes</button>
	</div>
</form>

<!-- Used to add new data field set items -->
<div class="form-group data-field-set-group-item" style="display:none;" id="dummy_data_field_set_div">
	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
		<label for="data_field_set_value_dummy_set_id">#dummy_set_counter</label>
	</div>
	<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
		<input type="text" id="data_field_set_value_dummy_set_id" name="data_field_set[dummy_set_id][value]" class="form-control" value="" placeholder="enter value" />
		<div class="text-right hidden-xs hidden-sm"><small>0 leads total, 0 leads today</small></div>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
		<textarea name="data_field_set[dummy_set_id][name]" class="form-control" placeholder="enter description"></textarea>
	</div>
	<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
		<button type="button" class="form-control btn btn-danger btn-remove-item"><span class="glyphicon glyphicon-remove"></span></button>
	</div>
	<div class="clearfix"></div>
</div>

<script>
//<!--
$(document).ready(function() {	
	$('#show_advanced_options_div_<?php echo $dataField->getId() ?>').click(function() {
		$('#advanced_options_div_<?php echo $dataField->getId() ?>').toggleClass('hidden');
		$(this).hide();
	});

	$('#access_type,#storage_type').selectize({
		valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				return '<div>' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'</div>';
			},
			option: function(item, escape) {
				return '<div>' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'</div>';
			}
		}
	});
	
	$('#tags,#request_name,#type').selectize({
		delimiter: ',',
		persist: false,
		allowEmptyOption: true,
		create: function(input) {
			return {
				value: input,
				text: input
			}
		}
	});

	$('#data_field_form_<?php echo $dataField->getId() ?>').form(function(data) {
		$.rad.notify('Data Field Updated', 'The data field has been added/updated in the system');
		$('#datafield_search_form').trigger('submit');
	}, {keep_form:1});

	$('#add_set_btn').on('click', function() {
		var index_number = $('#data_field_set_groups > .data-field-set-group-item').length;
		var data_field_set_div = $('#dummy_data_field_set_div').clone(true);
		data_field_set_div.removeAttr('id');
		data_field_set_div.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/dummy_set_id/g, index_number);
			oldHTML = oldHTML.replace(/dummy_set_counter/g, (index_number + 1));
			return oldHTML;
		});
		$('#data_field_set_groups').append(data_field_set_div);
		data_field_set_div.show();
	});

	$('#data_field_set_groups').on('click', '.btn-remove-item', function() {
		$(this).closest('.form-group').remove();
	});
});
<?php if ($dataField->getId() > 0) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this data field from the system?')) {
		$.rad.del({ func: '/admin/data-field/<?php echo $dataField->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this data field', 'You have deleted this data field.  You will need to refresh this page to see your changes.');
			$('#datafield_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>