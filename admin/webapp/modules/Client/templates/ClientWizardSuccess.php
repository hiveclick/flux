<?php
	/* @var $client Flux\Client */
	$client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo ($client->getId() > 0) ? 'Edit' : 'Add' ?> Client</h4>
</div>
<form class="" id="client_form_<?php echo $client->getId() ?>" method="<?php echo ($client->getId() > 0) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/client/client" />
	<input type="hidden" name="status" value="<?php echo \Flux\Client::CLIENT_STATUS_ACTIVE ?>" />
	<?php if ($client->getId() > 0) { ?>
		<input type="hidden" name="_id" value="<?php echo $client->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div class="help-block">Clients are used as advertisers or publishers to either manage offers or send traffic</div>
		<div class="form-group">
			<label class="control-label hidden-xs" for="name">Name</label>
			<input type="text" id="name" name="name" class="form-control" placeholder="Enter client's name..." value="<?php echo $client->getName() ?>" />
		</div>

		<div class="form-group">
			<label class="control-label hidden-xs" for="type">Client Type</label>
			<select class="form-control" name="client_type" id="client_type" placeholder="Select the role of this client...">
				<option value="<?php echo \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN ?>" <?php echo $client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN ? ' selected' : '' ?>>Primary Administrator</option>
				<option value="<?php echo \Flux\Client::CLIENT_TYPE_SECONDARY_ADMIN ?>" <?php echo $client->getClientType() == \Flux\Client::CLIENT_TYPE_SECONDARY_ADMIN ? ' selected' : '' ?>>Secondary Administrator</option>
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
	<div class="modal-footer">
		<?php if ($client->getId() > 0) { ?>
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
	}, {keep_form:1});

	$('#client_type,#status').selectize();
});

<?php if ($client->getId() > 0) { ?>
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