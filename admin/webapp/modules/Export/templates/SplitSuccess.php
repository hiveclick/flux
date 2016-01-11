<?php
	/* @var $split Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<!-- Add breadcrumbs -->
<ol class="breadcrumb small">
	<li><span class="fa fa-home"></span> <a href="/index">Home</a></li>
	<li><a href="/export/split-search">Splits</a></li>
	<li class="active"><?php echo $split->getName() ?></li>
</ol>

<!-- Page Content -->
<div class="panel-main">
	<div class="media">
		<div class="media-left">
			<span class="fa fa-random fa-border fa-4x fa-inverse" style="background-color:#fff;color:#000;"></span>
		</div>
		<div class="media-body">
			<h4 class="media-heading"><?php echo $split->getName() ?></h4>
			<div class="text-muted"><i><?php echo $split->getDescription() ?></i></div>
			<p />
			<table class="small table-condensed table-responsive table-bordered">
				<thead>
					<tr>
						<th>Last Hour</th>
						<th>Today</th>
						<th>Yesterday</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center"><?php echo number_format($split->getHourlyCount(), 0, null, ',') ?></td>
						<td class="text-center"><?php echo number_format($split->getDailyCount(), 0, null, ',') ?></td>
						<td class="text-center"><?php echo number_format($split->getYesterdayCount(), 0, null, ',') ?></td>
					</tr>
				</tbody>
			</table>
			<br /><br />
			<div class="">
				<div class="btn-group" role="group">
					<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#edit_split_modal" href="/export/split-pane-edit?_id=<?php echo $split->getId() ?>"><span class="fa fa-pencil"></span> edit split</a>
				</div>
				<div class="btn-group" role="group">
					<a class="btn btn-sm btn-info" href="/export/split-queue-search?split[split_id]=<?php echo $split->getId() ?>&split_id_array[]=<?php echo $split->getId() ?>"><span class="fa fa-eye"></span> view leads</a>
				</div>
				<div class="btn-group" role="group">
					<a class="btn btn-sm btn-info" href="#" id="clear_pid"><span class="fa fa-eraser"></span> clear pid</a>
				</div>
				<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-sm btn-danger" href="#"><span class="fa fa-trash-o"></span> delete</a>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<br /><br />

	<div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist" id="myTab">
			<li role="presentation" class="active"><a href="#tab_filtering" aria-controls="filtering" role="tab" data-toggle="tab">Filters</a></li>
			<li role="presentation"><a href="#tab_validation" aria-controls="validation" role="tab" data-toggle="tab">Validation</a></li>
			<li role="presentation"><a href="#tab_fulfillment" aria-controls="fulfillment" role="tab" data-toggle="tab">Fulfillment</a></li>
			<li role="presentation"><a href="#tab_leads" data-href="/export/split-pane-leads?split[split_id]=<?php echo $split->getId() ?>&date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_7_DAYS ?>&disposition_array[]=<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLED ?>&disposition_array[]=<?php echo \Flux\LeadSplit::DISPOSITION_PENDING ?>&disposition_array[]=<?php echo \Flux\LeadSplit::DISPOSITION_PROCESSING ?>&disposition_array[]=<?php echo \Flux\LeadSplit::DISPOSITION_UNFULFILLABLE ?>" aria-controls="leads" role="tab" data-toggle="tab">Pending Leads</a></li>
			<li role="presentation"><a href="#tab_leads_fulfilled" data-href="/export/split-pane-leads-fulfilled?split[split_id]=<?php echo $split->getId() ?>&date_range=<?php echo \Mojavi\Form\DateRangeForm::DATE_RANGE_TODAY ?>&disposition_array[]=<?php echo \Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED ?>&disposition_array[]=<?php echo \Flux\LeadSplit::DISPOSITION_FULFILLED ?>" aria-controls="leads" role="tab" data-toggle="tab">Recent Fulfillments</a></li>
		</ul>
	
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane" id="tab_validation">
				<div class="help-block">Validation is used for <b>host &amp; post</b> splits to validate the incoming data before it is fulfilled.</div>
				<?php if (count($split->getValidators()) > 0) { ?>
					<b>Validators</b> <a data-toggle="modal" data-target="#edit_split_modal" href="/export/split-pane-edit?_id=<?php echo $split->getId() ?>&tab=validators"><span class="fa fa-external-link"></span></a>
					<div class="help-block">This split will run these validators on posted data:</div>
					<ul class="list-group">
					<?php
						/* @var $filter \Flux\Link\DataField */ 
						foreach ($split->getValidators() as $validator) {
					?>
						<li class="list-group-item">
							<i class="fa fa-filter fa-fw"></i>&nbsp; 
							<?php echo $validator->getDataFieldName() ?>
							<?php if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS) { ?>
								<i>is</i>
								<?php echo implode(", ", $validator->getDataFieldValue()) ?>
							<?php } else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT) { ?>
								<i>is not</i>
								<?php echo implode(", ", $validator->getDataFieldValue()) ?>
							<?php } else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK) { ?>
								<i>is not blank</i>
							<?php } else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET) { ?>
								<i>is set</i>
							<?php } else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT) { ?>
								<i>is greater than</i>
								<?php echo implode(", ", $validator->getDataFieldValue()) ?>
							<?php } else if ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT) { ?>
								<i>is less than</i>
								<?php echo implode(", ", $validator->getDataFieldValue()) ?>
							<?php } ?>
						</li>
					<?php } ?>
					</ul>
				<?php } else { ?>
					<div class="help-block"><i>No Validation</i></div>
				<?php } ?>
			</div>
			<div role="tabpanel" class="tab-pane active" id="tab_filtering">
				<b>Offers</b> <a data-toggle="modal" data-target="#edit_split_modal" href="/export/split-pane-edit?_id=<?php echo $split->getId() ?>&tab=filters"><span class="fa fa-external-link"></span></a>
				<div class="help-block">This split will be applied to all traffic received on these offers:</div>
				<?php if (count($split->getOffers()) > 0) { ?>
					<?php
						/* @var $offer \Flux\Link\Offer */ 
						foreach ($split->getOffers() as $offer) { 
					?>
						 <div class="col-md-4">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="media">
										<div class="media-left">
											<div class="text-muted small text-center">
												<?php if ($offer->getOffer()->getRedirectType() != \Flux\Offer::REDIRECT_TYPE_POST) { ?>
													<img class="thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=48x48&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=<?php echo urlencode($offer->getOffer()->getDefaultCampaign()->getRedirectLink()) ?>" border="0" alt="Loading thumbnail..." data-url="<?php echo $offer->getOffer()->getDefaultCampaign()->getRedirectLink() ?>" width="48" />
												<?php } else { ?>
													<i class="fa fa-cloud-upload fa-4x fa-border" style="background-Color:white;"></i>
												<?php } ?>
											</div>
										</div>
										<div class="media-body">
											<h5 class="media-heading"><a href="/offer/offer?_id=<?php echo $offer->getId() ?>"><?php echo $offer->getOfferName() ?></a></h5>
											<div class="text-muted small"><?php echo $offer->getOffer()->getDefaultCampaign()->getRedirectLink() ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } else { ?>
					<li class="help-block"><i>All Offers</i></li>
				<?php } ?>
				<div class="clearfix"></div>
				<p />
				<b>Filters</b> <a data-toggle="modal" data-target="#edit_split_modal" href="/export/split-pane-edit?_id=<?php echo $split->getId() ?>&tab=filters"><span class="fa fa-external-link"></span></a>
				<div class="help-block">This split will filter traffic based on these filters:</div>
				<ul class="list-group">
					<?php if (count($split->getFilters()) > 0) { ?>
						<?php
							/* @var $filter \Flux\Link\DataField */ 
							foreach ($split->getFilters() as $filter) {
						?>
							<li class="list-group-item">
								<i class="fa fa-filter fa-fw"></i>&nbsp; 
								<?php echo $filter->getDataFieldName() ?>
								<?php if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS) { ?>
									<i>is</i>
									<?php echo implode(", ", $filter->getDataFieldValue()) ?>
								<?php } else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT) { ?>
									<i>is not</i>
									<?php echo implode(", ", $filter->getDataFieldValue()) ?>
								<?php } else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK) { ?>
									<i>is not blank</i>
								<?php } else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET) { ?>
									<i>is set</i>
								<?php } else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT) { ?>
									<i>is greater than</i>
									<?php echo implode(", ", $filter->getDataFieldValue()) ?>
								<?php } else if ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT) { ?>
									<i>is less than</i>
									<?php echo implode(", ", $filter->getDataFieldValue()) ?>
								<?php } ?>
							</li>
						<?php } ?>
					<?php } else { ?>
						<li class="help-block"><i>No Filters</i></li>
					<?php } ?>
				</ul>
			</div>
			<div role="tabpanel" class="tab-pane" id="tab_fulfillment">
				<div class="help-block">This split will use the following fulfillment and schedule when fulfilling leads</div>
				<b>Fulfillment Script</b> <a data-toggle="modal" data-target="#edit_split_modal" href="/export/split-pane-edit?_id=<?php echo $split->getId() ?>&tab=fulfillment"><span class="fa fa-external-link"></span></a>
				<ul class="list-group">
					<li class="list-group-item">
						<div class="media">
							<div class="media-left">
						    	<span class="fa fa-cloud-upload fa-2x"></span>
							</div>
							<div class="media-body">
								<h4 class="media-heading"><a href="/admin/fulfillment?_id=<?php echo $split->getFulfillment()->getId() ?>"><?php echo $split->getFulfillment()->getName() ?></a> <span class="small">($<?php echo number_format($split->getFulfillment()->getFulfillment()->getBounty(), 2, null, ',') ?>)</span></h4> 
						    	<div class="small"><?php echo $split->getFulfillment()->getFulfillment()->getDescription() ?></div>
								<?php if ($split->getFulfillImmediately()) { ?>
									<br />
									<div class="small">Fulfillment will be done automatically by the system</div>
									<?php if ($split->getFulfillDelay() > 0) { ?>
										<div class="small">Fulfillment will be delayed for <?php echo $split->getFulfillDelay() ?> minutes</div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</li>
					<li class="list-group-item">
						<div class="small">Days</div>
						<?php if (count($split->getScheduling()->getDays()) > 0) { ?>
							<?php
								$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); 
								foreach ($split->getScheduling()->getDays() as $day) { 
							?>
								<span class="label label-success"><?php echo isset($days[$day]) ? $days[$day] : '' ?></span>
							<?php } ?>
						<?php } else { ?>
							<span class="label label-success">Every day</span>
						<?php } ?>
					</li>
					<li class="list-group-item">
						<div class="small">Hours</div>
						<?php if (!($split->getScheduling()->getStartHour() == 0 && $split->getScheduling()->getEndHour() == 23)) { ?>
							Only between <span class="label label-default"><?php echo $split->getScheduling()->getStartHour() > 12 ? ($split->getScheduling()->getStartHour() - 12) : ($split->getScheduling()->getStartHour() > 1 ? $split->getScheduling()->getStartHour() : 12) ?> <?php echo $split->getScheduling()->getStartHour() < 12 ? 'AM' : 'PM' ?></span> and <span class="label label-default"><?php echo $split->getScheduling()->getEndHour() > 12 ? ($split->getScheduling()->getEndHour() - 12) : ($split->getScheduling()->getEndHour() > 1 ? $split->getScheduling()->getEndHour() : 12) ?> <?php echo $split->getScheduling()->getEndHour() < 12 ? 'AM' : 'PM' ?></span>
						<?php } else { ?>
							<span class="label label-success">All day</span>
						<?php } ?>	
					</li>
				</ul>	
						
			   <?php if ($split->getFailoverEnable()) { ?>
				   <hr />
				   <b>Failover Splits</b>
				   <br />
				   <ul class="list-group">
						<?php
							$next_split = $split->getFailoverSplit();
							$counter = 0;
							$continue = true;
							$used_split_id_array = array();
							while (is_object($next_split) && $continue) {
								$counter++;
						?>
							<li class="list-group-item" style="padding-Left:<?php echo 25 * $counter ?>px;">
								<div class="media">
									<div class="media-left">
								    	<span class="fa fa-chevron-right fa-2x"></span>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/export/split?_id=<?php echo $next_split->getSplitId() ?>"><?php echo $next_split->getSplitName() ?></a> <span class="small">($<?php echo number_format($next_split->getSplit()->getFulfillment()->getFulfillment()->getBounty(), 2, null, ',') ?>)</span></h4>
								    	<div class="small"><?php echo $next_split->getSplit()->getDescription() ?></div>
					   					<div class="small">If a conversion is not fired within <b><?php echo $split->getFailoverWaitTime() ?> minutes</b>, then the lead will be attempted on this failover split.</div>
										<?php 
											if (in_array($next_split->getSplitId(), $used_split_id_array)) { 
												$continue = false;
										?>
											<div class="alert alert-warning">
												This split is the same as another one in the chain and may cause a never-ending loop unless the lead fulfills successfully to a previous split.
											</div>
										<?php } ?>
									</div>
								</div>
					   		</li>
						
						<?php
								if ($next_split->getSplit()->getFailoverEnable()) {
									$used_split_id_array[] = $next_split->getSplitId();
									$next_split = $next_split->getSplit()->getFailoverSplit();
								} else {
									$next_split = null;
								}
							}
						?>
				   </ul>
			   <?php } ?>
			</div>
			<div role="tabpanel" class="tab-pane" id="tab_leads_fulfilled"></div>
			<div role="tabpanel" class="tab-pane" id="tab_leads"></div>
		</div>
	</div>
</div>

<!-- edit split modal -->
<div class="modal fade" id="edit_split_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<!-- confirm delete modal -->
<div class="modal fade" id="delete_modal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> Are you certain you want to delete this split?  All data associated with it will be removed as well.<p /></div><div class="modal-footer"><div id="confirm_delete" class="btn btn-danger">Yes, I'm sure</div> <div class="btn btn-default" data-dismiss="modal">No, close</div></div></div></div></div></div>
<!-- Flag Split modal -->
<div class="modal fade" id="flag_lead_split_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	$('#clear_pid,#clear_pid_sm').click(function() {
		$.rad.post('/api', { func: '/export/split-clear-pid', _id: '<?php echo $split->getId() ?>' }, function(data) {
			$.rad.notify('Split PID Cleared', 'The Split PID has been cleared and this split should start promptly.');
		});
	});

	$('#split_clear_pid_form').form(function(data) {
		$.rad.notify('Split PID Cleared', 'The Split PID has been cleared and this split should start promptly.');
	});

	$('#edit_split_modal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});

	// delete the client information
	$('#confirm_delete').click(function() {
		$.rad.del('/api', {func: '/export/split/<?php echo $split->getId() ?>' }, function() {
			window.location = '/export/split-search';
		});
	});

	$('#myTab a[data-href]').on('shown.bs.tab', function(e) {
		var $this = $(this);
		if ($this.data('loaded') != 1) {
			var url = $this.attr('data-href');
		    //Load the page
		    if (url != null) {
			    $($this.attr('href')).load(url, function(data) {
					$this.data('loaded', 1);
			    });
		    }
		}
    });
});
//-->
</script>