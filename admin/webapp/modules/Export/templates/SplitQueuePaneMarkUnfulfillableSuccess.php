<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute('split_queue', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Flag Lead with Disposition</h4>
</div>
<form id="split_queue_mark_unfulfillable_form" action="/api" method="PUT">
	<input type="hidden" name="func" value="/export/split-queue" />
	<input type="hidden" name="test" value="0" />
	<input type="hidden" name="_id" value="<?php echo $split_queue->getId() ?>" />
	<div class="modal-body">
		<div class="help-block">
			You can flag this lead as either unfulfillable, pending more information, or already fulfilled.  This will allow you to manage the list of leads.
		</div>
		<select name="disposition" id="disposition" placeholder="flag this lead">
			<option value="<?php echo \Flux\SplitQueue::DISPOSITION_UNFULFILLED ?>" data-data="<?php echo htmlentities(json_encode(array('name' => 'Flag as Unfulfilled', 'className' => 'text-muted', 'description' => 'Flag this lead as unfulfilled.  This lead will show in the list of unfulfilled leads and will be attempted again', 'value' => \Flux\SplitQueue::DISPOSITION_UNFULFILLED))) ?>">Flag as Unfulfilled</option>
			<option value="<?php echo \Flux\SplitQueue::DISPOSITION_PENDING ?>" data-data="<?php echo htmlentities(json_encode(array('name' => 'Flag as Pending', 'className' => 'text-warning', 'description' => 'Flag this lead as pending more information.  This lead will show in the list of queued leads until it is fulfilled', 'value' => \Flux\SplitQueue::DISPOSITION_PENDING))) ?>">Flag as Pending</option>
			<option value="<?php echo \Flux\SplitQueue::DISPOSITION_UNFULFILLABLE ?>" data-data="<?php echo htmlentities(json_encode(array('name' => 'Flag as Unfulfillable', 'className' => 'text-danger', 'description' => 'Flag this lead as unfulfillable.  This lead is missing information and does not qualify.  It will be removed from the list of queued leads.', 'value' => \Flux\SplitQueue::DISPOSITION_UNFULFILLABLE))) ?>">Flag as Unfulfillable</option>
			<option value="<?php echo \Flux\SplitQueue::DISPOSITION_ALREADY_FULFILLED ?>" data-data="<?php echo htmlentities(json_encode(array('name' => 'Flag as Already Fulfilled', 'className' => 'text-info', 'description' => 'Flag this lead already fulfilled.  This lead will be removed from the list of queued leads', 'value' => \Flux\SplitQueue::DISPOSITION_ALREADY_FULFILLED))) ?>">Flag as Already Fulfilled</option>
			<option value="<?php echo \Flux\SplitQueue::DISPOSITION_FULFILLED ?>" data-data="<?php echo htmlentities(json_encode(array('name' => 'Flag as Fulfilled', 'className' => 'text-success', 'description' => 'Flag this lead fulfilled.  This lead will be removed from the list of queued leads', 'value' => \Flux\SplitQueue::DISPOSITION_FULFILLED))) ?>">Flag as Fulfilled</option>
		</select>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Flag Lead</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#disposition').selectize({
		valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				return '<div style="width:100%;padding-right:25px;">' +
					'<b class="' + escape(item.className) + '">' + escape(item.name) + ' <span class="glyphicon glyphicon-flag" aria-hidden="true"></b></span><br />' +
					'<div>' + item.description + '</div>' +   
				'</div>';
			},
			option: function(item, escape) {
				return '<div style="width:100%;padding-right:25px;">' +
				'<b class="' + escape(item.className) + '">' + escape(item.name) + ' <span class="glyphicon glyphicon-flag" aria-hidden="true"></span></b><br />' +
				'<div>' + item.description + '</div>' +   
				'</div>';
			}
		}
	});
	
	$('#split_queue_mark_unfulfillable_form').form(function(data) {
		$.rad.notify('Split Marked Unfulfillable', 'This item has been marked as unfulfillable and will not be retried.');
		<?php if (!$split_queue->getIsCatchAll()) { ?>
		location.href = '/export/split-queue-search';
		<?php } else { ?>
		location.href = '/export/split-queue-catch-all-search';
		<?php } ?>
	});
});
//-->
</script>