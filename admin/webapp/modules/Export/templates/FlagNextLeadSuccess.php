<?php
	/* @var $lead_split \Flux\LeadSplit */
	$lead_split = $this->getContext()->getRequest()->getAttribute("lead_split", array());
?>
<h1>Flag Lead <?php echo $lead_split->getId() ?> Flagged Successfully</h1>