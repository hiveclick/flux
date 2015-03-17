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
		$.rad.notify('Note Saved', 'The note has been added to the lead successfully.');
		$.rad.get('/api', { func: '/lead/lead', _id: '<?php echo $lead->getId() ?>' }, function(data) {
			if (data.record && data.record.notes) {
				$('#note_tbody').html('');
				locale_date = '';
				notes = data.record.notes;
				notes.reverse();
				$.each(notes, function(i, note) {
					note_date = new Date(note.t.sec * 1000);
					tr = $('<tr />');
					if (note_date.toLocaleDateString() != locale_date) {
						locale_date = note_date.toLocaleDateString();
						tr.append('<td>' + note_date.toLocaleDateString() + '</td>');
					} else {
						tr.append('<td>&nbsp;</td>');
					}
					tr.append('<td>' + note_date.toLocaleTimeString() + '</td>');
					tr.append('<td style="word-break:break-word;">' + note.note + '</td>');
					$('#note_tbody').append(tr);
				});
			}
		});		
	},{keep_form:true});
});
//-->
</script>