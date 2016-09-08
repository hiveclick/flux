<?php
	/* @var $pingback Flux\Pingback */
	$pingback = $this->getContext()->getRequest()->getAttribute("pingback", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($pingback->getId()) ? 'Edit' : 'Add' ?> Pingback</h4>
</div>
<form class="" id="pingback_form_<?php echo $pingback->getId() ?>" method="<?php echo \MongoId::isValid($pingback->getId()) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/pingback" />
	<?php if (\MongoId::isValid($pingback->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $pingback->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div class="help-block">Enter the pingback details so you can use it to build SEO backlinks</div>
		<div class="form-group">
			<label class="control-label" for="name">Name</label>
			<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $pingback->getName() ?>" />
		</div>
		<div class="form-group">
			<label class="control-label" for="description">Description</label>
			<textarea name="description" id="description" class="form-control" placeholder="Enter Description..."><?php echo $pingback->getDescription() ?></textarea>
		</div>
		<hr />
		<div class="form-group">
			<label class="control-label" for="url">Blog URL</label>
			<textarea name="url" class="form-control" placeholder="http://www.domain.com/blog/2016/01/01/article.html"><?php echo $pingback->getUrl() ?></textarea>
		</div>
		<div class="form-group">
			<label class="control-label" for="rpc_url">XML-RPC Url</label>
			<input type="text" id="rpc_url" name="rpc_url" class="form-control" placeholder="http://www.domain.com/xmlrpc.php" value="<?php echo $pingback->getRpcUrl() ?>" />
		</div>
		<hr />
		<div class="form-group">
			<label class="control-label" for="rpc_url">Status</label>
			<select name="active" id="active">
				<option value="1" <?php echo $pingback->getActive() ? 'selected' : '' ?>>This pingback is active and will be used</option>
				<option value="0" <?php echo !$pingback->getActive() ? 'selected' : '' ?>>This pingback is NOT active and won't be used</option>
			</select>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($pingback->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Pingback" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" id="test_pingback" class="btn btn-info" data-dismiss="modal">Test Pingback</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#pingback_form_<?php echo $pingback->getId() ?>').form(function(data) {
		$.rad.notify('Pingback Updated', 'The pingback has been added/updated in the system');
		$('#pingback_search_form').trigger('submit');
		$('#edit_pingback_modal').modal('hide');
	}, {keep_form:1});

	$('#active').selectize();

	$('#test_pingback').click(function() {
		$.rad.get('/api', { func: '/admin/pingback-say-hello', rpc_url: $('#rpc_url').val() }, function(data) {
			$.rad.notify('Server Responded', data.record.rpc_response);
		});
	});
});

<?php if (\MongoId::isValid($pingback->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this pingback from the system?')) {
		$.rad.del('/api', { func: '/admin/pingback/<?php echo $pingback->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this pingback', 'You have deleted this pingback.  You will need to refresh this page to see your changes.');
			$('#pingback_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>