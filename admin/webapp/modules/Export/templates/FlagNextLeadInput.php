<?php
	/* @var $lead_split \Flux\LeadSplit */
	$lead_split = $this->getContext()->getRequest()->getAttribute("lead_split", array());
?>
<h1>Flag Lead <?php echo $lead_split->getId() ?></h1>

<form method="POST" action="/export/flag-next-lead" enctype="multipart/form-data">
	<input type="hidden" name="_id" value="<?php echo $lead_split->getId() ?>" />
	<div class="form-group">
		<label for="disposition">Select a disposition:</label>
		<select name="disposition" id="disposition" class="form-control" placeholder="Select a disposition for this lead...">
			<option value="<?php echo \Flux\LeadSplit::DISPOSITION_FULFILLED ?>">Flag as Fulfilled</option>
			<option value="<?php echo \Flux\LeadSplit::DISPOSITION_PENDING ?>">Flag as Pending</option>
		</select>	
	</div>
	<div class="form-group">
		<label for="response">Enter response:</label>
		<textarea name="response" id="response" class="form-control" placeholder="Enter the response from the server (SUCCESS, ERROR, etc)..."></textarea>
	</div>	
	<div class="form-group">
		<label for="error_message">Enter any error messages:</label>
		<textarea name="error_message" id="error_message" class="form-control" placeholder="Enter any error messages..."></textarea>
	</div>
	<div class="form-group">
		<label for="source">Enter the document source (for debugging purposes):</label>
		<textarea name="source" id="source" class="form-control" placeholder="Enter the document source (for debugging purposes)..."></textarea>
	</div>
	<div class="form-group">
		<label for="error_message">Upload screenshot of final page:</label>
		<input type="file" name="screenshot" id="screenshot" class="form-control" />
	</div>
	<div class="form-group">
		<input class="btn btn-primary" type="submit" name="btn_submit" id="btn_sumit" value="save lead" />
	</div>
</form>
