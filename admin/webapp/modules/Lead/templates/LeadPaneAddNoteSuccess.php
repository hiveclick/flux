<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Add Note to Lead</h4>
</div>
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
<script>
//<!--
$(document).ready(function() {
	// submit the form
	$('#lead_note_form').form(function(data) {
		$('#notes').trigger('add', data.record);
	},{keep_form:true});
});
//-->
</script>