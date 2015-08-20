<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute("split_queue", array());
?>
<h1>Flag Lead <?php echo $split_queue->getId() ?></h1>

<form method="POST" action="/export/flag-next-lead" enctype="multipart/form-data">
    <input type="hidden" name="_id" value="<?php echo $split_queue->getId() ?>" />
    <div class="form-group">
        <label for="disposition">Select a disposition:</label>
        <select name="disposition" id="disposition" class="form-control">
            <option value="<?php echo \Flux\SplitQueue::DISPOSITION_FULFILLED ?>">Flag as Fulfilled</option>
            <option value="<?php echo \Flux\SplitQueue::DISPOSITION_PENDING ?>">Flag as Pending</option>
        </select>    
    </div>
    <div class="form-group">
        <label for="response">Enter response:</label>
        <textarea name="response" id="response" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="error_message">Enter any error messages:</label>
        <textarea name="error_message" id="error_message" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="error_message">Upload screenshot of final page:</label>
        <input type="file" name="screenshot" id="screenshot" class="form-control" />
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="btn_submit" id="btn_sumit" value="save lead" />
    </div>
</form>
