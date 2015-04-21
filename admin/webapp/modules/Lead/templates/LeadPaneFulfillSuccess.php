<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
	$splits = $this->getContext()->getRequest()->getAttribute('splits', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Add to Split</h4>
</div>
<form action="/api" id="lead_split_form" method="POST">
	<input type="hidden" name="func" value="/lead/lead-split" />
	<input type="hidden" name="lead[lead_id]" value="<?php echo $lead->getId() ?>" />
	<input type="hidden" name="disposition" value="<?php echo \Flux\SplitQueue::DISPOSITION_UNFULFILLED ?>" />
    <div class="modal-body">
        <div class="help-block">You can assign this lead to a split and then fulfill it on that split</div>
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
    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    	<button type="submit" class="btn btn-primary">Add to Split</button>
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
	
	// submit the form
	$('#lead_split_form').form(function(data) {
		$.rad.notify('Lead Added to Split', 'This lead was added to a split and you can now fulfill it.');		
	},{keep_form:true});
});
//-->
</script>