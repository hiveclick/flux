<?php 
	/* @var $user \Flux\User */
	$user = $this->getContext()->getRequest()->getAttribute('user', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Change Password</h4>
</div>
<form id="user_password_form" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/user-password" />
	<input type="hidden" name="_id" value="<?php echo $user->getId() ?>" />
	<div class="modal-body">
		<div class="help-block">Use this form to change your password</div>
		<div class="form-group">
			<label for="password">Enter current password</label>
			<input type="password" name="password" class="form-control" value="<?php echo $user->getPassword() ?>" />
		</div>
		<div class="form-group">
			<label for="password">Enter new password</label>
			<input type="text" name="new_password" id="new_password" class="form-control" value="" />
			<span class="glyphicon form-control-feedback"></span>
		</div>
		<div class="form-group">
			<label for="password">Confirm new password</label>
			<input type="text" name="new_password_confirm" id="new_password_confirm" class="form-control" value="" />
			<span class="glyphicon form-control-feedback"></span>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-success">Update Password</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#user_password_form').form(function(data) {
		$.rad.notify('Password updated', 'Your password has been updated.  Please save your new password.')
	},{keep_form:1});

	$('#new_password,#new_password_confirm').on('keyup', function() {
		if ($('#new_password').val() == $('#new_password_confirm').val()) {
			$('#new_password,#new_password_confirm').parent().addClass('has-feedback');
			$('#new_password,#new_password_confirm').parent().removeClass('has-warning').addClass('has-success');
			$('#new_password,#new_password_confirm').next('.glyphicon').removeClass('glyphicon-warning-sign').addClass('glyphicon-ok');
		} else {
			$('#new_password,#new_password_confirm').parent().addClass('has-feedback');
			$('#new_password,#new_password_confirm').parent().removeClass('has-success').addClass('has-warning');
			$('#new_password,#new_password_confirm').next('.glyphicon').removeClass('glyphicon-ok').addClass('glyphicon-warning-sign');
		}
	});
	
});
//-->
</script>