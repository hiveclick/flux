<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
	$data_fields = $this->getContext()->getRequest()->getAttribute('data_fields', array());
	$selected_data_fields = $this->getContext()->getRequest()->getAttribute('selected_data_fields', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Add/Modify Data</h4>
</div>
<form class="form-horizontal" id="lead_data_field_form" name="lead_data_field_form" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/lead/lead-data-field" />
	<input type="hidden" name="_id" value="<?php echo $lead->getId() ?>" />
	<div class="modal-body">
		Use this form to add or modify data fields on this lead.  Simply click the button to add a data field and set its value.
		<p />
		<div id="data_field_posting_url_container">
			<?php 
				foreach ($selected_data_fields as $selected_data_field) { 
			?>
				<div class="form-group row">
					<div class="col-sm-6">
						<select name="posting_url_data_field_name[<?php echo $selected_data_field['posting_url_data_field_id'] ?>]" class="form-control selectize">
							<optgroup label="Data Fields">
								<?php foreach($data_fields AS $data_field) { 
									$data_field_set = $data_field->getDataFieldSet();
									array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
								?>
									<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
										<option value="<?php echo $data_field->getKeyName() ?>" <?php echo (isset($selected_data_field['posting_url_data_field_id']) && ($selected_data_field['posting_url_data_field_id'] == $data_field->getId())) ? 'selected="selected"' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
									<?php } ?>
								<?php } ?>
							</optgroup>
							<optgroup label="Derived">
							<?php foreach($data_fields AS $data_field) { 
									$data_field_set = $data_field->getDataFieldSet();
									array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
							?>
								<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED) { ?>
									<option value="<?php echo $data_field->getKeyName() ?>" <?php echo (isset($selected_data_field['posting_url_data_field_id']) && ($selected_data_field['posting_url_data_field_id'] == $data_field->getId())) ? 'selected="selected"' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
								<?php  } ?>
							<?php } ?>
							</optgroup>
							<optgroup label="Tracking">
								<?php foreach($data_fields AS $data_field) { 
										$data_field_set = $data_field->getDataFieldSet();
										array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
								?>
									<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
										<option value="<?php echo $data_field->getKeyName() ?>" <?php echo (isset($selected_data_field['posting_url_data_field_id']) && ($selected_data_field['posting_url_data_field_id'] == $data_field->getId())) ? 'selected="selected"' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
									<?php } ?>
								<?php } ?>
							</optgroup>
							<optgroup label="Events">
							<?php foreach($data_fields AS $data_field) { 
									$data_field_set = $data_field->getDataFieldSet();
									array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
							?>
								<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
									<option value="<?php echo $data_field->getKeyName() ?>" <?php echo (isset($selected_data_field['posting_url_data_field_id']) && ($selected_data_field['posting_url_data_field_id'] == $data_field->getId())) ? 'selected="selected"' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
								<?php  } ?>
							<?php } ?>
							</optgroup>
						</select>
					</div>
					<div class="col-sm-5">
						<select name="posting_url_data_field_value[<?php echo $selected_data_field['posting_url_data_field_id'] ?>][]" class="form-control selectize-text" multiple placeholder="Enter New Value">
							<?php if (count($selected_data_field['posting_url_data_field_set']) > 0) { ?>
								<?php foreach ($selected_data_field['posting_url_data_field_set'] as $data_field_set) { ?>
									<option value="<?php echo $data_field_set['value'] ?>" data-data="<?php echo htmlentities(json_encode($data_field_set)) ?>" <?php echo ((is_array($selected_data_field['posting_url_data_field_value']) && in_array($data_field_set['value'], $selected_data_field['posting_url_data_field_value'])) || (is_string($selected_data_field['posting_url_data_field_value']) && $selected_data_field['posting_url_data_field_value'] == $data_field_set['value'])) ? 'selected' : '' ?>><?php echo $data_field_set['name'] ?></option>
								<?php } ?>
							<?php } else { ?>
							    <?php if (is_array($selected_data_field['posting_url_data_field_value'])) { ?>
							        <?php foreach ($selected_data_field['posting_url_data_field_value'] as $value) { ?>
							            <option value="<?php echo $value ?>" selected><?php echo $value ?></option>
							        <?php } ?>
							    <?php } else { ?>
								    <option value="<?php echo $selected_data_field['posting_url_data_field_value'] ?>" selected><?php echo $selected_data_field['posting_url_data_field_value'] ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="col-sm-1">
						<button type="button" class="btn btn-sm btn-danger btn-remove-data_field">
							<span class="glyphicon glyphicon-minus"></span>
						</button>
					</div>
				</div>
				<div class="clearfix" />
			<?php } ?>
		</div>		
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-success btn-add-data_field"><span class="glyphicon glyphicon-plus"></span> Add Data Field</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save Changes</button>
	</div>
</form>

<!-- Dummy Data Field Div -->
<div class="form-group row" style="display:none;" id="dummy_posting_url_data_field">
	<div class="col-sm-6">
		<select name="posting_url_data_field_name[dummy-dummy_id]" class="form-control selectize">
			<optgroup label="Data Fields">
				<?php foreach($data_fields AS $data_fieldId => $data_field) { 
					$data_field_set = $data_field->getDataFieldSet();
					array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
				?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
					<?php } ?>
				<?php } ?>
			</optgroup>
			<optgroup label="Derived">
			<?php foreach($data_fields AS $data_fieldId => $data_field) { 
					$data_field_set = $data_field->getDataFieldSet();
					array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
			?>
				<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED) { ?>
					<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
				<?php  } ?>
			<?php } ?>
			</optgroup>
			<optgroup label="Tracking">
				<?php foreach($data_fields AS $data_fieldId => $data_field) { 
						$data_field_set = $data_field->getDataFieldSet();
						array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
				?>
					<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
						<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
					<?php } ?>
				<?php } ?>
			</optgroup>
			<optgroup label="Events">
			<?php foreach($data_fields AS $data_fieldId => $data_field) { 
					$data_field_set = $data_field->getDataFieldSet();
					array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
			?>
				<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
					<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
				<?php  } ?>
			<?php } ?>
			</optgroup>
		</select>
	</div>
	<div class="col-sm-5">
		<textarea name="posting_url_data_field_value[dummy-dummy_id][]" class="form-control selectize-text" placeholder="Enter New Value" rows="3"></textarea>
	</div>
	<div class="col-sm-1">
		<button type="button" class="btn btn-sm btn-danger btn-remove-data_field">
			<span class="glyphicon glyphicon-minus"></span>
		</button>
	</div>
</div>

<script>
//<!--
$(document).ready(function() {
	
	// Define our data field options
	var $selectize_options = {
		valueField: 'key_name',
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				var label = item.name || item.key;
	            var caption = item.description ? item.description : null;
	            var keyname = item.key_name ? item.key_name : null;
	            var tags = item.tags ? item.tags : null;
	            var tag_span = '';
				$.each(tags, function(j, tag_item) {
					tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
				});	            
	            return '<div style="width:100%;padding-right:25px;">' +
	                '<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
	                (caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
	                '<div>' + tag_span + '</div>' +   
	            '</div>';
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
		},
		onChange: function(value) {
			if (!value.length) return;
			var select_text = $(this.$dropdown).closest('.form-group').find('.selectize-text')[0].selectize;
			var data = this.options[value];
			select_text.clearOptions();
			$(data.data_field_set).each(function(i, item) {
				select_text.addOption(item);
			});
			select_text.refreshOptions();
		}
	};

	// Add new data fields and set them up along with value textboxes
	$('.btn-add-data_field').on('click', function() {
		var index_number = $('#data_field_posting_url_container > .form-group').length;
		var $dataFieldRow = $('#dummy_posting_url_data_field').clone(true);
		$dataFieldRow.removeAttr('id');
		$dataFieldRow.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/dummy_id/g, (index_number + 1));
			return oldHTML;
		});
		
		$('#data_field_posting_url_container').append($dataFieldRow);
		$dataFieldRow.find('.selectize').selectize($selectize_options);
		$dataFieldRow.find('.selectize-text').selectize({
			valueField: 'value',
			labelField: 'name',
			searchField: ['name'],
			sortField: 'name',
			sortDirection: 'ASC',
			diacritics:true,
			create: true,
			createOnBlur: true
		});
		$dataFieldRow.show();
	});

	// Setup existing data fields
	$('#data_field_posting_url_container .selectize').selectize($selectize_options);
	// Setup existing data field value textboxes
	$('#data_field_posting_url_container .selectize-text').selectize({
		valueField: 'value',
		labelField: 'name',
		searchField: ['name'],
		sortField: 'name',
		sortDirection: 'ASC',
		diacritics:true,
		create: true,
		createOnBlur: true
	});
	/*
	$('#data_field_posting_url_container .selectize').each(function(i, item) {
		if ($(this)[0].selectize == undefined) return;
		var select_text_obj = $(this).closest('.form-group').find('.selectize-text');
		var select_text = select_text_obj[0].selectize;
		var data = $(this)[0].selectize.options[$(this)[0].selectize.getValue()];
		$(data.data_field_set).each(function(i, item) {
			console.log(item);
			select_text.addOption(item);
		});
		select_text.refreshOptions();
	});
	*/
	
	
	
	// button to remove data fields
	$('.btn-remove-data_field').on('click', function() {
		$(this).closest('.form-group').remove();
	});

	// submit the form
	$('#lead_data_field_form').form(function(data) {
		$.rad.notify('Data Saved', 'The data fields have been saved to this lead.');
		$('#add-data-field-modal').modal('hide');
	},{keep_form:true});
});
//-->
</script>