<?php
    /* @var $client_export Gun\ClientExport */
    $client_export = $this->getContext()->getRequest()->getAttribute("client_export", array());
    $export_handlers = $this->getContext()->getRequest()->getAttribute("export_handlers", array());
    $default_mapping = array();
    $default_mapping[] = 'email';
    $default_mapping[] = 'first_name';
    $default_mapping[] = 'last_name';
    $default_mapping[] = 'address1';
    $default_mapping[] = 'city';
    $default_mapping[] = 'state';
    $default_mapping[] = 'zip';
    $default_mapping[] = 'country';
    $default_mapping[] = 'phone';
    $default_mapping[] = 'gender';
    $default_mapping[] = 'birthdate';
    $default_mapping[] = 'url';
    $default_mapping[] = 'ip';
    $default_mapping[] = '__created';

?>
<div id="header">
   <h2><a href="/client/client-search">Clients</a> <small><a href="/client/client?_id=<?php echo $client_export->getClientId() ?>"><?php echo $client_export->getClient()->getName() ?></a> - New Export</small></h2>
</div>
<div class="help-block">Exports store how a client can receive data from a split, either through FTP or a Real-time post</div>
<br/>
<div id="tab-content-container" class="tab-content">
    <form class="form-horizontal" name="export_form" method="POST" action="" autocomplete="off" role="form">
        <!-- default export to active -->
        <input type="hidden" name="status" value="<?php echo \Gun\ClientExport::CLIENT_EXPORT_STATUS_ACTIVE ?>" />
        <input type="hidden" name="client_id" value="<?php echo $client_export->getClientId() ?>" />
        <!-- default mapping -->
        <?php
            $counter = 0;
            foreach ($default_mapping as $request_name) {
                if (isset(\Gun\DataField::retrieveActiveDataFieldsByRequestName()[$request_name])) {
        ?>
                <input type="hidden" name="mapping[<?php echo $counter ?>][datafield_id]" value="<?php echo \Gun\DataField::retrieveActiveDataFieldsByRequestName()[$request_name]->getId() ?>" />
                <input type="hidden" name="mapping[<?php echo $counter ?>][default]" value="" />
        <?php
                    $counter++;
                }
            }
        ?>

        <div class="form-group">
            <label class="col-md-2 control-label" for="name">Name</label>
            <div class="col-md-10">
                <input type="text" id="name" name="name" class="form-control" required="required" placeholder="Enter nickname..." value="<?php echo $client_export->getName() ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="export_type">Export Type</label>
            <div class="col-md-10">
                <select class="form-control" name="export_class_name" id="export_class_name" required="required" placeholder="Select export type...">
                    <option value="">Select export type...</option>
                    <?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
                        <option value="<?php echo $export_class_name ?>"<?php echo $client_export->getExportClassName() == $export_class_name ? ' selected="selected"' : ''; ?>><?php echo $export_class_instance->getName() ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div id="ftp_settings" class="<?php echo $client_export->getExportClass()->getClientExportType() != \Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_FTP ? 'hidden' : ''; ?>">
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_hostname">FTP Host/Port</label>
                <div class="col-md-10 form-inline row">
                    <input class="form-control" type="text" id="ftp_hostname" name="ftp_hostname" value="<?php echo $client_export->getFtpHostname() ?>" placeholder="remote hostname" />
                    <input class="form-control" type="text" id="ftp_port" size="3" name="ftp_port" value="<?php echo $client_export->getFtpPort() ?>" placeholder="21" />
                    <input type="button" id="test_ftp" name="test_ftp" class="btn btn-info" value="test ftp" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_username">FTP Username</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="ftp_username" name="ftp_username" value="<?php echo $client_export->getFtpUsername() ?>" placeholder="username credential" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_password">FTP Password</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="ftp_password" name="ftp_password" value="<?php echo $client_export->getFtpPassword() ?>" placeholder="password credential" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_folder">FTP Folder</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="ftp_folder" name="ftp_folder" value="<?php echo $client_export->getFtpFolder() ?>" placeholder="subfolder for storing new files"  />
                </div>
            </div>
           </div>

           <div id="post_settings" class="<?php echo $client_export->getExportClass()->getClientExportType() != \Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_POST ? 'hidden' : ''; ?>">
               <div class="form-group">
                    <label class="col-md-2 control-label" for="post_url">Post URL</label>
                    <div class="col-md-8">
                        <textarea name="post_url" id="post_url" class="form-control" rows="4" placeholder="enter posting url here..."></textarea><br />
                    </div>
                    <div class="col-md-2">
                        <input type="button" id="parse_url" name="parse_url" class="btn btn-info" value="parse url" />
                    </div>
                </div>
           </div>
           
           <div id="email_settings" class="<?php echo $client_export->getExportClass()->getClientExportType() != \Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_EMAIL ? 'hidden' : ''; ?>">
               <div class="form-group">
                    <label class="col-md-2 control-label" for="email_address">Email Address</label>
                    <div class="col-md-10">
                        <input type="text" name="email_address" id="email_address" class="form-control" value="<?php echo implode(",", $client_export->getEmailAddress()) ?>" placeholder="enter emails here..." /><br />
                    </div>
                </div>
           </div>

        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <input type="submit" name="save" class="btn btn-success" value="Save" />
            </div>
        </div>

    </form>
</div>

<script>
//<!--
$(document).ready(function() {
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
	
    $('#client_id,#export_type').selectize();

    $('#parse_url').click(function() {
        $.rad.get('/api', { func: '/client/build-post-url', 'post_url': $('#post_url').val() }, function(data) {
            if (data.record) {
                $('#post_url').val(data.record.post_url);
            }
        });
    });

    $('#export_class_name').change(function() {
    	<?php foreach($export_handlers AS $export_class_name => $export_class_instance) { ?>
        	if ($('#export_class_name').val() == '<?php echo $export_class_name ?>') {
            	<?php if ($export_class_instance->getClientExportType() == \Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_FTP) { ?>
         	        $('#ftp_settings').removeClass('hidden');
            	<?php } else { ?>
            	    $('#ftp_settings').addClass('hidden');
            	<?php } ?>
            	<?php if ($export_class_instance->getClientExportType() == \Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_EMAIL) { ?>
         	        $('#email_settings').removeClass('hidden');
            	<?php } else { ?>
            	    $('#email_settings').addClass('hidden');
            	<?php } ?>
            	<?php if ($export_class_instance->getClientExportType() == \Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_POST) { ?>
         	        $('#post_settings').removeClass('hidden');
            	<?php } else { ?>
            	    $('#post_settings').addClass('hidden');
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
