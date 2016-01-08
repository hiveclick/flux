<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute("lead", array());
	$datafields = $this->getContext()->getRequest()->getAttribute("datafields", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/lead/lead-search">Leads</a></li>
	<li class="active">Lead #<?php echo $lead->getId() ?></li>
</ol>

<div class="panel-main">
	<div class="media">
		<div class="media-left">
			<i class="fa fa-cloud-upload fa-4x fa-border"></i>
		</div>
		<div class="media-body small">
			<h4 class="media-heading">
				<?php if ($lead->getValue('name') != '') { ?>
					<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('name')->getId() ?>"><?php echo $lead->getValue('name') ?></a>
				<?php } else if ($lead->getValue('fn') != '' || $lead->getValue('ln') != '') { ?>
					<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('fn')->getId() ?>"><?php echo $lead->getValue('fn') ?></a>
					<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('ln')->getId() ?>"><?php echo $lead->getValue('ln') ?></a>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $lead->getValue('ln') ?>/<?php echo $lead->getValue('zi') ?>">address lookup</a>)
				 <?php } else { ?>
					 <?php echo $lead->getId() ?>
				 <?php } ?>
			</h4>
			<div class="text-muted">Received <?php echo date('m/d/Y g:i:s a', $lead->getId()->getTimestamp()) ?> on <?php echo $lead->getT()->getIp() ?></div>
			<div class="text-muted"><?php echo $lead->getT()->getOffer()->getOfferName() ?> on <?php echo $lead->getT()->getClient()->getClientName() ?></div><br />
			<div class="">
				Address: 
				<?php if ($lead->getValue('a1') != '') { ?>
					<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('a1')->getId() ?>"><?php echo $lead->getValue('a1') ?></a>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $lead->getValue('a1') ?>&where=<?php echo $lead->getValue('zi') ?>">address lookup</a>)<br />
				<?php } ?>
				<?php if ($lead->getValue('cy') != '') { ?>
					<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('cy')->getId() ?>"><?php echo $lead->getValue('cy') ?></a>,
				<?php } ?> 
				<?php if ($lead->getValue('st') != '') { ?>
					<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('st')->getId() ?>"><?php echo $lead->getValue('st') ?></a>
				<?php } ?>
				<?php if ($lead->getValue('zi') != '') { ?>
					<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('zi')->getId() ?>"><?php echo $lead->getValue('zi') ?></a>
					<?php if ($lead->getValue('a1') != '') { ?>
						(<a class="small" target="_blank" href="http://www.whitepages.com/search/FindNearby?street=<?php echo $lead->getValue('a1') ?>&where=<?php echo $lead->getValue('zi') ?>">address lookup</a>)
					<?php } else { ?>
						(<a class="small" target="_blank" href="http://www.whitepages.com/name/<?php echo $lead->getValue('ln') ?>/<?php echo $lead->getValue('zi') ?>">address lookup</a>)
					<?php } ?>
				<?php } ?>
			</div>
			<?php if ($lead->getValue('ph') != '') { ?>
				<div class="">Phone: <a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('ph')->getId() ?>"><?php echo $lead->getValue('ph') ?></a>&nbsp;(<a class="small" target="_blank" href="http://www.whitepages.com/phone/<?php echo $lead->getValue('ph') ?>">phone lookup</a>)</div>
			<?php } ?>
			<?php if ($lead->getValue('em') != '') { ?>
				<div class="">Email: <a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo \Flux\DataField::retrieveDataFieldFromKeyName('em')->getId() ?>"><?php echo $lead->getValue('em') ?></a></div>
			<?php } ?>
			
			<br /><br />
			<div class="">
				<a class="btn btn-sm btn-success" data-toggle="modal" data-target="#fulfillment_modal" href="/lead/lead-pane-fulfill?_id=<?php echo $lead->getId() ?>"><i class="fa fa-plus"></i> add to split</a>
				<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>"><i class="fa fa-pencil"></i> add/change data</a>
				<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#debug_modal" href="/lead/lead-pane-debug?_id=<?php echo $lead->getId() ?>"><i class="fa fa-gear"></i> view debug</a>
				<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> delete</a>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<br /><br />

	<div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#data" aria-controls="data" role="tab" data-toggle="tab">Data</a></li>
			<li role="presentation"><a href="#tracking" aria-controls="tracking" role="tab" data-toggle="tab">Tracking</a></li>
			<li role="presentation"><a href="#attempts" aria-controls="attempts" role="tab" data-toggle="tab">Fulfillments</a></li>
			<li role="presentation"><a href="#pages" aria-controls="pages" role="tab" data-toggle="tab">Pages</a></li>
			<li role="presentation"><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a></li>
		</ul>
	
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="data">
				<div class="help-block">This is a list of the data collected on this lead</div>
				<div class="container">
					<table class="table table-responsive table-bordered table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Value</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$known_fields = array('name', 'fn', 'ln', 'a1', 'cy', 'st', 'zi', 'em', 'ph'); 
							foreach ($known_fields as $known_field) { 
								$known_field_found = false;
								foreach ($lead->getD() as $key => $value) {
									if ($key == $known_field) {
										$known_field_found = true;
										$data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
										if (!is_null($data_field)) {
											 $display_value = $value;
											 if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE) {
												 $display_value = date('m/d/Y', $value->sec);
											 } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) {
												 $display_value = date('m/d/Y g:i:s a', $value->sec);
											 } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY && is_array($value)) {
												 $display_value = implode(", ", $value);
											 } else if (is_array($value)) {
												 $display_value = implode(", ", $value);
											 } else {
												 $display_value = $value;
											 }
						?>
										<tr>
											<td>
												<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>
											</td>
											<td><?php echo $display_value ?></td>
										</tr>
						<?php 
										}
									} 
								}
								if (!$known_field_found) { 
									$data_field = \Flux\DataField::retrieveDataFieldFromKeyName($known_field);
						?>
							<tr>
								<td>
									<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>
								</td>
								<td>&nbsp;</td>
							</tr>
						<?php 
								}
							}
						?>
						</tbody>
					</table>
					<table class="table table-responsive table-bordered table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Value</th>
								<th width="20">&nbsp;</th>
							</tr>
						</thead>
						<tbody>	
						<?php
							 $known_fields = array('fn', 'ln', 'a1', 'cy', 'st', 'zi', 'em', 'name', 'ph'); 
							 foreach ($lead->getD() as $key => $value) { 
						?>
							<?php if (!in_array($key, $known_fields)) { ?>
								<?php	 							 
									 $data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
									 if (!is_null($data_field)) {
										 $display_value = $value;
										 if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE) {
											 $display_value = date('m/d/Y', $value->sec);
										 } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) {
											 $display_value = date('m/d/Y g:i:s a', $value->sec);
										 } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY && is_array($value)) {
											 $display_value = implode(", ", $value);
										 } else if (is_array($value)) {
											 $display_value = implode(", ", $value);
										 } else {
											 $display_value = $value;
										 }
								?>
									<tr>
										<td>
											<a data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>
											<div class="small"><i><?php echo $key ?></i></div>
										</td>
										<td><?php echo $display_value ?></td>
										<td><button class="btn btn-default" type="button" data-toggle="modal" data-target="#add-data-field-modal" href="/lead/lead-pane-data-field?_id=<?php echo $lead->getId() ?>&data_field_id=<?php echo $data_field->getId() ?>"><i class="fa fa-edit"></i></button></td>
									</tr>
								<?php } else { ?>
									<tr>
										<td><?php echo $key ?></td>
										<td><?php echo is_array($value) ? implode(", ", $value) : $value ?></td>
										<td>&nbsp;</td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						</tbody>
					</table>
					<table class="table table-responsive table-bordered table-striped">
						<thead>
							<tr>
								<th>Event</th>
								<th>Time</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($lead->getE() as $key => $value) { ?>
								<tr>
									<td><?php echo $value->getDataField()->getName() ?></td>
									<td><?php echo date('m/d/Y g:i a', $value->getT()->sec) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="tracking">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row vertical-divider">
							<div class="col-md-3 text-center">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-user fa-stack-1x fa-inverse"></i>
								</span>
								<div><a href="/client/client?_id=<?php echo $lead->getTracking()->getClient()->getClientId() ?>"><?php echo $lead->getTracking()->getClient()->getClientName() ?></a></div>
								<div class="text-muted">&nbsp;</div>
							</div> 
							<div class="col-md-3 text-center">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-stack-1x fa-inverse">O</i>
								</span>
								<div><a href="/offer/offer?_id=<?php echo $lead->getTracking()->getOffer()->getOfferId() ?>"><?php echo $lead->getTracking()->getOffer()->getOfferName() ?></a></div>
								<div class="text-muted">&nbsp;</div>
							</div>   
							<div class="col-md-3 text-center">
								<img src="/images/traffic-sources/<?php echo $lead->getTracking()->getCampaign()->getCampaign()->getTrafficSource()->getTrafficSourceIcon() ?>_48.png" class="fa-2x" border="0" />
								<div><a href="/campaign/campaign?_id=<?php echo $lead->getTracking()->getCampaign()->getCampaignId() ?>"><?php echo $lead->getTracking()->getCampaign()->getCampaignId() ?></a></div>
								<div class="text-muted small"><?php echo $lead->getTracking()->getCampaign()->getCampaign()->getDescription() ?></div>
								<div class="text-muted small"><?php echo $lead->getTracking()->getS1() ?> / <?php echo $lead->getTracking()->getS2() ?> / <?php echo $lead->getTracking()->getS3() ?></div>
							</div>
							<div class="col-md-3 text-center">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-stack-1x fa-inverse">L</i>
								</span>
								<div><?php echo $lead->getId() ?></div>
								<div class="text-muted small">C: <?php echo date('m/d/Y g:i:s a', $lead->getId()->getTimestamp()) ?></div>
								<?php if ($lead->getModified() instanceof \MongoDate) { ?>
									<div class="text-muted small">U: <?php echo date('m/d/Y g:i:s a', $lead->getModified()->sec) ?></div>
								<?php } ?>
							</div>
						</div> 
					</div>  
					<div class="panel-footer small">
						<div class="row small">
							<div class="col-md-8 text-left">
								<ul class="list-inline">
									<li><b>S1:</b> <?php echo $lead->getTracking()->getS1() ?></li>
									<li><i class="fa fa-ellipsis-v"></i></li>
									<li><b>S2:</b> <?php echo $lead->getTracking()->getS2() ?></li>
									<li><i class="fa fa-ellipsis-v"></i></li>
									<li><b>S3:</b> <?php echo $lead->getTracking()->getS3() ?></li>
									<li><i class="fa fa-ellipsis-v"></i></li>
									<li><b>S4:</b> <?php echo $lead->getTracking()->getS4() ?></li>
									<li><i class="fa fa-ellipsis-v"></i></li>
									<li><b>S5:</b> <?php echo $lead->getTracking()->getS5() ?></li>
									<li><i class="fa fa-ellipsis-v"></i></li>
									<li><b>Uid:</b> <?php echo $lead->getTracking()->getUid() ?></li>
								</ul>
							</div>
							<div class="col-md-4 text-right">
								<?php echo $lead->getTracking()->getIp() ?>
								/
								<i class="fa fa-<?php echo strtolower($lead->getTracking()->getUserAgentBrowser()) ?>"></i> <?php echo $lead->getTracking()->getUserAgentBrowser() ?> <?php echo $lead->getTracking()->getUserAgentVersion() ?>
								/
								<?php if (stripos($lead->getTracking()->getUserAgentPlatform(), 'win') === 0) { ?>
									<i class="fa fa-windows"></i> <?php echo $lead->getTracking()->getUserAgentPlatform() ?>
								<?php } else if (stripos($lead->getTracking()->getUserAgentPlatform(), 'mac') === 0) { ?>
									<i class="fa fa-apple"></i> <?php echo $lead->getTracking()->getUserAgentPlatform() ?>
								<?php } else if (stripos($lead->getTracking()->getUserAgentPlatform(), 'lin') === 0) { ?>
									<i class="fa fa-linux"></i> <?php echo $lead->getTracking()->getUserAgentPlatform() ?>
								<?php } else if (stripos($lead->getTracking()->getUserAgentPlatform(), 'and') === 0) { ?>
									<i class="fa fa-android"></i> <?php echo $lead->getTracking()->getUserAgentPlatform() ?>
								<?php } else if (stripos($lead->getTracking()->getUserAgentPlatform(), 'ios') === 0) { ?>
									<i class="fa fa-iphone"></i> <?php echo $lead->getTracking()->getUserAgentPlatform() ?>
								<?php } else { ?>
									<i class="fa fa-laptop"></i> <?php echo $lead->getTracking()->getUserAgentPlatform() ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-body" style="word-wrap:break-word;">
						<div class="row vertical-divider">
							<div class="col-md-6 text-center">
								<h4>Keywords</h4>
								<?php if (trim($lead->getTracking()->getKeywords()) != '') { ?>
									<?php echo $lead->getTracking()->getKeywords() ?>
								<?php } else { ?>
									<div class="text-muted">-- no keywords detected --</div>
								<?php } ?>
							</div>
							<div class="col-md-6">
								<h4>Source</h4>
								<?php if (trim($lead->getTracking()->getSourceUrl()) != '') { ?>
									<?php echo $lead->getTracking()->getSourceUrl() ?>
								<?php } else { ?>
									<div class="text-muted">-- no source detected --</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<hr />
					<div class="panel-body small" style="word-wrap:break-word;">
						<div>
							<h5>Url</h5>
							<?php echo $lead->getTracking()->getUrl() ?>
						</div>
						<hr />
						<div style="word-wrap:break-word;">
							<h5>Referrer</h5>
							<?php echo urldecode($lead->getTracking()->getRef()) ?>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row vertical-divider">
							<?php
								/* @var $event \Flux\LeadEvent */ 
								foreach ($lead->getE() as $key => $event) { 
							?>
								<div class="col-md-3 text-center">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-stack-1x fa-inverse"><?php echo strtoupper(substr($event->getDataField()->getName(), 0, 1)) ?></i>
									</span>
									<div><?php echo $event->getDataField()->getName() ?></div>
									<div class="text-muted"><?php echo date('m/d/Y g:i a', $event->getT()->sec) ?></div>
								</div> 
							<?php } ?>
						</div> 
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="attempts">
				<div id="no_splits_alert" class="<?php echo (count($lead->getLeadSplits()) == 0) ? '' : 'hidden' ?>">
					<h3>There are no splits associated with this lead</h3>
					<a class="btn btn-success" data-toggle="modal" data-target="#fulfillment_modal" href="/lead/lead-pane-fulfill?_id=<?php echo $lead->getId() ?>"><i class="fa fa-plus"></i> add to split</a>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="pages">
				<?php
					/* @var $page \Flux\LeadPage */ 
					foreach ($lead->getPages() as $page) { 
				?>
					<div class="col-xs-6 col-sm-4 col-md-3">
						<div class="thumbnail">
							<div class="thumbnail">
								<img id="offer_page_thumbnail_img_<?php echo $page->getId() ?>" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=300x300&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo $page->getHref() ?>" class="page_thumbnail" src="" border="0" alt="Loading thumbnail..." />
								<div class="text-center"><i class="small"><?php echo $page->getOfferPage()->getOfferPage()->getName() ?></i></div>
							</div>
							<div class="caption">
								<h4><?php echo $page->getOfferPage()->getOfferPage()->getName() ?></h4>
								<div>
									<div>Entrance: <?php echo date('m/d/Y h:i:s', $page->getEntranceTime()->sec) ?></div>
									<div>Exit: <?php echo date('m/d/Y h:i:s', $page->getExitTime()->sec) ?></div>
									<p />
									<div>Time on Page: <?php echo $page->getTimeOnPage() ?> seconds</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="notes">
				<table class="table table-responsive table-striped table-bordered">
					<thead>
						<tr>
							<th class="col-md-2">Date</th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody id="note_tbody">
						<?php 
							$notes = $lead->getNotes();
							$notes = array_reverse($lead->getNotes());
							$last_date = null;
							foreach ($notes as $note) { 
						?>
							<tr>
								<td><?php echo strtoupper(date('g:i:s a T', $note['t']->sec)) ?></td>
								<td style="word-break:break-word;"><?php echo $note['note'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<div class="text-center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_note_modal"><i class="fa fa-plus"></i> Add Note</button></div>
			</div>
		</div>
	</div>
</div>
	
<!-- Add data field modal -->
<div class="modal fade" id="add-data-field-modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Add complete lead modal -->
<div class="modal fade" id="add-complete-modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Add Note modal -->
<div class="modal fade" id="add_note_modal"><div class="modal-dialog"><div class="modal-content">
	<div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title">Add Note to Lead</h4></div>
	<form action="/api" id="lead_note_form" method="POST">
		<input type="hidden" name="func" value="/lead/lead-note" />
		<input type="hidden" name="_id" value="<?php echo $lead->getId() ?>" />
		<div class="modal-body">
			<div class="help-block">Add a note to this client using the form below</div>
			<textarea name="note" rows="10" class="form-control"></textarea>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<input type="submit" class="btn btn-primary" value="Save Note" />
		</div>
	</form>
</div></div></div>
<!-- Debug Data modal -->
<div class="modal fade" id="debug_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- Fulfillment modal -->
<div class="modal fade" id="fulfillment_modal"><div class="modal-lg modal-dialog"><div class="modal-content"></div></div></div>
<!-- confirm delete modal -->
<div class="modal fade" id="delete_modal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Are you certain you want to delete this lead?  All data associated with it will be removed as well.<p /></div><div class="modal-footer"><div id="confirm_delete" class="btn btn-danger">Yes, I'm sure</div><div class="btn btn-default" data-dismiss="modal">No, close</div></div></div></div></div>
<!-- Flag Split modal -->
<div class="modal fade" id="flag_lead_split_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<!-- Attempt Fulfillment modal -->
<div class="modal fade" id="lead_split_fulfill_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>
<!-- Attempt Details modal -->
<div class="modal fade" id="lead_split_attempt_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<div id="lead_split_dummy" class="hidden">
	<div class="col-md-6 lead_split_item" id="lead_split_item_#ID#">
		<div class="panel #CONFIRMEDCLASS#">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-9">
						<h5 class="media-heading">#NAME# (#ID#)</h5>
						<div class="small">Queued: #QUEUETIME# <span class="disposition label label-#DISPOSITION_CLASS#">#DISPOSITION#</span></div>
					</div>
					<div class="col-md-3 text-right">
						<div class="btn-group">
							<button href="/lead/lead-split-flag-disposition?_id=#ID#" data-toggle="modal" data-target="#flag_lead_split_modal" class="btn btn-#DISPOSITION_CLASS# btn-sm disposition-flag"><i class="fa fa-flag"></i>&nbsp;</button>
							<button href="/lead/lead-split-fulfill?_id=#ID#" data-toggle="modal" data-target="#lead_split_fulfill_modal" class="btn btn-default btn-sm"><i class="fa fa-cogs"></i>&nbsp;</button>
							<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="/lead/lead-split-flag-disposition?_id=#ID#" data-toggle="modal" data-target="#flag_lead_split_modal">Flag Fulfillment</a></li>
								<li><a href="/lead/lead-split-fulfill?_id=#ID#" data-toggle="modal" data-target="#lead_split_fulfill_modal">Attempt Fulfillment</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="javascript:confirmLeadSplitDelete('#ID#');">Delete</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				#CONFIRMEDALERT#
				<div class="lead_split_attempts">#ATTEMPTS#</div>
			</div>
			<div class="panel-footer small">
				<ul class="list-inline">
					<li>Last Attempted: <span class="last_attempt_time">#LASTATTEMPTTIME#</span></li>
					<li>Next Attempt: <span class="next_attempt_time">#NEXTATTEMPTTIME#</span></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script>
//<!--
$(document).ready(function() {
	// delete the client information
	$('#confirm_delete').click(function() {
		$.rad.del('/api', {func: '/lead/lead/<?php echo $lead->getId() ?>' }, function() {
			window.location = '/lead/lead-search';
		});
	});

	$('#lead_note_form').form(function(data) {
		$.rad.notify('Note Saved', 'The note has been added to the lead successfully.');

		$.rad.get('/api', { func: '/lead/lead', _id: '<?php echo $lead->getId() ?>' }, function(data) {
			if (data.record && data.record.notes) {
				$('#note_tbody').html('');
				notes = data.record.notes;
				notes.reverse();
				$.each(notes, function(i, note) {
					$('#notes').trigger('add', note);
				});
			}
		});	
			
	},{keep_form:true});

	$('#add-data-field-modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#flag_lead_split_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#lead_split_fulfill_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#lead_split_attempt_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	$('#notes').on('add', function(event, obj) {
		$lead_note_item = $('<tr>');
		$lead_note_item.append('<td>'+ moment.unix(obj.t.sec).calendar() + '</td>');
		$lead_note_item.append('<td style="word-break:break-word;">'+ obj.note + '</td>');
		console.log($lead_note_item);
		$lead_note_item.removeClass('hidden');
		$('#note_tbody').append($lead_note_item);
	});

	$('#attempts').on('add', function(event, obj) {
		$('#no_splits_alert').fadeOut();
		$lead_split_item = $('#lead_split_dummy').clone(true);
		$lead_split_item.removeAttr('id');
		$lead_split_item.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/#ID#/g, obj._id);
			oldHTML = oldHTML.replace(/#NAME#/g, obj.split.name);
			oldHTML = oldHTML.replace(/#QUEUETIME#/g, ((obj.queue_time && obj.queue_time.sec) ? moment.unix(obj.queue_time.sec).calendar() : '<i>-- not queued yet --</i>'));
			if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLED ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'default');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Unfulfilled');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_PENDING ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'warning');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Pending');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_PROCESSING ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'warning');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Processing');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'info');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Already Fulfilled');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_FULFILLED ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'success');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Fulfilled');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLABLE ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'danger');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Unfulfillable');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_CONFIRMED ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'success');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Confirmed');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_FAILOVER ?>') {
				oldHTML = oldHTML.replace(/#DISPOSITION_CLASS#/g, 'warning');
				oldHTML = oldHTML.replace(/#DISPOSITION#/g, 'Failover');
			}
			 
			if (obj.is_confirmed) {
				oldHTML = oldHTML.replace(/#CONFIRMEDCLASS#/g, 'panel-success');
				oldHTML = oldHTML.replace(/#CONFIRMEDALERT#/g, ((obj.confirmed_note) ? '<div class="alert alert-success"><i class="fa fa-check"></i> ' + obj.confirmed_note + '</div>' : '<div class="alert alert-success"><i class="fa fa-check"></i> This fulfillment has been confirmed paid by the network</div>'));
			} else {
				oldHTML = oldHTML.replace(/#CONFIRMEDCLASS#/g, 'panel-default');
				oldHTML = oldHTML.replace(/#CONFIRMEDALERT#/g, '');
			}
			oldHTML = oldHTML.replace(/#LASTATTEMPTTIME#/g, ((obj.last_attempt_time && obj.last_attempt_time.sec) ? moment.unix(obj.last_attempt_time.sec).calendar() : '<i>-- not attempted yet --</i>'));
			oldHTML = oldHTML.replace(/#NEXTATTEMPTTIME#/g, ((obj.next_attempt_time && obj.next_attempt_time.sec) ? moment.unix(obj.next_attempt_time.sec).calendar() : '<i>-- not set yet --</i>'));

			var $attempts = '';
			obj.attempts.forEach(function(item, i) {
				var attempt = '<div class="media"><div class="media-left"><a href="#"><img class="media-object img-thumbnail img-responsive" src="data:image/png;base64,' + item.screenshot + '" width="90" alt="fulfillment screenshot"></a></div>';
				attempt += '<div class="media-body"><h5 class="media-heading"><a data-toggle="modal" data-target="#lead_split_attempt_modal" href="/lead/lead-split-attempt?_id=' + obj._id + '&index=' + i + '">' + item.fulfillment.name + '</a></h5><div class="small">Attempted ' + moment.unix(item.attempt_time.sec).calendar();
				attempt += '<div class="text-muted">Response Time: ' + $.number(item.response_time, 2) + 's</div>';
				if (item.is_error) {
					attempt += '<div class="text-danger">' + item.error_message + '</div>';
				} else {
					attempt += '<div class="text-success">Successfully fulfilled</div>';
				}
				attempt += '</div></div></div>';
				$attempts += attempt;
			});
			oldHTML = oldHTML.replace(/#ATTEMPTS#/g, (($attempts != '') ? $attempts : '<i>-- No attempts yet --</i>'));
			return oldHTML;
		});

		$lead_split_item.removeClass('hidden');
		$('#attempts').append($lead_split_item);
	}).delegate('.lead_split_item', 'disposition_change', function(event, obj) {
		if ($(this).attr('id') == 'lead_split_item_' + obj._id) {
			$('button.disposition-flag', this).removeClass('btn-default').removeClass('btn-warning').removeClass('btn-info').removeClass('btn-success').removeClass('btn-danger');
			$('span.disposition', this).removeClass('label-default').removeClass('label-warning').removeClass('label-info').removeClass('label-success').removeClass('label-danger');
			if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLED ?>') {
				$('button.disposition-flag', this).addClass('btn-default');
				$('span.disposition', this).addClass('label-default');
				$('span.disposition', this).html('Unfulfilled');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_PENDING ?>') {
				$('button.disposition-flag', this).addClass('btn-warning');
				$('span.disposition', this).addClass('label-warning');
				$('span.disposition', this).html('Pending');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_PROCESSING ?>') {
				$('button.disposition-flag', this).addClass('btn-warning');
				$('span.disposition', this).addClass('label-warning');
				$('span.disposition', this).html('Processing');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED ?>') {
				$('button.disposition-flag', this).addClass('btn-info');
				$('span.disposition', this).addClass('label-info');
				$('span.disposition', this).html('Already Fulfilled');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_FULFILLED ?>') {
				$('button.disposition-flag', this).addClass('btn-success');
				$('span.disposition', this).addClass('label-success');
				$('span.disposition', this).html('Fulfilled');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLABLE ?>') {
				$('button.disposition-flag', this).addClass('btn-danger');
				$('span.disposition', this).addClass('label-danger');
				$('span.disposition', this).html('Unfulfillable');
			} else if (obj.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_FAILOVER ?>') {
				$('button.disposition-flag', this).addClass('btn-warning');
				$('span.disposition', this).addClass('label-warning');
				$('span.disposition', this).html('Failover');
			}
		}
	}).delegate('.lead_split_item', 'remove', function(event, obj) {
		$(this).fadeOut(function() {
			if ($('.lead_split_item', '#attempts').size() == 0) {
				$('#no_splits_alert').fadeIn();
			}
		});
	}).delegate('.lead_split_item', 'attempt_add', function(event, obj) {
		if ($(this).attr('id') == 'lead_split_item_' + obj._id) {

			var $attempts = '';
			if (obj.attempts) {
				$('.lead_split_attempts', this).html('');
				obj.attempts.forEach(function(item, i) {
					var attempt = '<div class="media"><div class="media-left"><a href="#"><img class="media-object img-thumbnail img-responsive" src="data:image/png;base64,' + item.screenshot + '" width="90" alt="fulfillment screenshot"></a></div>';
					attempt += '<div class="media-body"><h5 class="media-heading"><a data-toggle="modal" data-target="#lead_split_attempt_modal" href="/lead/lead-split-attempt?_id=' + obj._id + '&index=' + i + '">' + item.fulfillment.name + '</a></h5><div class="small">Attempted ' + moment.unix(item.attempt_time.sec).calendar();
					attempt += '<div class="text-muted">Response Time: ' + $.number(item.response_time, 2) + 's</div>';
					if (item.is_error) {
						attempt += '<div class="text-danger">' + item.error_message + '</div>';
					} else {
						attempt += '<div class="text-success">Successfully fulfilled</div>';
					}
					attempt += '</div></div></div>';
					$attempts += attempt;
				});
			}

			$('.lead_split_attempts', this).html((($attempts != '') ? $attempts : '<i>-- No attempts yet --</i>'));
			
			$('.last_attempt_time', this).html(((obj.last_attempt_time && obj.last_attempt_time.sec) ? moment.unix(obj.last_attempt_time.sec).calendar() : '<i>-- not attempted yet --</i>'));
			$('.next_attempt_time', this).html(((obj.next_attempt_time && obj.next_attempt_time.sec) ? moment.unix(obj.next_attempt_time.sec).calendar() : '<i>-- not attempted yet --</i>'));			
		}
	});

	<?php
		/* @var $page \Flux\LeadSplit */ 
		foreach ($lead->getLeadSplits() as $lead_split) { 
	?>
		$('#attempts').trigger('add', <?php echo json_encode($lead_split->toArray(true)) ?>)
	<?php } ?>

	<?php if (isset($_REQUEST['tab'])) { ?>
		$('.nav-tabs a[href=#<?php echo $_REQUEST['tab'] ?>]').tab('show');
	<?php } ?>
});

function confirmLeadSplitDelete(_id) {
	$.rad.del('/api', { func: '/lead/lead-split/' + _id }, function(data) {
		$('#lead_split_item_' + _id).trigger('remove', {_id: _id });
	});
}
//-->
</script>