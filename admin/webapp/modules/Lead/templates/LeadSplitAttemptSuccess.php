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
		<li role="presentation" class=""><a href="#source" role="tab" data-toggle="tab">Source Code</a></li>
		<li role="presentation" class=""><a href="#debug_screenshots" role="tab" data-toggle="tab">Debug Screenshots</a></li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="basic">
			<div class="help-block">Below is what was sent to the fulfillment</div>
			<div class="form-group">
				<textarea readonly class="form-control" rows="10"><?php echo htmlentities($lead_split_attempt->getRequest()) ?></textarea>
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
		<div role="tabpanel" class="tab-pane fade in" id="source">
			<div class="form-group">
				<textarea readonly class="form-control" rows="15"><?php echo $lead_split_attempt->getSource() ?></textarea>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="debug_screenshots">
			<?php 
				foreach ($lead_split_attempt->getDebugScreenshots() as $key => $debug_screenshot) { 
			?>
				<h3><?php echo $key + 1 ?></h3>
				<?php if (is_array($debug_screenshot) && isset($debug_screenshot['screenshot'])) { ?>
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#debug_screenshot_<?php echo $key ?>" role="tab" data-toggle="tab">Screenshot</a></li>
						<li role="presentation" class=""><a href="#debug_source_<?php echo $key ?>" role="tab" data-toggle="tab">Source</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="debug_screenshot_<?php echo $key ?>">
							<img src="data:image/png;base64,<?php echo $debug_screenshot['screenshot'] ?>" border="0" class="img-thumbnail" />
						</div>
						<div role="tabpanel" class="tab-pane fade in" id="debug_source_<?php echo $key ?>">
							<textarea name="debug_source_<?php echo $key ?>_textarea" class="form-control" rows="10"><?php echo $debug_screenshot['source'] ?></textarea>
						</div>
					</div>
				<?php } else { ?>
					<img src="data:image/png;base64,<?php echo $debug_screenshot ?>" border="0" class="img-thumbnail" />
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>