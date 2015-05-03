<?php
	/* @var $user Flux\User */
	$user = $this->getContext()->getRequest()->getAttribute("user", array());
?>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a data-toggle="modal" data-target="#change_password_modal" href="/account/user-password">change password</a></li>
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#change_password_modal" href="/account/user-password">change password</a>
			</div>
		</div>
	</div>
	<h1>Your Account</h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/campaign/campaign-search">Users</a></li>
	<li class="active"><?php echo $user->getName() ?></li>
</ol>
<form id="user_form_<?php echo $user->getId() ?>" method="PUT" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/user" />
	<input type="hidden" name="status" value="<?php echo \Flux\User::USER_STATUS_ACTIVE ?>" />
	<input type="hidden" name="user_type" value="<?php echo \Flux\User::USER_TYPE_ADMIN ?>" />
	<input type="hidden" name="client[client_id]" value="<?php echo $user->getClient()->getClientId() ?>" />
	<input type="hidden" name="_id" value="<?php echo $user->getId() ?>" />
	<div class="help-block">You can manage your user settings from this page.</div>
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
		<label class="control-label hidden-xs" for="type">Timezone</label>
		<select class="form-control" name="timezone" id="timezone" placeholder="Select the user's timezone...">
			<?php foreach(\Flux\Timezone::retrieveTimezonesFormatted() AS $timezone_id => $timezone_string) { ?>
			<option value="<?php echo $timezone_id; ?>"<?php echo $user->getTimezone() == $timezone_id ? ' selected="selected"' : ''; ?>><?php echo $timezone_string; ?></option>
			<?php } ?>
		</select>
	</div>
	<button type="submit" class="btn btn-primary">Save changes</button>
</form>

<!-- Change Password modal -->
<div class="modal fade" id="change_password_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	$('#user_form_<?php echo $user->getId() ?>').form(function(data) {
		$.rad.notify('User Updated', 'The user has been added/updated in the system');
		$('#user_search_form').trigger('submit');
	}, {keep_form:1});

	$('#status,#type,#client_id,#timezone').selectize();
});
//-->
</script>