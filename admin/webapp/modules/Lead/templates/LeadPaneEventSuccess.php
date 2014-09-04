<?php
	/* @var $lead \Gun\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="help-block">You can view the various events that fired on this lead and when they fired</div>
<br/>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Event</th>
			<th>Time</th>
			<th>Payout</th>
			<th>Revenue</th>
		</tr>
	</thead>
	<tbody>
	<?php
	   /* @var $lead_event \Gun\LeadEvent */ 
	   foreach ($lead->getE() as $key => $lead_event) { 
    ?>
		<tr>
			<td><?php echo $lead_event->getDataField()->getName() ?></td>
			<td>
			    <?php if ($lead_event->getT() instanceof \MongoDate) { ?>
                    <?php echo date('m/d/Y g:i:s a', $lead_event->getT()->sec) ?>
                <?php } else { ?>
                    <mark><?php echo $lead_event->getT() ?></mark> <span class="label label-danger">Date missing or not \MongoDate object</span>
                <?php } ?>
			</td>
			<td>$<?php echo number_format($lead_event->getPayout(), 2, null, ',') ?></td>
			<td>$<?php echo number_format($lead_event->getRevenue(), 2, null, ',') ?></td>
		</tr>
	<?php } ?>
</tbody>