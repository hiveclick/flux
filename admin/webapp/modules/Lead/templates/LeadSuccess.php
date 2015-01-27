<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute("lead", array());
	$datafields = $this->getContext()->getRequest()->getAttribute("datafields", array());
?>
<div id="header">
	<div class="pull-right visible-xs">
		<button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<h2><a href="/lead/lead-search">Leads</a> <small><?php echo $lead->getId() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
	<ul id="flow_tabs" class="nav nav-pills">
		<li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Main</a></li>
		<li><a id="tabs-a-events" href="#tabs-events" data-toggle="tab" data-url="/lead/lead-pane-event?_id=<?php echo $lead->getId() ?>">Events</a></li>
		<li><a id="tabs-a-pages" href="#tabs-pages" data-toggle="tab" data-url="/lead/lead-pane-pages?_id=<?php echo $lead->getId() ?>">Pages</a></li>
		<li><a id="tabs-a-exports" href="#tabs-exports" data-toggle="tab" data-url="/lead/lead-pane-export?_id=<?php echo $lead->getId() ?>">Exports</a></li>
		<li><a id="tabs-a-fulfillment" href="#tabs-fulfillment" data-toggle="tab" data-url="/lead/lead-pane-fulfill?_id=<?php echo $lead->getId() ?>">Fulfillment</a></li>
		<li><a id="tabs-a-notes" href="#tabs-notes" data-toggle="tab" data-url="/lead/lead-pane-notes?_id=<?php echo $lead->getId() ?>">Notes</a></li>
		<li><a id="tabs-a-debug" href="#tabs-debug" data-toggle="tab" data-url="/lead/lead-pane-debug?_id=<?php echo $lead->getId() ?>">Debug</a></li>
	</ul>
</div>
<div id="tab-content-container" class="tab-content">
	<div id="tabs-main" class="tab-pane active">
		<div class="help-block">You can view a lead on this screen and see how it was tracked</div>
		<br/>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="pull-right">
						 <a class="btn-sm btn-info" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>" data-toggle="modal" data-target="#add-data-field-modal">add/change data</a>
					</div>
					Data Information
				</div>
				<div class="panel-body">
				  <dl class="dl-horizontal">
					<?php if ($lead->getValue('fn') != '') { ?>
						  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('fn')->getId() ?>">Firstname:</a></dt><dd><?php echo $lead->getValue('fn') ?>&nbsp;</dd>
					<?php } ?>
					<?php if ($lead->getValue('ln') != '') { ?>
						  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('ln')->getId() ?>">Lastname:</a></dt><dd><?php echo $lead->getValue('ln') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $lead->getValue('ln') ?>/<?php echo $lead->getValue('zi') ?>">address lookup</a>)</dd>
					<?php } ?>
					<?php if ($lead->getValue('em') != '') { ?>
						  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('em')->getId() ?>">Email:</a></dt><dd><?php echo $lead->getValue('em') ?>&nbsp;</dd>
					<?php } ?>
					<?php if ($lead->getValue('a1') != '') { ?>
						  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('a1')->getId() ?>">Address:</a></dt><dd><?php echo $lead->getValue('a1') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $lead->getValue('a1') ?>&where=<?php echo $lead->getValue('zi') ?>">address lookup</a>)</dd>
					<?php } ?>
					<?php if ($lead->getValue('cy') != '') { ?>
						  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('cy')->getId() ?>">City:</a></dt><dd><?php echo $lead->getValue('cy') ?>&nbsp;</dd>
					<?php } ?>
					<?php if ($lead->getValue('st') != '') { ?>
						<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('st')->getId() ?>">State:</a></dt><dd><?php echo $lead->getValue('st') ?>&nbsp;</dd>
					<?php } ?>
					<?php if ($lead->getValue('zi') != '') { ?>
						<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('zi')->getId() ?>">Zip:</a></dt>
						<dd><?php echo $lead->getValue('zi') ?>&nbsp;
							<?php if ($lead->getValue('a1') != '') { ?>
								(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $lead->getValue('a1') ?>&where=<?php echo $lead->getValue('zi') ?>">address lookup</a>)
							<?php } else { ?>
								(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $lead->getValue('ln') ?>/<?php echo $lead->getValue('zi') ?>">address lookup</a>)
							<?php } ?>
						</dd>
					<?php } ?>
					<?php if ($lead->getValue('ph') != '') { ?>
						  <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('ph')->getId() ?>">Phone:</a></dt><dd><?php echo $lead->getValue('ph') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/phone/<?php echo $lead->getValue('ph') ?>">phone lookup</a>)</dd>
					<?php } ?>
				</dl>
				<hr />
				<dl class="dl-horizontal">
					<?php
						 $known_fields = array('fn', 'ln', 'a1', 'cy', 'st', 'zi', 'em'); 
						 foreach ($lead->getD() as $key => $value) { 
					?>
						<?php if (!in_array($key, $known_fields)) { ?>
							<?php	 							 
								 $data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
								 if (!is_null($data_field)) {
							?>
								<?php if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE) { ?>
									<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo date('m/d/Y', $value->sec) ?>&nbsp;</dd>
								<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
									<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo date('m/d/Y g:i:s a', $value->sec) ?>&nbsp;</dd>
								<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) { ?>
									<?php if (is_array($value)) { ?>
									   <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo implode(', ', $value) ?>&nbsp;</dd>
									<?php } else if (is_string($value)) { ?>
									   <dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo $value ?>&nbsp;</dd>
									<?php } ?>
								<?php } else if (is_array($value)) { ?>
									<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo implode(', ', $value) ?>&nbsp;</dd>
								<?php } else { ?>
									<dt><a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>:</dt><dd><?php echo $value ?>&nbsp;</dd>
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
								<th>Payout</th>
								<th>Revenue</th>
							</tr>
						</thead>
						<tbody>
							<?php
							   /* @var $lead_event \Flux\LeadEvent */ 
							   foreach ($lead->getE() as $key => $lead_event) { 
							?>
								<tr>
									<td><?php echo $lead_event->getDataField()->getName() ?></td>
									<td>
										 <?php if ($lead_event->getT() instanceof \MongoDate) { ?>
											 <?php echo date('m/d/Y g:i:s a', $lead_event->getT()->sec) ?>
										 <?php } else { ?>
											 &nbsp;
										 <?php } ?>
									</td>
									<td>$<?php echo number_format($lead_event->getPayout(), 2, null, ',') ?></td>
									<td>$<?php echo number_format($lead_event->getRevenue(), 2, null, ',') ?></td>
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
					Tracking Information
				</div>
				<div class="panel-body word-break">
					<dl class="dl-horizontal">
						<dt>Id:</dt>
						<dd><?php echo $lead->getId() ?></dd>
						<dt>Created:</dt><dd><?php echo date('m/d/Y g:i:s a', $lead->getId()->getTimestamp()) ?></dd>
					</dl>
					<hr />
					<dl class="dl-horizontal">
						<dt>Offer:</dt>
						<dd><a href="/offer/offer?_id=<?php echo $lead->getTracking()->getOfferId() ?>"><?php echo $lead->getTracking()->getOfferName() ?></a></dd>
						<dt>Client:</dt>
						<dd><a href="/client/client?_id=<?php echo $lead->getTracking()->getClientId() ?>"><?php echo $lead->getTracking()->getClientName() ?></a></dd>
						<dt>Campaign:</dt>
						<dd><a href="/campaign/campaign?_id=<?php echo $lead->getTracking()->getCampaignId() ?>"><?php echo $lead->getTracking()->getCampaignId() ?></a></dd>
					</dl>
					<hr />
					<dl class="dl-horizontal">
						<dt>Sub Id #1:</dt><dd><?php echo $lead->getTracking()->getS1() ?></dd>
						<dt>Sub Id #2:</dt><dd><?php echo $lead->getTracking()->getS2() ?></dd>
						<dt>Sub Id #3:</dt><dd><?php echo $lead->getTracking()->getS3() ?></dd>
						<dt>Sub Id #4:</dt><dd><?php echo $lead->getTracking()->getS4() ?></dd>
						<dt>Sub Id #5:</dt><dd><?php echo $lead->getTracking()->getS5() ?></dd>
						<dt>Unique Id:</dt><dd><?php echo $lead->getTracking()->getUid() ?></dd>
					</dl>
					<hr class="clear-fix" />
					<dl class="dl-horizontal">
						<dt>IP:</dt><dd><?php echo $lead->getTracking()->getIp() ?></dd>
						<dt>Browser:</dt><dd><?php echo $lead->getTracking()->getUserAgentBrowser() ?></dd>
						<dt>Platform:</dt><dd><?php echo $lead->getTracking()->getUserAgentPlatform() ?></dd>
						<dt>Version:</dt><dd><?php echo $lead->getTracking()->getUserAgentVersion() ?></dd>
					</dl>
					<hr />
					<dl class="dl-horizontal">
						<dt>URL:</dt><dd><?php echo $lead->getTracking()->getUrl() ?></dd>
						<dt>Referral:</dt><dd><?php echo urldecode($lead->getTracking()->getRef()) ?></dd>
					</dl>
					<dl class="dl-horizontal">
					<?php foreach ($lead->getT() as $key => $value) { ?>
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
	</div>
	<div id="tabs-events" class="tab-pane"></div>
	<div id="tabs-pages" class="tab-pane"></div>
	<div id="tabs-exports" class="tab-pane"></div>
	<div id="tabs-fulfillment" class="tab-pane"></div>
	<div id="tabs-notes" class="tab-pane"></div>
	<div id="tabs-debug" class="tab-pane"></div>
	
	<!-- Add data field modal -->
	<div class="modal fade" id="add-data-field-modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		e.preventDefault();
		var hash = this.hash;
		if ($(this).attr("data-url")) {
			// only load the page the first time
			if ($(hash).html() == '') {
				// ajax load from data-url
				$(hash).load($(this).attr("data-url"));
			}
		}
	}).on('show.bs.tab', function (e) {
		try {
			sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
		} catch (err) { }
	});

	$('#btn_delete').click(function() {
		if (confirm('Are you sure you want to delete this lead and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/lead/lead/<?php echo $lead->getId() ?>' }, function(data) {
				$.rad.notify('Lead Removed', 'This lead has been removed from the system.');
			});
		}
	});

	$('#add-data-field-modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });

	// Store the last clicked tab so it can be loaded on page refreshes
	var localTabStorageName = <?php echo json_encode('lead_tab_' . $lead->getId()); ?>;
	var lastTab = sessionStorage.getItem(localTabStorageName);
	if (lastTab) {
		$('a[href='+lastTab+']').tab('show');
	} else {
		$('ul.nav-pills a:first').tab('show');
	}
});
//-->
</script>