<?php
	/* @var $user Flux\User */
	$user = $this->getContext()->getRequest()->getAttribute("user", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo ($user->getId() > 0) ? 'Edit' : 'Add' ?> User</h4>
</div>
<form class="" id="user_form_<?php echo $user->getId() ?>" method="<?php echo ($user->getId() > 0) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/user" />
	<input type="hidden" name="status" value="<?php echo \Flux\User::USER_STATUS_ACTIVE ?>" />
	<input type="hidden" name="user_type" value="<?php echo \Flux\User::USER_TYPE_ADMIN ?>" />
	<?php if ($user->getId() > 0) { ?>
		<input type="hidden" name="_id" value="<?php echo $user->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
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
		<div class="help-block">Users are assigned to a default client's account for reporting</div>	

		<div class="form-group">
			<label class="control-label hidden-xs" for="client_id">Client</label>
			<select class="form-control" name="client_id" id="client_id" placeholder="Assign this user to a client...">
				<?php
					/* @var $client \Flux\Client */ 
					foreach($clients AS $client) { 
				?>
					<option value="<?php echo $client->getId() ?>"<?php echo $user->getClientId() == $client->getId() ? ' selected="selected"' : ''; ?>><?php echo $client->getName() ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="modal-footer">
		<?php if ($user->getId() > 0) { ?>
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
	}, {keep_form:1});

	$('#status,#type,#client_id,#timezone').selectize();
});

<?php if ($user->getId() > 0) { ?>
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