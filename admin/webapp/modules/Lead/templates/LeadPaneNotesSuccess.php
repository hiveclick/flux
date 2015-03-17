<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
	$notes = array_reverse($lead->getNotes());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">View Lead Notes</h4>
</div>
<div class="modal-body">
	<div style="max-height:400px;overflow:scroll;">
		<table class="table table-responsive table-striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Note</th>
				</tr>
			</thead>
			<tbody id="note_tbody">
				<?php 
				   $last_date = null;
				   foreach ($notes as $note) { ?>
					<tr>
						<td>
							 <?php if (date('m/d/Y', $note['t']->sec) != $last_date) { 
								 $last_date = date('m/d/Y', $note['t']->sec);
							 ?>
								 <?php echo date('F j, Y', $note['t']->sec) ?>
							 <?php } ?>
						</td>
						<td><?php echo strtoupper(date('g:i:s a T', $note['t']->sec)) ?></td>
						<td style="word-break:break-word;"><?php echo $note['note'] ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_note_modal" href="/lead/lead-pane-add-note?_id=<?php echo $lead->getId() ?>">Add Note</button>
</div>
