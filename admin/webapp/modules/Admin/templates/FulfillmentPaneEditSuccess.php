<?php
	/* @var $fulfillment \Flux\Fulfillment */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$export_handlers = $this->getContext()->getRequest()->getAttribute("export_handlers", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Edit Fulfillment for <?php echo $fulfillment->getName() ?></h4>
</div>
<form id="fulfillment_form" name="export_form" method="PUT" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="_id" value="<?php echo $fulfillment->getId() ?>" />
	<input type="hidden" name="func" value="/admin/fulfillment" />
	<input type="hidden" name="client[client_id]" value="<?php echo $fulfillment->getClient()->getClientId() ?>" />
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#export_type" role="tab" data-toggle="tab">Export Type</a></li>
			<li role="presentation" class=""><a href="#scheduling" role="tab" data-toggle="tab">Scheduling</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
				<div class="help-block">Manage an export and map data fields to columns per the clients specifications</div>
				<div class="form-group">
					<label class="control-label" for="name">Name</label>
					<input type="text" id="name" name="name" class="form-control" value="<?php echo $fulfillment->getName() ?>" placeholder="enter a name for this fulfillment" />
				</div>
				
				<div class="form-group">
					<label class="control-label" for="description">Description</label>
					<textarea id="description" name="description" class="form-control" placeholder="enter a brief description for this fulfillment handler"><?php echo $fulfillment->getDescription() ?></textarea>
				</div>

				<div class="form-group">
					<label class="control-label" for="status">Status</label>
					<select class="form-control" name="status" id="status" placeholder="set whether this fulfillment is active or not">
						<option value="<?php echo \Flux\Fulfillment::FULFILLMENT_STATUS_ACTIVE ?>"<?php echo $fulfillment->getStatus() == \Flux\Fulfillment::FULFILLMENT_STATUS_ACTIVE ? ' selected="selected"' : ''; ?>>Active</option>
						<option value="<?php echo \Flux\Fulfillment::FULFILLMENT_STATUS_INACTIVE ?>"<?php echo $fulfillment->getStatus() == \Flux\Fulfillment::FULFILLMENT_STATUS_INACTIVE ? ' selected="selected"' : ''; ?>>Inactive</option>
					</select>
				</div>
				<hr />
				<div class="form-group">
					<label class="control-label" for="export_type">Fulfillment Payer</label>
					<select class="form-control" name="client[client_id]" id="client_id" placeholder="Select owner...">
						<?php foreach($clients AS $client) { ?>
							<option value="<?php echo $client->getId() ?>"<?php echo $fulfillment->getClient()->getClientId() == $client->getId() ? ' selected="selected"' : ''; ?>><?php echo $client->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label" for="bounty">Payout</label>
					<div class="input-group">
					   <div class="input-group-addon">$</div>
					   <input type="text" name="bounty" id="bounty" class="form-control" value="<?php echo number_format($fulfillment->getBounty(), 2) ?>" placeholder="Enter payout from advertiser...">
					</div>
				</div>
				<div class="help-block">Specify who will pay us and how much they will pay when this fulfillment is successfully received</div>	
				<hr />
				<div class="form-group">
					<div class="row">
						<div class="col-md-9">
							<label class="control-label" for="export_type">Trigger Fulfillment Flag</label>
							<div class="help-block">Determines if the fulfillment flag should be added to a lead when this fulfillment is successful</div>	
						</div>
						<div class="col-md-3 text-right">
							<input type="hidden" name="trigger_fulfillment_flag" value="0" />
							<input type="checkbox" id="trigger_fulfillment_flag_1" name="trigger_fulfillment_flag" value="1" <?php echo ($fulfillment->getTriggerFulfillmentFlag() == 1) ? 'checked' : '' ?> />
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="export_type">
				<div class="help-block">Select the type of export and enter any additional settings</div>
				<div class="form-group">
					<label class="control-label" for="export_type">Export Type</label>
					<select class="form-control" name="export_class_name" id="export_class_name" required="required" placeholder="Select export type...">
						<option value="">Select export type...</option>
						<?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
							<option value="<?php echo $export_class_name ?>"<?php echo $fulfillment->getExportClassName() == $export_class_name ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $export_class_instance->getName(), 'export_class_name' => $export_class_name, 'description' => $export_class_instance->getDescription()))) ?>"><?php echo $export_class_instance->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				
				<hr />

				<!-- FTP specific settings -->
				<div id="ftp_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_FTP ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the FTP credentials for the remote server</div>
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
						<input class="form-control" type="text" id="ftp_username" name="ftp_username" value="<?php echo $fulfillment->getFtpUsername() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="ftp_password">FTP Password</label>
						<input class="form-control" type="text" id="ftp_password" name="ftp_password" value="<?php echo $fulfillment->getFtpPassword() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="ftp_folder">FTP Folder</label>
						<input class="form-control" type="text" id="ftp_folder" name="ftp_folder" value="<?php echo $fulfillment->getFtpFolder() ?>" />
					</div>
				</div>
				
				<!-- PING-POST specific settings -->
				<div id="ping_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_PING_POST ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the PING url below.  Any POST parameters not in the list of filtered fields will be added to the mapping</div>
					<div class="form-group">
						<label class="control-label" for="post_url">Ping URL</label>
						<textarea name="ping_url" id="ping_url" class="form-control" rows="4" placeholder="enter ping url here..."><?php echo $fulfillment->getPingUrl() ?></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="ping_success_msg">Response success text</label>
						<textarea name="ping_success_msg" id="ping_success_msg" class="form-control" rows="2" placeholder="enter the response text that denotes a successful ping"><?php echo $fulfillment->getPingSuccessMsg() ?></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="ping_field_filter">Ping Fields</label>
						<input type="text" name="ping_field_filter" id="ping_field_filter" class="form-control" placeholder="enter fields that should be ommitted from the ping (PII fields)" value="<?php echo implode(",", $fulfillment->getPingFieldFilter()) ?>" />
					</div>
					
					<div class="help-block">Enter the POST url below.  Any POST parameters will be added to the mapping</div>
					<div class="form-group">
						<label class="control-label" for="ping_post_url">Post URL</label>
						<textarea name="ping_post_url" id="ping_post_url" class="form-control" rows="4" placeholder="enter posting url here..."><?php echo $fulfillment->getPingPostUrl() ?></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="ping_post_success_msg">Response success text</label>
						<textarea name="ping_post_success_msg" id="ping_post_success_msg" class="form-control" rows="2" placeholder="enter the response text that denotes a successful post"><?php echo $fulfillment->getPingPostSuccessMsg() ?></textarea>
					</div>
				</div>
				
				<!-- POST specific settings -->
				<div id="post_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the POST url and what to check in the result for a successful post.  Column mappings should be done on the <i>Post Mapping</i> tab below.</div>
					<div class="form-group">
						<label class="control-label" for="parse_url">Post URL</label>
						<textarea name="post_url" rows="4" class="form-control" placeholder="enter posting url here..."><?php echo $fulfillment->getPostUrl() ?></textarea>
					</div>
		
					<div class="form-group">
						<label class="control-label" for="success_msg">Success String</label>
						<textarea name="success_msg" class="form-control" placeholder="enter the response text that denotes a successful post"><?php echo $fulfillment->getSuccessMsg() ?></textarea>
					</div>
				</div>
				
				<!-- FORM FILL specific settings -->
				<div id="formfill_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MULTI_POST ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the TRACKING url below.  This is the url provided by the affiliate network that redirects you to the landing page</div>
					<div class="form-group">
						<label class="control-label" for="tracking_url">Tracking URL</label>
						<textarea name="tracking_url" id="tracking_url" class="form-control" rows="4" placeholder="enter tracking url here..."><?php echo $fulfillment->getTrackingUrl() ?></textarea>
					</div>
					<div class="help-block">Enter the FORM POST url below.  You can retrieve this from viewing the source of the landing page</div>
					<div class="form-group">
						<label class="control-label" for="form_post_url">Post URL</label>
						<textarea name="form_post_url" id="form_post_url" class="form-control" rows="4" placeholder="enter posting url here..."><?php echo $fulfillment->getPostUrl() ?></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="form_success_msg">Response success text</label>
						<textarea name="form_success_msg" id="form_success_msg" class="form-control" rows="2" placeholder="enter the response text that denotes a successful post"><?php echo $fulfillment->getSuccessMsg() ?></textarea>
					</div>
				</div>
				
				<!-- Email specific settings -->
				<div id="email_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL ? 'hidden' : ''; ?>">
					<div class="help-block">Enter one or more email addresses below</div>
					<div class="form-group">
						<label class="control-label" for="email_address">Email Address</label>
						<input type="text" name="email_address" id="email_address" class="form-control" value="<?php echo implode(",", $fulfillment->getEmailAddress()) ?>" placeholder="enter emails here..." /><br />
					</div>
				</div>
			
				<!-- INFUSIONSOFT specific settings -->
				<div id="infusionsoft_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the Infusionsoft domain name and api key found in the Infusionsoft Dashboard</div>
					<div class="form-group">
						<label class="control-label" for="infusionsoft_host">Infusionsoft Host</label>
						<input type="text" name="infusionsoft_host" id="infusionsoft_host" class="form-control" rows="4" placeholder="enter your infusionsoft domain name..." value="<?php echo $fulfillment->getInfusionsoftHost() ?>">
					</div>
					<div class="form-group">
						<label class="control-label" for="infusionsoft_api_key">Api Key</label>
						<textarea name="infusionsoft_api_key" id="infusionsoft_api_key" class="form-control" rows="4" placeholder="enter your infusionsoft api key..."><?php echo $fulfillment->getInfusionsoftApiKey() ?></textarea>
					</div>
				</div>
				
				<!-- MAILCHIMP specific settings -->
				<div id="mailchimp_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MAILCHIMP ? 'hidden' : ''; ?>">
					<div class="help-block">Enter your Mailchimp API key and the mailing list you want subscribers to be added to</div>					
					<div class="form-group">
						<label class="control-label" for="mailchimp_api_key">Api Key</label>
						<textarea name="mailchimp_api_key" id="mailchimp_api_key" class="form-control" rows="4" placeholder="enter your mailchimp api key..."><?php echo $fulfillment->getMailchimpApiKey() ?></textarea><br />
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
			<div role="tabpanel" class="tab-pane fade in" id="scheduling">
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
					<select name="scheduling[days][]" id="scheduling_days" multiple class="form-control" placeholder="all days">
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
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#fulfillment_form').form(function(data) {
		$.rad.notify('You have updated this fulfillment', 'You have updated this fulfillment.');
	}, {keep_form: true});

	$('#scheduling_interval,#scheduling_days,#status,#client_id,#mailchimp_list').selectize();

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
	
	$('#parse_url').click(function() {
		$.rad.get('/api', { func: '/admin/build-post-url', 'post_url': $('#post_url').val() }, function(data) {
			if (data.record) {
				$('#post_url').val(data.record.post_url);
			}
		});
	});

	$('#refresh_mc_lists').click(function() {
		// Refresh the mailchimp lists based on the api key
		reloadMailchimpLists();
	});

	$('#export_class_name').change(function() {
		<?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
			if ($('#export_class_name').val() == '<?php echo $export_class_name ?>') {
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_FTP) { ?>
		 			$('#ftp_settings').removeClass('hidden');
				<?php } else { ?>
					$('#ftp_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL) { ?>
		 			$('#email_settings').removeClass('hidden');
				<?php } else { ?>
					$('#email_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MULTI_POST) { ?>
		 			$('#formfill_settings').removeClass('hidden');
		 		<?php } else { ?>
			 		$('#formfill_settings').addClass('hidden');
		 		<?php } ?>
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST) { ?>
		 			$('#post_settings').removeClass('hidden');
				<?php } else { ?>
					$('#post_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_PING_POST) { ?>
					$('#ping_settings').removeClass('hidden');
				<?php } else { ?>
					$('#ping_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT) { ?>
		 			$('#infusionsoft_settings').removeClass('hidden');
				<?php } else { ?>
					$('#infusionsoft_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_MAILCHIMP) { ?>
		 			$('#mailchimp_settings').removeClass('hidden');
				<?php } else { ?>
					$('#mailchimp_settings').addClass('hidden');
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

	<?php if ($fulfillment->getMailchimpApiKey() != '') { ?>
	reloadMailchimpLists();
	<?php } ?>

	$('#trigger_fulfillment_flag_1').bootstrapSwitch({
		onText: 'Yes',
		offText: 'No'
	});
});

function reloadMailchimpLists() {
	$mc_api_key = $('#mailchimp_api_key').val();
	$region = $mc_api_key.substring($mc_api_key.indexOf("-")+1);
	$.get('/api', { func: '/lists/list', apikey: $mc_api_key, '_api_url': 'https://' + $region + '.api.mailchimp.com/2.0/' }, function(data) {
		$select = $('#mailchimp_list').selectize()[0].selectize;
		$select.clearOptions();
		data.data.forEach(function(item) {
			$select.addOption({text: item.name + ' (' + item.default_from_name + ')', value: item.id});
			if (item.id == '<?php echo $fulfillment->getMailchimpList() ?>') {
				$select.addItem(item.id);
			}
		});
		$select.refreshOptions();
		$select.refreshItems();
	}, 'json');
}
//-->
</script>