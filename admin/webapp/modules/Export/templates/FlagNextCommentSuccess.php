<?php
	/* @var $ubot_queue \Flux\UbotQueue */
	$ubot_queue = $this->getContext()->getRequest()->getAttribute("ubot_queue", array());
?>
<h1>Flag Comment <?php echo $ubot_queue->getId() ?> Flagged Successfully</h1>