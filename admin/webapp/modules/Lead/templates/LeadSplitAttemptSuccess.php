<?php
	/* @var $lead_split_attempt \Flux\LeadSplitAttempt */
	$lead_split_attempt = $this->getContext()->getRequest()->getAttribute('lead_split_attempt', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">
	   Attempt #<?php echo $lead_split_attempt->getAttemptIndex() + 1 ?> - <?php echo $lead_split_attempt->getFulfillment()->getFulfillmentName() ?>
	   <div>Sent <?php echo date('m/d/Y g:i:s a', $lead_split_attempt->getAttemptTime()->sec) ?></div>
	</h4>
</div>

<div class="modal-body">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Fulfillment</a></li>
		<li role="presentation" class=""><a href="#screenshot" role="tab" data-toggle="tab">Screenshot</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="basic">
			<div class="help-block">Below is what was sent to the fulfillment</div>
			<div class="form-group">
				<textarea readonly class="form-control" rows="10"><?php echo $lead_split_attempt->getRequest() ?></textarea>
			</div>
			<hr />
			<div class="help-block">This is the response received from the fulfillment</div>
			<div class="form-group">
				<textarea readonly class="form-control" rows="6"><?php echo $lead_split_attempt->getResponse() ?></textarea>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="screenshot">
			<img src="data:image/png;base64,<?php echo $lead_split_attempt->getScreenshot() ?>" border="0" class="img-thumbnail" />
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>