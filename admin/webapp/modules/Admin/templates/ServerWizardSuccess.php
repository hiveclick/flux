<?php
    /* @var $server Gun\Server */
    $server = $this->getContext()->getRequest()->getAttribute("server", array());
?>
<div id="header">
   <h2><a href="/admin/server-search">Servers</a> <small>New Server</small></h2>
</div>
<div class="help-block">Create a new server that can host landing pages</div>
<br/>
<form class="form-horizontal" name="server_form" method="POST" action="" autocomplete="off">
    <input type="hidden" name="status" value="<?php echo \Gun\Server::SERVER_STATUS_ACTIVE ?>" />

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="ip_address">IP Address</label>
        <div class="col-sm-10">
            <input type="text" id="ip_address" name="ip_address" class="form-control" required placeholder="Server Main IP Address" value="<?php echo $server->getIpAddress() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="root_username">Username</label>
        <div class="col-sm-10">
            <input type="text" id="root_username" name="root_username" class="form-control" required placeholder="Root Username" value="<?php echo $server->getRootUsername() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="root_password">Password</label>
        <div class="col-sm-10">
            <input type="text" id="root_password" name="root_password" class="form-control" required placeholder="Root Password" value="<?php echo $server->getRootPassword() ?>" />
        </div>
    </div>

    <hr />

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="hostname">Hostname</label>
        <div class="col-sm-8">
            <input type="text" id="hostname" name="hostname" class="form-control" required placeholder="Server Hostname" value="<?php echo $server->getHostname() ?>" />
        </div>
        <div class="col-sm-2">
            <a href="#" class="btn btn-info btn-block" id="btn_lookup_hostname" name="btn_lookup_hostname" value="lookup hostname">lookup hostname</a>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="alternate_hostname">Alternate Hostname</label>
        <div class="col-sm-10">
            <input type="text" id="alternate_hostname" name="alternate_hostname" class="form-control" required placeholder="Alternate Hostname" value="<?php echo $server->getAlternateHostname() ?>" />
        </div>
    </div>

    <hr />

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="docroot_dir">DocumentRoot Folder</label>
        <div class="col-sm-10">
            <input type="text" id="docroot_dir" name="docroot_dir" class="form-control" required placeholder="Location of web sites" value="<?php echo $server->getDocrootDir() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="gunfe_lib_dir">GunFE LIB Folder</label>
        <div class="col-sm-10">
            <input type="text" id="gunfe_lib_dir" name="gunfe_lib_dir" class="form-control" required placeholder="Location of GunFE webapp/lib folder" value="<?php echo $server->getGunfeLibDir() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="web_user">Web User</label>
        <div class="col-sm-10">
            <input type="text" id="web_user" name="web_user" class="form-control" required placeholder="Website owner" value="<?php echo $server->getWebUser() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="web_group">Web Group</label>
        <div class="col-sm-10">
            <input type="text" id="web_group" name="web_group" class="form-control" required placeholder="Website owner" value="<?php echo $server->getWebGroup() ?>" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save Server" />
        </div>
    </div>
</form>

<script>
//<!--
$(document).ready(function() {
    $('#btn_lookup_hostname').click(function() {
        $.rad.get('/api', { func: '/admin/server-lookup-hostname', ip_address: $('#ip_address').val(), root_username: $('#root_username').val(), root_password: $('#root_password').val() }, function(data) {
            if (data.record && data.record.hostname) {
                $('#hostname').val(data.record.hostname);
                $('#hostname').addClass('focus');
            }
        });
    });
});
//-->
</script>