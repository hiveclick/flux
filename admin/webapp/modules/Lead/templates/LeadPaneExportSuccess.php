<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="help-block">You can view the various exports that have included this lead below</div>
<br/>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Export</th>
			<th>Time</th>
			<th>Revenue</th>
		</tr>
	</thead>
	<tbody>
	<?php
	   /* @var $lead_export \Flux\LeadExport */ 
	   foreach ($lead->getExports() as $key => $lead_export) { 
	?>
		<tr>
			<td><?php echo $lead_export->getClientExport()->getName() ?></td>
			<td>
				<?php if ($lead_export->getExportDate() instanceof \MongoDate) { ?>
					<?php echo date('m/d/Y g:i:s a', $lead_export->getExportDate()->sec) ?>
				<?php } else { ?>
					<mark><?php echo $lead_export->getExportDate() ?></mark> <span class="label label-danger">Date missing or not \MongoDate object</span>
				<?php } ?>
			</td>
			<td>$<?php echo number_format($lead_export->getRevenue(), 2, null, ',') ?></td>
		</tr>
	<?php } ?>
</tbody>