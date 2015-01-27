<?php
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
?>
<div class="help-block">Manage what happens when an event is fired on this offer</div>
<br/>
<div class="form-group event-group-item" style="display:none;" id="dummy_event_div">
	<div class="col-sm-4">
		<select name="eventsDummyReqName[dummy_event_id][event_id]" class="form-control selectize">
			<?php foreach(\Flux\DataField::retrieveActiveEvents() AS $event) { ?>
			<option value="<?php echo $event->retrieveValueHtml('_id'); ?>">When the <?php echo $event->retrieveValueHtml('name'); ?> event is fired</option>
			<?php } ?>
		</select>
	</div>
	<div class="col-sm-2">
		<select name="eventsDummyReqName[dummy_event_id][modifier_id]" class="form-control selectize">
			<?php foreach(\Flux\DataField::retrieveModifiers() AS $modifier_id => $modifier_name) { ?>
			<option value="<?php echo $modifier_id; ?>"><?php echo $modifier_name; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-sm-2">
		<select name="eventsDummyReqName[dummy_event_id][field]" class="form-control selectize">
			<option value="payout">Payout</option>
			<option value="payout">Revenue</option>
		</select>
	</div>
	<div class="col-sm-2">
		<input type="text" name="eventsDummyReqName[dummy_event_id][value]" class="form-control" value="" placeholder="value" />
	</div>
	<div class="col-sm-1">
		<button class="btn btn-danger remove_event_btn" type="button">Remove</button>
	</div>
</div>
<form class="form-horizontal" id="offer_events_form" name="offer_events_form" method="PUT" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/offer/offer-event" />
	<input type="hidden" name="_id" value="<?php echo $offer->getId() ?>" />
	<div id="event_groups">
		<?php
			if(is_array($offer->getEvents())) {
				$counter = 0;
				foreach($offer->getEvents() AS $offer_event) {
		?>
		<div class="form-group event-group-item">
			<div class="col-sm-4">
				<select name="events[<?php echo $counter;?>][event_id]" class="form-control selectize">
					<?php foreach(\Flux\DataField::retrieveActiveEvents() AS $event) { ?>
					<option value="<?php echo $event->retrieveValueHtml('_id'); ?>"<?php echo $event->retrieveValue('_id') == $offer_event['event_id'] ? ' selected' : ''; ?>>When the <?php echo $event->retrieveValueHtml('name'); ?> event is fired</option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-2">
				<select name="events[<?php echo $counter;?>][modifier_id]" class="form-control selectize">
					<?php foreach(\Flux\DataField::retrieveModifiers() AS $modifier_id => $modifier_name) { ?>
					<option value="<?php echo $modifier_id; ?>"<?php echo $modifier_id == $offer_event['modifier_id'] ? ' selected' : ''; ?>><?php echo $modifier_name; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-2">
				<select name="events[<?php echo $counter;?>][field]" class="form-control selectize">
					<option value="payout" <?php echo ($offer_event['field'] == 'payout') ? "selected='selected'" : "" ?>>Payout</option>
					<option value="revenue" <?php echo ($offer_event['field'] == 'revenue') ? "selected='selected'" : "" ?>>Revenue</option>
				</select>
			</div>
			<div class="col-sm-2">
				<input type="text" name="events[<?php echo $counter;?>][value]" class="form-control" value="<?php echo htmlspecialchars($offer_event['value']); ?>" placeholder="value" />
			</div>
			<div class="col-sm-1">
				<button class="btn btn-danger remove_event_btn" type="button">Remove</button>
			</div>
		</div>
		<?php
				$counter++;
				}
			}
		?>

	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="button" class="btn btn-info" id="add_event_btn">Add Event</button>
			<input type="submit" name="__saveEvents" class="btn btn-success" value="Save Events" />
		</div>
	</div>

</form>
<script>
//<!--
$(document).ready(function() {
	$('#offer_events_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Events updated', 'The events have been saved to the offer');
		}
	},{keep_form:true});

	$('#event_groups .selectize').selectize();

	$('#add_event_btn').on('click', function() {
		var index_number = $('#event_groups > .event-group-item').length;
		var event_div = $('#dummy_event_div').clone();
		$('#event_groups').append(event_div);
		event_div.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/eventsDummyReqName/g, 'events');
			oldHTML = oldHTML.replace(/dummy_event_id/g, index_number);
			return oldHTML;
		});
		event_div.find('.selectize').selectize();
		event_div.show();
	});

	$('#event_groups').on('click', '.remove_event_btn', function() {
		$(this).closest('.form-group').remove();
	});
});
//-->
</script>