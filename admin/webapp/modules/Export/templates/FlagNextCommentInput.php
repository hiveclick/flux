<?php
	/* @var $ubot_queue \Flux\UbotQueue */
	$ubot_queue = $this->getContext()->getRequest()->getAttribute("ubot_queue", array());
?>
<h1>Flag Comment <?php echo $ubot_queue->getId() ?></h1>

<form method="POST" action="/export/flag-next-comment" enctype="multipart/form-data">
	<input type="hidden" name="_id" value="<?php echo $ubot_queue->getId() ?>" />
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
		<input type="file" name="source" id="source" class="form-control" />
	</div>
	<div class="form-group">
		<label for="error_message">Upload screenshot of final page:</label>
		<input type="file" name="screenshot" id="screenshot" class="form-control" />
	</div>
	<div class="form-group">
		<input class="btn btn-primary" type="submit" name="btn_submit" id="btn_sumit" value="save comment" />
	</div>
</form>
