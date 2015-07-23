<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Complete Lead</h4>
</div>
<form class="form-horizontal" id="lead_complete_form" name="lead_complete_form" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/lead/lead-complete" />
	<input type="hidden" name="_id" value="<?php echo $lead->getId() ?>" />
	<div class="modal-body">
		Use this form attempt to complete this lead using lookups
		<p />
		
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-success btn-add-data_field"><span class="glyphicon glyphicon-plus"></span> Add Data Field</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save Changes</button>
	</div>
</form>

<script>
//<!--
$(document).ready(function() {

	// submit the form
	$('#lead_complete_form').form(function(data) {
		$.rad.notify('Data Saved', 'The data fields have been saved to this lead.');
		$('#add-complete-modal').modal('hide');
	},{keep_form:true});
});
//-->
</script>