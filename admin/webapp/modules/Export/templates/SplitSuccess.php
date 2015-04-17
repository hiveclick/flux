<?php
	/* @var $split Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="page-header">
	<!-- Actions -->
	<div class="pull-right">
		<div class="visible-sm visible-xs">
			<div class="btn-group">
  				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right" role="menu">
					<li><a data-toggle="modal" data-target="#edit_split_modal" href="/export/split-pane-edit?_id=<?php echo $split->getId() ?>">edit split</a></li>
					<li class="divider"></li>
					<li><a href="/export/split-queue-search?split[split_id]=<?php echo $split->getId() ?>&split_id_array[]=<?php echo $split->getId() ?>">view leads</a></li>
					<li class="divider"></li>
					<li><a href="#" id="clear_pid_sm">clear pid</a></li>
					<li class="divider"></li>
					<li><a data-toggle="modal" id="btn_delete_sm" data-target="#delete_modal" href="#"><span class="text-danger">delete</span></a></li>
				</ul>
			</div>
		</div>
		<div class="hidden-sm hidden-xs">
			<div class="btn-group" role="group">
				<a class="btn btn-info" data-toggle="modal" data-target="#edit_split_modal" href="/export/split-pane-edit?_id=<?php echo $split->getId() ?>">edit split</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" href="/export/split-queue-search?split[split_id]=<?php echo $split->getId() ?>&split_id_array[]=<?php echo $split->getId() ?>">view leads</a>
			</div>
			<div class="btn-group" role="group">
				<a class="btn btn-info" href="#" id="clear_pid">clear pid</a>
			</div>
			<a data-toggle="modal" id="btn_delete" data-target="#delete_modal" class="btn btn-danger" href="#">delete</a>
		</div>
	</div>
	<h1><?php echo $split->getName() ?></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/export/split-search">Splits</a></li>
	<li class="active"><?php echo $split->getName() ?></li>
</ol>

<!-- Page Content -->

<div class="col-xs-6 col-sm-8 col-md-8 col-lg-9">
	<div class="help-block"><?php echo $split->getDescription() ?></div>
	<hr />
	<h3>Validation</h3>
	<div class="help-block">This split will use the following validation before fulfilling leads</div>
	<ul>
		<?php if (count($split->getValidators()) > 0) { ?>
			<?php
				/* @var $filter \Flux\Link\DataField */ 
				foreach ($split->getValidators() as $validator) {
			?>
				<li>
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
		<?php } else { ?>
			<li class="help-block"><i>No Validation</i></li>
		<?php } ?>
	</ul>
	<hr />
	<h3>Filtering</h3>
	<div class="help-block">This split will use the following filters when finding and fulfilling leads</div>
	<b>Offers</b>
	<ul>
		<?php if (count($split->getOffers()) > 0) { ?>
			<?php
				/* @var $offer \Flux\Link\Offer */ 
				foreach ($split->getOffers() as $offer) { 
			?>
				<li><?php echo $offer->getOfferName() ?></li>
			<?php } ?>
		<?php } else { ?>
			<li class="help-block"><i>All Offers</i></li>
		<?php } ?>
	</ul>
	<p />
	<b>Filters</b>
	<ul>
		<?php if (count($split->getFilters()) > 0) { ?>
			<?php
				/* @var $filter \Flux\Link\DataField */ 
				foreach ($split->getFilters() as $filter) {
			?>
				<li>
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
	<hr />
	<h3>Fulfillment</h3>
	<div class="help-block">This split will use the following fulfillment and schedule when fulfilling leads</div>
	<b>Fulfillment Script</b>
	<ul>
	   <li class="help-block">
	       <a href="/admin/fulfillment?_id=<?php echo $split->getFulfillment()->getFulfillmentId() ?>"><?php echo $split->getFulfillment()->getFulfillmentName() ?></a>
	       <div class="small"><?php echo $split->getFulfillment()->getFulfillment()->getDescription() ?></div>
	   </li>
	</ul>
	<b>Schedule</b>
	<ul>
	   <?php if (count($split->getScheduling()->getDays()) > 0) { ?>
	       <li class="help-block">
    	   <?php
    	       $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); 
    	       foreach ($split->getScheduling()->getDays() as $day) { 
    	   ?>
    	       <?php echo isset($days[$day]) ? $days[$day] : '' ?>
    	   <?php } ?>
    	   </li>
        <?php } else { ?>
            <li class="help-block"><i>Every day</i></li>
        <?php } ?>
	</ul>
	<b>Hours</b>
	<ul>
	   <?php if (!($split->getScheduling()->getStartHour() == 0 && $split->getScheduling()->getEndHour() == 23)) { ?>
    	    <li class="help-block">Only between <?php echo $split->getScheduling()->getStartHour() > 12 ? ($split->getScheduling()->getStartHour() - 12) : $split->getScheduling()->getStartHour() ?> <?php echo $split->getScheduling()->getStartHour() < 12 ? 'AM' : 'PM' ?> and <?php echo $split->getScheduling()->getEndHour() > 12 ? ($split->getScheduling()->getEndHour() - 12) : $split->getScheduling()->getEndHour() ?> <?php echo $split->getScheduling()->getEndHour() < 12 ? 'AM' : 'PM' ?></li> 
        <?php } else { ?>
            <li class="help-block"><i>All day</i></li>
        <?php } ?>
	</ul>
</div>
<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
	<ul class="list-group">
		<li class="list-group-item active">Split Statistics</li>
		<li class="list-group-item">
			<span class="badge"><?php echo number_format($split->getHourlyCount(), 0, null, ',') ?></span>
			Leads this hour
		</li>
		<li class="list-group-item">
			<span class="badge"><?php echo number_format($split->getDailyCount(), 0, null, ',') ?></span>
			Leads today
		</li>
		<li class="list-group-item">
			<span class="badge"><?php echo number_format($split->getYesterdayCount(), 0, null, ',') ?></span>
			Leads yesterday
		</li>
	</ul>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">Split Info</h4>
		</div>
		<div class="panel-body">
			<div class="help-block">This split was last run at <?php echo date('m/d/Y g:i:s a', $split->getLastRunTime()->sec) ?>.  If it is frozen, you can attempt to clear the PID information and restart it.</div>
			<form id="split_clear_pid_form" class="text-center" method="POST" action="/api">
				<input type="hidden" name="func" value="/export/split-clear-pid" />
				<input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
				<input type="submit" class="btn btn-danger" name="btn_submit" value="clear pid" />
			</form>
		</div>
	</div>
</div>

<!-- edit split modal -->
<div class="modal fade" id="edit_split_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	$('#clear_pid,#clear_pid_sm').click(function() {
		$.rad.post('/api', { func: '/export/split-clear-pid', _id: '<?php echo $split->getId() ?>' }, function(data) {
			$.rad.notify('Split PID Cleared', 'The Split PID has been cleared and this split should start promptly.')
		});
	});

	$('#edit_split_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });

	$('#btn_delete,#btn_delete_sm').click(function() {
		if (confirm('Are you sure you want to delete this split and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/export/split', _id: '<?php echo $split->getId() ?>' }, function(data) {
				$.rad.notify('Split Removed', 'This split has been removed from the system.');
				window.location.href = '/export/split-search';
			});
		}
	});
});
//-->
</script>