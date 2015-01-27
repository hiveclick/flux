<?php
	/* @var $client_export Flux\ClientExport */
	$client_export = $this->getContext()->getRequest()->getAttribute("client_export", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$export_handlers = $this->getContext()->getRequest()->getAttribute("export_handlers", array());
?>
<div id="header">
	<div class="pull-right visible-xs">
		<button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<h2><a href="/client/fulfillment-search">Fulfillment Handlers</a> <small><?php echo $client_export->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
	<ul id="export_tabs" class="nav nav-pills">
		<li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Main</a></li>
		<li><a id="tabs-a-map" href="#tabs-map" data-toggle="tab" data-url="/client/fulfillment-pane-map?_id=<?php echo $client_export->getId() ?>">Map</a></li>
		<li><a id="tabs-a-scheduling" href="#tabs-scheduling" data-toggle="tab" data-url="/client/fulfillment-pane-scheduling?_id=<?php echo $client_export->getId() ?>">Scheduling</a></li>
		<li><a id="tabs-a-export" href="#tabs-export" data-toggle="tab" data-url="/client/fulfillment-pane-export?_id=<?php echo $client_export->getId() ?>">Exports</a></li>
		<li><a id="tabs-a-spy" href="#tabs-spy" data-toggle="tab" data-url="/client/fulfillment-pane-spy?_id=<?php echo $client_export->getId() ?>">Spy</a></li>
	</ul>
</div>
<div id="tab-content-container" class="tab-content">
	<div id="tabs-main" class="tab-pane active">
		<div class="help-block">Manage an export and map data fields to columns per the clients specifications</div>
		<br/>
		<form class="form-horizontal" id="fulfillment_form" name="export_form" method="PUT" action="/api" autocomplete="off" role="form">
		   <input type="hidden" name="_id" value="<?php echo $client_export->getId() ?>" />
		   <input type="hidden" name="func" value="/client/client-export" />
		   <input type="hidden" name="client_id" value="<?php echo $client_export->getClientId() ?>" />
			<div class="form-group">
				<label class="col-md-2 control-label" for="name">Name</label>
				<div class="col-md-10">
					<input type="text" id="name" name="name" class="form-control" required="required" value="<?php echo $client_export->getName() ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="status">Status</label>
				<div class="col-md-10">
					<select class="form-control" name="status" id="status" required="required">
						<?php foreach(\Flux\Export::retrieveStatuses() AS $status_id => $status_name) { ?>
							<option value="<?php echo $status_id; ?>"<?php echo $client_export->getStatus() == $status_id ? ' selected="selected"' : ''; ?>><?php echo $status_name; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="export_type">Export Type</label>
				<div class="col-md-10">
					<select class="form-control" name="export_class_name" id="export_class_name" required="required" placeholder="Select export type...">
						<option value="">Select export type...</option>
						<?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
							<option value="<?php echo $export_class_name ?>"<?php echo $client_export->getExportClassName() == $export_class_name ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $export_class_instance->getName(), 'export_class_name' => $export_class_name, 'description' => $export_class_instance->getDescription()))) ?>"><?php echo $export_class_instance->getName() ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div id="ftp_settings" class="<?php echo $client_export->getExportClass()->getClientExportType() != \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_FTP ? 'hidden' : ''; ?>">
				<div class="form-group">
					<label class="col-md-2 control-label" for="ftp_hostname">FTP Host/Port</label>
					<div class="col-md-10 form-inline row">
						<input class="form-control" type="text" id="ftp_hostname" name="ftp_hostname" value="<?php echo $client_export->getFtpHostname() ?>" />
						<input class="form-control" type="text" id="ftp_port" size="3" name="ftp_port" value="<?php echo $client_export->getFtpPort() ?>" />
						<input type="button" id="test_ftp" name="test_ftp" class="btn btn-info" value="test ftp" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="ftp_username">FTP Username</label>
					<div class="col-md-10">
						<input class="form-control" type="text" id="ftp_username" name="ftp_username" value="<?php echo $client_export->getFtpUsername() ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="ftp_password">FTP Password</label>
					<div class="col-md-10">
						<input class="form-control" type="text" id="ftp_password" name="ftp_password" value="<?php echo $client_export->getFtpPassword() ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="ftp_folder">FTP Folder</label>
					<div class="col-md-10">
						<input class="form-control" type="text" id="ftp_folder" name="ftp_folder" value="<?php echo $client_export->getFtpFolder() ?>" />
					</div>
				</div>
			</div>

			<div id="post_settings" class="<?php echo $client_export->getExportClass()->getClientExportType() != \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_POST ? 'hidden' : ''; ?>">
				<div class="form-group">
					<label class="col-md-2 control-label" for="parse_url">Post URL</label>
					<div class="col-md-8">
						<textarea name="post_url" rows="4" class="form-control"><?php echo $client_export->getPostUrl() ?></textarea>
					</div>
					<div class="col-md-2">
						<input type="button" id="parse_url" name="parse_url" class="btn btn-info" value="parse url" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="success_msg">Success String</label>
					<div class="col-md-10">
						<textarea name="success_msg" class="form-control"><?php echo $client_export->getSuccessMsg() ?></textarea>
					</div>
				</div>
			</div>
			
			<div id="email_settings" class="<?php echo $client_export->getExportClass()->getClientExportType() != \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_EMAIL ? 'hidden' : ''; ?>">
			   <div class="form-group">
					<label class="col-md-2 control-label" for="email_address">Email Address</label>
					<div class="col-md-10">
						<input type="text" name="email_address" id="email_address" class="form-control" value="<?php echo implode(",", $client_export->getEmailAddress()) ?>" placeholder="enter emails here..." /><br />
					</div>
				</div>
			</div>
			
			<div id="infusionsoft_settings" class="<?php echo $client_export->getExportClass()->getClientExportType() != \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_INFUSIONSOFT ? 'hidden' : ''; ?>">
			   <div class="form-group">
				   	<label class="col-md-2 control-label" for="infusionsoft_host">Infusionsoft Host</label>
					<div class="col-md-8">
						<input type="text" name="infusionsoft_host" id="infusionsoft_host" class="form-control" rows="4" placeholder="enter your infusionsoft domain name..." value="<?php echo $client_export->getInfusionsoftHost() ?>">
					</div>
			   </div>
			   <div class="form-group">
					<label class="col-md-2 control-label" for="infusionsoft_api_key">Api Key</label>
					<div class="col-md-8">
						<textarea name="infusionsoft_api_key" id="infusionsoft_api_key" class="form-control" rows="4" placeholder="enter your infusionsoft api key..."><?php echo $client_export->getInfusionsoftApiKey() ?></textarea><br />
					</div>
				</div>
		   </div>

			<div class="form-group">
				<div class="col-md-offset-2 col-md-10">
					<input type="submit" name="save" class="btn btn-success" value="Save" />
					<input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Export" />
				</div>
			</div>
		</form>
	</div>
	<div id="tabs-map" class="tab-pane"></div>
	<div id="tabs-scheduling" class="tab-pane"></div>
	<div id="tabs-export" class="tab-pane"></div>
	<div id="tabs-spy" class="tab-pane"></div>
</div>
<script>
//<!--
$(document).ready(function() {
	$('#fulfillment_form').form(function(data) {
		$.rad.notify('Fulfillment Updated', 'The fulfillment handler has been added/updated in the system');
	}, {keep_form:1});
	
	$('#status').selectize();
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
		$.rad.get('/api', { func: '/client/build-post-url', 'post_url': $('#post_url').val() }, function(data) {
			if (data.record) {
				$('#post_url').val(data.record.post_url);
			}
		});
	});

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		e.preventDefault();
		var hash = this.hash;
		if ($(this).attr("data-url")) {
			// only load the page the first time
			if ($(hash).html() == '') {
				// ajax load from data-url
				$(hash).load($(this).attr("data-url"));
			}
		}
	}).on('show.bs.tab', function (e) {
		try {
	   	   sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
		} catch (err) { }
	});

	$('#export_class_name').change(function() {
		<?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
			if ($('#export_class_name').val() == '<?php echo $export_class_name ?>') {
				<?php if ($export_class_instance->getClientExportType() == \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_FTP) { ?>
		 			$('#ftp_settings').removeClass('hidden');
				<?php } else { ?>
					$('#ftp_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getClientExportType() == \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_EMAIL) { ?>
		 			$('#email_settings').removeClass('hidden');
				<?php } else { ?>
					$('#email_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getClientExportType() == \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_POST) { ?>
		 			$('#post_settings').removeClass('hidden');
				<?php } else { ?>
					$('#post_settings').addClass('hidden');
				<?php } ?>
				<?php if ($export_class_instance->getClientExportType() == \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_INFUSIONSOFT) { ?>
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

	$('#btn_delete').click(function() {
		if (confirm('Are you sure you want to delete this export and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/client/client-export/<?php echo $client_export->getId() ?>' }, function(data) {
				$.rad.notify('Export Removed', 'This export has been removed from the system.');
			});
		}
	});

	// Store the last clicked tab so it can be loaded on page refreshes
	var localTabStorageName = <?php echo json_encode('client_export_tab_' . $client_export->getId()); ?>;
	var lastTab = sessionStorage.getItem(localTabStorageName);
	if (lastTab) {
		$('a[href='+lastTab+']').tab('show');
	} else {
		$('ul.nav-pills a:first').tab('show');
	}
});
//-->
</script>