<?php
	/* @var $fulfillment \Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a data-toggle="modal" data-target="#edit_modal" href="/admin/fulfillment-pane-edit?_id=<?php echo $fulfillment->getId() ?>">edit fulfillment</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" data-target="#map_preview_modal" href="/admin/fulfillment-pane-map-preview-modal?_id=<?php echo $fulfillment->getId() ?>">preview mapping</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" data-target="#clone_modal" href="/admin/fulfillment-pane-clone?_id=<?php echo $fulfillment->getId() ?>">clone</a></li>
					<li><a data-toggle="modal" data-target="#test_modal" href="/admin/fulfillment-pane-test?_id=<?php echo $fulfillment->getId() ?>">test</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" id="btn_delete_sm" data-target="#delete_modal" href="#"><span class="text-danger">delete</span></a></li>
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#edit_modal" href="/admin/fulfillment-pane-edit?_id=<?php echo $fulfillment->getId() ?>">edit fulfillment</a>
			</div>
			<div class="btn-group" role="group">
			    <a class="btn btn-info" data-toggle="modal" data-target="#map_preview_modal" href="/admin/fulfillment-pane-map-preview-modal?_id=<?php echo $fulfillment->getId() ?>">preview mapping</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#clone_modal" href="/admin/fulfillment-pane-clone?_id=<?php echo $fulfillment->getId() ?>">clone</a>
				<a class="btn btn-info" data-toggle="modal" data-target="#test_modal" href="/admin/fulfillment-pane-test?_id=<?php echo $fulfillment->getId() ?>">test fulfillment</a>
			</div>
			<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-danger" href="#">delete</a>
		</div>
	</div>
	<h1><?php echo $fulfillment->getName() ?> <small>Manage Fulfillment</small></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/admin/fulfillment-search">Fulfillments</a></li>
	<li class="active"><?php echo $fulfillment->getName() ?></li>
</ol>

<!-- Page Content -->
<div class="help-block">This is the default mapping for this fulfillment.  If you need to change the posting url, then click on edit fulfillment above.</div>
<form class="form-horizontal" id="export_map_form" name="export_map_form" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/fulfillment-map" />
	<input type="hidden" name="_id" value="<?php echo $fulfillment->getId() ?>" />
	<div id="map_groups_header" class="clearfix">
		<div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
		<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4"><b>Post Parameter</b></div>
		<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5"><b>Map to Datafield</b></div>
		<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
		    <button type="button" class="btn btn-info" id="add_map_btn"><span class="glyphicon glyphicon-plus"></span></button>
			<input type="submit" name="__saveMapping" class="btn btn-success" value="Save Mapping" />
		</div>
	</div>
	<hr />
	<div id="map_groups">
		<?php
			if (is_array($fulfillment->getMapping())) {
				$counter = 0;
				foreach($fulfillment->getMapping() AS $fulfillment_map) {
		?>
		<div class="map-group-item">
			<div class="hidden-xs hidden-sm col-md-1 col-lg-1">
				<label class="col-md-2 control-label" for="mapping[<?php echo $counter;?>][datafield]">Col&nbsp;#<?php echo $counter + 1 ?></label>
			</div>
			<div class="hidden-md hidden-lg">
				<label class="control-label" for="mapping[<?php echo $counter;?>][datafield]">Col&nbsp;#<?php echo $counter + 1 ?></label>
			</div>
			<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
				<div class="form-group">
					<input type="text" name="mapping[<?php echo $counter;?>][field_name]" class="form-control" value="<?php echo $fulfillment_map->getFieldName() ?>" placeholder="POST field name (optional)" />
					<input type="text" name="mapping[<?php echo $counter;?>][default_value]" class="form-control" value="<?php echo $fulfillment_map->getDefaultValue() ?>" placeholder="default value (optional)" />
				</div>
			</div>
			<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
				<select name="mapping[<?php echo $counter;?>][datafield]" class="form-control selectize">
					<optgroup label="Custom Field">
						<option value="0" <?php echo $fulfillment_map->getDataField()->getDataFieldId() == 0 ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('key_name' => 'custom', 'name' => 'Custom Field', 'description' => 'Custom field such as an API Token', 'request_names' => '', 'tags' => array(0 => 'custom')))) ?>">Custom Field</option>
					</optgroup>
					<optgroup label="Data Fields">
						<?php
							/* @var $data_field \Flux\DataField */ 
							foreach($data_fields AS $data_field) { 
						?>
							<option value="<?php echo $data_field->getId() ?>" <?php echo ($fulfillment_map->getDataField()->getDataFieldId() == $data_field->getId()) ? 'selected' : '' ?>  data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName()), $data_field->getRequestName())))) ?>"><?php echo $data_field->getName() ?></option>
						<?php } ?>
					</optgroup>
				</select>
				<input type="hidden" id="mapping_func-<?php echo $counter; ?>" name="mapping[<?php echo $counter; ?>][mapping_func]" value="<?php echo htmlspecialchars($fulfillment_map->getMappingFunc()) ?>" />
			</div>
			<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
				<a class="btn btn-<?php echo ($fulfillment_map->getMappingFunc() == \Flux\FulfillmentMap::getDefaultMappingFunc()) ? 'info' : 'warning' ?> map_options-<?php echo $counter ?>" type="button" data-toggle="modal" data-target="#map_options_modal" href="/admin/fulfillment-pane-map-options-modal?<?php echo http_build_query($fulfillment_map->toArray(true), null, '&') ?>&column_id=<?php echo $counter ?>"><?php echo ($fulfillment_map->getMappingFunc() == \Flux\FulfillmentMap::getDefaultMappingFunc()) ? 'Options&nbsp;' : 'Options*' ?></a>
				<a href="#" type="button" class="btn btn-danger btn-remove-map"><span class="glyphicon glyphicon-remove"></span></a>
			</div>
			<div class="clearfix"></div>	
		</div>
		<?php 
				$counter++;
				}
			} 
		?>
	</div>
</form>

<div class="map-group-item" style="display:none;" id="dummy_map_div">
	<div class="hidden-xs hidden-sm col-md-1 col-lg-1">
		<label class="col-md-2 control-label" for="mapping[<?php echo $counter;?>][datafield]">Col&nbsp;#dummy_column_id</label>
	</div>
	<div class="hidden-md hidden-lg">
		<label class="control-label" for="mapping[<?php echo $counter;?>][datafield]">Col&nbsp;#dummy_column_id</label>
	</div>
	<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
		<div class="form-group">
			<input type="text" name="mapDummyReqName[dummy_datafield_id][field_name]" class="form-control" value="" placeholder="POST field name (optional)" />
			<input type="text" name="mapDummyReqName[dummy_datafield_id][default_value]" class="form-control" value="" placeholder="default value (optional)" />
		</div>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
		<select name="mapDummyReqName[dummy_datafield_id][datafield]" class="form-control">
			<optgroup label="Custom Field">
				<option value="0" data-data="<?php echo htmlentities(json_encode(array('key_name' => 'custom', 'name' => 'Custom Field', 'description' => 'Custom field such as an API Token', 'request_names' => '', 'tags' => array(0 => 'custom')))) ?>">Custom Field</option>
			</optgroup>
			<optgroup label="Data Fields">
				<?php
					/* @var $data_field \Flux\DataField */ 
					foreach($data_fields AS $data_field) { 
				?>
					<option value="<?php echo $data_field->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
				<?php } ?>
			</optgroup>
		</select>
		<input type="hidden" id="mapping_func-dummy_datafield_id" name="mapping[dummy_datafield_id][mapping_func]" value="<?php echo htmlspecialchars(\Flux\FulfillmentMap::getDefaultMappingFunc()) ?>" />
	</div>
	<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
		<a class="btn btn-info map_options-dummy_datafield_id" type="button" data-toggle="modal" data-target="#map_options_modal" href="/admin/fulfillment-pane-map-options-modal?column_id=dummy_datafield_id">Options</a>
		<a href="#" type="button" class="btn btn-danger btn-remove-map"><span class="glyphicon glyphicon-remove"></span></a>
	</div>
	<div class="clearfix"></div>
</div>

<!-- Map Custom Function modal -->
<div class="modal fade" id="map_options_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>

<!-- Map Preview modal -->
<div class="modal fade" id="map_preview_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>

<!-- Fulfillment Edit modal -->
<div class="modal fade" id="edit_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>

<!-- Fulfillment Test modal -->
<div class="modal fade" id="test_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>

<!-- Fulfillment Clone modal -->
<div class="modal fade" id="clone_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {

	// Define our data field options
	var $selectize_options = {
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		render: {
			item: function(item, escape) {
				var label = item.name || item.key_name;
	            var caption = item.description ? item.description : null;
	            var keyname = item.key_name ? item.key_name : null;
	            var tags = item.tags ? item.tags : new Array();
	            var tag_span = '';
				$.each(tags, function(j, tag_item) {
					tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
				});
	            return '<div style="width:99%;padding-right:25px;">' +
	                '<b>' + escape(item.name) + '</b> <span class="pull-right label label-success">' + escape(item.key_name) + '</span><br />' +
	                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
	                '<div>' + tag_span + '</div>' +   
	            '</div>';
			},
			option: function(item, escape) {
				var label = item.name || item.key;
	            var caption = item.description ? item.description : null;
	            var keyname = item.key_name ? item.key_name : null;
	            var tags = item.tags ? item.tags : new Array();
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
		map_div.removeAttr('id');
		map_div.find('select').selectize($selectize_options);
		$('#map_groups').append(map_div);
		map_div.show();
	});

	/* Clear the filter modal when it is hidden */
	$('#map_options_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	$('#map_groups').on('click', '.btn-remove-map', function() {
		$(this).closest('.map-group-item').remove();
	});

	$('#btn_delete,#btn_delete_sm').click(function() {
		if (confirm('Are you sure you want to delete this data field from the system?')) {
			$.rad.del({ func: '/admin/fulfillment/<?php echo $fulfillment->getId() ?>' }, function(data) {
				$.rad.notify('You have deleted this fulfillment', 'You have deleted this fulfillment successfully.');
				window.location.href = '/admin/fulfillment-search';
			});
		}
	})
});
//-->
</script>