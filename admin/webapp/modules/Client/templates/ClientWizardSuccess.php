<?php
	/* @var $client Flux\Client */
	$client = $this->getContext()->getRequest()->getAttribute("client", array());
	$network_handlers = $this->getContext()->getRequest()->getAttribute("network_handlers", array());
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($client->getId()) ? 'Edit' : 'Add' ?> Client</h4>
</div>
<form id="client_form_<?php echo $client->getId() ?>" method="<?php echo \MongoId::isValid($client->getId()) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/client/client" />
	<input type="hidden" name="status" value="<?php echo \Flux\Client::CLIENT_STATUS_ACTIVE ?>" />
	<?php if (\MongoId::isValid($client->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $client->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
        <!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#network" role="tab" data-toggle="tab">Network Login</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
        		<div class="help-block">Clients are used as advertisers or publishers to either manage offers or send traffic</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="name">Name</label>
        			<input type="text" id="name" name="name" class="form-control" placeholder="Enter client's name..." value="<?php echo $client->getName() ?>" />
        		</div>
        
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="type">Client Type</label>
        			<select class="form-control" name="client_type" id="client_type" placeholder="Select the role of this client...">
        				<option value="<?php echo \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN ?>" <?php echo $client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN ? ' selected' : '' ?>>Primary Administrator</option>
        				<option value="<?php echo \Flux\Client::CLIENT_TYPE_AFFILIATE ?>" <?php echo $client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE ? ' selected' : '' ?>>Affiliate</option>
        			</select>
        		</div>
        		
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="type">Status</label>
        			<select class="form-control" name="status" id="status" placeholder="Select the status of this client...">
        				<option value="<?php echo \Flux\Client::CLIENT_STATUS_ACTIVE ?>" <?php echo $client->getStatus() == \Flux\Client::CLIENT_STATUS_ACTIVE ? ' selected' : '' ?>>Active</option>
        				<option value="<?php echo \Flux\Client::CLIENT_STATUS_INACTIVE ?>" <?php echo $client->getStatus() == \Flux\Client::CLIENT_STATUS_INACTIVE ? ' selected' : '' ?>>Inactive</option>
        			</select>
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="email">Email</label>
        			<input type="email" id="email" name="email" class="form-control" placeholder="Enter client's email..." value="<?php echo $client->getEmail() ?>" />
        		</div>
        	</div>
        	<div role="tabpanel" class="tab-pane fade in" id="network">
                <div class="help-block">You can enter the login and report syncing class here</div>
                <div class="form-group">
        			<label class="control-label hidden-xs" for="login_url">Login URL</label>
        			<input type="text" id="login_url" name="network_url" class="form-control" placeholder="Enter login url" value="<?php echo $client->getNetworkUrl() ?>" />
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="username">Username</label>
        			<input type="text" id="username" name="network_username" class="form-control" placeholder="Enter login username" value="<?php echo $client->getNetworkUsername() ?>" />
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="password">Password</label>
        			<input type="text" id="password" name="network_password" class="form-control" placeholder="Enter login password" value="<?php echo $client->getNetworkPassword() ?>" />
        		</div>
        		<hr />
    			<div class="help-block">Select the color used when displaying this domain group in graphs and charts</div>
    			<div class="form-group">
                    <div class="input-group" id="color_<?php echo $client->getId() ?>">
                        <input type="text" name="color" value="<?php echo $client->getColor() ?>" class="form-control" />
                        <span class="input-group-addon"><i></i></span>
                    </div>
    			</div>
    			<div class="clearfix"></div>
        		<hr />
        		<div class="help-block">You can sync reporting from this network by entering the API credentials below</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="reporting_api_class">Network</label>
        			<select name="reporting_api_class" id="reporting_api_class">
        			     <?php foreach ($network_handlers as $key => $network_handler) { ?>
        			         <option value="<?php echo get_class($network_handler) ?>" <?php echo $client->getReportingApiClass() == get_class($network_handler) ? 'SELECTED' : '' ?>><?php echo get_class($network_handler) ?></option>
        			     <?php } ?>
        			</select>
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="reporting_api_url">API Url</label>
        			<input type="text" id="reporting_api_url" name="reporting_api_url" class="form-control" placeholder="Enter API url" value="<?php echo $client->getReportingApiUrl() ?>" />
        		</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="reporting_api_token">API Token</label>
        			<textarea id="reporting_api_token" name="reporting_api_token" class="form-control" placeholder="Enter API token"><?php echo $client->getReportingApiToken() ?></textarea>
        		</div>
        	</div>
        </div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($client->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Client" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#client_form_<?php echo $client->getId() ?>').form(function(data) {
		$.rad.notify('Client Updated', 'The client has been added/updated in the system');
		$('#client_search_form').trigger('submit');
		$('#edit_client_modal').modal('hide');
	}, {keep_form:1});

	$('#client_type,#status,#reporting_api_class').selectize();

	$('#color_<?php echo $client->getId() ?>').colorpicker({
	    color: '#<?php echo $client->getColor() ?>'
	});
	
});

<?php if (\MongoId::isValid($client->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this client from the system?')) {
		$.rad.del({ func: '/client/client/<?php echo $client->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this client', 'You have deleted this client.  You will need to refresh this page to see your changes.');
			$('#client_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>