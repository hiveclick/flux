<?php
    /* @var $export Gun\Export */
    $export = $this->getContext()->getRequest()->getAttribute("export", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
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
   <h2><a href="/export/export-search">Exports</a> <small>New Export</small></h2>
</div>
<div class="help-block">Exports store how a client can receive data from a split, either through FTP or a Real-time post</div>
<br/>
<div id="tab-content-container" class="tab-content">
    <form class="form-horizontal" name="export_form" method="POST" action="" autocomplete="off" role="form">
        <!-- default export to active -->
        <input type="hidden" name="status" value="<?php echo \Gun\Export::EXPORT_STATUS_ACTIVE ?>" />
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
                <input type="text" id="name" name="name" class="form-control" required="required" placeholder="Enter nickname..." value="<?php echo $export->getName() ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="client_id">Advertising Client</label>
            <div class="col-md-10">
                <select class="form-control" name="client_id" id="client_id" required="required" placeholder="Select client...">
                    <option value="">Select client...</option>
                    <?php foreach($clients AS $client_record) { ?>
                        <option value="<?php echo $client_record->getId(); ?>"<?php echo $export->getClientId() == $client_record->getId() ? ' selected="selected"' : ''; ?>><?php echo $client_record->getName() ?> (<?php echo $client_record->getId() ?>)</option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" for="export_type">Export Type</label>
            <div class="col-md-10">
                <select class="form-control" name="export_type" id="export_type" required="required" placeholder="Select export type...">
                    <option value="">Select export type...</option>
                    <?php foreach(\Gun\Export::retrieveExportTypes() AS $export_type_id => $export_type_name) { ?>
                        <option value="<?php echo $export_type_id; ?>"<?php echo $export->getExportType() == $export_type_id ? ' selected="selected"' : ''; ?>><?php echo $export_type_name; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div id="ftp_settings" class="<?php echo $export->getExportType() != \Gun\Export::EXPORT_TYPE_BATCH ? 'hidden' : ''; ?>">
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_hostname">FTP Host/Port</label>
                <div class="col-md-10">
                    <div class="form-inline row">
                        <input class="form-control" type="text" id="ftp_hostname" name="ftp_hostname" value="<?php echo $export->getFtpHostname() ?>" placeholder="remote hostname" />
                        <input class="form-control" type="text" id="ftp_port" size="3" name="ftp_port" value="<?php echo $export->getFtpPort() ?>" placeholder="21" />
                        <input type="button" id="test_ftp" name="test_ftp" class="btn btn-info" value="test ftp" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_username">FTP Username</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="ftp_username" name="ftp_username" value="<?php echo $export->getFtpUsername() ?>" placeholder="username credential" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_password">FTP Password</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="ftp_password" name="ftp_password" value="<?php echo $export->getFtpPassword() ?>" placeholder="password credential" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_folder">FTP Folder</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="ftp_folder" name="ftp_folder" value="<?php echo $export->getFtpFolder() ?>" placeholder="subfolder for storing new files"  />
                </div>
            </div>
           </div>

           <div id="post_settings" class="<?php echo $export->getExportType() != \Gun\Export::EXPORT_TYPE_REALTIME ? 'hidden' : ''; ?>">
               <div class="form-group">
                <label class="col-md-2 control-label" for="ftp_folder">Post URL</label>
                <div class="col-md-10">
                    <textarea name="post_url" class="form-control" placeholder="enter posting url here..."></textarea>
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
    $('#client_id,#export_type').selectize();

    $('#export_type').change(function() {
        if ($('#export_type').val() == <?php echo \Gun\Export::EXPORT_TYPE_BATCH ?>) {
            $('#ftp_settings').removeClass('hidden');
            $('#post_settings').addClass('hidden');
        } else {
            $('#ftp_settings').addClass('hidden');
            $('#post_settings').removeClass('hidden');
        }
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
