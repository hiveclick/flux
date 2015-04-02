<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute('split_queue', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Mark as Unfulfillable</h4>
</div>
<form id="split_queue_mark_unfulfillable_form" action="/api" method="PUT">
	<input type="hidden" name="func" value="/export/split-queue" />
	<input type="hidden" name="test" value="0" />
	<input type="hidden" name="_id" value="<?php echo $split_queue->getId() ?>" />
	<input type="hidden" name="is_unfulfillable" value="1" />
	<div class="modal-body">
		<div class="help-block">
            You have chosen to mark this item as unfulfillable.  It will not show in the list of queued leads and it will not be queued again.
            <p />
            Are you sure you want to do this?
        </div>
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-danger">Yes, Mark as Unfulfillable</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
    $('#split_queue_mark_unfulfillable_form').form(function(data) {
        $.rad.notify('Split Marked Unfulfillable', 'This item has been marked as unfulfillable and will not be retried.');
        location.href = '/export/split-queue-catch-all-search';
    });
});
//-->
</script>