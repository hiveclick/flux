<?php
	/* @var $lead_split \Flux\LeadSplit */
	$lead_split = $this->getContext()->getRequest()->getAttribute("lead_split", array());
?>
<?php if (!$lead_split) { ?>
	<h1>No more leads on split</h1>
<?php } else {?>
	<div class="page-header">
		<!-- Actions -->
		<div class="pull-right">
			<div class="visible-sm visible-xs">
				<div class="btn-group">
	  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu">
						<li><a href="/export/fulfill-next-lead?_id=<?php echo $lead_split->getId() ?>">mark as fulfilled</a></li>
					</ul>
				</div>
			</div>
			<div class="hidden-sm hidden-xs">
				<a class="btn btn-info" href="/export/fulfill-next-lead?_id=<?php echo $lead_split->getId() ?>">mark as fulfilled</a>
			</div>
		</div>
		<h1>View Next Lead</h1>
	</div>
	<!-- Add breadcrumbs -->
	<ol class="breadcrumb">
		<li><a href="/export/split-search">Splits</a></li>
		<li><a href="/export/split?_id=<?php echo $lead_split->getSplit()->getSplitId() ?>"><?php echo $lead_split->getSplit()->getSplitName() ?></a></li>
		<li class="active">Lead #<?php echo $lead_split->getId() ?></li>
	</ol>
	
	<!-- Page Content -->
	<div class="help-block">You can view a lead on this screen and see how it was tracked</div>
	<br/>
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Data Information</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="name">Split <span class="small text-muted">(split_id)<span>: </label>
					<input type="text" class="form-control" name="split_id" value="<?php echo $lead_split->getSplit()->getSplitId() ?>" id="split_id" />
				</div>
				<div class="form-group">
					<label for="name">Split Name <span class="small text-muted">(split_name)<span>: </label>
					<input type="text" class="form-control" name="split_name" value="<?php echo $lead_split->getSplit()->getSplitName() ?>" id="split_name" />
				</div>
				<div class="form-group">
					<label for="name">Fullname <span class="small text-muted">(name)<span>: </label>
					<input type="text" class="form-control" name="name" value="<?php echo $lead_split->getLead()->getLead()->getValue('name') ?>" id="name" />
				</div>
				<div class="form-group">
					<label for="firstname">Firstname <span class="small text-muted">(firstname)<span>: </label>
					<input type="text" class="form-control" name="firstname" value="<?php echo $lead_split->getLead()->getLead()->getValue('fn') != '' ? $lead_split->getLead()->getLead()->getValue('fn') : substr($lead_split->getLead()->getLead()->getValue('name'), 0, strpos($lead_split->getLead()->getLead()->getValue('name'), ' ')) ?>" id="firstname" />
				</div>
				<div class="form-group">
					<label for="lastname">Lastname <span class="small text-muted">(lastname)<span>: </label>
					<input type="text" class="form-control" name="lastname" value="<?php echo $lead_split->getLead()->getLead()->getValue('ln') != '' ? $lead_split->getLead()->getLead()->getValue('ln') : substr($lead_split->getLead()->getLead()->getValue('name'), strrpos($lead_split->getLead()->getLead()->getValue('name'), ' ') + 1) ?>" id="lastname" />
				</div>
				<div class="form-group">
					<label for="email">Email <span class="small text-muted">(email)<span>: </label>
					<input type="text" class="form-control" name="email" value="<?php echo $lead_split->getLead()->getLead()->getValue('em') ?>" id="email" />
				</div>
				<div class="form-group">
					<label for="a1">Address <span class="small text-muted">(a1)<span>: </label>
					<input type="text" class="form-control" name="a1" value="<?php echo $lead_split->getLead()->getLead()->getValue('a1') ?>" id="a1" />
				</div>
				<div class="form-group">
					<label for="cy">City <span class="small text-muted">(cy)<span>: </label>
					<input type="text" class="form-control" name="cy" value="<?php echo $lead_split->getLead()->getLead()->getDerivedCity() ?>" id="cy" />
				</div>
				<div class="form-group">
					<label for="st">State <span class="small text-muted">(st)<span>: </label>
					<input type="text" class="form-control" name="st" value="<?php echo $lead_split->getLead()->getLead()->getDerivedState() ?>" id="st" />
				</div>
				<div class="form-group">
					<label for="zi">Zip <span class="small text-muted">(zi)<span>: </label>
					<input type="text" class="form-control" name="zi" value="<?php echo $lead_split->getLead()->getLead()->getValue('zi') ?>" id="zi" />
				</div>
				<div class="form-group">
					<label for="ph">Phone <span class="small text-muted">(ph, stripped_ph, area_code, prefix_ph, suffix_ph)<span>: </label>
					<div class="row">
						<div class="col-md-3"><input type="text" class="form-control" name="ph" value="<?php echo $lead_split->getLead()->getLead()->getValue('ph') ?>" id="ph" /></div>
						<div class="col-md-3"><input type="text" class="form-control" name="stripped_ph" value="<?php echo preg_replace('/[^0-9]/', '', $lead_split->getLead()->getLead()->getValue('ph')) ?>" id="stripped_ph" /></div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-4">
									<input type="text" class="form-control" name="area_code" value="<?php echo substr(preg_replace('/[^0-9]/', '', $lead_split->getLead()->getLead()->getValue('ph')), 0, 3) ?>" id="area_code" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="prefix_ph" value="<?php echo substr(preg_replace('/[^0-9]/', '', $lead_split->getLead()->getLead()->getValue('ph')), 3, 3) ?>" id="prefix_ph" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="suffix_ph" value="<?php echo substr(preg_replace('/[^0-9]/', '', $lead_split->getLead()->getLead()->getValue('ph')), 6) ?>" id="suffix_ph" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr />
				<?php
					 $known_fields = array('fn', 'ln', 'a1', 'cy', 'st', 'zi', 'em', 'name', 'ph'); 
					 foreach ($lead_split->getLead()->getLead()->getD() as $key => $value) { 
				?>
					<?php if (!in_array($key, $known_fields)) { ?>
						<?php	 							 
							 $data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
						?>
						<div class="form-group">
							<?php if (!is_null($data_field)) { ?>
								<label for="<?php echo $key ?>"><?php echo $data_field->getName() ?> <span class="small text-muted">(<?php echo $key ?>)<span>: </label>
								<?php if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE) { ?>
									<input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo date('m/d/Y', $value->sec) ?>" id="<?php echo $key ?>" />
								<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
									<input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo date('m/d/Y g:i:s a', $value->sec) ?>" id="<?php echo $key ?>" />
								<?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) { ?>
									<?php if (is_array($value)) { ?>
										<input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo implode(', ', $value) ?>" id="<?php echo $key ?>" />
									<?php } else if (is_string($value)) { ?>
										<input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo $value ?>" id="<?php echo $key ?>" />
									<?php } ?>
			   					<?php } else if (is_array($value)) { ?>
									<input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo implode(', ', $value) ?>" id="<?php echo $key ?>" />
					 			<?php } else { ?>
									<input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo $value ?>" id="<?php echo $key ?>" />
								<?php } ?>
							<?php } else { ?>
								<label for="<?php echo $key ?>"><?php echo $key ?>: </label>
								<input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo is_array($value) ? implode(", ", $value) : $value ?>" id="<?php echo $key ?>" />
							<?php } ?>
						</div>
					<?php } ?>
				<?php } ?>
				<hr />
				<?php 
					$fulfillment = $lead_split->getSplit()->getSplit()->getFulfillment()->getFulfillment();
					$lead_split_attempt = new \Flux\SplitQueueAttempt();
					$lead_split_attempt->setFulfillment($fulfillment->getId());
					$lead_split_attempt->setSplitQueue($lead_split->getId());					
					$params = $lead_split_attempt->mergeLead();
					foreach ($params as $key => $value) { 
				?>
					<div class="form-group">
						<label for="<?php echo $key ?>"><?php echo $key ?> <span class="small text-muted">(mapped_<?php echo $key ?>)<span>: </label>
						<input type="text" class="form-control" name="mapped_<?php echo $key ?>" value="<?php echo $value ?>" id="mapped_<?php echo $key ?>" />
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				Tracking Information
			</div>
			<div class="panel-body word-break">
				<div class="form-group">
					<label for="_id">Id <span class="small text-muted">(_id)<span>: </label>
					<input type="text" class="form-control" name="_id" value="<?php echo $lead_split->getId() ?>" id="_id" />
				</div>
				<div class="form-group">
					<label for="lead_id">Lead Id <span class="small text-muted">(lead_id)<span>: </label>
					<input type="text" class="form-control" name="lead_id" value="<?php echo $lead_split->getLead()->getLeadId() ?>" id="lead_id" />
				</div>
				<div class="form-group">
					<label for="created">Created <span class="small text-muted">(created)<span>: </label>
					<input type="text" class="form-control" name="created" value="<?php echo date('m/d/Y g:i:s a', $lead_split->getId()->getTimestamp()) ?>" id="created" />
				</div>
				<hr />
				<div class="form-group">
					<label for="offer">Offer <span class="small text-muted">(offer)<span>: </label>
					<input type="text" class="form-control" name="offer" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getOffer()->getOfferName() ?>" id="offer" />
				</div>
				<div class="form-group">
					<label for="client">Client <span class="small text-muted">(client)<span>: </label>
					<input type="text" class="form-control" name="client" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getClient()->getClientName() ?>" id="client" />
				</div>
				<div class="form-group">
					<label for="campaign">Campaign <span class="small text-muted">(campaign)<span>: </label>
					<input type="text" class="form-control" name="campaign" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getCampaign()->getCampaignId() ?>" id="campaign" />
				</div>
				<hr />
				<div class="form-group">
					<label for="s1">Sub Id #1 <span class="small text-muted">(s1)<span>: </label>
					<input type="text" class="form-control" name="s1" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getS1() ?>" id="s1" />
				</div>
				<div class="form-group">
					<label for="s2">Sub Id #2 <span class="small text-muted">(s2)<span>: </label>
					<input type="text" class="form-control" name="s2" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getS2() ?>" id="s2" />
				</div>
				<div class="form-group">
					<label for="s3">Sub Id #3 <span class="small text-muted">(s3)<span>: </label>
					<input type="text" class="form-control" name="s3" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getS3() ?>" id="s3" />
				</div>
				<div class="form-group">
					<label for="s4">Sub Id #4 <span class="small text-muted">(s4)<span>: </label>
					<input type="text" class="form-control" name="s4" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getS4() ?>" id="s4" />
				</div>
				<div class="form-group">
					<label for="s5">Sub Id #5 <span class="small text-muted">(s5)<span>: </label>
					<input type="text" class="form-control" name="s5" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getS5() ?>" id="s5" />
				</div>
				<div class="form-group">
					<label for="uid">Unique Id <span class="small text-normal text-muted">(uid)<span>: </label>
					<input type="text" class="form-control" name="uid" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getUid() ?>" id="uid" />
				</div>
				<hr />
				<div class="form-group">
					<label for="ip">IP <span class="small text-normal text-muted">(ip)<span>: </label>
					<input type="text" class="form-control" name="ip" value="<?php echo $lead_split->getLead()->getLead()->getTracking()->getIp() ?>" id="ip" />
				</div>
				<div class="form-group">
					<label for="referer">Referer <span class="small text-normal text-muted">(referer)<span>: </label>
					<input type="text" class="form-control" name="referer" value="<?php echo urldecode($lead_split->getLead()->getLead()->getTracking()->getRef()) ?>" id="referer" />
				</div>
			</div>
		</div>
	</div>
	
	<script>
	//<!--
	$(document).ready(function() {
	
	});
	//-->
	</script>
<?php } ?>