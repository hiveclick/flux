<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute("lead", array());
	$datafields = $this->getContext()->getRequest()->getAttribute("datafields", array());
?>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a href="/campaign/campaign-lead-page-search?_id=<?php echo $lead->getId() ?>">pages</a></li>
					<li><a data-toggle="modal" data-target="#debug_modal" href="/campaign/campaign-lead-pane-debug?_id=<?php echo $lead->getId() ?>">view debug</a></li>
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<a class="btn btn-info" href="/campaign/campaign-lead-page-search?_id=<?php echo $lead->getId() ?>">pages</a>
			<a class="btn btn-info" data-toggle="modal" data-target="#debug_modal" href="/campaign/campaign-lead-pane-debug?_id=<?php echo $lead->getId() ?>">view debug</a>
		</div>
	</div>
	<h1>Raw Lead <small><?php echo $lead->getId() ?></small></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
    <li><a href="/campaign/campaign-search">Campaigns</a></li>
	<li><a href="/campaign/campaign?_id=<?php echo $lead->getTracking()->getCampaign()->getCampaignName() ?>">Campaign #<?php echo $lead->getTracking()->getCampaign()->getCampaignName() ?></a></li>
	<li><a href="/campaign/campaign-leads?_id=<?php echo $lead->getTracking()->getCampaign()->getCampaignName() ?>">Leads</a></li>
	<li class="active">Lead #<?php echo $lead->getId() ?></li>
</ol>

<!-- Page Content -->
<div class="help-block">You can view a lead on this screen and see how it was tracked</div>
<br/>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">Data Information</div>
		<div class="panel-body">
            <dl class="dl-horizontal">
            <?php if ($lead->getValue('name') != '') { ?>
				  <dt>Name:</dt><dd><?php echo $lead->getValue('name') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($lead->getValue('fn') != '') { ?>
				  <dt>Firstname:</dt><dd><?php echo $lead->getValue('fn') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($lead->getValue('ln') != '') { ?>
				  <dt>Lastname:</dt><dd><?php echo $lead->getValue('ln') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $lead->getValue('ln') ?>/<?php echo $lead->getValue('zi') ?>">address lookup</a>)</dd>
			<?php } ?>
			<?php if ($lead->getValue('em') != '') { ?>
				  <dt>Email:</dt><dd><?php echo $lead->getValue('em') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($lead->getValue('a1') != '') { ?>
				  <dt>Address:</dt><dd><?php echo $lead->getValue('a1') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $lead->getValue('a1') ?>&where=<?php echo $lead->getValue('zi') ?>">address lookup</a>)</dd>
			<?php } ?>
			<?php if ($lead->getValue('cy') != '') { ?>
				  <dt>City:</dt><dd><?php echo $lead->getValue('cy') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($lead->getValue('st') != '') { ?>
				<dt>State:</dt><dd><?php echo $lead->getValue('st') ?>&nbsp;</dd>
			<?php } ?>
			<?php if ($lead->getValue('zi') != '') { ?>
				<dt>Zip:</dt>
				<dd><?php echo $lead->getValue('zi') ?>&nbsp;
					<?php if ($lead->getValue('a1') != '') { ?>
						(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $lead->getValue('a1') ?>&where=<?php echo $lead->getValue('zi') ?>">address lookup</a>)
					<?php } else { ?>
						(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $lead->getValue('ln') ?>/<?php echo $lead->getValue('zi') ?>">address lookup</a>)
					<?php } ?>
				</dd>
			<?php } ?>
			<?php if ($lead->getValue('ph') != '') { ?>
				  <dt>Phone:</dt><dd><?php echo $lead->getValue('ph') ?>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/phone/<?php echo $lead->getValue('ph') ?>">phone lookup</a>)</dd>
			<?php } ?>
		</dl>
		<hr />
		<dl class="dl-horizontal">
			<?php
				 $known_fields = array('fn', 'ln', 'a1', 'cy', 'st', 'zi', 'em', 'name', 'ph'); 
				 foreach ($lead->getD() as $key => $value) { 
			?>
				<?php if (!in_array($key, $known_fields)) { ?>
					<?php	 							 
						 $data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
						 if (!is_null($data_field)) {
					?>
						<?php if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE) { ?>
							<dt><?php echo $data_field->getName() ?>:</dt><dd><?php echo date('m/d/Y', $value->sec) ?>&nbsp;</dd>
						<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
							<dt><?php echo $data_field->getName() ?>:</dt><dd><?php echo date('m/d/Y g:i:s a', $value->sec) ?>&nbsp;</dd>
						<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) { ?>
							<?php if (is_array($value)) { ?>
							   <dt><?php echo $data_field->getName() ?>:</dt><dd><?php echo implode(', ', $value) ?>&nbsp;</dd>
							<?php } else if (is_string($value)) { ?>
							   <dt><?php echo $data_field->getName() ?>:</dt><dd><?php echo $value ?>&nbsp;</dd>
							<?php } ?>
						<?php } else if (is_array($value)) { ?>
							<dt><?php echo $data_field->getName() ?>:</dt><dd><?php echo implode(', ', $value) ?>&nbsp;</dd>
						<?php } else { ?>
							<dt><?php echo $data_field->getName() ?>:</dt><dd><?php echo $value ?>&nbsp;</dd>
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
					</tr>
				</thead>
				<tbody>
					<?php
					   /* @var $lead_event \Flux\LeadEvent */ 
					   foreach ($lead->getE() as $key => $lead_event) { 
					?>
						<tr>
							<td><?php echo $lead_event->getDataField()->getDataField()->getName() ?></td>
							<td>
								 <?php if ($lead_event->getT() instanceof \MongoDate) { ?>
									 <?php echo date('m/d/Y g:i:s a', $lead_event->getT()->sec) ?>
								 <?php } else { ?>
									 &nbsp;
								 <?php } ?>
							</td>
							<td class="text-center"><?php echo $lead_event->getValue() ?></td>
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
				<?php if ($lead->getModified() instanceof \MongoDate) { ?>
				    <dt>Updated:</dt><dd><?php echo date('m/d/Y g:i:s a', $lead->getModified()->sec) ?></dd>
				<?php } ?>
			</dl>
			<hr />
			<dl class="dl-horizontal">
				<dt>Offer:</dt>
				<dd><?php echo $lead->getTracking()->getOffer()->getOfferName() ?></dd>
				<dt>Client:</dt>
				<dd><?php echo $lead->getTracking()->getClient()->getClientName() ?></dd>
				<dt>Campaign:</dt>
				<dd><a href="/campaign/campaign?_id=<?php echo $lead->getTracking()->getCampaign()->getCampaignId() ?>"><?php echo $lead->getTracking()->getCampaign()->getCampaignId() ?></a></dd>
			</dl>
			<hr />
			<dl class="dl-horizontal">
				<dt>Keywords:</dt><dd><?php echo $lead->getTracking()->getKeywords() ?></dd>
				<dt>Source:</dt><dd><?php echo $lead->getTracking()->getSourceUrl() ?></dd>
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
	

<!-- Debug Data modal -->
<div class="modal fade" id="debug_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>


<script>
//<!--
$(document).ready(function() {
	// delete the client information
});
//-->
</script>