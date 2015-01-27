<?php
	/* @var $split Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo ($split->getId() > 0) ? 'Edit' : 'Add' ?> Split</h4>
</div>
<form class="" id="split_form_<?php echo $split->getId() ?>" method="<?php echo ($split->getId() > 0) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/export/split" />
	<input type="hidden" name="status" value="<?php echo \Flux\Split::SPLIT_STATUS_ACTIVE ?>" />
	<?php if ($split->getId() > 0) { ?>
		<input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div class="help-block">Splits define rules that determine how and when an export receives data</div>
		<div class="form-group">
			<label class="control-label" for="name">Name</label>
			<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $split->getName() ?>" />
		</div>

		<div class="form-group">
			<label class="control-label" for="name">Description</label>
			<textarea name="description" id="description" class="form-control" placeholder="Enter Description..." required><?php echo $split->getDescription() ?></textarea>
		</div>

		<hr />
		<div class="help-block">Select filters below to define how this split will run</div>

		<div class="form-group">
			<input type="hidden" name="offer_id" value="" />
			<label class="control-label" for="offer_id">Include leads from these offers:</label>
			<select class="form-control" name="offer_id[]" id="offer_id" multiple placeholder="all offers">
				<?php foreach($offers AS $offer) { ?>
					<option value="<?php echo $offer->getId(); ?>"<?php echo in_array($offer->getId(), $split->getOfferId()) ? ' selected="selected"' : ''; ?>><?php echo $offer->getName() ?></option>
				<?php } ?>
			</select>
		</div>
		
		<div class="form-group">
			<input type="hidden" name="data_field_id" value="" />
			<label class="control-label" for="data_field_id[]">Include leads with these fields:</label>
			<select class="form-control" name="data_field_id[]" id="data_field_id" multiple placeholder="no fields required">
				<?php foreach($data_fields AS $data_field) { ?>
					<option value="<?php echo $data_field->getId(); ?>"<?php echo in_array($data_field->getId(), $split->getDataFieldId()) ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => $data_field->getId(), 'name' => $data_field->getName(), 'keyname' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'request_names' => implode(", ", array_merge(array($data_field->getKeyName()), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
				<?php } ?>
			</select>
		</div>

		<div class="form-group">
			<input type="hidden" name="domain_group_id" value="" />
			<label class="control-label" for="domain_group_id">Include leads with emails in these domain groups:</label>
			<select class="form-control" name="domain_group_id[]" id="domain_group_id" multiple placeholder="all domain groups">
				<?php foreach($domain_groups AS $domain_group) { ?>
					<option value="<?php echo $domain_group->getId(); ?>"<?php echo in_array($domain_group->getId(), $split->getDomainGroupId()) ? ' selected="selected"' : ''; ?>><?php echo $domain_group->getName() ?></option>
				<?php } ?>
			</select>
		</div>

	</div>
	<div class="modal-footer">
		<?php if ($split->getId() > 0) { ?>
			<input type="button" class="btn btn-danger" value="Delete Split" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	var $selectize_options = {
		valueField: '_id',
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				return '<div>' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
				'</div>';
			},
			option: function(item, escape) {
				return '<div>' +
					'<span class="title">' + 
					'<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
					'</span>' +
					'<span class="description">' + '<span class="pull-right badge alert-info">' + escape(item.request_names ? item.request_names : '') + '</span>' + escape(item.description ? item.description : 'no description') + '</span>' +
				'</div>';
			}
		}
	};
	
	$('#vertical_id').selectize();
	$('#domain_group_id').selectize();
	$('#offer_id').selectize();
	$('#data_field_id').selectize($selectize_options);

	$('#split_form_<?php echo $split->getId() ?>').form(function(data) {
		$.rad.notify('Split Updated', 'The split has been updated/added into the system');
	},{keep_form:true});
});
//-->
</script>
