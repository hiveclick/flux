<?php
	/* @var $vertical Flux\Vertical */
	$vertical = $this->getContext()->getRequest()->getAttribute("vertical", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($vertical->getId()) ? 'Edit' : 'Add' ?> Vertical</h4>
</div>
<form class="" id="vertical_form_<?php echo $vertical->getId() ?>" method="<?php echo \MongoId::isValid($vertical->getId()) ? 'PUT' : 'POST' ?>" action="/admin/vertical" autocomplete="off" role="form">
	<input type="hidden" name="status" value="<?php echo \Flux\Vertical::VERTICAL_STATUS_ACTIVE ?>" />
	<?php if (\MongoId::isValid($vertical->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $vertical->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div class="help-block">Create a new vertical that you can use to organize offers</div>
		<div class="form-group">
			<label class="control-label" for="name">Name</label>
			<input type="text" id="name" name="name" class="form-control" placeholder="Enter Name..." value="<?php echo $vertical->getName() ?>" />
		</div>
	
		<div class="form-group">
			<label class="control-label" for="description">Description</label>
			<textarea name="description" id="description" class="form-control" placeholder="Enter Description..."><?php echo $vertical->getDescription() ?></textarea>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($vertical->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Vertical" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#vertical_form_<?php echo $vertical->getId() ?>').form(function(data) {
		$.rad.notify('Vertical Updated', 'The vertical has been added/updated in the system');
		$('#vertical_search_form').trigger('submit');
		$('#edit_vertical_modal').modal('hide');
	}, {keep_form:1});
});

<?php if (\MongoId::isValid($vertical->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this vertical from the system?')) {
		$.rad.del('/admin/vertical/<?php echo $vertical->getId() ?>', { }, function(data) {
			$.rad.notify('You have deleted this vertical', 'You have deleted this vertical.  You will need to refresh this page to see your changes.');
			$('#vertical_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>