<?php
	/* @var $fulfillment Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$export_handlers = $this->getContext()->getRequest()->getAttribute("export_handlers", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo ($fulfillment->getId() > 0) ? 'Edit' : 'Add' ?> Fulfillment Handler</h4>
</div>
<form class="" id="fulfillment_form_<?php echo $fulfillment->getId() ?>" method="<?php echo ($fulfillment->getId() > 0) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/fulfillment" />
	<input type="hidden" name="status" value="<?php echo \Flux\Fulfillment::FULFILLMENT_STATUS_ACTIVE ?>" />
	<?php if ($fulfillment->getId() > 0) { ?>
		<input type="hidden" name="_id" value="<?php echo $fulfillment->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#mapping" role="tab" data-toggle="tab">Mapping</a></li>
			<li role="presentation" class=""><a href="#scheduling" role="tab" data-toggle="tab">Scheduling</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
  				<div class="help-block">Fulfillment handlers control how a lead can be fulfilled via FTP, POST, InfusionSoft, or Email</div>
				<div class="form-group">
					<label class="control-label" for="export_type">Select how you want to fulfill data:</label>
					<select class="form-control" name="export_class_name" id="export_class_name" placeholder="Select export type...">
						<option value="">Select export type...</option>
						<?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
							<option value="<?php echo $export_class_name ?>"<?php echo $fulfillment->getExportClassName() == $export_class_name ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $export_class_instance->getName(), 'export_class_name' => $export_class_name, 'description' => $export_class_instance->getDescription()))) ?>"><?php echo $export_class_instance->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<hr />
				<!-- Basic Export Settings such as name and description -->
				<div id="export_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() == 0 ? 'hidden' : ''; ?>">
					<div class="help-block">Associate this handler with a client and give it a name</div>
					<div class="form-group">
						<input type="text" id="name" name="name" class="form-control" required="required" placeholder="Enter nickname..." value="<?php echo $fulfillment->getName() ?>" />
					</div>
					
					<div class="form-group">
						<select class="form-control" name="client[client_id]" id="client_id" required="required" placeholder="Assign an owner to this handler...">
							<option value="">Assign an owner to this handler...</option>
							<?php
								/* @var $client \Flux\Client */ 
								foreach ($clients AS $client) { 
							?>
								<option value="<?php echo $client->getId() ?>"><?php echo $client->getName() ?></option>
							<?php } ?>
						</select>
					</div>
					<hr />
				</div>
				
				<!-- FTP specific settings -->
				<div id="ftp_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_FTP ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the FTP settings used to connect to the server</div>
					<div class="form-group">
						<label class="control-label" for="ftp_hostname">FTP Host</label>
						<div class="input-group">
							<input class="form-control" type="text" id="ftp_hostname" name="ftp_hostname" value="<?php echo $fulfillment->getFtpHostname() ?>" placeholder="enter ftp hostname" />
							<div class="input-group-addon">
								<div id="test_ftp" style="cursor:pointer;">test ftp</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="ftp_username">FTP Username</label>
						<input class="form-control" type="text" id="ftp_username" name="ftp_username" value="<?php echo $fulfillment->getFtpUsername() ?>" placeholder="username credential" />
					</div>
					<div class="form-group">
						<label class="control-label" for="ftp_password">FTP Password</label>
						<input class="form-control" type="text" id="ftp_password" name="ftp_password" value="<?php echo $fulfillment->getFtpPassword() ?>" placeholder="password credential" />
					</div>
					<div class="form-group">
						<label class="control-label" for="ftp_folder">FTP Folder</label>
						<input class="form-control" type="text" id="ftp_folder" name="ftp_folder" value="<?php echo $fulfillment->getFtpFolder() ?>" placeholder="subfolder for storing new files"  />
					</div>
				</div>
				
				<!-- POST specific settings -->
				<div id="post_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the POST url below.  Any POST parameters will be added to the mapping</div>
					<div class="form-group">
						<label class="control-label" for="post_url">Post URL</label>
						<textarea name="post_url" id="post_url" class="form-control" rows="4" placeholder="enter posting url here..."></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="post_url">Response success text</label>
						<textarea name="success_msg" id="success_msg" class="form-control" rows="2" placeholder="enter the response text that denotes a successful post"></textarea>
					</div>
				</div>
			   
				<!-- Email specific settings -->
				<div id="email_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL ? 'hidden' : ''; ?>">
					<div class="help-block">Enter one or more email addresses in the box below</div>
					<div class="form-group">
						<label class="control-label" for="email_address">Email Address</label>
						<input type="text" name="email_address" id="email_address" class="form-control" value="<?php echo implode(",", $fulfillment->getEmailAddress()) ?>" placeholder="enter emails here..." /><br />
					</div>
				</div>
		   
				<!-- INFUSIONSOFT specific settings -->
				<div id="infusionsoft_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT ? 'hidden' : ''; ?>">
					<div class="help-block">Enter your Infusionsoft domain name and API key found on your Infusionsoft Profile page</div>					
					<div class="form-group">
						<label class="control-label" for="infusionsoft_host">Infusionsoft Host</label>
						<input type="text" name="infusionsoft_host" id="infusionsoft_host" class="form-control" rows="4" placeholder="enter your infusionsoft domain name..." value="">
					</div>
					<div class="form-group">
						<label class="control-label" for="infusionsoft_api_key">Api Key</label>
						<textarea name="infusionsoft_api_key" id="infusionsoft_api_key" class="form-control" rows="4" placeholder="enter your infusionsoft api key..."></textarea><br />
					</div>
				</div>
  			</div>
			<div role="tabpanel" class="tab-pane fade" id="mapping">
				<div class="help-block">Map data fields to columns according to the specification from the client</div>
				<div id="map_groups" style="height:500px;overflow:scroll;">
					<?php
						if (is_array($fulfillment->getMapping())) {
							$counter = 0;
							foreach($fulfillment->getMapping() AS $fulfillment_map) {
					?>
						<div class="form-group map-group-item">
							<div class="hidden-xs hidden-sm col-md-1 col-lg-1">
								<label>#<?php echo $counter + 1 ?></label>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<input type="text" id="mapping_<?php echo $counter ?>" name="mapping[<?php echo $counter;?>][field_name]" class="form-control" value="<?php echo $fulfillment_map->getFieldName() ?>" placeholder="POST field name (required)" />
								<input type="text" name="mapping[<?php echo $counter;?>][default_value]" class="form-control" value="<?php echo $fulfillment_map->getDefaultValue() ?>" placeholder="default value (optional)" />
							</div>
							<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
								<select name="mapping[<?php echo $counter;?>][datafield_id]" class="form-control map-selectize">
									<optgroup label="Custom Field">
										<option value="0"<?php echo $fulfillment_map->getDataField()->getDataFieldId() == 0 ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => 0, 'name' => 'Custom Field', 'keyname' => 'custom', 'description' => 'Custom field such as an API Token', 'request_names' => ''))) ?>">Custom Field</option>
									</optgroup>
									<optgroup label="Data Fields">
										<?php
											/* @var $datafield \Flux\DataField */ 
											foreach($data_fields AS $datafield) { 
										?>
											<option value="<?php echo $datafield->getId() ?>"<?php echo $datafield->getId() == $fulfillment_map->getDataField()->getDataFieldId() ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => $datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
										<?php } ?>
									</optgroup>
								</select>
								<input type="hidden" id="mapping_func-<?php echo $counter ?>" name="mapping[<?php echo $counter ?>][mapping_func]" value="<?php echo htmlspecialchars($fulfillment_map->getMappingFunc()) ?>" />
							</div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
								<a class="btn btn-info map_options-<?php echo $counter ?>" type="button" data-toggle="modal" data-target="#map_options_modal" href="/admin/fulfillment-pane-map-options-modal?column_id=<?php echo $counter ?>">Options</a>
								<button type="button" class="btn btn-danger btn-remove-map">
									<span class="glyphicon glyphicon-remove"></span>
								</button>
							</div>
							<div class="clearfix"></div>
						</div>
						<?php $counter++; ?>
					<?php } ?>
				</div>
				<?php } ?>
				<div class="form-group">
					<div class="col-xs-2 col-xs-offset-10 col-sm-2 col-sm-offset-10 col-md-1 col-md-offset-11 col-lg-1 col-lg-offset-11">
						<button type="button" class="btn btn-info" id="add_map_btn"><span class="glyphicon glyphicon-plus"></span></button>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="scheduling">
				<div class="help-block">Set the schedule for automatically running this fulfillment</div>
				<div class="form-group">
					<label class="control-label" for="scheduling[interval]">How often do you want to send the data?</label>
					<select name="scheduling[interval]" id="scheduling_interval" class="form-control">
						<optgroup label="Realtime">
							<option value="immediately" <?php echo (isset($fulfillment->getScheduling()['interval']) && $fulfillment->getScheduling()['interval'] == 'immediately') ? "selected" : "" ?>>Immediately</option>
						</optgroup>
						<optgroup label="Batched">
							<option value="daily" <?php echo (isset($fulfillment->getScheduling()['interval']) && $fulfillment->getScheduling()['interval'] == 'daily') ? "selected" : "" ?>>Daily</option>
							<option value="weekly" <?php echo (isset($fulfillment->getScheduling()['interval']) && $fulfillment->getScheduling()['interval'] == 'weekly') ? "selected" : "" ?>>Weekly</option>
							<option value="monthly_first" <?php echo (isset($fulfillment->getScheduling()['interval']) && $fulfillment->getScheduling()['interval'] == 'monthly_first') ? "selected" : "" ?>>Monthly on the 1st</option>
							<option value="monthly" <?php echo (isset($fulfillment->getScheduling()['interval']) && $fulfillment->getScheduling()['interval'] == 'monthly') ? "selected" : "" ?>>Monthly on the 31st</option>
						</optgroup>
					</select>
				</div>
				
				<p />
				<div class="form-group">
					<label class="control-label" for="scheduling[days]">Select the days that this user can accept data?</label>
					<select name="scheduling[days][]" id="scheduling_days" multiple class="form-control">
						<option value="0" <?php echo isset($fulfillment->getScheduling()['days']) && in_array('0', $fulfillment->getScheduling()['days']) ? "selected" : "" ?>>Sunday</option>
						<option value="1" <?php echo isset($fulfillment->getScheduling()['days']) && in_array('1', $fulfillment->getScheduling()['days']) ? "selected" : "" ?>>Monday</option>
						<option value="2" <?php echo isset($fulfillment->getScheduling()['days']) && in_array('2', $fulfillment->getScheduling()['days']) ? "selected" : "" ?>>Tuesday</option>
						<option value="3" <?php echo isset($fulfillment->getScheduling()['days']) && in_array('3', $fulfillment->getScheduling()['days']) ? "selected" : "" ?>>Wednesday</option>
						<option value="4" <?php echo isset($fulfillment->getScheduling()['days']) && in_array('4', $fulfillment->getScheduling()['days']) ? "selected" : "" ?>>Thursday</option>
						<option value="5" <?php echo isset($fulfillment->getScheduling()['days']) && in_array('5', $fulfillment->getScheduling()['days']) ? "selected" : "" ?>>Friday</option>
						<option value="6" <?php echo isset($fulfillment->getScheduling()['days']) && in_array('6', $fulfillment->getScheduling()['days']) ? "selected" : "" ?>>Saturday</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php if ($fulfillment->getId() > 0) { ?>
			<input type="button" class="btn btn-danger" value="Delete Handler" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>

<!-- Dummy template for adding new mappings -->
<div class="form-group map-group-item" style="display:none;" id="dummy_map_div">
	<div class="hidden-xs hidden-sm col-md-1 col-lg-1">
		<label class="col-md-2 control-label" for="mapping[dummy_datafield_id][datafield_id]">#dummy_column_id</label>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
		<input type="text" name="mapDummyReqName[dummy_datafield_id][field_name]" class="form-control" value="" placeholder="POST field name (required)" />
		<input type="text" name="mapDummyReqName[dummy_datafield_id][default_value]" class="form-control" value="" placeholder="default value (optional)" />		
	</div>
	<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
		<select name="mapDummyReqName[dummy_datafield_id][datafield_id]" class="form-control">
			<optgroup label="Custom Field">
				<option value="0" data-data="<?php echo htmlentities(json_encode(array('_id' => 0, 'name' => 'Custom Field', 'keyname' => 'custom', 'description' => 'Custom field such as an API Token', 'request_names' => ''))) ?>">Custom Field</option>
			</optgroup>
			<optgroup label="Data Fields">
				<?php
					/* @var $datafield \Flux\DataField */ 
					foreach($data_fields AS $datafield) { 
				?>
					<option value="<?php echo $datafield->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $datafield->getId(), 'name' => $datafield->getName(), 'keyname' => $datafield->getKeyName(), 'description' => $datafield->getDescription(), 'request_names' => implode(", ", array_merge(array($datafield->getKeyName()), $datafield->getRequestName()))))) ?>"><?php echo $datafield->getName() ?></option>
				<?php } ?>
			</optgroup>
		</select>
		<input type="hidden" id="mapping_func-dummy_datafield_id" name="mapping[dummy_datafield_id][mapping_func]" value="<?php echo htmlspecialchars(\Flux\FulfillmentMap::getDefaultMappingFunc()) ?>" />
	</div>
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
		<a class="btn btn-info map_options-dummy_datafield_id" type="button" data-toggle="modal" data-target="#map_options_modal" href="/admin/fulfillment-pane-map-options-modal?column_id=dummy_datafield_id">Options</a>
		<button type="button" class="btn btn-danger btn-remove-map">
			<span class="glyphicon glyphicon-remove"></span>
		</button>
	</div>
	<div class="clearfix"></div>
</div>

<script>
//<!--
$(document).ready(function() {
	$('#fulfillment_form_<?php echo $fulfillment->getId() ?>').form(function(data) {
		$.rad.notify('Fulfillment Updated', 'The fulfillment handler has been added/updated in the system');
		$('#fulfillment_search_form').trigger('submit');
	}, {keep_form:1});

	$('#status,#export_type,#client_id,#scheduling_interval,#scheduling_days').selectize();

	$('#email_address').selectize({
		delimiter: ',',
		persist: false,
		create: function(input) {
			return {
				value: input,
				text: input
			}
		}
	});

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

	$('.map-selectize').selectize($selectize_options);

	$('#add_map_btn').on('click', function() {
		var index_number = $('#map_groups > .map-group-item').length;
		var map_div = $('#dummy_map_div').clone();
		map_div.removeAttr('id');
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

	$('#map_groups').on('click', '.btn-remove-map', function() {
		$(this).closest('.form-group').remove();
	});

	$('#export_class_name').selectize({
		valueField: '_id',
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

	$('#parse_url').click(function() {
		$.rad.get('/api', { func: '/admin/build-post-url', 'post_url': $('#post_url').val() }, function(data) {
			if (data.record) {
				$('#post_url').val(data.record.post_url);
			}
		});
	});

	$('#export_class_name').change(function() {
		$('#export_settings').addClass('hidden');
		$('#ftp_settings').addClass('hidden');
		$('#email_settings').addClass('hidden');
		$('#post_settings').addClass('hidden');
		$('#infusionsoft_settings').addClass('hidden');
		$('#export_settings').addClass('hidden');
		if ($('#export_class_name').val() != '') {
			$('#export_settings').removeClass('hidden');
		}
		<?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
			if ($('#export_class_name').val() == '<?php echo $export_class_name ?>') {
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_FTP) { ?>
		 			$('#ftp_settings').removeClass('hidden');
				<?php } else if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL) { ?>
		 			$('#email_settings').removeClass('hidden');
				<?php } else if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST) { ?>
		 			$('#post_settings').removeClass('hidden');
				<?php } if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT) { ?>
		 			$('#infusionsoft_settings').removeClass('hidden');
				<?php } ?>
				return true;
			}
		<?php } ?>
	});

	$('#test_ftp').click(function() {
		if (confirm('This will test the connection to the FTP server and verify that the username and password is correct.')) {
			$.rad.get('/api', { func: '/export/test-ftp', ftp_hostname: $('#ftp_hostname').val(), ftp_port: $('#ftp_port').val(), ftp_username: $('#ftp_username').val(), ftp_password: $('#ftp_password').val(), ftp_folder: $('#ftp_folder').val() }, function(data) {
				if (data.record) {
					$.rad.notify('FTP Connection Successful', 'We were able to connect to the FTP server and verify that it is correct.');
				}
			});
		}
	});
});

<?php if ($fulfillment->getId() > 0) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this user from the system?')) {
		$.rad.del({ func: '/admin/fulfillment/<?php echo $fulfillment->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this fulfillment', 'You have deleted this fulfillment.  You will need to refresh this page to see your changes.');
			$('#fulfillment_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>
