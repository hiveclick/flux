<?php
	/* @var $export \Flux\Export */
	$fulfillment = $this->getContext()->getRequest()->getAttribute("fulfillment", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$export_handlers = $this->getContext()->getRequest()->getAttribute("export_handlers", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Edit Fulfillment</h4>
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
			
				<div class="form-group">
					<label class="control-label" for="export_type">Owner</label>
					<select class="form-control" name="client[client_id]" id="client_id" placeholder="Select owner...">
						<?php foreach($clients AS $client) { ?>
							<option value="<?php echo $client->getId() ?>"<?php echo $fulfillment->getClient()->getClientId() == $client->getId() ? ' selected="selected"' : ''; ?>><?php echo $client->getName() ?></option>
						<?php } ?>
					</select>
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

				<div id="post_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST ? 'hidden' : ''; ?>">
					<div class="help-block">Enter the POST url and what to check in the result for a successful post</div>
					<div class="form-group">
						<label class="control-label" for="parse_url">Post URL</label>
						<textarea name="post_url" rows="4" class="form-control" placeholder="enter posting url here..."><?php echo $fulfillment->getPostUrl() ?></textarea>
					</div>
		
					<div class="form-group">
						<label class="control-label" for="success_msg">Success String</label>
						<textarea name="success_msg" class="form-control" placeholder="enter the response text that denotes a successful post"><?php echo $fulfillment->getSuccessMsg() ?></textarea>
					</div>
				</div>
			
				<div id="email_settings" class="<?php echo $fulfillment->getExportClass()->getFulfillmentType() != \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_EMAIL ? 'hidden' : ''; ?>">
					<div class="help-block">Enter one or more email addresses below</div>
					<div class="form-group">
						<label class="control-label" for="email_address">Email Address</label>
						<input type="text" name="email_address" id="email_address" class="form-control" value="<?php echo implode(",", $fulfillment->getEmailAddress()) ?>" placeholder="enter emails here..." /><br />
					</div>
				</div>
			
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

	$('#scheduling_interval').selectize();
	$('#scheduling_days').selectize();
	$('#status,#client_id').selectize();
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
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_POST) { ?>
		 			$('#post_settings').removeClass('hidden');
				<?php } else { ?>
					$('#post_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getFulfillmentType() == \Flux\Export\ExportAbstract::FULFILLMENT_TYPE_INFUSIONSOFT) { ?>
		 			$('#infusionsoft_settings').removeClass('hidden');
				<?php } else { ?>
					$('#infusionsoft_settings').addClass('hidden');
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
//-->
</script>