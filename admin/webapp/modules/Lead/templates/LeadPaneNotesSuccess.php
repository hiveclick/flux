<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
	$notes = array_reverse($lead->getNotes());
?>
<div class="help-block">You can view the notes for this lead below</div>
<br/>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Date</th>
			<th>Time</th>
			<th>Note</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	   $last_date = null;
	   foreach ($notes as $note) { ?>
		<tr>
			<td>
				 <?php if (date('m/d/Y', $note['t']->sec) != $last_date) { 
					 $last_date = date('m/d/Y', $note['t']->sec);
				 ?>
					 <?php echo date('F dS, Y', $note['t']->sec) ?>
				 <?php } ?>
			</td>
			<td><?php echo date('g:i:s a', $note['t']->sec) ?></td>
			<td><?php echo $note['note'] ?></td>
		</tr>
	<?php } ?>
</tbody>