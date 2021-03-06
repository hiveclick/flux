<?php
	/* @var $server Flux\Server */
	$server = $this->getContext()->getRequest()->getAttribute("server", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($server->getId()) ? 'Edit' : 'Add' ?> Server</h4>
</div>
<form class="" id="server_form_<?php echo $server->getId() ?>" method="<?php echo \MongoId::isValid($server->getId()) ? 'PUT' : 'POST' ?>" action="/admin/server" autocomplete="off" role="form">
	<input type="hidden" name="status" value="<?php echo \Flux\Server::SERVER_STATUS_ACTIVE ?>" />
	<?php if (\MongoId::isValid($server->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $server->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#ftp" role="tab" data-toggle="tab">FTP</a></li>
			<li role="presentation" class=""><a href="#mysql" role="tab" data-toggle="tab">Mysql</a></li>
			<li role="presentation" class=""><a href="#apache" role="tab" data-toggle="tab">Apache Setup</a></li>
			<?php if (\MongoId::isValid($server->getId())) { ?>
				<li role="presentation" class=""><a href="#apc" role="tab" data-toggle="tab">APC</a></li>
			<?php } ?>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
				<div class="help-block">Create a new server that can host landing pages</div>
				<div class="form-group">
					<label class="control-label" for="ip_address">IP Address</label>
					<input type="text" id="ip_address" name="ip_address" class="form-control" placeholder="Server Main IP Address" value="<?php echo $server->getIpAddress() ?>" />
				</div>

				<div class="form-group">
					<label class="control-label" for="root_username">Root Username</label>
					<input type="text" id="root_username" name="root_username" class="form-control" placeholder="Root Username" value="<?php echo $server->getRootUsername() ?>" />
				</div>

				<div class="form-group">
					<label class="control-label" for="root_password">Root Password</label>
					<input type="text" id="root_password" name="root_password" class="form-control" placeholder="Root Password" value="<?php echo $server->getRootPassword() ?>" />
				</div>

				<hr />

				<div class="form-group">
					<label class="control-label" for="hostname">Hostname</label>
					<div class="input-group">
						<input type="text" id="hostname" name="hostname" class="form-control" placeholder="Server Hostname" value="<?php echo $server->getHostname() ?>" />
						<a href="#" class="input-group-addon btn btn-info btn-block" id="btn_lookup_hostname">lookup hostname</a>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label" for="alternate_hostname">Alternate Hostname</label>
					<input type="text" id="alternate_hostname" name="alternate_hostname" class="form-control" placeholder="Alternate Hostname" value="<?php echo $server->getAlternateHostname() ?>" />
					<div class="help-block small">If the hostname on the server is not accessible, you can specify an alternate hostname</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="ftp">
				<div class="help-block">Setup FTP access to this server</div>
				
				<div class="form-group">
					<label class="control-label" for="ftp_username">Username</label>
					<input type="text" id="ftp_username" name="ftp_username" class="form-control" placeholder="FTP Username" value="<?php echo $server->getFtpUsername() ?>" />
				</div>

				<div class="form-group">
					<label class="control-label" for="ftp_password">Password</label>
					<input type="text" id="ftp_password" name="ftp_password" class="form-control" placeholder="FTP Password" value="<?php echo $server->getFtpPassword() ?>" />
				</div>				
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="mysql">
				<div class="help-block">Setup MySQL/Wordpress access to this server</div>
				
				<div class="form-group">
					<label class="control-label" for="mysql_username">Username</label>
					<input type="text" id="mysql_username" name="mysql_username" class="form-control" placeholder="MySQL Username" value="<?php echo $server->getMysqlUsername() ?>" />
				</div>

				<div class="form-group">
					<label class="control-label" for="mysql_password">Password</label>
					<input type="text" id="mysql_password" name="mysql_password" class="form-control" placeholder="MySQL Password" value="<?php echo $server->getMysqlPassword() ?>" />
				</div>		
				
				<div class="form-group">
					<label class="control-label" for="mysql_db">Database Name</label>
					<input type="text" id="mysql_db" name="mysql_db" class="form-control" placeholder="Wordpress Database name" value="<?php echo $server->getMysqlDb() ?>" />
				</div>			
			</div>
			<div role="tabpanel" class="tab-pane fade" id="apache">
				<div class="help-block">These settings are used to configure Apache and when pushing new files to the server</div>
				<div class="form-group">
					<label class="control-label" for="docroot_dir">DocRoot Folder</label>
					<input type="text" id="docroot_dir" name="docroot_dir" class="form-control" placeholder="Location of web sites" value="<?php echo $server->getDocrootDir() ?>" />
				</div>

				<div class="form-group">
					<label class="control-label" for="Fluxfe_lib_dir">FluxFE LIB Folder</label>
					<input type="text" id="Fluxfe_lib_dir" name="Fluxfe_lib_dir" class="form-control" placeholder="Location of FluxFE webapp/lib folder" value="<?php echo $server->getFluxfeLibDir() ?>" />
				</div>

				<hr />
				
				<div class="help-block">The web user and group will be assigned as the default owner of new files and folders</div>
				
				<div class="form-group">
					<label class="control-label" for="web_user">Web User</label>
					<input type="text" id="web_user" name="web_user" class="form-control" placeholder="Website owner" value="<?php echo $server->getWebUser() ?>" />
				</div>

				<div class="form-group">
					<label class="control-label" for="web_group">Web Group</label>
					<input type="text" id="web_group" name="web_group" class="form-control" placeholder="Website owner" value="<?php echo $server->getWebGroup() ?>" />
				</div>
			</div>
			<?php if (\MongoId::isValid($server->getId())) { ?>
				<div role="tabpanel" class="tab-pane fade" id="apc">
					<div class="help-block">Displays the APC cache running on this server.  Clear the cache when you push changes to this server.</div>
					<br/>
					<iframe src="http://<?php echo $server->getHostname() ?>/apc.php" width="100%" height="100%" seamless style="min-height:700px;"></iframe>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($server->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Server" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
	
<script>
//<!--
$(document).ready(function() {
	$('#server_form_<?php echo $server->getId() ?>').form(function(data) {
		$.rad.notify('Server Updated', 'The server has been added/updated in the system');
		$('#server_search_form').trigger('submit');
		$('#edit_server_modal').modal('hide');
	}, {keep_form:1});
	
	$('#btn_lookup_hostname').click(function() {
		$.rad.get('/admin/server-lookup-hostname', { ip_address: $('#ip_address').val(), root_username: $('#root_username').val(), root_password: $('#root_password').val() }, function(data) {
			if (data.record && data.record.hostname) {
				$('#hostname').val(data.record.hostname);
				$('#hostname').addClass('focus');
			}
		});
	});
});

<?php if (\MongoId::isValid($server->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this server from the system?')) {
		$.rad.del('/admin/server/<?php echo $server->getId() ?>', { }, function(data) {
			$.rad.notify('You have deleted this server', 'You have deleted this server.  You will need to refresh this page to see your changes.');
			$('#server_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>