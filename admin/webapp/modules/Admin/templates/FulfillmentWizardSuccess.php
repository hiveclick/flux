<?php
	/* @var $fulfillment Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$export_handlers = $this->getContext()->getRequest()->getAttribute("export_handlers", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="modal-header bg-success">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($fulfillment->getId()) ? 'Edit' : 'Add' ?> Fulfillment Handler</h4>
</div>
<form class="" id="fulfillment_form_<?php echo $fulfillment->getId() ?>" method="<?php echo \MongoId::isValid($fulfillment->getId()) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/fulfillment" />
	<input type="hidden" name="status" value="<?php echo \Flux\Fulfillment::FULFILLMENT_STATUS_ACTIVE ?>" />
	<?php if (\MongoId::isValid($fulfillment->getId())) { ?>
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
						<input type="text" id="name" name="name" class="form-control" placeholder="Enter nickname..." value="<?php echo $fulfillment->getName() ?>" />
					</div>
					
					<div class="form-group">
						<textarea id="description" name="description" class="form-control" placeholder="Enter description..."><?php echo $fulfillment->getDescription() ?></textarea>
					</div>
					<hr />
					<div class="help-block">Select who will pay when this fulfillment is successful and how much they will pay</div>
					<div class="form-group">
						<select class="form-control" name="client[client_id]" id="client_id" placeholder="Assign an owner to this handler...">
							<option value=""></option>
							<optgroup label="Administrators">
								<?php
									/* @var $client \Flux\Client */ 
									foreach ($clients AS $client) { 
								?>
									<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
										<option value="<?php echo $client->getId() ?>"><?php echo $client->getName() ?></option>
									<?php } ?>
								<?php } ?>
							</optgroup>
							<optgroup label="Affiliates">
								<?php
									/* @var $client \Flux\Client */ 
									foreach ($clients AS $client) { 
								?>
									<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
										<option value="<?php echo $client->getId() ?>"><?php echo $client->getName() ?></option>
									<?php } ?>
								<?php } ?>
							</optgroup>
						</select>
					</div>
					
					<div class="form-group">
						<div class="input-group">
						   <div class="input-group-addon">$</div>
						   <input type="text" name="bounty" id="bounty" class="form-control" value="<?php echo $fulfillment->getBounty() > 0 ? number_format($fulfillment->getBounty(), 2) : '' ?>" placeholder="Enter payout from advertiser...">
						</div>
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
				
				<!-- PING-POST specific settings -->
				<div id="ping_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_PING_POST ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the PING url below.  Any POST parameters not in the list of filtered fields will be added to the mapping</div>
					<div class="form-group">
						<label class="control-label" for="post_url">Ping URL</label>
						<textarea name="ping_url" id="ping_url" class="form-control" rows="4" placeholder="enter ping url here..."></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="ping_success_msg">Response success text</label>
						<textarea name="ping_success_msg" id="ping_success_msg" class="form-control" rows="2" placeholder="enter the response text that denotes a successful ping"></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="ping_field_filter">Ping Fields</label>
						<input type="text" name="ping_field_filter" id="ping_field_filter" class="form-control" placeholder="enter fields that should be ommitted from the ping (PII fields)" value="" />
					</div>
					
					<div class="help-block">Enter the POST url below.  Any POST parameters will be added to the mapping</div>
					<div class="form-group">
						<label class="control-label" for="ping_post_url">Post URL</label>
						<textarea name="ping_post_url" id="ping_post_url" class="form-control" rows="4" placeholder="enter posting url here..."></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="ping_post_success_msg">Response success text</label>
						<textarea name="ping_post_success_msg" id="ping_post_success_msg" class="form-control" rows="2" placeholder="enter the response text that denotes a successful post"></textarea>
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
				
				<!-- FORM FILL specific settings -->
				<div id="formfill_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MULTI_POST ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the TRACKING url below.  This is the url provided by the affiliate network that redirects you to the landing page</div>
					<div class="form-group">
						<label class="control-label" for="tracking_url">Tracking URL</label>
						<textarea name="tracking_url" id="tracking_url" class="form-control" rows="4" placeholder="enter tracking url here..."></textarea>
					</div>
					<div class="help-block">Enter the FORM POST url below.  You can retrieve this from viewing the source of the landing page</div>
					<div class="form-group">
						<label class="control-label" for="form_post_url">Post URL</label>
						<textarea name="form_post_url" id="form_post_url" class="form-control" rows="4" placeholder="enter posting url here..."></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="form_success_msg">Response success text</label>
						<textarea name="form_success_msg" id="form_success_msg" class="form-control" rows="2" placeholder="enter the response text that denotes a successful post"></textarea>
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
				
				<!-- MAILCHIMP specific settings -->
				<div id="mailchimp_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MAILCHIMP ? 'hidden' : ''; ?>">
					<div class="help-block">Enter your Mailchimp API key and the mailing list you want subscribers to be added to</div>					
					<div class="form-group">
						<label class="control-label" for="mailchimp_api_key">Api Key</label>
						<textarea name="mailchimp_api_key" id="mailchimp_api_key" class="form-control" rows="4" placeholder="enter your mailchimp api key..."></textarea><br />
					</div>
					<div class="form-group">
						<label class="control-label" for="mailchimp_list">Mailchimp Mailing List</label>
						<div class="row">
							<div class="col-md-10"><select name="mailchimp_list" id="mailchimp_list" placeholder="hit refresh to load the mailing lists..."></select></div>
							<div class="col-md-2"><a id="refresh_mc_lists" href="#" class="btn btn-info">Reload Lists</a></div>
						</div>
					</div>
					<div class="help-block">You need to add new mappings for <i>email</i>, <i>firstname</i>, <i>lastname</i>, <i>addr1</i>, <i>addr2</i>, <i>city</i>, <i>state</i>, <i>zip</i>, and <i>country</i>.  They should be mapped to the appropriate fields.</div>
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
						<div class="form-group map-group-item row">
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
											/* @var $data_field \Flux\DataField */ 
											foreach($data_fields AS $data_field) { 
										?>
											<option value="<?php echo $data_field->getId() ?>"<?php echo $data_field->getId() == $fulfillment_map->getDataField()->getDataFieldId() ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
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
		<?php if (\MongoId::isValid($fulfillment->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Handler" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>

<!-- Dummy template for adding new mappings -->
<div class="form-group map-group-item row" style="display:none;" id="dummy_map_div">
	<div class="hidden-xs hidden-sm col-md-1 col-lg-1">
		<label class="col-md-2 control-label" for="mapping[dummy_datafield_id][datafield_id]">#dummy_column_id</label>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
		<input type="text" name="mapDummyReqName[dummy_datafield_id][field_name]" class="form-control" value="" placeholder="POST field name (required)" />
		<input type="text" name="mapDummyReqName[dummy_datafield_id][default_value]" class="form-control" value="" placeholder="default value (optional)" />		
	</div>
	<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
		<select name="mapDummyReqName[dummy_datafield_id][datafield]" class="form-control">
			<optgroup label="Custom Field">
				<option value="0" data-data="<?php echo htmlentities(json_encode(array('_id' => 0, 'name' => 'Custom Field', 'keyname' => 'custom', 'description' => 'Custom field such as an API Token', 'request_names' => ''))) ?>">Custom Field</option>
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
		$('#edit_fulfillment_modal').modal('hide');
	}, {keep_form:1});

	$('#status,#export_type,#client_id,#scheduling_interval,#scheduling_days,#mailchimp_list').selectize();

	$('#refresh_mc_lists').click(function() {
		// Refresh the mailchimp lists based on the api key
		$mc_api_key = $('#mailchimp_api_key').val();
		$region = $mc_api_key.substring($mc_api_key.indexOf("-")+1);
		$.get('/api', { func: '/lists/list', apikey: $mc_api_key, '_api_url': 'https://' + $region + '.api.mailchimp.com/2.0/' }, function(data) {
			$select = $('#mailchimp_list').selectize()[0].selectize;
			$select.clearOptions();
			data.data.forEach(function(item) {
				$select.addOption({text: item.name + ' (' + item.default_from_name + ')', value: item.id});
			});
			$select.refreshOptions();
		}, 'json');
	});
	
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

	$('#ping_field_filter').selectize({
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
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				var label = item.name || item.key;
				var caption = item.description ? item.description : null;
				var keyname = item.key_name ? item.key_name : null;
				var tags = item.tags ? item.tags : new Array();
				var tag_span = '';
				$.each(tags, function(j, tag_item) {
					tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
				});
				return '<div style="width:99%;padding-right:25px;">' +
					'<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
					(caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
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
		$('#formfill_settings').addClass('hidden');
		$('#ping_settings').addClass('hidden');
		$('#export_settings').addClass('hidden');
		$('#ftp_settings').addClass('hidden');
		$('#email_settings').addClass('hidden');
		$('#post_settings').addClass('hidden');
		$('#infusionsoft_settings').addClass('hidden');
		$('#mailchimp_settings').addClass('hidden');
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
				<?php } else if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MULTI_POST) { ?>
		 			$('#formfill_settings').removeClass('hidden');
				<?php } if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT) { ?>
		 			$('#infusionsoft_settings').removeClass('hidden');
				<?php } if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MAILCHIMP) { ?>
		 			$('#mailchimp_settings').removeClass('hidden');
				<?php } if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_PING_POST) { ?>
		 			$('#ping_settings').removeClass('hidden');
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

<?php if (\MongoId::isValid($fulfillment->getId())) { ?>
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
