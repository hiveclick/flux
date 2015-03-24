<?php
	/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
	$split_queue_attempt = $this->getContext()->getRequest()->getAttribute('split_queue_attempt', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">
	   Attempt #<?php echo $split_queue_attempt->getAttemptIndex() + 1 ?> - <?php echo $split_queue_attempt->getFulfillment()->getFulfillmentName() ?>
	   <div class="small">Sent <?php echo date('m/d/Y g:i:s a', $split_queue_attempt->getAttemptTime()->sec) ?></div>
    </h4>
</div>

<div class="modal-body">
	<div class="help-block">Below is what was sent to the fulfillment</div>
	<div class="form-group">
		<textarea readonly class="form-control" rows="10"><?php echo $split_queue_attempt->getRequest() ?></textarea>
	</div>
	<hr />
	<div class="help-block">This is the response received from the fulfillment</div>
	<div class="form-group">
		<textarea readonly class="form-control" rows="6"><?php echo $split_queue_attempt->getResponse() ?></textarea>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>