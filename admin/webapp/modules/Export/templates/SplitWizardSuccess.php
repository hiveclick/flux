<?php
	/* @var $split Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Add Split</h4>
</div>
<form class="" id="split_form" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/export/split" />
	<input type="hidden" name="status" value="<?php echo \Flux\Split::SPLIT_STATUS_ACTIVE ?>" />
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li role="presentation" class=""><a href="#filters" role="tab" data-toggle="tab">Filters</a></li>
			<li role="presentation" class=""><a href="#validators" role="tab" data-toggle="tab">Validation</a></li>
			<li role="presentation" class=""><a href="#fulfillment" role="tab" data-toggle="tab">Fulfillment</a></li>
		</ul>
		<!-- Tab panes -->
  		<div class="tab-content">
  			<div role="tabpanel" class="tab-pane fade in active" id="basic">
				<div class="help-block">Splits define rules that determine how and when an export receives data</div>
				<div class="form-group">
					<label class="control-label" for="name">Enter a name and description for this split</label>
					<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $split->getName() ?>" />
				</div>
		
				<div class="form-group">
					<textarea name="description" id="description" class="form-control" placeholder="Enter Description..." rows="4" required><?php echo $split->getDescription() ?></textarea>
				</div>
				
				<hr />
				<div class="help-block">Select what type of split this is</div>
				
				<div class="form-group">
					<select name="split_type" id="split_type">
                        <option value="<?php echo \Flux\Split::SPLIT_TYPE_NORMAL ?>" <?php echo $split->getSplitType() == \Flux\Split::SPLIT_TYPE_NORMAL ? 'selected' : '' ?>>This is a normal split that will find leads that match the filters</option>
                        <option value="<?php echo \Flux\Split::SPLIT_TYPE_CATCH_ALL ?>" <?php echo $split->getSplitType() == \Flux\Split::SPLIT_TYPE_CATCH_ALL ? 'selected' : '' ?>>This is a catch-all split and will only receive leads if no other splits match</option>
                        <option value="<?php echo \Flux\Split::SPLIT_TYPE_HOST_POST ?>" <?php echo $split->getSplitType() == \Flux\Split::SPLIT_TYPE_HOST_POST ? 'selected' : '' ?>>This is a host &amp; post split that can fulfill leads through a POST</option>
					</select>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="filters">
				<div class="help-block">Select which offers should have this split applied to them</div>
				<div class="form-group">
					<input type="hidden" name="offers" value="" />
					<label class="control-label" for="offers">Include leads from these offers:</label>
					<select class="form-control" name="offers[][offer_id]" id="offer_select" multiple placeholder="select one or more offers or leave blank for all offers">
						<?php 
							/* @var $offer \Flux\Offer */
							foreach ($offers AS $offer) { 
						?>
							<option value="<?php echo $offer->getId(); ?>"<?php echo in_array($offer->getId(), $split->getOffers()) ? ' selected="selected"' : ''; ?>><?php echo $offer->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<hr />
				<div class="help-block">Add one or more filters to define which leads will be handled by this split</div>
				<div id="filter_container"></div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="fulfillment">
				<div class="help-block">When leads match the filter criteria, automatically fulfill them to the following fulfillment</div>
				<div class="form-group">
					<input type="hidden" name="fulfillment[fulfillment_id]" value="" />
					<label class="control-label" for="fulfillment_id">Fulfillment</label>
					<select class="form-control" name="fulfillment[fulfillment_id]" id="fulfillment_id" placeholder="choose a fulfillment to run when the lead matches the criteria">
						<option value=""></option>
						<?php
        					/* @var $client \Flux\Client */ 
        					foreach ($clients as $client) { 
        				?>
        					<?php if (count($client->getFulfillments()) > 0) { ?>
        						<optgroup label="<?php echo $client->getName() ?>">
        							<?php 
        								/* @var $client \Flux\Fulfillment */
        								foreach ($client->getFulfillments() AS $fulfillment) { 
        							?>
        								<option value="<?php echo $fulfillment->getId() ?>" <?php echo $split->getFulfillment()->getFulfillmentId() == $fulfillment->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('_id' => $fulfillment->getId(), 'name' => $fulfillment->getName()))) ?>"><?php echo $fulfillment->getName() ?></option>
        							<?php } ?>
        						</optgroup>
        					<?php } ?>
        				<?php } ?>
					</select>
				</div>
				<p />
				<div class="row">
				    <input type="hidden" id="fulfill_immediately_0" name="fulfill_immediately" value="0" />
				    <div class="col-md-8"><div class="help-block">Choose when the fulfillment script will be run on this split</div></div>
				    <div class="col-md-4 text-right"><input type="checkbox" class="form-control" id="fulfill_immediately_1" name="fulfill_immediately" value="1" <?php echo $split->getFulfillImmediately() ? 'checked' : '' ?> /></div>
				</div>
				<hr />
				<div class="help-block">If a lead cannot be fulfilled, you can send an email notification</div>
				<div class="form-group">
					<label class="control-label" for="name">Email Notification</label>
					<input type="text" id="email_notification" name="email_notification" class="form-control" placeholder="enter email address for notifications" value="<?php echo $split->getEmailNotification() ?>" />
				</div>
				<hr />
				<div class="help-block">Set a schedule for when leads can be fulfilled</div>
				<div class="row">
    				<div class="form-group col-md-8">
    					<label class="control-label" for="days">Days</label>
    					<select id="days" name="scheduling[days][]" class="form-control" multiple placeholder="select one or more days when leads can be accepted">
    						<option value="0" <?php echo in_array(0, $split->getScheduling()->getDays()) ? 'SELECTED' : '' ?>>Sunday</option>
    						<option value="1" <?php echo in_array(1, $split->getScheduling()->getDays()) ? 'SELECTED' : '' ?>>Monday</option>
    						<option value="2" <?php echo in_array(2, $split->getScheduling()->getDays()) ? 'SELECTED' : '' ?>>Tuesday</option>
    						<option value="3" <?php echo in_array(3, $split->getScheduling()->getDays()) ? 'SELECTED' : '' ?>>Wednesday</option>
    						<option value="4" <?php echo in_array(4, $split->getScheduling()->getDays()) ? 'SELECTED' : '' ?>>Thursday</option>
    						<option value="5" <?php echo in_array(5, $split->getScheduling()->getDays()) ? 'SELECTED' : '' ?>>Friday</option>
    						<option value="6" <?php echo in_array(6, $split->getScheduling()->getDays()) ? 'SELECTED' : '' ?>>Saturday</option>
    					</select>
    				</div>
    				<div class="form-group col-md-2">
    					<label class="control-label" for="start_hour">Hours</label>
    					<select id="start_hour" name="scheduling[start_hour]" class="form-control" placeholder="enter starting hour">
    						<?php for ($i=0;$i<24;$i++) { ?>
    							<option value="<?php echo $i ?>" <?php echo ($split->getScheduling()->getStartHour() == $i) ? 'SELECTED' : '' ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>:00</option>
    						<?php } ?>
    					</select>
    				</div>
    				<div class="form-group col-md-2">
    					<label class="control-label" for="end_hour">&nbsp;</label>
    					<select id="end_hour" name="scheduling[end_hour]" class="form-control" placeholder="enter ending hour">
    						<?php for ($i=0;$i<24;$i++) { ?>
    							<option value="<?php echo $i ?>" <?php echo ($split->getScheduling()->getEndHour() == $i) ? 'SELECTED' : '' ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>:00</option>
    						<?php } ?>
    					</select>
    				</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="validators">
				<div class="help-block">Add one or more validation checks that will be verified before the lead is fulfilled</div>
				<div id="validator_container"></div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" style="display:none;" class="btn btn-success btn-add-dataField"><span class="glyphicon glyphicon-plus"></span> Add Filter</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Add Split</button>
	</div>
</form>

<!-- Dummy Filter Row -->
<div class="form-group row" style="display:none;" id="dummy_filter_data_field">
	<div class="col-sm-5">
		<select name="filters[dummy-dummy_id][data_field_key_name]" class="form-control selectize">
			<optgroup label="Data Fields">
				<?php 
					/* @var $data_field \Flux\DataField */
					foreach ($data_fields AS $data_field) { 
						$data_field_set = $data_field->getDataFieldSet();
						array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
				?>
					<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
				<?php } ?>
			</optgroup>
		</select>
	</div>
	<div class="col-sm-2">
		<select name="filters[dummy-dummy_id][data_field_condition]" class="form-control selectize-cond">
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS ?>">is</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT ?>">is not</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK ?>">is not blank</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET ?>">is set</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT ?>">is greater than</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT ?>">is less than</option>
		</select>
	</div>
	<div class="col-sm-4">
		<textarea name="filters[dummy-dummy_id][data_field_value][]" class="form-control selectize-text" placeholder="Select one or more filter values" rows="3"></textarea>
	</div>
	<div class="col-sm-1">
		<button type="button" class="btn btn-sm btn-danger btn-remove-dataField"><span class="glyphicon glyphicon-minus"></span></button>
	</div>
	<div class="clearfix"></div>
</div>

<!-- Dummy Validator Row -->
<div class="form-group row" style="display:none;" id="dummy_validator_data_field">
	<div class="col-sm-5">
		<select name="validators[dummy-dummy_id][data_field_key_name]" class="form-control selectize">
			<optgroup label="Data Fields">
				<?php 
					/* @var $data_field \Flux\DataField */
					foreach ($data_fields AS $data_field) { 
						$data_field_set = $data_field->getDataFieldSet();
						array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
				?>
					<option value="<?php echo $data_field->getKeyName() ?>" data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
				<?php } ?>
			</optgroup>
		</select>
	</div>
	<div class="col-sm-2">
		<select name="validators[dummy-dummy_id][data_field_condition]" class="form-control selectize-cond">
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS ?>">is</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT ?>">is not</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK ?>">is not blank</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET ?>">is set</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT ?>">is greater than</option>
			<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT ?>">is less than</option>
		</select>
	</div>
	<div class="col-sm-4">
		<textarea name="validators[dummy-dummy_id][data_field_value][]" class="form-control selectize-text" placeholder="Select one or more validator values" rows="3"></textarea>
	</div>
	<div class="col-sm-1">
		<button type="button" class="btn btn-sm btn-danger btn-remove-dataField"><span class="glyphicon glyphicon-minus"></span></button>
	</div>
	<div class="clearfix"></div>
</div>

<script>
//<!--
$(document).ready(function() {
	// Define our data field options
	var $selectize_options = {
		valueField: 'key_name',
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				var label = item.name || item.key;
	            var caption = item.description ? item.description : null;
	            var keyname = item.key_name ? item.key_name : null;
	            var tags = item.tags ? item.tags : null;
	            var tag_span = '';
				$.each(tags, function(j, tag_item) {
					tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
				});	            
	            return '<div style="width:100%;padding-right:25px;">' +
	                '<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
	                (caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
	                '<div>' + tag_span + '</div>' +   
	            '</div>';
			},
			option: function(item, escape) {
				var label = item.name || item.key;
	            var caption = item.description ? item.description : null;
	            var keyname = item.key_name ? item.key_name : null;
	            var tags = item.tags ? item.tags : null;
	            var tag_span = '';
				$.each(tags, function(j, tag_item) {
					tag_span += '<span class="label label-default">' + escape(tag_item) + '</span> ';
				});	            
	            return '<div style="border-bottom: 1px dotted #C8C8C8;">' +
	                '<b>' + escape(label) + '</b> <span class="pull-right label label-success">' + escape(keyname) + '</span><br />' +
	                (caption ? '<span class="text-muted small">' + escape(caption) + ' </span>' : '') +
	                '<div>' + tag_span + '</div>' +
	            '</div>';
			}
		},
		onChange: function(value) {
			if (!value.length) return;
			var select_text = $(this.$dropdown).closest('.form-group').find('.selectize-text')[0].selectize;
			var data = this.options[value];
			select_text.clearOptions();
			$(data.data_field_set).each(function(i, item) {
				select_text.addOption(item);
			});
			select_text.refreshOptions();
		}
	};

	var $selectize_cond_options = {
		onChange: function(value) {
			// disable the text entry for options 3 and 4 (isset and is not blank)
			if (value == 3 || value == 4) {
				$(this.$dropdown).closest('.form-group').find('.selectize-text')[0].selectize.disable();
			} else {
				$(this.$dropdown).closest('.form-group').find('.selectize-text')[0].selectize.enable();
			}
		}
	};

	// button to remove data fields
	$('.btn-remove-dataField').on('click', function() {
		$(this).closest('.form-group').remove();
	});
	
	// Add new data fields and set them up along with value textboxes
	$('.btn-add-dataField').on('click', function() {
		if ($('#filters').is(':hidden')) {
    		var index_number = $('#validator_container > .form-group').length;
    		var $dataFieldRow = $('#dummy_validator_data_field').clone(true);
    		$dataFieldRow.removeAttr('id');
    		$dataFieldRow.html(function(i, oldHTML) {
    			oldHTML = oldHTML.replace(/dummy_id/g, (index_number + 1));
    			return oldHTML;
    		});
    		
    		$('#validator_container').append($dataFieldRow);
    		$dataFieldRow.find('.btn-remove-dataField').on('click', function() {
    			$(this).closest('.form-group').remove();
    		});
    		$dataFieldRow.find('.selectize').selectize($selectize_options);
    		$dataFieldRow.find('.selectize-cond').selectize($selectize_cond_options);
    		$dataFieldRow.find('.selectize-text').selectize({
    			valueField: 'value',
    			labelField: 'name',
    			searchField: ['name'],
    			sortField: 'name',
    			sortDirection: 'ASC',
    			diacritics:true,
    			create: true,
    			createOnBlur: true
    		});
		
    	    $dataFieldRow.show();
		} else {
			var index_number = $('#filter_container > .form-group').length;
    		var $dataFieldRow = $('#dummy_filter_data_field').clone(true);
    		$dataFieldRow.removeAttr('id');
    		$dataFieldRow.html(function(i, oldHTML) {
    			oldHTML = oldHTML.replace(/dummy_id/g, (index_number + 1));
    			return oldHTML;
    		});
    		
    		$('#filter_container').append($dataFieldRow);
    		$dataFieldRow.find('.btn-remove-dataField').on('click', function() {
    			$(this).closest('.form-group').remove();
    		});
    		$dataFieldRow.find('.selectize').selectize($selectize_options);
    		$dataFieldRow.find('.selectize-cond').selectize($selectize_cond_options);
    		$dataFieldRow.find('.selectize-text').selectize({
    			valueField: 'value',
    			labelField: 'name',
    			searchField: ['name'],
    			sortField: 'name',
    			sortDirection: 'ASC',
    			diacritics:true,
    			create: true,
    			createOnBlur: true
    		});
		
    	    $dataFieldRow.show();
		}
	});

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		if ($(e.target).attr('href') == '#filters') {
			$('.btn-add-dataField').html('<span class="glyphicon glyphicon-plus"></span> Add Filter').show();
		} else if ($(e.target).attr('href') == '#validators') {
		   $('.btn-add-dataField').html('<span class="glyphicon glyphicon-plus"></span> Add Validator').show();
		} else {
			$('.btn-add-dataField').hide();
		}
	});

	$('#fulfill_immediately_1').bootstrapSwitch({
		onText: 'Immediate',
		offText: 'Manual',
		size: 'small'
	});
	
	$('#offer_select,#fulfillment_id,#days,#start_hour,#end_hour,#split_type').selectize();

	$('#split_form').form(function(data) {
		$.rad.notify('Split Added', 'The split has been added into the system');
	},{keep_form:true});
});
//-->
</script>
