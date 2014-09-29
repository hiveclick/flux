<?php
    /* @var $server Flux\Server */
    $server = $this->getContext()->getRequest()->getAttribute("server", array());
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
    <h2><a href="/admin/server-search">Servers</a> <small><?php echo $server->getHostname() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse" class="navbar-collapse collapse">
    <ul id="datafield-tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Server</a></li>
        <li><a id="tabs-a-apc" href="#tabs-apc" data-toggle="tab" data-url="/admin/server-pane-apc?_id=<?php echo $server->getId() ?>">APC Cache</a></li>
    </ul>
</div>
<div id="tab-content-container" class="tab-content">
   <div id="tabs-main" class="tab-pane active">
		<div class="help-block">Servers are used to host paths and offers.  You can manage this server below</div>
		<br/>
		<form class="form-horizontal" name="server_form" method="POST" action="" autocomplete="off">
		    <input type="hidden" name="_id" value="<?php echo $server->getId() ?>" />
		
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
		
		    <div class="form-group">
		        <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
		        <div class="col-sm-10">
		            <select class="form-control" name="status" id="status" required placeholder="Status">
		                <?php foreach(\Flux\Server::retrieveStatuses() AS $status_id => $status_name) { ?>
		                <option value="<?php echo $status_id; ?>"<?php echo $server->getStatus() == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
		                <?php } ?>
		            </select>
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
		        <label class="col-sm-2 control-label hidden-xs" for="Fluxfe_lib_dir">FluxFE LIB Folder</label>
		        <div class="col-sm-10">
		            <input type="text" id="Fluxfe_lib_dir" name="Fluxfe_lib_dir" class="form-control" required placeholder="Location of FluxFE webapp/lib folder" value="<?php echo $server->getFluxfeLibDir() ?>" />
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
		            <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Server" />
		        </div>
		    </div>
		</form>
	 </div>
    <div id="tabs-apc" class="tab-pane"></div>
</div>

<script>
//<!--
$(document).ready(function() {
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

	// Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('server_tab_' . $server->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (lastTab) {
        $('a[href='+lastTab+']').tab('show');
    } else {
        $('ul.nav-pills a:first').tab('show');
    }
	
    $('#btn_lookup_hostname').click(function() {
        $.rad.get('/api', { func: '/admin/server-lookup-hostname', ip_address: $('#ip_address').val(), root_username: $('#root_username').val(), root_password: $('#root_password').val() }, function(data) {
            if (data.record && data.record.hostname) {
                $('#hostname').val(data.record.hostname);
                $('#hostname').addClass('focus');
            }
        });
    });

    $('#btn_delete').click(function() {
        if (confirm('Are you sure you want to delete this server and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/admin/server/<?php echo $server->getId() ?>' }, function(data) {
                $.rad.notify('Server Removed', 'This server has been removed from the system.');
            });
        }
    });
});
//-->
</script>