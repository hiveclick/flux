<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute("split_queue", array());
?>
<h1>Lead <?php echo $split_queue->getId() ?> marked as fulfilled successfully</h1>