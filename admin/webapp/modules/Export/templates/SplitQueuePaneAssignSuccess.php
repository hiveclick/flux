<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute('split_queue', array());
	$splits = $this->getContext()->getRequest()->getAttribute('splits', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Assign to Split</h4>
</div>
<form id="split_queue_assign_form" action="/api" method="PUT">
	<input type="hidden" name="func" value="/export/split-queue" />
	<input type="hidden" name="test" value="0" />
	<input type="hidden" name="_id" value="<?php echo $split_queue->getId() ?>" />
	<input type="hidden" name="is_catch_all" value="0" />
	<div class="modal-body">
		<div class="help-block">You can fulfill this item by choosing a fulfillment below</div>
		<div class="form-group">
            <select name="split[split_id]" id="split_id">
                <?php 
                    /* @var $split \Flux\Split */
                    foreach ($splits as $split) {
                ?>
                    <option value="<?php echo $split->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $split->getId(), 'name' => $split->getName(), 'description' => $split->getDescription(), 'client_name' => $split->getFulfillment()->getFulfillment()->getClient()->getClientName(), 'fulfillment_name' => $split->getFulfillment()->getFulfillmentName()))) ?>"><?php echo $split->getName() ?></option>
                <?php } ?>
            </select>
        </div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary">Assign to Split</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#split_id').selectize({
		valueField: '_id',
		labelField: 'name',
		searchField: ['name', 'description'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {	            
	            return '<div style="width:100%;padding-right:25px;">' +
	                '<b>' + escape(item.name) + '</b> <span class="pull-right label label-success">' + escape(item.fulfillment_name) + ' on ' + escape(item.client_name) + '</span><br />' +
	                '<span class="text-muted small">' + escape(item.description) + ' </span>' + 
	            '</div>';
			},
			option: function(item, escape) {
				 return '<div style="width:100%;padding-right:25px;">' +
	                '<b>' + escape(item.name) + '</b> <span class="pull-right label label-success">' + escape(item.fulfillment_name) + ' on ' + escape(item.client_name) + '</span><br />' +
	                '<span class="text-muted small">' + escape(item.description) + ' </span>' + 
	            '</div>';
			}
		}
    });

    $('#split_queue_assign_form').form(function(data) {
        $.rad.notify('Split Assigned', 'You have assigned a split to this catch-all record.  The page will now refresh');
        location.reload();
    });
});
//-->
</script>