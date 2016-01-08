<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute("split_queue", array());
	$datafields = $this->getContext()->getRequest()->getAttribute("datafields", array());
?>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a data-toggle="modal" data-target="#note_modal" href="/lead/lead-pane-notes?_id=<?php echo $split_queue->getLead()->getLeadId() ?>">view notes</a></li>
					<li><a data-toggle="modal" data-target="#debug_modal" href="/lead/lead-pane-debug?_id=<?php echo $split_queue->getLead()->getLeadId() ?>">view debug</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>">add/change data</a></li>
					<li class="divider"></li>
					<?php if ($split_queue->getIsCatchAll()) { ?>
						<li><a data-toggle="modal" data-target="#fulfillment_modal" href="/export/split-queue-pane-assign?_id=<?php echo $split_queue->getId() ?>">assign to split</a></li>
					<?php } else { ?>
						<li><a data-toggle="modal" data-target="#fulfillment_modal" href="/export/split-queue-pane-fulfill?_id=<?php echo $split_queue->getId() ?>">fulfill lead</a></li>
					<?php } ?>
					<li class="divider"></li>
					<?php if ($split_queue->getIsCatchAll()) { ?>
						<li><a data-toggle="modal" data-target="#unfulfillable_modal" href="/export/split-queue-pane-mark-unfulfillable?_id=<?php echo $split_queue->getId() ?>"><span class="text-danger">flag lead</span></a></li>
					<?php } else { ?>
						<li><a data-toggle="modal" data-target="#unfulfillable_modal" href="/export/split-queue-pane-mark-unfulfillable?_id=<?php echo $split_queue->getId() ?>"><span class="text-danger">flag lead</span></a></li>
					<?php } ?>   
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#note_modal" href="/lead/lead-pane-notes?_id=<?php echo $split_queue->getLead()->getLeadId() ?>">view notes</a>
				<a class="btn btn-info" data-toggle="modal" data-target="#debug_modal" href="/lead/lead-pane-debug?_id=<?php echo $split_queue->getLead()->getLeadId() ?>">view debug</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>">add/change data</a>
			</div>
			<div class="btn-group" role="group">
				<?php if ($split_queue->getIsCatchAll()) { ?>
					<a class="btn btn-info" data-toggle="modal" data-target="#fulfillment_modal" href="/export/split-queue-pane-assign?_id=<?php echo $split_queue->getId() ?>">assign to split</a>
				<?php } else { ?>
					<a class="btn btn-info" data-toggle="modal" data-target="#fulfillment_modal" href="/export/split-queue-pane-fulfill?_id=<?php echo $split_queue->getId() ?>">fulfill lead</a>
				<?php } ?>
			</div>
			<?php if ($split_queue->getIsCatchAll()) { ?>
				<a data-toggle="modal" data-target="#unfulfillable_modal" href="/export/split-queue-pane-mark-unfulfillable?_id=<?php echo $split_queue->getId() ?>" class="btn btn-danger">flag lead</a>
			<?php } else { ?>
				<a data-toggle="modal" data-target="#unfulfillable_modal" href="/export/split-queue-pane-mark-unfulfillable?_id=<?php echo $split_queue->getId() ?>" class="btn btn-danger">flag lead</a>
			<?php } ?>
		</div>
	</div>
	<h1>View Queue Item <small><?php echo $split_queue->getId() ?></small></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/export/split-search">Splits</a></li>
	<li><a href="/export/split?_id=<?php echo $split_queue->getSplit()->getSplitId() ?>"><?php echo $split_queue->getSplit()->getSplitName() ?></a></li>
	<li class="active">Lead #<?php echo $split_queue->getId() ?></li>
</ol>

<!-- Page Content -->
<div class="help-block">You can view a lead on this screen and see how it was tracked</div>
<br/>
<div class="col-md-8">
	<?php if ($split_queue->getIsError()) { ?>
		<div class="alert alert-warning"><?php echo $split_queue->getErrorMessage() ?></div>
	<?php } ?>
	<div class="panel panel-default">
		<div class="panel-heading">Data Information</div>
		<div class="panel-body">
			<dl class="dl-horizontal">
			<?php if ($split_queue->getLead()->getLead()->getValue('name') != '') { ?>
				  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('name')->getId() ?>">Name:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('name') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('fn') != '') { ?>
				  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('fn')->getId() ?>">Firstname:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('fn') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('ln') != '') { ?>
				  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('ln')->getId() ?>">Lastname:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('ln') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $split_queue->getLead()->getLead()->getValue('ln') ?>/<?php echo $split_queue->getLead()->getLead()->getValue('zi') ?>">address lookup</a>)</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('em') != '') { ?>
				  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('em')->getId() ?>">Email:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('em') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('a1') != '') { ?>
				  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('a1')->getId() ?>">Address:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('a1') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $split_queue->getLead()->getLead()->getValue('a1') ?>&where=<?php echo $split_queue->getLead()->getLead()->getValue('zi') ?>">address lookup</a>)</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('cy') != '') { ?>
				  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('cy')->getId() ?>">City:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('cy') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('st') != '') { ?>
				<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('st')->getId() ?>">State:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('st') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('zi') != '') { ?>
				<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('zi')->getId() ?>">Zip:</a></dt>
				<dd><?php echo $split_queue->getLead()->getLead()->getValue('zi') ?>&nbsp;
					<?php if ($split_queue->getLead()->getLead()->getValue('a1') != '') { ?>
						(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $split_queue->getLead()->getLead()->getValue('a1') ?>&where=<?php echo $split_queue->getLead()->getLead()->getValue('zi') ?>">address lookup</a>)
					<?php } else { ?>
						(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $split_queue->getLead()->getLead()->getValue('ln') ?>/<?php echo $split_queue->getLead()->getLead()->getValue('zi') ?>">address lookup</a>)
					<?php } ?>
				</dd>
			<?php } ?>
			<?php if ($split_queue->getLead()->getLead()->getValue('ph') != '') { ?>
				  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('ph')->getId() ?>">Phone:</a></dt><dd><?php echo $split_queue->getLead()->getLead()->getValue('ph') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/phone/<?php echo $split_queue->getLead()->getLead()->getValue('ph') ?>">phone lookup</a>)</dd>
			<?php } ?>
		</dl>
		<hr />
		<dl class="dl-horizontal">
			<?php
				 $known_fields = array('fn', 'ln', 'a1', 'cy', 'st', 'zi', 'em', 'name', 'ph'); 
				 foreach ($split_queue->getLead()->getLead()->getD() as $key => $value) { 
			?>
				<?php if (!in_array($key, $known_fields)) { ?>
					<?php	 							 
						 $data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
						 if (!is_null($data_field)) {
					?>
						<?php if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE) { ?>
							<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo date('m/d/Y', $value->sec) ?>&nbsp;</dd>
						<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
							<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo date('m/d/Y g:i:s a', $value->sec) ?>&nbsp;</dd>
						<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) { ?>
							<?php if (is_array($value)) { ?>
							   <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo implode(', ', $value) ?>&nbsp;</dd>
							<?php } else if (is_string($value)) { ?>
							   <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo $value ?>&nbsp;</dd>
							<?php } ?>
						<?php } else if (is_array($value)) { ?>
							<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo implode(', ', $value) ?>&nbsp;</dd>
						<?php } else { ?>
							<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $split_queue->getLead()->getLeadId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo $value ?>&nbsp;</dd>
						<?php } ?>
					<?php } else { ?>
						 <dt><i><?php echo $key ?></i>:</dt><dd><?php echo is_array($value) ? implode(", ", $value) : $value ?>&nbsp;</dd>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			</dl>
		</div>
	</div>
	<br />
	<div class="panel panel-default">
		<div class="panel-heading">
			Events
		</div>
		<div class="panel-body">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Event</th>
						<th>Time</th>
						<th class="text-center">Value</th>
						<th>Payout</th>
						<th>Revenue</th>
					</tr>
				</thead>
				<tbody>
					<?php
					   /* @var $split_queue_event \Flux\LeadEvent */ 
					   foreach ($split_queue->getLead()->getLead()->getE() as $key => $split_queue_event) { 
					?>
						<tr>
							<td><?php echo $split_queue_event->getDataField()->getDataField()->getName() ?></td>
							<td>
								 <?php if ($split_queue_event->getT() instanceof \MongoDate) { ?>
									 <?php echo date('m/d/Y g:i:s a', $split_queue_event->getT()->sec) ?>
								 <?php } else { ?>
									 &nbsp;
								 <?php } ?>
							</td>
							<td class="text-center"><?php echo $split_queue_event->getValue() ?></td>
							<td>$<?php echo number_format($split_queue_event->getPayout(), 2, null, ',') ?></td>
							<td>$<?php echo number_format($split_queue_event->getRevenue(), 2, null, ',') ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			Fulfillment Attempts
		</div>
		<div class="panel-body">
			<table class="table table-striped">
				<thead>
					<tr>
						<th width="90">Screenshot</th>
						<th width="40%">Fulfillment</th>
						<th class="text-center">Response</th>
					</tr>
				</thead>
				<tbody id="attempt_tbody"></tbody>
			</table>
		</div>
	</div>
	<p />

	<div class="panel panel-default">
		<div class="panel-heading">
			Tracking Information
		</div>
		<div class="panel-body word-break">
			<dl class="dl-horizontal">
				<dt>Id:</dt>
				<dd><a href="/lead/lead?_id=<?php echo $split_queue->getLead()->getLeadId() ?>"><?php echo $split_queue->getLead()->getLeadId() ?></a></dd>
				<dt>Created:</dt><dd><?php echo date('m/d/Y g:i:s a', $split_queue->getId()->getTimestamp()) ?></dd>
				<?php if ($split_queue->getLead()->getLead()->getModified() instanceof \MongoDate) { ?>
					<dt>Updated:</dt><dd><?php echo date('m/d/Y g:i:s a', $split_queue->getLead()->getLead()->getModified()->sec) ?></dd>
				<?php } ?>
			</dl>
			<hr />
			<dl class="dl-horizontal">
				<dt>Offer:</dt>
				<dd><a href="/offer/offer?_id=<?php echo $split_queue->getLead()->getLead()->getTracking()->getOffer()->getOfferId() ?>"><?php echo $split_queue->getLead()->getLead()->getTracking()->getOffer()->getOfferName() ?></a></dd>
				<dt>Client:</dt>
				<dd><a href="/client/client?_id=<?php echo $split_queue->getLead()->getLead()->getTracking()->getClient()->getClientId() ?>"><?php echo $split_queue->getLead()->getLead()->getTracking()->getClient()->getClientName() ?></a></dd>
				<dt>Campaign:</dt>
				<dd><a href="/campaign/campaign?_id=<?php echo $split_queue->getLead()->getLead()->getTracking()->getCampaign()->getCampaignId() ?>"><?php echo $split_queue->getLead()->getLead()->getTracking()->getCampaign()->getCampaignId() ?></a></dd>
			</dl>
			<hr />
			<dl class="dl-horizontal">
				<dt>Sub Id #1:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getS1() ?></dd>
				<dt>Sub Id #2:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getS2() ?></dd>
				<dt>Sub Id #3:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getS3() ?></dd>
				<dt>Sub Id #4:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getS4() ?></dd>
				<dt>Sub Id #5:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getS5() ?></dd>
				<dt>Unique Id:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getUid() ?></dd>
			</dl>
			<hr class="clear-fix" />
			<dl class="dl-horizontal">
				<dt>IP:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getIp() ?></dd>
				<dt>Browser:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getUserAgentBrowser() ?></dd>
				<dt>Platform:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getUserAgentPlatform() ?></dd>
				<dt>Version:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getUserAgentVersion() ?></dd>
			</dl>
			<hr />
			<dl class="dl-horizontal">
				<dt>URL:</dt><dd><?php echo $split_queue->getLead()->getLead()->getTracking()->getUrl() ?></dd>
				<dt>Referral:</dt><dd><?php echo urldecode($split_queue->getLead()->getLead()->getTracking()->getRef()) ?></dd>
			</dl>
			<dl class="dl-horizontal">
			<?php foreach ($split_queue->getLead()->getLead()->getT() as $key => $value) { ?>
				<?php if (!in_array($key, array(\Flux\DataField::DATA_FIELD_REF_CLIENT_ID, \Flux\DataField::DATA_FIELD_REF_OFFER_ID))) { ?>
					<?php 
						 $data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
						 if (!is_null($data_field)) {
					?>	 
						<dt><?php echo $data_field->getName() ?>:</dt><dd><?php echo is_array($value) ? var_export($value, true) : $value ?></dd>
					<?php } else { ?>
						 <dt><i><?php echo $key ?></i>:</dt><dd><?php echo $value ?></dd>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			</dl>
		</div>
	</div>
</div>
	
<!-- Add data field modal -->
<div class="modal fade" id="add-data-field-modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Notes modal -->
<div class="modal fade" id="note_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Add Note modal -->
<div class="modal fade" id="add_note_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<!-- Debug Data modal -->
<div class="modal fade" id="debug_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Fulfillment modal -->
<div class="modal fade" id="fulfillment_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Attempt modal -->
<div class="modal fade" id="attempt_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Unfulfillable modal -->
<div class="modal fade" id="unfulfillable_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	$('#btn_delete,#btn_delete_sm').click(function() {
		if (confirm('Are you sure you want to delete this lead and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/export/split-queue/<?php echo $split_queue->getId() ?>' }, function(data) {
				$.rad.notify('Lead Removed', 'This lead has been removed from the system.');
				location.href = '/export/split-queue-search';
			});
		}
	});

	$('#add-data-field-modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#attempt_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	loadAttempts();
});

function loadAttempts() {
	$.rad.get('/api', { func: '/export/split-queue', _id: '<?php echo $split_queue->getId() ?>' }, function(data) {
		$('#attempt_tbody').html('');
		$.each(data.record.attempts, function(i, item) {
			var tr = $('<tr />');
			td = $('<td />').appendTo(tr);
			if (item.screenshot && item.screenshot != '') {
				  $('<a data-toggle="modal" data-target="#attempt_modal" href="/export/split-queue-pane-attempt?_id=<?php echo $split_queue->getId() ?>&index=' + i + '"><img src="data:image/png;base64,' + item.screenshot + '" border="0" class="img-thumbnail img-responsive" width="90" /></a>').appendTo(td);
			}
			td = $('<td />').appendTo(tr);
			$('<a data-toggle="modal" data-target="#attempt_modal" href="/export/split-queue-pane-attempt?_id=<?php echo $split_queue->getId() ?>&index=' + i + '">' + item.fulfillment.fulfillment_name + '</a>').appendTo(td);
			$('<div class="small">' + moment.unix(item.attempt_time.sec).calendar() + '</div>').appendTo(td);
			td = $('<td class="text-center small ' + (item.is_error ? "text-danger" : "text-success") + '">' + (item.is_error ? item.error_message : 'no errors') + '</td>').appendTo(tr);
			tr.appendTo($('#attempt_tbody'));
		});
	});
}
//-->
</script>