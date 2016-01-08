<?php
	/* @var $fulfillment \Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>

<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/export/split-search">Splits</a></li>
	<li><a href="/admin/fulfillment-search">Fulfillments</a></li>
	<li class="active"><?php echo $fulfillment->getName() ?></li>
</ol>

<!-- Page Content -->

<!-- Page Content -->
<div class="panel-main">
	<div class="media">
		<div class="media-left">
			<i class="fa fa-cloud-upload fa-4x fa-border fa-inverse" style="background-Color:white;color:#000;"></i>
		</div>
		<div class="media-body">
			<h4 class="media-heading"><?php echo $fulfillment->getName() ?></h4>
			<div class=""><i><?php echo $fulfillment->getDescription() ?></i></div>
			<div class="">Owned by <?php echo $fulfillment->getClient()->getClientName() ?></div>
			<div class="">Pays $<?php echo number_format($fulfillment->getBounty(), 2, null, ',') ?></div> 
			<br /><br />
			<div class="">
				<div class="btn-group" role="group">
					<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#edit_modal" href="/admin/fulfillment-pane-edit?_id=<?php echo $fulfillment->getId() ?>"><span class="fa fa-pencil"></span> edit fulfillment</a>
				</div>
				<div class="btn-group" role="group">
					<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#map_preview_modal" href="/admin/fulfillment-pane-map-preview-modal?_id=<?php echo $fulfillment->getId() ?>"><span class="fa fa-search"></span> preview mapping</a>
				</div>
				<div class="btn-group" role="group">
					<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#clone_modal" href="/admin/fulfillment-pane-clone?_id=<?php echo $fulfillment->getId() ?>"><span class="fa fa-files-o"></span> clone</a>
				</div>
				<div class="btn-group" role="group">
					<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#test_modal" href="/admin/fulfillment-pane-test?_id=<?php echo $fulfillment->getId() ?>"><span class="fa fa-repeat"></span> test fulfillment</a>
				</div>
				<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-sm btn-danger" href="#"><span class="fa fa-trash-o"></span> delete</a>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<br /><br />

	<div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#mapping" aria-controls="mapping" role="tab" data-toggle="tab">Post Mapping</a></li>
			<li role="presentation"><a href="#preview" aria-controls="preview" role="tab" data-toggle="tab">Preview Mapping</a></li>
			<li role="presentation"><a href="#attempts" aria-controls="attempts" role="tab" data-toggle="tab">Attempts</a></li>
			<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
		</ul>
	
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="mapping">
				<form id="export_map_form" name="export_map_form" method="POST" action="/api" autocomplete="off" role="form">
					<input type="hidden" name="func" value="/admin/fulfillment-map" />
					<input type="hidden" name="_id" value="<?php echo $fulfillment->getId() ?>" />
					<br />
					<div class="row">
						<div class="col-md-9">
							<?php if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST) { ?>
								This export will <strong>POST</strong> to the following URL:
								<p />
								<pre><?php echo $fulfillment->getPostUrl() ?></pre>
							<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT) { ?>
								This export send data to <strong>InfusionSoft</strong> using the following credentials:
								<p />
								<pre>Host: <?php echo $fulfillment->getInfusionsoftHost() ?></pre>
								<pre>Api Key: <?php echo $fulfillment->getInfusionsoftApiKey() ?></pre>
							<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL) { ?>
								This export will send an <strong>email</strong> to the following recipients:
								<p />
								<pre><?php echo implode(", ", $fulfillment->getEmailAddress()) ?></pre>
							<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MAILCHIMP) { ?>
								This export will send the data to <strong>Mailchimp</strong> on the following account:
								<p />
								<pre>Api key: <?php echo $fulfillment->getMailchimpApiKey() ?></pre>
								<pre>List: <?php echo $fulfillment->getMailchimpList() ?></pre>
							<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_FTP) { ?>
								This export will send the data to the following <strong>FTP</strong> account:
								<p />
								<pre>Host: <?php echo $fulfillment->getFtpUsername() ?>@<?php echo $fulfillment->getFtpHostname() ?></pre>
								<?php if ($fulfillment->getFtpFolder() != '') { ?>
									<pre>Folder: <?php echo $fulfillment->getFtpFolder() ?></pre>
								<?php } ?>
							<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MANUAL) { ?>
								This export will be submitted <strong>manually</strong> and should just be flagged as fulfilled
								<p />
							<?php } ?>
						</div>
						<div class="col-md-3 text-right">
							<input type="submit" name="__saveMapping" class="btn btn-lg btn-primary" value="Save Mapping" />
						</div>
					</div>
					<hr />
					<div class="help-block">These are the fields that will be posted to the fulfillment script.</div>
					<table class="table table-responsive table-striped table-bordered table-condensed">
						<thead>
						   <tr>
							   <th>Post Parameter</th>
							   <th>Map To Datafield</th>
						   </tr>
						</thead>
						<tbody id="map_groups">
							<?php if (is_array($fulfillment->getMapping())) { ?>
							<?php 
								foreach($fulfillment->getMapping() AS $key => $fulfillment_map) {
							?>
								<tr class="map-group-item">
									<td>
										<div class="input-group">
											<input type="text" name="mapping[<?php echo $key ?>][field_name]" class="form-control" value="<?php echo $fulfillment_map->getFieldName() ?>" placeholder="POST field name (optional)" />
											<span class="input-group-btn">
												<button class="btn <?php echo ($fulfillment_map->getMappingFunc() == \Flux\FulfillmentMap::getDefaultMappingFunc()) ? 'btn-default' : 'btn-success' ?>" id="map_options-<?php echo $key ?>" type="button" data-toggle="modal" data-target="#map_options_modal" href="/admin/fulfillment-pane-map-options-modal?<?php echo http_build_query($fulfillment_map->toArray(true), null, '&') ?>&column_id=<?php echo $key ?>"><span class="fa fa-gear"></span></button>
												<button class="btn btn-danger btn-remove-map map_delete-<?php echo $key ?>" type="button"><span class="fa fa-trash-o"></span></button>
											</span>
										</div>
										<div class="help-block" style="padding-Left:5px;"><i id="map_defaults-<?php echo $key ?>" class="small"><?php echo $fulfillment_map->getDefaultValue() != '' ? "Default Value:" : "" ?> <?php echo $fulfillment_map->getDefaultValue() ?></i></div>
									</td>
									<td style="width:50%;">
										<select name="mapping[<?php echo $key;?>][datafield]" class="form-control selectize">
											<optgroup label="Custom Field">
												<option value="0" <?php echo !\MongoId::isValid($fulfillment_map->getDataField()->getDataFieldId()) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('key_name' => 'custom', 'name' => 'Custom Field', 'description' => 'Custom field such as an API Token', 'request_names' => '', 'tags' => array(0 => 'custom')))) ?>">Custom Field</option>
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
										<input type="hidden" id="mapping_default-<?php echo $key; ?>" name="mapping[<?php echo $key; ?>][default_value]" value="<?php echo htmlspecialchars($fulfillment_map->getDefaultValue()) ?>" />
										<input type="hidden" id="mapping_func-<?php echo $key; ?>" name="mapping[<?php echo $key; ?>][mapping_func]" value="<?php echo htmlspecialchars($fulfillment_map->getMappingFunc()) ?>" />
									</td>
								</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
					<button type="button" class="btn btn-info" id="add_map_btn"><span class="fa fa-plus"></span> add new field</button>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="preview">
				<br />
				<?php if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST) { ?>
					This export will <strong>POST</strong> to the following URL:
					<p />
					<pre><?php echo $fulfillment->getPostUrl() ?></pre>
				<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT) { ?>
					This export send data to <strong>InfusionSoft</strong> using the following credentials:
					<p />
					<pre>Host: <?php echo $fulfillment->getInfusionsoftHost() ?></pre>
					<pre>Api Key: <?php echo $fulfillment->getInfusionsoftApiKey() ?></pre>
				<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL) { ?>
					This export will send an <strong>email</strong> to the following recipients:
					<p />
					<pre><?php echo implode(", ", $fulfillment->getEmailAddress()) ?></pre>
				<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MAILCHIMP) { ?>
					This export will send the data to <strong>Mailchimp</strong> on the following account:
					<p />
					<pre>Api key: <?php echo $fulfillment->getMailchimpApiKey() ?></pre>
					<pre>List: <?php echo $fulfillment->getMailchimpList() ?></pre>
				<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_FTP) { ?>
					This export will send the data to the following <strong>FTP</strong> account:
					<p />
					<pre>Host: <?php echo $fulfillment->getFtpUsername() ?>@<?php echo $fulfillment->getFtpHostname() ?></pre>
					<?php if ($fulfillment->getFtpFolder() != '') { ?>
						<pre>Folder: <?php echo $fulfillment->getFtpFolder() ?></pre>
					<?php } ?>
				<?php } else if ($fulfillment->getExportClass()->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MANUAL) { ?>
					This export will be submitted <strong>manually</strong> and should just be flagged as fulfilled
					<p />
				<?php } ?>
				<p />
				with these fields set:
				<p />
				<table class="table table-responsive table-bordered">
					<thead>
						<?php foreach ($fulfillment->getMapping() as $fulfillment_mapping) { ?>
							<tr>
								<td>
									<strong><?php echo $fulfillment_mapping->getFieldName() == '' ? $fulfillment_mapping->getDataField()->getDataField()->getKeyName() : $fulfillment_mapping->getFieldName() ?></strong>
								</td>
								<td>
									<?php if ($fulfillment_mapping->getMappingFunc() != \Flux\FulfillmentMap::getDefaultMappingFunc()) { ?>
										<div class="custom-function">
											<button class="btn btn-sm btn-info btn-show-code pull-right">show</button>
											<em>- custom function -</em>
											<div class="code-preview collapse"><div class="clearfix"></div><pre><?php echo $fulfillment_mapping->getMappingFunc() ?></pre></div>
										</div>
									<?php } else if ($fulfillment_mapping->getDataField()->getDataField()->getKeyName() == 'custom') { ?>
										<?php echo $fulfillment_mapping->getDefaultValue() ?>
									<?php } else { ?>
										<?php echo $fulfillment_mapping->getDataField()->getDataField()->getKeyName() ?>
									<?php } ?>
									<?php if ($fulfillment_mapping->getDefaultValue() != '') { ?>
										<i><?php echo $fulfillment_mapping->getDefaultValue() ?></i>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</thead>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="attempts">
				<div class="help-block">Recent leads that have been attempted on this fulfillment will appear here</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="settings">
				<div class="help-block">Not sure what will be here yet</div>
			</div>
		</div>
	</div>
</div>

<div class="map-group-item row" style="display:none;" id="dummy_map_div">
	<div>
		<div class="input-group">
			<input type="text" name="mapDummyReqName[dummy_datafield_id][field_name]" class="form-control" value="" placeholder="POST field name (optional)" />
			<span class="input-group-btn small">
				<button class="btn btn-default" id="map_options-dummy_datafield_id" type="button" data-toggle="modal" data-target="#map_options_modal" href="/admin/fulfillment-pane-map-options-modal?column_id=dummy_datafield_id"><span class="fa fa-gear"></span></button>
				<button class="btn btn-danger btn-remove-map map_delete-dummy_datafield_id" type="button"><span class="fa fa-trash-o"></span></button>
			</span>
		</div>
		<div class="help-block"><i id="map_defaults-dummy_datafield_id" class="small"></i></div>
	</div>
	<div>
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
		<input type="hidden" id="mapping_default-dummy_datafield_id" name="mapping[dummy_datafield_id][default_value]" value="" />
		<input type="hidden" id="mapping_func-dummy_datafield_id" name="mapping[dummy_datafield_id][mapping_func]" value="<?php echo htmlspecialchars(\Flux\FulfillmentMap::getDefaultMappingFunc()) ?>" />
	</div>
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

<!-- confirm delete modal -->
<div class="modal fade" id="delete_modal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Are you certain you want to delete this fulfillment?  All data associated with it will be removed as well.<p /></div><div class="modal-footer"><div id="confirm_delete" class="btn btn-danger">Yes, I'm sure</div> <div class="btn btn-default" data-dismiss="modal">No, close</div></div></div></div></div>

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
				ret_val = '<div style="width:95%;">';
				ret_val += '<div class="pull-right"><div class="label label-success">' + escape(item.key_name) + '</div></div>';
				ret_val += '<div><b>' + escape(item.name ? item.name : item.key_name) + '</b></div>';
				ret_val += '<div class="text-muted small">' + escape(item.description ? item.description : '') + '</div>';
				ret_val += '</div>';
				return ret_val;
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
			$.rad.notify('Mapping updated', 'The mapping has been saved to the fulfillment');
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

		var tr = $('<tr class="map-group-item" />');
		var td = $('<td />').appendTo(tr);
		td.append(map_div.find('div:first'));
		var td = $('<td style="width:50%;" />').appendTo(tr);
		second_div = map_div.find('div:last');
		second_div.find('select').selectize($selectize_options);
		td.append(second_div);
		
		$('#map_groups').append(tr);
	});

	/* Clear the filter modal when it is hidden */
	$('#map_options_modal').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	$('#map_groups').on('click', '.btn-remove-map', function() {
		$(this).closest('.map-group-item').remove();
	});

	// delete the client information
	$('#confirm_delete').click(function() {
		$.rad.del('/api', {func: '/admin/fulfillment/<?php echo $fulfillment->getId() ?>' }, function() {
			window.location = '/admin/fulfillment-search';
		});
	});
});
//-->
</script>