<?php
	/* @var $datafield Flux\DataField */
	$data_field = $this->getContext()->getRequest()->getAttribute("datafield", array());
	$tags = $this->getContext()->getRequest()->getAttribute("tags", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($data_field->getId()) ? 'Edit' : 'Add' ?> Data Field</h4>
</div>
<form id="data_field_form_<?php echo $data_field->getId() ?>" method="POST" action="/api" role="form">
	<input type="hidden" name="func" value="/admin/data-field" />
	<?php if (\MongoId::isValid($data_field->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $data_field->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#storage" role="tab" data-toggle="tab">Storage</a></li>
			<li role="presentation" class=""><a href="#fields" role="tab" data-toggle="tab">Field Values</a></li>
			<li role="presentation" class=""><a href="#advanced" role="tab" data-toggle="tab">Advanced Settings</a></li>
			<li role="presentation" class=""><a href="#html" role="tab" data-toggle="tab">HTML</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
				<div class="help-block">Data Fields define which data can be collected on offer pages</div>
				<div class="form-group">
					<label class="control-label hidden-xs" for="name">Name:</label>
					<input type="text" id="name_<?php echo $data_field->getId() ?>" name="name" class="form-control" placeholder="Name" value="<?php echo $data_field->getName() ?>" />
				</div>
			
				<div class="form-group">
					<label class="control-label hidden-xs" for="description">Description:</label>
					<textarea type="text" id="description_<?php echo $data_field->getId() ?>" name="description" class="form-control" placeholder="Enter description..."><?php echo $data_field->getDescription() ?></textarea>
				</div>
				<hr />
			
				<div class="form-group">
					<label class="control-label hidden-xs" for="key_name">Unique Key Name:</label>
					<input type="text" id="key_name_<?php echo $data_field->getId() ?>" name="key_name" class="form-control" placeholder="Enter unique key name" value="<?php echo $data_field->getKeyName() ?>" />
					<div class="small help-block">This is the primary request name used to save this field.  Click the HTML tab to see the HTML code to place</div>
				</div>
			
			
				<div class="form-group">
					<label class="control-label hidden-xs" for="request_name">Additional Request Fields:</label>
					<input type="text" id="request_name_<?php echo $data_field->getId() ?>" name="request_name" class="form-control" placeholder="Request Name" value="<?php echo implode(",", $data_field->getRequestName()) ?>" />
					<div class="help-block small">Enter any additional request names (other than the unique key name) here (i.e. zip, postal_code, zipcode, etc)</div>
				</div>
		
				 <div class="form-group">
					<label class="control-label hidden-xs" for="tags">Tags:</label>
					<input type="text" id="tags_<?php echo $data_field->getId() ?>" name="tags" class="form-control" placeholder="Tags" value="<?php echo implode(",", $data_field->getTags()) ?>" />
					<div class="help-block small">Categorize this data field by tagging it with category tags</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="storage">
				<div class="help-block">Set how and where this data field will be stored and accessed</div>
				<div class="form-group">
					<label class="control-label hidden-xs" for="access_type">Access Type</label>
					<select class="form-control" name="access_type" id="access_type_<?php echo $data_field->getId() ?>" placeholder="Access Type">
						<option value="<?php echo \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM ?>" <?php echo $data_field->getAccessType() == \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'System', 'value' => \Flux\DataField::DATA_FIELD_ACCESS_TYPE_SYSTEM, 'description' => 'SYSTEM fields cannot be edited nor populate from realtime'))) ?>">System</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_ACCESS_TYPE_RESERVED ?>" <?php echo $data_field->getAccessType() == \Flux\DataField::DATA_FIELD_ACCESS_TYPE_RESERVED ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Reserved', 'value' => \Flux\DataField::DATA_FIELD_ACCESS_TYPE_RESERVED, 'description' => 'RESERVED fields can be edited but not populated from realtime'))) ?>">Reserved</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC ?>" <?php echo $data_field->getAccessType() == \Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Public', 'value' => \Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC, 'description' => 'PUBLIC are accessible to everything'))) ?>">Public</option>				
					</select>
					<small class="help-block">Select the type of data field and where it is available to be viewed or manipulated</small>
				</div>
				<hr />
				<div class="form-group">
					<label class="control-label hidden-xs" for="storage_type">Storage Type</label>
					<select class="form-control" name="storage_type" id="storage_type_<?php echo $data_field->getId() ?>" placeholder="Storage Type">
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT ?>" <?php echo $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Default', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT, 'description' => 'Default storage for most saved data'))) ?>">Default</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING ?>" <?php echo $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Tracking', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING, 'description' => 'Tracking storage for subids, user agent, and campaign data'))) ?>">Tracking</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT ?>" <?php echo $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Events', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT, 'description' => 'Event storage for clicks, partials, and conversions'))) ?>">Event</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED ?>" <?php echo $data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Derived', 'value' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED, 'description' => 'Data derived from other data fields'))) ?>">Derived</option>
					</select>
					<small class="help-block">Select where this field should be stored.  Tracking storage cannot be changed once set, Default storage changes, Event Storage is added</small>
				</div>
				<hr />
				<div class="form-group">
					<label class="control-label hidden-xs" for="type">Data Type</label>
					<select class="form-control" name="field_type" id="field_type_<?php echo $data_field->getId() ?>" placeholder="Data Type">
						<option value="<?php echo \Flux\DataField::DATA_FIELD_TYPE_STRING ?>" <?php echo $data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_STRING ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'String', 'value' => \Flux\DataField::DATA_FIELD_TYPE_STRING, 'description' => 'Stores single string data such as names, phone numbers, yes/no, and other information'))) ?>">String</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_TYPE_ARRAY ?>" <?php echo $data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Array', 'value' => \Flux\DataField::DATA_FIELD_TYPE_ARRAY, 'description' => 'Used to store multiple values such as side effects, multi-selects, and checkboxes'))) ?>">Array</option>
						<option value="<?php echo \Flux\DataField::DATA_FIELD_TYPE_OBJECT ?>" <?php echo $data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_OBJECT ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Object', 'value' => \Flux\DataField::DATA_FIELD_TYPE_OBJECT, 'description' => 'Links to other objects in the system such as offers, clients, or campaigns'))) ?>">Object</option>
					</select>
					<small class="help-block">Select what type of data should be expected.  This affects validation and how the data is stored</small>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="advanced">
			    <div class="help-block">Define a custom function that you can use to convert this field to a value that the API accepts</div>
				<div class="form-group">
					<div id="advanced_options_div_<?php echo $data_field->getId() ?>">
						<div class="help-text">
				            <span class="text-success">
				            /**<br />
				            &nbsp;* Custom mapping function<br />
				            &nbsp;* $value - Value from mapping<br />
				            &nbsp;* $lead - \Flux\Lead object<br />
				            &nbsp;*/<br />
				            </span>
				            <strong>
				            $mapping_func = function ($value, $lead) {
				            </strong>
				        </div>
				        <div class="col-sm-offset-1">
							<div class="form-group">
								<textarea id="custom_code_<?php echo $data_field->getId() ?>" class="form-control" name="custom_code" rows="15" placeholder="return $value;"><?php echo $data_field->getCustomCode() ?></textarea>
							</div>
							<div class="form-group">
					        	<select name="custom_code_examples" class="form-control" id="custom_code_examples" placeholder="Type custom code above or select a template from the list">
									<option value=""></option>
									<option value="1">Convert YES/NO to 1/0 (*data field must be of type String)</option>
									<option value="2">Check if a value is chosen (*data field must be of type Array)</option>
									<option value="3">Combine two values on a lead</option>
									<option value="4">Lookup a city based on the zipcode</option>
									<option value="5">Lookup a state based on the zipcode</option>
								</select>
							</div>
						</div>
						<div class="help-text"><strong>}</strong></div>
						<div class="text-center">
							<button type="button" class="btn btn-success" id="btn_validate">Validate Custom Code</button>
						</div>
						<p />
						<div id="validation_result_<?php echo $data_field->getId() ?>" style="display:none;" class="alert alert-danger"></div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="fields">
				<div class="help-block">These are the values that can be used in this data field.  Use these when adding data fields to a lead</div>
				<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
				<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
					<b>Display Name</b>
				</div>
				<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
					<b>Value</b>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
					&nbsp;
				</div>
				<div class="clearfix"></div>
				<div id="data_field_set_groups">
					<?php
						$counter = 0;
						/* @var $data_field_set \Flux\DataFieldSet */
						foreach($data_field->getDataFieldSet() AS $data_field_set) {
					?>
						<div class="form-group data-field-set-group-item">
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<label for="data_field_set_value_<?php echo $counter ?>">#<?php echo $counter + 1 ?></label>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
								<textarea name="data_field_set[<?php echo $counter ?>][name]" class="form-control" placeholder="enter display name"><?php echo $data_field_set->getName() ?></textarea>
							</div>
							<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
								<input type="text" id="data_field_set_value_<?php echo $counter ?>" name="data_field_set[<?php echo $counter;?>][value]" class="form-control" value="<?php echo $data_field_set->getValue() ?>" placeholder="enter value for data field #<?php echo $counter + 1 ?>" />
								<div class="text-right hidden-xs hidden-sm"><small><?php echo number_format($data_field_set->getLeadTotal(), 0, null, ',') ?> leads total, <?php echo number_format($data_field_set->getDailyTotal(), 0, null, ',') ?> leads today</small></div>
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
			<div role="tabpanel" class="tab-pane fade" id="html">
				<div class="help-block">Use this HTML code when placing this field on your paths</div>
				<div class="panel-group" id="accordion" role="tablist">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="html_select_example_header">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#html_select_example">Select (Single Select)</a></h4>
    					</div>
    					<div id="html_select_example" class="panel-collapse collapse" role="tabpanel">
      						<div class="panel-body">
      							<?php if (count($data_field->getDataFieldSet()) > 0) { ?>
	      							<textarea readonly class="form-control" rows="15" placeholder="select html code will show here"><?php 
	      								$buffer = array();
	      								$buffer[] = sprintf('<select class="form-control" name="%s">', $data_field->getKeyName());
	      								/* @var $data_field_set \Flux\DataFieldSet */
										foreach($data_field->getDataFieldSet() AS $data_field_set) {
											$buffer[] = sprintf('	<option value="%s">%s</option>', $data_field_set->getValue(), $data_field_set->getName());
										}
										$buffer[] = '</select>';
	      								echo implode("\n", $buffer);
	      							?></textarea>
	      						<?php } else { ?>
	      							<div class="alert alert-warning">
	      								You do not have any field values defined.  Please assign field values to this data field.
	      							</div>
	      						<?php } ?>
      						</div>
      					</div>
      				</div>
      				<div class="panel panel-default">
      					<div class="panel-heading" role="tab" id="html_multiselect_example_header">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#html_multiselect_example">Select (Multi Select)</a></h4>
    					</div>
    					<div id="html_multiselect_example" class="panel-collapse collapse" role="tabpanel">
      						<div class="panel-body">
      							<?php if (count($data_field->getDataFieldSet()) > 0) { ?>
	      							<textarea readonly class="form-control" rows="15" placeholder="select html code will show here"><?php 
	      								$buffer = array();
	      								$buffer[] = sprintf('<select class="form-control" name="%s[]" multiple>', $data_field->getKeyName());
	      								/* @var $data_field_set \Flux\DataFieldSet */
										foreach($data_field->getDataFieldSet() AS $data_field_set) {
											$buffer[] = sprintf('	<option value="%s">%s</option>', $data_field_set->getValue(), $data_field_set->getName());
										}
										$buffer[] = '</select>';
	      								echo implode("\n", $buffer);
	      							?></textarea>
	      							<div class="help-block">* Make sure you set this field to an <i>Array</i> Data Type under the Storage tab above</div>
	      						<?php } else { ?>
	      							<div class="alert alert-warning">
	      								You do not have any field values defined.  Please assign field values to this data field.
	      							</div>
	      						<?php } ?>
      						</div>
      					</div>
      				</div>
      				<div class="panel panel-default">
      					<div class="panel-heading" role="tab" id="html_checkbox_example_header">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#html_checkbox_example">Checkbox</a></h4>
    					</div>
    					<div id="html_checkbox_example" class="panel-collapse collapse" role="tabpanel">
      						<div class="panel-body">
      							<textarea readonly class="form-control" rows="15" placeholder="checkbox html code will show here"><?php 
      								$buffer = array();
      								if (count($data_field->getDataFieldSet()) > 0) {
	      								/* @var $data_field_set \Flux\DataFieldSet */
	      								foreach($data_field->getDataFieldSet() AS $data_field_set) {
	      									$buffer[] = sprintf('<label><input type="checkbox" class="form-control" name="%s" value="%s"> %s</label>', $data_field->getKeyName(), $data_field_set->getValue(), $data_field_set->getName());
	      								}
	      							} else { 
										$buffer[] = sprintf('<label><input type="checkbox" class="form-control" name="%s" value="1"> %s</label>', $data_field->getKeyName(), $data_field->getName());
									}
      								echo implode("\n", $buffer);
      							?></textarea>
      						</div>
      					</div>
      				</div>
      				<div class="panel panel-default">
      					<div class="panel-heading" role="tab" id="html_radio_example_header">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#html_radio_example">Radio buttons</a></h4>
    					</div>
    					<div id="html_radio_example" class="panel-collapse collapse" role="tabpanel">
      						<div class="panel-body">
      							<textarea readonly class="form-control" rows="15" placeholder="radio button html code will show here"><?php 
      								$buffer = array();
      								if (count($data_field->getDataFieldSet()) > 0) {
	      								/* @var $data_field_set \Flux\DataFieldSet */
	      								foreach($data_field->getDataFieldSet() AS $data_field_set) {
	      									$buffer[] = sprintf('<label><input type="radio" class="form-control" name="%s" value="%s"> %s</label>', $data_field->getKeyName(), $data_field_set->getValue(), $data_field_set->getName());
	      								}
      								} else {
										$buffer[] = '<div class="form-group">' + "\n";
										$buffer[] = sprintf('<label>%s<input type="checkbox" class="form-control" name="%s" value="1"></label>', $data_field->getName(), $data_field->getKeyName());
      									$buffer[] = '</div>';
      								}
      								echo implode("\n", $buffer);
      							?></textarea>
      						</div>
      					</div>
      				</div>
      				<div class="panel panel-default">
      					<div class="panel-heading" role="tab" id="html_textbox_example_header">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#html_textbox_example">Text</a></h4>
    					</div>
    					<div id="html_textbox_example" class="panel-collapse collapse" role="tabpanel">
      						<div class="panel-body">
      							<textarea readonly class="form-control" rows="15" placeholder="select html code will show here"><?php 
      								$buffer = array();
      								$buffer[] = '<div class="form-group">' + "\n";
      								$buffer[] = sprintf('    <input type="text" class="form-control" name="%s" value="" placeholder="%s" />', $data_field->getKeyName(), $data_field->getName());
      								$buffer[] = '</div>';
      								echo implode("\n", $buffer);
      							?></textarea>
      						</div>
      					</div>
      				</div>
      			</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($data_field->getId())) { ?>
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
	<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
		<textarea name="data_field_set[dummy_set_id][name]" class="form-control" placeholder="enter display name"></textarea>
	</div>
	<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
		<input type="text" id="data_field_set_value_dummy_set_id" name="data_field_set[dummy_set_id][value]" class="form-control" value="" placeholder="enter value" />
		<div class="text-right hidden-xs hidden-sm"><small>0 leads total, 0 leads today</small></div>
	</div>
	<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
		<button type="button" class="form-control btn btn-danger btn-remove-item"><span class="glyphicon glyphicon-remove"></span></button>
	</div>
	<div class="clearfix"></div>
</div>

<script>
//<!--
$(document).ready(function() {	
	/* Validate the mapping function */
	$('#btn_validate').click(function() {
		$('#validation_result_<?php echo $data_field->getId() ?>').removeClass('alert-danger').removeClass('alert-success').html('').hide();
		var params = $('#data_field_form_<?php echo $data_field->getId() ?>').serialize();
		params += "&func=/admin/data-field-validate";
		$.rad.post('/api', params, function(data) {
			if (data.record.validation_result == '') {
			    $.rad.notify('Function validated', 'Validation was successful for this function');
			    $('#validation_result_<?php echo $data_field->getId() ?>').addClass('alert-success').html('Validation successful').show();
			} else {
				$.rad.notify.error('Function incorrect', 'Validation failed for this function');
				$('#validation_result_<?php echo $data_field->getId() ?>').addClass('alert-danger').html(data.record.validation_result).show();
			}
		});
	});
	
	$('#show_advanced_options_div_<?php echo $data_field->getId() ?>').click(function() {
		$('#advanced_options_div_<?php echo $data_field->getId() ?>').toggleClass('hidden');
		$(this).hide();
	});

	$('#access_type_<?php echo $data_field->getId() ?>').selectize({
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

	$('#storage_type_<?php echo $data_field->getId() ?>').selectize({
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

	$('#field_type_<?php echo $data_field->getId() ?>').selectize({
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
	
	$('#request_name_<?php echo $data_field->getId() ?>').selectize({
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

	$('#tags_<?php echo $data_field->getId() ?>').selectize({
	    delimiter: ',',
	    persist: true,
	    searchField: ['name'],
	    valueField: 'name',
	    labelField: 'name',
	    options: [
			{ name: "<?php echo implode('"}, {name: "', $tags) ?>" }
		],
	    create: function(input) {
	        return {name: input}
	    }
	});

	$('#data_field_form_<?php echo $data_field->getId() ?>').form(function(data) {
		$.rad.notify('Data Field Updated', 'The data field has been added/updated in the system');
		$('#datafield_search_form').trigger('submit');
		$('#edit_datafield_modal').modal('hide');
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

	$('#custom_code_examples').selectize().on('change', function() {
		if ($(this).val() == '1') {
			// Convert Yes/No to 1/0
			var custom_code = 'if (strtoupper(trim($value)) == "YES") {' + "\n";
			custom_code += '    return "1";' + "\n";
			custom_code += '}' + "\n";
			custom_code += 'return "0";' + "\n";
			$('#custom_code_<?php echo $data_field->getId() ?>').val(custom_code);
		} else if ($(this).val() == '2') {
			// Check if a value is in an array
			var custom_code = 'if (in_array("value_to_find", $value)) {' + "\n";
			custom_code += '    return "1";' + "\n";
			custom_code += '}' + "\n";
			custom_code += 'return "0";' + "\n";
			$('#custom_code_<?php echo $data_field->getId() ?>').val(custom_code);
		} else if ($(this).val() == '3') {
			// Combine 2 values on a lead
			var custom_code = 'return sprintf("%s %s", $lead->getValue("fname"), $lead->getValue("lname"));' + "\n";
			$('#custom_code_<?php echo $data_field->getId() ?>').val(custom_code);
		} else if ($(this).val() == '4') {
			// Find the city based on the zipcode
			var custom_code = 'if ($lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("city")->getKeyname() ?>") != "") {' + "\n";
			custom_code += '    return $lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("city")->getKeyname() ?>");' + "\n";
			custom_code += '}' + "\n";
			custom_code += 'if ($lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("zip")->getKeyname() ?>") != "") {' + "\n";
			custom_code += '    // do a lookup based on the zipcode' + "\n";
			custom_code += '    $city = \\Flux\\Zip::lookupCity($lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("zip")->getKeyname() ?>"));' + "\n";
			custom_code += '    return $city;' + "\n";
			custom_code += '}' + "\n";			
			$('#custom_code_<?php echo $data_field->getId() ?>').val(custom_code);
		} else if ($(this).val() == '5') {
			// Find the state based on the zipcode
			var custom_code = 'if ($lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("state")->getKeyname() ?>") != "") {' + "\n";
			custom_code += '    return $lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("state")->getKeyname() ?>");' + "\n";
			custom_code += '}' + "\n";
			custom_code += 'if ($lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("zip")->getKeyname() ?>") != "") {' + "\n";
			custom_code += '    // do a lookup based on the zipcode' + "\n";
			custom_code += '    $state = \\Flux\\Zip::lookupState($lead->getValue("<?php echo \Flux\DataField::retrieveDataFieldFromName("zip")->getKeyname() ?>"));' + "\n";
			custom_code += '    return $state;' + "\n";
			custom_code += '}' + "\n";			
			$('#custom_code_<?php echo $data_field->getId() ?>').val(custom_code);
		}
	});
});
<?php if (\MongoId::isValid($data_field->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this data field from the system?')) {
		$.rad.del({ func: '/admin/data-field/<?php echo $data_field->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this data field', 'You have deleted this data field.  You will need to refresh this page to see your changes.');
			$('#datafield_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>