<?php
	/* @var $split Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
	$failover_splits = $this->getContext()->getRequest()->getAttribute("failover_splits", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Edit Split</h4>
</div>
<form class="" id="split_form" method="PUT" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/export/split" />
	<input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
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
				
				<div class="form-group">
					<label class="control-label" for="status">Mark this split as active or inactive</label>
					<select name="status" id="status" placeholder="Set the status of this split">
						<option value="<?php echo \Flux\Split::SPLIT_STATUS_ACTIVE ?>" <?php echo $split->getStatus() == \Flux\Split::SPLIT_STATUS_ACTIVE ? 'selected' : '' ?>>This split is active and will be used</option>
						<option value="<?php echo \Flux\Split::SPLIT_STATUS_INACTIVE ?>" <?php echo $split->getStatus() == \Flux\Split::SPLIT_STATUS_INACTIVE ? 'selected' : '' ?>>This split is inactive and will NOT be used</option>
					</select>
				</div>
				<hr />
				<div class="help-block">Select what type of split this is</div>
				
				<div class="form-group">
					<select name="split_type" id="split_type">
						<option value="<?php echo \Flux\Split::SPLIT_TYPE_NORMAL ?>" <?php echo $split->getSplitType() == \Flux\Split::SPLIT_TYPE_NORMAL ? 'selected' : '' ?>>This is a normal split that will find leads that match the filters</option>
						<option value="<?php echo \Flux\Split::SPLIT_TYPE_CATCH_ALL ?>" <?php echo $split->getSplitType() == \Flux\Split::SPLIT_TYPE_CATCH_ALL ? 'selected' : '' ?>>This is a catch-all split and will only receive leads if no other splits match</option>
						<option value="<?php echo \Flux\Split::SPLIT_TYPE_HOST_POST ?>" <?php echo $split->getSplitType() == \Flux\Split::SPLIT_TYPE_HOST_POST ? 'selected' : '' ?>>This is a host & post split that will immediately fulfill leads through a POST</option>
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
							<option value="<?php echo $offer->getId(); ?>"<?php echo $split->isOfferSelected($offer->getId()) ? 'selected' : '' ?>><?php echo $offer->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				<hr />
				<div class="help-block">Add one or more filters to define which leads will be handled by this split</div>
				<div id="filter_container" style="max-height:500px;overflow:auto;">
					<input type="hidden" name="filters" value="" />
					<?php 
						/* @var $filter \Flux\Link\DataField */
						foreach ($split->getFilters() as $key => $filter) { 
							$selected_data_set = array();
					?>
						<div class="form-group row">
							<div class="col-sm-5">
								<select name="filters[<?php echo $key ?>][data_field_key_name]" class="form-control selectize">
									<optgroup label="Data Fields">
										<?php 
											/* @var $data_field \Flux\DataField */
											foreach ($data_fields AS $data_field) { 
												$data_field_set = $data_field->getDataFieldSet();
												array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
												if ($filter->getDataFieldId() == $data_field->getId()) { $selected_data_set = $data_field_set; }
										?>
											<option value="<?php echo $data_field->getKeyName() ?>" <?php echo $filter->getDataFieldId() == $data_field->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
										<?php } ?>
									</optgroup>
								</select>
							</div>
							<div class="col-sm-2">
								<select name="filters[<?php echo $key ?>][data_field_condition]" class="form-control selectize-cond">
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS ?>" <?php echo $filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS ? 'selected' : '' ?>>is</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT ?>" <?php echo $filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT ? 'selected' : '' ?>>is not</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK ?>" <?php echo $filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK ? 'selected' : '' ?>>is not blank</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET ?>" <?php echo $filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET ? 'selected' : '' ?>>is set</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT ?>" <?php echo $filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT ? 'selected' : '' ?>>is greater than</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT ?>" <?php echo $filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT ? 'selected' : '' ?>>is less than</option>
								</select>
							</div>
							<div class="col-sm-4">
								<select name="filters[<?php echo $key ?>][data_field_value][]" class="form-control selectize-text" placeholder="Select one or more filter values" rows="3" multiple <?php echo ($filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK || $filter->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET) ? 'disabled' : '' ?>>
									<?php
										$item_found = false; 
										foreach ($selected_data_set as $data_set_item) {
											 if (in_array($data_set_item['value'], $filter->getDataFieldValue())) { $item_found = true; }
									?>
										<option value="<?php echo $data_set_item['value'] ?>" <?php echo in_array($data_set_item['value'], $filter->getDataFieldValue()) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode($data_set_item)) ?>"><?php echo $data_set_item['name'] ?></option>
									<?php } ?>
									<?php if (!$item_found) { ?>
										<?php foreach ($filter->getDataFieldValue() as $filter_value) { ?>
											<option value="<?php echo $filter_value ?>" selected data-data="<?php echo htmlentities(json_encode(array('name' => $filter_value, 'value' => $filter_value))) ?>"><?php echo $filter_value ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-1">
								<button type="button" class="btn btn-sm btn-danger btn-remove-dataField"><span class="glyphicon glyphicon-minus"></span></button>
							</div>
							<div class="clearfix"></div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="fulfillment">
				<div class="help-block">When leads match the filter criteria, automatically fulfill them to the following fulfillment</div>
				<div class="form-group">
					<input type="hidden" name="fulfillment[fulfillment_id]" value="" />
					<label class="control-label" for="fulfillment_id">Fulfillment</label>
					<select class="form-control" name="fulfillment[fulfillment_id]" id="fulfillment_id" placeholder="choose a fulfillment to run when the lead matches the criteria">
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
										<option value="<?php echo $fulfillment->getId() ?>" <?php echo $split->getFulfillment()->getFulfillmentId() == $fulfillment->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$fulfillment->getId(), 'name' => $fulfillment->getName()))) ?>"><?php echo $fulfillment->getName() ?></option>
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
				<div class="row">
					<div class="col-md-8"><div class="help-block">You can delay fulfillment to allow people to finish filling out a form</div></div>
					<div class="col-md-4">
						<select name="fulfill_delay" id="fulfill_delay" <?php echo $split->getFulfillImmediately() ? '' : 'DISABLED' ?>>
							<option value="0" <?php echo $split->getFulfillDelay() == 0 ? 'SELECTED' : '' ?>>Do not delay</option>
							<option value="5" <?php echo $split->getFulfillDelay() == 5 ? 'SELECTED' : '' ?>>Delay for 5 minutes</option>
							<option value="10" <?php echo $split->getFulfillDelay() == 10 ? 'SELECTED' : '' ?>>Delay for 10 minutes</option>
							<option value="15" <?php echo $split->getFulfillDelay() == 15 ? 'SELECTED' : '' ?>>Delay for 15 minutes</option>
							<option value="60" <?php echo $split->getFulfillDelay() == 60 ? 'SELECTED' : '' ?>>Delay for 1 hour</option>
						</select>
					</div>
				</div>
				<hr />
				<label class="control-label" for="failover_fulfillment_id">Failover Options</label>
				<div class="row">
					<input type="hidden" id="failover_enable_0" name="failover_enable" value="0" />
					<div class="col-md-7"><div class="help-block">If a lead is not accepted in a timely manner, what should be done</div></div>
					<div class="col-md-5 text-right"><input type="checkbox" class="form-control" id="failover_enable_1" name="failover_enable" value="1" <?php echo $split->getFailoverEnable() ? 'checked' : '' ?> /></div>
				</div>
				<div class="row">
					<div class="col-md-8"><div class="help-block">Choose how long to wait for a conversion before attempting the failover</div></div>
					<div class="col-md-4">
						<select name="failover_wait_time" id="failover_wait_time" <?php echo $split->getFailoverEnable() ? '' : 'DISABLED' ?>>
							<option value="5" <?php echo $split->getFailoverWaitTime() == 5 ? 'SELECTED' : '' ?>>Wait for 5 minutes</option>
							<option value="10" <?php echo $split->getFailoverWaitTime() == 10 ? 'SELECTED' : '' ?>>Wait for 10 minutes</option>
							<option value="15" <?php echo $split->getFailoverWaitTime() == 15 ? 'SELECTED' : '' ?>>Wait for 15 minutes</option>
							<option value="60" <?php echo $split->getFailoverWaitTime() == 60 ? 'SELECTED' : '' ?>>Wait for 1 hour</option>
							<option value="120" <?php echo $split->getFailoverWaitTime() == 120 ? 'SELECTED' : '' ?>>Wait for 2 hours</option>
							<option value="360" <?php echo $split->getFailoverWaitTime() == 360 ? 'SELECTED' : '' ?>>Wait for 6 hours</option>
							<option value="720" <?php echo $split->getFailoverWaitTime() == 720 ? 'SELECTED' : '' ?>>Wait for 12 hours</option>
							<option value="1440" <?php echo $split->getFailoverWaitTime() == 1440 ? 'SELECTED' : '' ?>>Wait for 24 hours</option>
						</select>
					</div>
				</div>
				<div class="row">
					<input type="hidden" id="fulfill_backup_0" name="fulfill_backup" value="0" />
					<div class="col-md-8"><div class="help-block">On a failover, re-queue the lead to this split</div></div>
					<div class="col-md-4">
						<select class="form-control" name="failover_split[split_id]" id="failover_split_id" placeholder="choose a split to run as a failover" <?php echo $split->getFailoverEnable() ? '' : 'DISABLED' ?>>
							<?php
								/* @var $failover_split \Flux\Split */ 
								foreach ($failover_splits as $failover_split) { 
							?>
								<option value="<?php echo $failover_split->getId() ?>" <?php echo $split->getFailoverSplit()->getId() == $failover_split->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$failover_split->getId(), 'name' => $failover_split->getName()))) ?>"><?php echo $failover_split->getName() ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="help-block"><i class="small">This should only be used if you can sync sub ids/revenue from the buyer/network</i></div>
				
				<hr />
				<div class="help-block">Set a schedule for when leads can be fulfilled</div>
				<div class="row">
					<div class="form-group col-md-8">
						<label class="control-label" for="days">Days</label>
						<select id="days" name="scheduling[days][]" class="form-control" multiple placeholder="select one or more days when leads can be accepted">
							<option value="0" <?php echo in_array("0", $split->getScheduling()->getDays()) ? "selected" : "" ?>>Sunday</option>
							<option value="1" <?php echo in_array("1", $split->getScheduling()->getDays()) ? "selected" : "" ?>>Monday</option>
							<option value="2" <?php echo in_array("2", $split->getScheduling()->getDays()) ? "selected" : "" ?>>Tuesday</option>
							<option value="3" <?php echo in_array("3", $split->getScheduling()->getDays()) ? "selected" : "" ?>>Wednesday</option>
							<option value="4" <?php echo in_array("4", $split->getScheduling()->getDays()) ? "selected" : "" ?>>Thursday</option>
							<option value="5" <?php echo in_array("5", $split->getScheduling()->getDays()) ? "selected" : "" ?>>Friday</option>
							<option value="6" <?php echo in_array("6", $split->getScheduling()->getDays()) ? "selected" : "" ?>>Saturday</option>
						</select>
					</div>
					<div class="form-group col-md-2">
						<label class="control-label" for="start_hour">Hours</label>
						<select id="start_hour" name="scheduling[start_hour]" class="form-control" placeholder="enter starting hour">
							<?php for ($i=0;$i<24;$i++) { ?>
								<option value="<?php echo $i ?>" <?php echo $split->getScheduling()->getStartHour() == $i ? "selected" : "" ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>:00</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-2">
						<label class="control-label" for="end_hour">&nbsp;</label>
						<select id="end_hour" name="scheduling[end_hour]" class="form-control" placeholder="enter ending hour">
							<?php for ($i=0;$i<24;$i++) { ?>
								<option value="<?php echo $i ?>" <?php echo $split->getScheduling()->getEndHour() == $i ? "selected" : "" ?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT) ?>:00</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="validators">
				<div class="help-block">Add one or more validation checks that will be verified before the lead is fulfilled</div>
				<div id="validator_container" style="max-height:500px;overflow:auto;">
					<input type="hidden" name="validators" value="" />
					<?php 
						/* @var $validator \Flux\Link\DataField */
						foreach ($split->getValidators() as $key => $validator) { 
							$selected_data_set = array();
					?>
						<div class="form-group row">
							<div class="col-sm-5">
								<select name="validators[<?php echo $key ?>][data_field_key_name]" class="form-control selectize">
									<optgroup label="Data Fields">
										<?php 
											/* @var $data_field \Flux\DataField */
											foreach ($data_fields AS $data_field) { 
												$data_field_set = $data_field->getDataFieldSet();
												array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
												if ($validator->getDataFieldId() == $data_field->getId()) { $selected_data_set = $data_field_set; }
										?>
											<option value="<?php echo $data_field->getKeyName() ?>" <?php echo $validator->getDataFieldId() == $data_field->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'data_field_set' => $data_field_set, 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?> (<?php echo $data_field->getKeyName() ?>, <?php echo implode(", ", $data_field->getRequestName()) ?>)</option>
										<?php } ?>
									</optgroup>
								</select>
							</div>
							<div class="col-sm-2">
								<select name="validators[<?php echo $key ?>][data_field_condition]" class="form-control selectize-cond">
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS ?>" <?php echo $validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS ? 'selected' : '' ?>>is</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT ?>" <?php echo $validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT ? 'selected' : '' ?>>is not</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK ?>" <?php echo $validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK ? 'selected' : '' ?>>is not blank</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET ?>" <?php echo $validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET ? 'selected' : '' ?>>is set</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT ?>" <?php echo $validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_GT ? 'selected' : '' ?>>is greater than</option>
									<option value="<?php echo \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT ?>" <?php echo $validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_LT ? 'selected' : '' ?>>is less than</option>
								</select>
							</div>
							<div class="col-sm-4">
								<select name="validators[<?php echo $key ?>][data_field_value][]" class="form-control selectize-text" placeholder="Select one or more validator values" rows="3" multiple <?php echo ($validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_NOT_BLANK || $validator->getDataFieldCondition() == \Flux\Link\DataField::DATA_FIELD_CONDITION_IS_SET) ? 'disabled' : '' ?>>
									<?php
										$item_found = false; 
										foreach ($selected_data_set as $data_set_item) {
											 if (in_array($data_set_item['value'], $validator->getDataFieldValue())) { $item_found = true; }
									?>
										<option value="<?php echo $data_set_item['value'] ?>" <?php echo in_array($data_set_item['value'], $validator->getDataFieldValue()) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode($data_set_item)) ?>"><?php echo $data_set_item['name'] ?></option>
									<?php } ?>
									<?php if (!$item_found) { ?>
										<?php foreach ($validator->getDataFieldValue() as $validator_value) { ?>
											<option value="<?php echo $validator_value ?>" selected data-data="<?php echo htmlentities(json_encode(array('name' => $validator_value, 'value' => $validator_value))) ?>"><?php echo $validator_value ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-1">
								<button type="button" class="btn btn-sm btn-danger btn-remove-dataField"><span class="glyphicon glyphicon-minus"></span></button>
							</div>
							<div class="clearfix"></div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" style="display:none;" class="btn btn-success btn-add-dataField"><span class="glyphicon glyphicon-plus"></span> Add Filter</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Update Split</button>
	</div>
</form>
<!-- Dummy Filter Div -->
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

<!-- Dummy Filter Div -->
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

	var $selectize_value_options = {
		valueField: 'value',
		labelField: 'name',
		searchField: ['name'],
		sortField: 'name',
		sortDirection: 'ASC',
		diacritics:true,
		create: true,
		createOnBlur: true
	};

	$('#filter_container .selectize').selectize($selectize_options);
	$('#filter_container .selectize-cond').selectize($selectize_cond_options);
	$('#filter_container .selectize-text').selectize($selectize_value_options);

	$('#validator_container .selectize').selectize($selectize_options);
	$('#validator_container .selectize-cond').selectize($selectize_cond_options);
	$('#validator_container .selectize-text').selectize($selectize_value_options);

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
			$dataFieldRow.find('.selectize-text').selectize($selectize_value_options);
			
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
			$dataFieldRow.find('.selectize-text').selectize($selectize_value_options);
			
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
		size: 'small',
		onSwitchChange: function(event, state) {
			if (state) {
				$('#fulfill_delay').removeAttr('disabled');
				$('#fulfill_delay')[0].selectize.enable();
			} else {
				$('#fulfill_delay').attr('disabled', 'disabled');
				$('#fulfill_delay')[0].selectize.disable();
			}
		}
	});

	$('#failover_enable_1').bootstrapSwitch({
		onText: 'Use&nbsp;Failover',
		offText: 'Do&nbsp;Nothing',
		size: 'small',
		onSwitchChange: function(event, state) {
			if (state) {
				$('#failover_split_id').removeAttr('disabled');
				$('#failover_split_id')[0].selectize.enable();
				$('#failover_wait_time').removeAttr('disabled');
				$('#failover_wait_time')[0].selectize.enable();
			} else {
				$('#failover_split_id').attr('disabled', 'disabled');
				$('#failover_split_id')[0].selectize.disable();
				$('#failover_wait_time').attr('disabled', 'disabled');
				$('#failover_wait_time')[0].selectize.disable();
			}
		}
	});
	
	$('#offer_select,#fulfillment_id,#failover_split_id,#failover_wait_time,#days,#start_hour,#end_hour,#split_type,#fulfill_delay,#status').selectize();

	$('#split_form').form(function(data) {
		$.rad.notify('Split Updated', 'The split has been updated in the system');
		$('#edit_split_modal').modal('hide');
	},{keep_form:true});

	<?php if (isset($_REQUEST['tab'])) { ?>
		$('.nav-tabs a[href=#<?php echo $_REQUEST['tab'] ?>]').tab('show');
	<?php } ?>
});
//-->
</script>
