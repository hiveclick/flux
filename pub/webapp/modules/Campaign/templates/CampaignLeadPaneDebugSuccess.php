<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">View Internal Lead Data</h4>
</div>
<div class="modal-body">
	<div class="help-block">This page will help you debug the lead to determine any problems when testing offers and flows</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#leadDataAccordion">Data</a>
			</div>
		</div>
		<div id="leadDataAccordion" class="panel-body panel-collapse collapse out">
			<table class="table table-hover table-bordered table-striped table-responsive lead-data-table">
				<thead>
					<tr>
						<th>Data Field</th>
						<th>Request Name</th>
						<th>Type</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
				<?php
				   /* @var $lead_event \Flux\LeadEvent */ 
				   foreach ($lead->getD() as $key => $value) { 
						$data_field = \Flux\DataField::retrieveDataFieldFromName($key);
				?>
					<tr>
						<td>
							<?php if (!is_null($data_field)) { ?>
								<?php echo $data_field->getName() ?>
							<?php } else { ?> 
								<mark><i><?php echo $key ?></i></mark> <span class="label label-danger">Unmatched data field</span>
							<?php } ?>
						</td>
						<td>
							<?php if (!is_null($data_field)) { ?>
								<?php echo implode(", ", array_merge(array('<strong>' . $data_field->getKeyName() . '</strong>'), $data_field->getRequestName())) ?>
							<?php } else { ?> 
								<mark><i><?php echo $key ?></i></mark>
							<?php } ?>
						</td>
						<td>
							 <?php if (is_array($value)) { ?>
								 Array
							 <?php } else if (is_string($value)) { ?>
								 String
							 <?php } else if (is_object($value) && $value instanceof \MongoDate) { ?>
								  MongoDate
							 <?php } else if (is_object($value) && $value instanceof \MongoId) { ?>
								  MongoId
							 <?php } else if (is_object($value) && $value instanceof \Flux\LeadEvent) { ?>
								  LeadEvent
							 <?php } else if (is_object($value)) { ?>
								  Object <span class="label label-warning">Unknown object (<?php echo get_class($value) ?>)</span>
							 <?php } else if (is_numeric($value)) { ?>
								  <?php echo $value ?>
							 <?php } else { ?>
								  Unknown <span class="label label-danger">Unknown data type<?php echo !is_null($data_field) ? ', should be ' . $data_field->getFieldTypeName() : '' ?></span>
							 <?php } ?>
						</td>
						<td>
							<?php if (is_array($value)) { ?>
								 <?php echo implode(", ", $value) ?>
							<?php } else if (is_string($value)) { ?>
								<?php echo $value ?>
							<?php } else { ?>
								<?php echo var_dump($value) ?>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#leadEventAccordion">Events</a>
			</div>
		</div>
		<div id="leadEventAccordion" class="panel-body panel-collapse collapse out">
			<table class="table table-hover table-bordered table-striped table-responsive lead-event-table">
				<thead>
					<tr>
						<th>Data Field</th>
						<th>Event Time</th>
						<th>Request Name</th>
						<th>Offer</th>
						<th>Client</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
				<?php
				   /* @var $lead_event \Flux\LeadEvent */ 
				   foreach ($lead->getE() as $key => $lead_event) { 
				?>
					<tr>
						<td>
							<?php if (\MongoId::isValid($lead_event->getDatafield()->getDataFieldId())) { ?>
								<?php echo $lead_event->getDataField()->getDatafieldName() ?>
							<?php } else { ?>
								<?php echo $lead_event->getDatafield()->getDataFieldId() ?> <span class="label label-danger">Unmatched data field</span>
							<?php } ?>
						</td>
						<td>
							<?php if ($lead_event->getT() instanceof \MongoDate) { ?>
								<?php echo date('m/d/Y g:i:s a', $lead_event->getT()->sec) ?>
							<?php } else { ?>
								<mark><?php echo $lead_event->getT() ?></mark> <span class="label label-danger">Date missing or not \MongoDate object</span>
							<?php } ?>
						</td>
						<td>
							<?php echo implode(", ", array_merge(array('<strong>' . $lead_event->getDatafield()->getDataField()->getKeyName() . '</strong>'), $lead_event->getDatafield()->getDataField()->getRequestName())) ?>
						</td>
						<td>
							<?php if (\MongoId::isValid($lead_event->getOffer()->getOfferId())) { ?>
								<?php if ($lead_event->getOffer()->getOfferId() == $lead_event->getOffer()->getOfferId()) { ?>
									<?php echo $lead_event->getOffer()->getOfferName() ?> (<?php echo $lead_event->getOffer()->getOfferId() ?>)
								<?php } else { ?>
									<?php echo $lead_event->getOffer()->getOfferId() ?> <span class="label label-danger">Unmatched Offer Id</span>
								<?php } ?>
							<?php } else { ?>
								<?php echo $lead_event->getOffer()->getOfferId() ?> <span class="label label-danger">Invalid Offer Id</span>
							<?php } ?>
						</td>
						<td>
							<?php if (\MongoId::isValid($lead_event->getClient()->getClientId())) { ?>
								<?php if ($lead_event->getClient()->getClientId() == $lead_event->getClient()->getClientId()) { ?>
									<?php echo $lead_event->getClient()->getClientName() ?> (<?php echo $lead_event->getClient()->getClientId() ?>)
								<?php } else { ?>
									<?php echo $lead_event->getClient()->getClientId() ?> <span class="label label-danger">Unmatched Client Id</span>
								<?php } ?>
							<?php } else { ?>
								<?php echo $lead_event->getClient()->getClientId() ?> <span class="label label-danger">Invalid Client Id</span>
							<?php } ?>
						</td>
						<td>
							<?php if (is_array($lead_event->getValue())) { ?>
								 <?php echo implode(", ", $lead_event->getValue()) ?>
							<?php } else if (is_string($lead_event->getValue())) { ?>
								<?php echo $lead_event->getValue() ?>
							<?php } else if (is_numeric($lead_event->getValue())) { ?>
								<?php echo $lead_event->getValue() ?>
							<?php } else { ?>
								<?php echo var_dump($lead_event->getValue()) ?>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script>
//<!--
$(document).ready(function() {
	/*
	$('.lead-data-table').dataTable({
		autoWidth: false,
		pageLength: 15,
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
	});

	$('.lead-event-table').dataTable({
		autoWidth: false,
		pageLength: 15,
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
	});
	*/
});
//-->
</script>