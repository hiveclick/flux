<?php
	/* @var $user Flux\User */
	$user = $this->getContext()->getRequest()->getAttribute("user", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($user->getId()) ? 'Edit' : 'Add' ?> User</h4>
</div>
<form class="" id="user_form_<?php echo $user->getId() ?>" method="<?php echo \MongoId::isValid($user->getId()) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/user" />
	<input type="hidden" name="status" value="<?php echo \Flux\User::USER_STATUS_ACTIVE ?>" />
	<?php if (\MongoId::isValid($user->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $user->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
	    <!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#token" role="tab" data-toggle="tab">Token Authentication</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
        		<div class="help-block">Create a new user with access to log into the system</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="name">Name</label>
        			<input type="text" id="name" name="name" class="form-control" placeholder="Enter user's name" value="<?php echo $user->getName() ?>" />
        		</div>
        
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="email">Email</label>
        			<input type="email" id="email" name="email" class="form-control" placeholder="Enter email address..." value="<?php echo $user->getEmail() ?>" />
        			<div class="help-block small">This user will login using their email address</div>
        		</div>
        
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="password">Password</label>
        			<input type="text" id="password" name="password" class="form-control" placeholder="Enter password..." value="<?php echo $user->getPassword() ?>" />
        		</div>
        
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="type">Timezone</label>
        			<select class="form-control" name="timezone" id="timezone" placeholder="Select the user's timezone...">
        				<?php foreach(\Flux\Timezone::retrieveTimezonesFormatted() AS $timezone_id => $timezone_string) { ?>
        				<option value="<?php echo $timezone_id; ?>"<?php echo $user->getTimezone() == $timezone_id ? ' selected="selected"' : ''; ?>><?php echo $timezone_string; ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<hr />
        		<div class="help-block">Users can either see everything in the site or do data entry only</div>
    			<div class="form-group">
        			<label class="control-label hidden-xs" for="user_type">Role</label>
        			<select class="form-control" name="user_type" id="user_type" placeholder="Assign this user a role">
        				<option value="<?php echo \Flux\User::USER_TYPE_ADMIN ?>" <?php echo $user->getUserType() == \Flux\User::USER_TYPE_ADMIN ? 'selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => \Flux\User::USER_TYPE_ADMIN, 'name' => 'Administrator', 'description' => 'This user will be able to see and edit everything in the system including revenue reports, lead data, and users'))) ?>">Administrator</option>
        				<option value="<?php echo \Flux\User::USER_TYPE_DATA_ENTRY ?>" <?php echo $user->getUserType() == \Flux\User::USER_TYPE_DATA_ENTRY ? 'selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => \Flux\User::USER_TYPE_DATA_ENTRY, 'name' => 'Data Entry', 'description' => 'This user will be able to view lead reports and alter lead information only'))) ?>">Data Entry</option>
        			</select>
        		</div>
        		<hr />
        		<div class="help-block">Users are assigned to a default client's account for reporting</div>	
        
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="client_id">Client</label>
        			<select class="form-control" name="client[client_id]" id="client_id" placeholder="Assign this user to a client...">
        				<optgroup label="Administrators">
        				<?php
        					/* @var $client \Flux\Client */ 
        					foreach($clients AS $client) { 
        				?>
        				    <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
        					<option value="<?php echo $client->getId() ?>" <?php echo $user->getClient()->getClientId() == $client->getId() ? 'selected' : ''; ?>><?php echo $client->getName() ?></option>
        					<?php } ?>
        				<?php } ?>
        				</optgroup>
        				<optgroup label="Affiliates">
        				<?php
        					/* @var $client \Flux\Client */ 
        					foreach($clients AS $client) { 
        				?>
        				    <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
        					<option value="<?php echo $client->getId() ?>" <?php echo $user->getClient()->getClientId() == $client->getId() ? 'selected' : ''; ?>><?php echo $client->getName() ?></option>
        					<?php } ?>
        				<?php } ?>
        				</optgroup>
        			</select>
        		</div>
    		</div>
    		<div role="tabpanel" class="tab-pane fade in" id="token">
                <div class="help-block">You can access this site without a login by using the token below.  Simply pass it in the request using the TOKEN parameter</div>
                <div class="form-group">
        			<label class="control-label hidden-xs" for="token">Token</label>
        			<div id="token_<?php echo $user->getId() ?>"><?php echo $user->getToken() ?></div>
        		</div>
        		<hr />
        		<div class="alert alert-warning">
                    When issuing a token request, simply append the token parameter like below:
                    <br /><br />
                    http://<?php echo $_SERVER['SERVER_NAME'] ?>/export/get-next-lead?split_id_array[]=55a592a068ca4e777a8fa2c6<b>&amp;token=<span id="token_url_<?php echo $user->getId() ?>"><?php echo $user->getToken() ?></span></b>
        		</div>
        		<hr />
        		You can generate a new token if this one has been compromised.  To do this, click the button below.
        		<p />
        		<a class="btn btn-info" href="#" id="generate_token_<?php echo $user->getId() ?>">Generate New Token</a>
    		</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($user->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete User" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#user_form_<?php echo $user->getId() ?>').form(function(data) {
		$.rad.notify('User Updated', 'The user has been added/updated in the system');
		$('#user_search_form').trigger('submit');
		$('#edit_user_modal').modal('hide');
	}, {keep_form:1});

    $('#user_type', '#user_form_<?php echo $user->getId() ?>').selectize({
    	valueField: '_id',
		allowEmptyOption: true,
		labelField: 'name',
		searchField: ['name','description'],
		render: {
			item: function(item, escape) {
				var ret_val = '<div class="item">' +
                '<b>' + escape(item.name) + '</b><br />' +
                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
                '</div>';
				return ret_val;
			},
			option: function(item, escape) {
				var ret_val = '<div class="option">' +
                '<b>' + escape(item.name) + '</b><br />' +
                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
                '</div>';
				return ret_val;
			}
		}
    });
	
	$('#status,#client_id,#timezone', '#user_form_<?php echo $user->getId() ?>').selectize();

	$('#generate_token_<?php echo $user->getId() ?>').click(function() {
	    if (confirm('You have chosen to generate a new token.  This will make the existing token invalid and all scripts that use it will BREAK.\n\nAre you sure you want to do this?')) {
	        $.rad.post('/api', { func: '/admin/user-generate-token', _id: '<?php echo $user->getId() ?>' }, function(data) {
		        if (data.record) {
		            $('#token_<?php echo $user->getId() ?>,#token_url_<?php echo $user->getId() ?>').html(data.record.token);
		        }
	        });
	    }
	});
});

<?php if (\MongoId::isValid($user->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this user from the system?')) {
		$.rad.del({ func: '/admin/user/<?php echo $user->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this user', 'You have deleted this user.  You will need to refresh this page to see your changes.');
			$('#user_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>