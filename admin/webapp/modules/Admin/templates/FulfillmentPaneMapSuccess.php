<?php
	/* @var $fulfillment \Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	
	$tracking_data_fields = $this->getContext()->getRequest()->getAttribute("tracking_data_fields", array());
	$default_data_fields = $this->getContext()->getRequest()->getAttribute("default_data_fields", array());
	$event_data_fields = $this->getContext()->getRequest()->getAttribute("event_data_fields", array());
	$derived_data_fields = $this->getContext()->getRequest()->getAttribute("derived_data_fields", array());
?>
<div class="help-block">Map data fields to columns according to the specification from the client</div>
<br/>
<div class="form-group map-group-item" style="display:none;" id="dummy_map_div">
	<div class="hidden-xs hidden-sm col-md-1 col-lg-1">
		<label class="col-md-2 control-label" for="mapping[dummy_datafield_id][datafield_id]">Col&nbsp;#dummy_column_id</label>
	</div>
	<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
		<input type="text" name="mapDummyReqName[dummy_datafield_id][field_name]" class="form-control" value="" placeholder="POST field name (optional)" />
		<input type="text" name="mapDummyReqName[dummy_datafield_id][default_value]" class="form-control" value="" placeholder="default value (optional)" />
	</div>
	<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
		<select name="mapDummyReqName[dummy_datafield_id][datafield_id]" class="form-control">
			<optgroup label="Custom Field">
				<option value="0" data-data="<?php echo htmlentities(json_encode(array('_id' => 0, 'name' => 'Custom Field', 'keyname' => 'custom', 'description' => 'Custom field such as an API Token', 'request_names' => ''))) ?>">Custom Field</option>
			</optgroup>
			<optgroup label="Data Fields">
				<?php foreach($default_data_fields AS $datafield) { ?>
					<option value="<?php echo $datafield->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
				<?php } ?>
			</optgroup>
			<optgroup label="Derived Fields">
				<?php foreach($derived_data_fields AS $datafield) { ?>
					<option value="<?php echo $datafield->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
				<?php } ?>
			</optgroup>
			<optgroup label="Tracking Fields">
				<?php foreach($tracking_data_fields AS $datafield) { ?>
					<option value="<?php echo $datafield->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
				<?php } ?>
			</optgroup>
			<optgroup label="Event Fields">
				<?php foreach($event_data_fields AS $datafield) { ?>
					<option value="<?php echo $datafield->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
				<?php } ?>
			</optgroup>
		</select>
		<input type="hidden" id="mapping_func-dummy_datafield_id" name="mapping[dummy_datafield_id][mapping_func]" value="<?php echo htmlspecialchars(\Flux\FulfillmentMap::getDefaultMappingFunc()) ?>" />
	</div>
	<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
		<a class="btn btn-info map_options-dummy_datafield_id" type="button" data-toggle="modal" data-target="#map_options_modal" href="/admin/fulfillment-pane-map-options-modal?column_id=dummy_datafield_id">Options</a>
		<button type="button" class="btn btn-danger btn-sm btn-remove-map">
			<span class="glyphicon glyphicon-remove"></span>
		</button>
	</div>
</div>

<form class="form-horizontal" id="export_map_form" name="export_map_form" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/fulfillment-map" />
	<input type="hidden" name="_id" value="<?php echo $fulfillment->getId() ?>" />
	<div id="map_groups">
		<?php
			if (is_array($fulfillment->getMapping())) {
				$counter = 0;
				foreach($fulfillment->getMapping() AS $fulfillment_map) {
		?>
		<div class="form-group map-group-item">
			<div class="hidden-xs hidden-sm col-md-1 col-lg-1">
				<label class="col-md-2 control-label" for="mapping[<?php echo $counter;?>][datafield_id]">Col&nbsp;#<?php echo $counter + 1 ?></label>
			</div>
			<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
				<input type="text" name="mapping[<?php echo $counter;?>][field_name]" class="form-control" value="<?php echo $fulfillment_map->getFieldName() ?>" placeholder="POST field name (optional)" />
				<input type="text" name="mapping[<?php echo $counter;?>][default_value]" class="form-control" value="<?php echo $fulfillment_map->getDefaultValue() ?>" placeholder="default value (optional)" />
			</div>
			<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
				<select name="mapping[<?php echo $counter;?>][datafield_id]" class="form-control selectize">
					<optgroup label="Custom Field">
						<option value="0"<?php echo $fulfillment_map->getDataFieldId() == 0 ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => 0, 'name' => 'Custom Field', 'keyname' => 'custom', 'description' => 'Custom field such as an API Token', 'request_names' => ''))) ?>">Custom Field</option>
					</optgroup>
					<optgroup label="Data Fields">
						<?php foreach($default_data_fields AS $datafield) { ?>
							<option value="<?php echo $datafield->getId() ?>"<?php echo $datafield->getId() == $fulfillment_map->getDataFieldId() ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
						<?php } ?>
					</optgroup>
					<optgroup label="Derived Fields">
						<?php foreach($derived_data_fields AS $datafield) { ?>
							<option value="<?php echo $datafield->getId() ?>"<?php echo $datafield->getId() == $fulfillment_map->getDataFieldId() ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
						<?php } ?>
					</optgroup>
					<optgroup label="Tracking Fields">
						<?php foreach($tracking_data_fields AS $datafield) { ?>
							<option value="<?php echo $datafield->getId() ?>"<?php echo $datafield->getId() == $fulfillment_map->getDataFieldId() ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
						<?php } ?>
					</optgroup>
					<optgroup label="Event Fields">
						<?php foreach($event_data_fields AS $datafield) { ?>
							<option value="<?php echo $datafield->getId() ?>"<?php echo $datafield->getId() == $fulfillment_map->getDataFieldId() ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
						<?php } ?>
					</optgroup>
				</select>
				<input type="hidden" id="mapping_func-<?php echo $counter; ?>" name="mapping[<?php echo $counter; ?>][mapping_func]" value="<?php echo htmlspecialchars($fulfillment_map->getMappingFunc()) ?>" />
			</div>
			<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
				<a class="btn btn-<?php echo ($fulfillment_map->getMappingFunc() == \Flux\FulfillmentMap::getDefaultMappingFunc()) ? 'info' : 'warning' ?> map_options-<?php echo $counter ?>" type="button" data-toggle="modal" data-target="#map_options_modal" href="/client/fulfillment-pane-map-options-modal?<?php echo http_build_query($fulfillment_map->toArray(true), null, '&') ?>&column_id=<?php echo $counter ?>"><?php echo ($fulfillment_map->getMappingFunc() == \Flux\FulfillmentMap::getDefaultMappingFunc()) ? 'Options&nbsp;' : 'Options*' ?></a>
				<button type="button" class="btn btn-danger btn-sm btn-remove-map">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</div>
			
		</div>
		<?php
				$counter++;
				}
		?>
		<hr />
		<?php 
			}
		?>

	</div>
	<div class="form-group row">
		<div class="col-md-offset-2 col-md-10">
			<button type="button" class="btn btn-info" id="add_map_btn"><span class="glyphicon glyphicon-plus"></span> Add Field</button>
			<button type="button" class="btn btn-primary" id="preview_map_btn" data-toggle="modal" data-target="#map_preview_modal">Preview Mapping</button>
			<input type="submit" name="__saveMapping" class="btn btn-success" value="Save Mapping" />
		</div>
	</div>
	<div class="help-text small">* denotes a custom mapping function is defined for this data field</div>
</form>

<!-- Map Custom Function modal -->
<div class="modal fade" id="map_options_modal">
	<div class="modal-dialog">
		<div class="modal-content"></div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Map Preview modal -->
<div class="modal fade" id="map_preview_modal">
	<div class="modal-dialog">
		<div class="modal-content"></div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
//<!--
$(document).ready(function() {
	
	var $selectize_options = {
		valueField: '_id',
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				return '<div>' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'<ul class="meta"><li class="language">Tags:</li><li><span>' + escape(item.request_names ? item.request_names : '') + '</span></li></ul>' +
				'</div>';
			},
			option: function(item, escape) {
				return '<div>' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
					'<ul class="meta"><li class="language">Tags:</li><li><span>' + escape(item.request_names ? item.request_names : '') + '</span></li></ul>' +
				'</div>';
			}
		}
	};

	$('.selectize').selectize($selectize_options);
	
	$('#export_map_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Mapping updated', 'The mapping has been saved to the export');
		}
	},{keep_form:true});

	$('#map_preview_modal').modal({
		show: false,
		remote: '/admin/fulfillment-pane-map-preview-modal?_id=<?php echo $fulfillment->getId() ?>'
	});

	$('#map_preview_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#add_map_btn').on('click', function() {
		var index_number = $('#map_groups > .map-group-item').length;
		var map_div = $('#dummy_map_div').clone();
		
		map_div.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/mapDummyReqName/g, 'mapping');
			oldHTML = oldHTML.replace(/dummy_datafield_id/g, index_number);
			oldHTML = oldHTML.replace(/dummy_column_id/g, (index_number + 1));
			return oldHTML;
		});
		map_div.find('select').selectize($selectize_options);
		$('#map_groups').append(map_div);
		map_div.show();
	});

	/* Clear the filter modal when it is hidden */
	$('#map_options_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	$('#map_groups').on('click', '.btn-remove-map', function() {
		$(this).closest('.form-group').remove();
	});
});
//-->
</script>