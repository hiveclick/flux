<?php
	/* @var $flow Flux\Flow */
	$flow = $this->getContext()->getRequest()->getAttribute("flow", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$flows = $this->getContext()->getRequest()->getAttribute("flows", array());
?>
<style type="text/css">
	.depth-0 {
		padding-left:0;
	}
	.depth-1 {
		padding-left:10px;
	}
	.depth-2 {
		padding-left:20px;
	}
	.depth-3 {
		padding-left:30px;
	}
	.depth-4 {
		padding-left:40px;
	}
	.depth-5 {
		padding-left:50px;
	}
	.inactive {
		background-color:#ccc;
		font-style:italic;
	}
	.inactive-child {
		background-color:#ccc;
	}
</style>
<div class="help-block">Define your flow by adding rules, filters, and endpoints (offers) to it</div>
<br/>
<form name="flow_form" method="POST" class="form-horizontal" autocomplete="off">
	<input type="hidden" name="_id" value="<?php echo $flow->retrieveValueHtml('_id'); ?>" />
	<div id="flow_master_container">
	<?php foreach($flow->getFlowNodes() AS $flow_node) { ?>
		<div class="flow-node">
			<div class="form-group">
				<div class="col-xs-4">
					<input type="hidden" name="flow_nodes[<?php echo $flow_node['guid']; ?>][guid]" class="input_flow_nodes_guid" value="<?php echo $flow_node['guid']; ?>" />
					<input type="hidden" name="flow_nodes[<?php echo $flow_node['guid']; ?>][ref]" class="input_flow_nodes_ref" value="<?php echo $flow_node['ref']; ?>" />
					<input type="hidden" name="flow_nodes[<?php echo $flow_node['guid']; ?>][type]" class="input_flow_nodes_type" value="<?php echo $flow_node['type']; ?>" />
					<input type="hidden" name="flow_nodes[<?php echo $flow_node['guid']; ?>][active]" class="input_flow_nodes_active" value="<?php echo $flow_node['active']; ?>" />
					<div class="input-group flow-node-type">
						<div class="input-group-btn">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
								<span class="glyphicon"></span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu node-type-ul" role="menu">
								<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_RULE_FIRST; ?>" data-glyphicon-type="glyphicon glyphicon-align-left">Rule</a></li>
								<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_OFFER; ?>" data-glyphicon-type="glyphicon glyphicon-import">Offer</a></li>
								<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_FLOW; ?>" data-glyphicon-type="glyphicon glyphicon-indent-left">Flow</a></li>
								<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_URL; ?>" data-glyphicon-type="glyphicon glyphicon-share-alt">URL</a></li>
							</ul>
						</div>
						<input type="text" name="flow_nodes[<?php echo $flow_node['guid']; ?>][label]" class="form-control input_flow_nodes_label" placeholder="" value="<?php echo $flow_node['label']; ?>" />
					</div>
				</div>
				<div class="col-xs-4">
					<div class="input-group-btn-bag">
						<select name="flow_nodes[<?php echo $flow_node['guid']; ?>][offer_id]" class="form-control row_offer_type_<?php echo \Flux\Flow::FLOW_NODE_TYPE_OFFER; ?>" style="display:none;">
							<?php foreach($offers AS $offer) { ?>
								<option value="<?php echo $offer->retrieveValueHtml('_id'); ?>"<?php echo $flow_node['offer_id'] == $offer->retrieveValue('_id') ? ' selected' : ''; ?>><?php echo $offer->retrieveValueHtml('name'); ?></option>
							<?php } ?>
						</select>
						<select name="flow_nodes[<?php echo $flow_node['guid']; ?>][flow_id]" class="form-control row_offer_type_<?php echo \Flux\Flow::FLOW_NODE_TYPE_FLOW; ?>" style="display:none;">
							<?php foreach($flows AS $flow_item) { ?>
								<option value="<?php echo $flow_item->retrieveValueHtml('_id'); ?>"<?php echo $flow_node['flow_id'] == $flow_item->retrieveValue('_id') ? ' selected' : ''; ?>><?php echo $flow_item->retrieveValueHtml('name'); ?></option>
							<?php } ?>
						</select>
						<div class="input-group row_offer_type_<?php echo \Flux\Flow::FLOW_NODE_TYPE_URL; ?>" style="display:none;">
							<input name="flow_nodes[][url]" class="form-control" placeholder="URL" value="<?php echo $flow_node['url']; ?>" />
							<div class="input-group-btn">
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataFieldModal">
									<span class="glyphicon glyphicon-info-sign"></span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-4">
					<button type="button" class="btn btn-info btn-options" data-toggle="button">Options</button>
					<button type="button" class="btn btn-info btn-filters" data-toggle="button">Filters</button>
					<button type="button" class="btn btn-info btn-setters" data-toggle="button">Setters</button>
					<div class="btn-group btn-group-actions">
						<button type="button" class="btn btn-primary dropdown-toggle btn-actions" data-toggle="dropdown">
							Action <span class="caret"></span>
						</button>
						<ul class="dropdown-menu action-type-ul" role="menu">
							<li><a href="#" data-action-type="1">Add sibling before</a></li>
							<li><a href="#" data-action-type="2">Add sibling after</a></li>
							<li class="divider"></li>
							<li><a href="#" data-action-type="4">Inactivate</a></li>
							<li class="divider"></li>
							<li><a href="#" data-action-type="3">Remove</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="panel-container">
				<div class="panel panel-info collapse collapse-filters">
					<div class="panel-heading">
						<h3 class="panel-title">Filters</h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-xs-12">
								<button type="button" class="btn btn-primary btn-add-filter">Add Filter</button>
							</div>
						</div>
						<?php
							if(is_array($flow_node['filters'])) {
								foreach($flow_node['filters'] AS $flow_node_filter_id => $flow_node_filter) {
						?>
						<div class="form-group form-group-filter">
							<div class="col-xs-3">
								<select name="flow_nodes[<?php echo $flow_node['guid']; ?>][filters][<?php echo $flow_node_filter_id; ?>][dataField]" class="form-control input_flow_nodes_filters_dataField">
								<?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataField_id => $dataField) { ?>
									<option value="<?php echo $dataField_id; ?>" data-type="<?php echo $dataField->retrieveValueHtml('type'); ?>" data-set="<?php echo htmlspecialchars(json_encode($dataField->retrieveValue('set'))); ?>"<?php echo $flow_node_filter['dataField'] == $dataField_id ? ' selected' : ''; ?>><?php echo $dataField->retrieveValueHtml('name'); ?></option>
								<?php } ?>
								</select>
							</div>
							<div class="col-xs-2">
								<select name="flow_nodes[<?php echo $flow_node['guid']; ?>][filters][<?php echo $flow_node_filter_id; ?>][operator]" class="form-control input_flow_nodes_filters_operator">
								<?php foreach(\Flux\Flow::retrieveFilterOperators() AS $flow_operator_id => $flow_operator) { ?>
									<option value="<?php echo $flow_operator_id; ?>" data-type="<?php echo $flow_operator['type']; ?>"<?php echo $flow_node_filter['operator'] == $flow_operator_id ? ' selected' : ''; ?>><?php echo $flow_operator['name']; ?></option>
								<?php } ?>
								</select>
							</div>
							<div class="col-xs-5">
								<div class="filter_value_container">
									<select name="flow_nodes[][filters][][value][]" class="form-control selectize" multiple>
									<?php foreach($flow_node_filter['value'] AS $flow_node_filter_value) { ?>
										<option value="<?php echo htmlspecialchars($flow_node_filter_value); ?>" selected><?php echo htmlspecialchars($flow_node_filter_value); ?></option>
									<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-xs-2">
								<button type="button" class="btn btn-danger btn-remove-filter">Remove</button>
							</div>
						</div>
						<?php
								}
							}
						?>
					</div>
				</div>
				<div class="panel panel-info collapse collapse-options">
					<div class="panel-heading">
						<h3 class="panel-title">Options</h3>
					</div>
					<div class="panel-body">
						<div class="form-group form-group-exclusive">
							<div class="col-xs-12">
								<label class="btn btn-default btn-exclusive">
									<input type="hidden" name="flow_nodes[<?php echo $flow_node['guid']; ?>][exclusive]" value="<?php echo $flow_node['exclusive']; ?>" /><span class="glyphicon glyphicon-forward"></span> <span class="btn-text">Not Exclusive</span>
								</label>
							</div>
						</div>
						<div class="form-group form-group-cap">
							<div class="col-xs-4">
								<div class="input-group input-group-cap">
									<input type="number" min="0" name="flow_nodes[<?php echo $flow_node['guid']; ?>][cap]" class="form-control cap-toggle" value="<?php echo $flow_node['cap']; ?>" placeholder="Amount" />
									<span class="input-group-addon">per</span>
								</div>
								<span class="label label-success label-cap-on">Cap On</span>
								<span class="label label-success label-cap-count"><?php if(strlen($flow_node['cap']) > 0) { ?>
								<?php echo $flow_node['cap_count']; ?> / <?php echo $flow_node['cap']; ?>
								<?php } ?></span>
							</div>
							<div class="col-xs-4" style="vertical-align:bottom;">
								<input type="number" min="0" name="flow_nodes[<?php echo $flow_node['guid']; ?>][cap_time_amount]" class="form-control" value="<?php echo $flow_node['cap_time_amount']; ?>" placeholder="" />
							</div>
							<div class="col-xs-4">
								<select name="flow_nodes[<?php echo $flow_node['guid']; ?>][cap_time]" class="form-control">
									<?php foreach(\Flux\Flow::retrieveCapTimes() AS $cap_time_id => $cap_time) { ?>
										<option value="<?php echo $cap_time_id; ?>"<?php echo $flow_node['cap_time'] == $cap_time_id ? ' selected' : ''; ?>><?php echo $cap_time['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group form-group-weight">
							<div class="col-xs-12">
								<input type="number" min="0" name="flow_nodes[<?php echo $flow_node['guid']; ?>][weight]" class="form-control weight-toggle" value="<?php echo $flow_node['weight']; ?>" placeholder="Weight" />
								<span class="label label-success label-weight-on">
								Weighted
								</span>
								<span class="label label-success label-weight-count" <?php echo (strlen($flow_node['weight']) > 0) ? '' : ' style="display:none;"'; ?>>
								<?php echo isset($flow_node['weight_count']) ? '0' : $flow_node['weight_count']; ?>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-info collapse collapse-setters">
					<div class="panel-heading">
						<h3 class="panel-title">Setters</h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-xs-12">
								<button type="button" class="btn btn-primary btn-add-setter">Add Setter</button>
							</div>
						</div>
						<?php
							if(is_array($flow_node['setters'])) {
								foreach($flow_node['setters'] AS $flow_node_setter_id => $flow_node_setter) {
						?>
						<div class="form-group form-group-setter">
							<div class="col-xs-4">
								<input type="text" name="flow_nodes[][setters][][dataField]" class="form-control" value="<?php echo $flow_node_setter['dataField']; ?>" />
							</div>
							<div class="col-xs-2">
								<select name="flow_nodes[][setters][][operator]" class="form-control">
								<?php foreach(\Flux\Flow::retrieveSetterOperators() AS $flow_operator_id => $flow_operator) { ?>
									<option value="<?php echo $flow_operator_id; ?>"<?php echo $flow_node_setter['operator'] == $flow_operator_id ? ' selected' : ''; ?>><?php echo $flow_operator['name']; ?></option>
								<?php } ?>
								</select>
							</div>
							<div class="col-xs-4">
								<input type="text" name="flow_nodes[][setters][][value]" class="form-control" value="<?php echo $flow_node_setter['value']; ?>" />
							</div>
							<div class="col-xs-2">
								<button type="button" class="btn btn-danger btn-remove-setter">Remove</button>
							</div>
						</div>
						<?php
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
	<div class="form-group">
		<div class="col-xs-12">
			<input type="submit" name="__saveFlowNodes" class="btn btn-success" value="Save Flow Offers" />
		</div>
	</div>
</form>

<!-- These are modal dialogs -->
<div class="modal fade" id="dataFieldModal" tabindex="-1" role="dialog" aria-labelledby="dataFieldModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="dataFieldModalLabel">Data Field Placeholders</h4>
			</div>
			<div class="modal-body">
<pre>
<?php foreach(\Flux\DataField::getUrlPlaceholders() AS $url_placeholder) { ?>
#<?php echo $url_placeholder['request_name']; ?>#
<?php } ?>
</pre>
			</div>
		</div>
	</div>
</div>

<div style="display:none;" id="dummy_filter">
	<div class="form-group form-group-filter">
		<div class="col-xs-3">
			<select name="flow_nodes[][filters][][dataField]" class="form-control input_flow_nodes_filters_dataField">
			<?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataField_id => $dataField) { ?>
				<option value="<?php echo $dataField_id; ?>" data-type="<?php echo $dataField->retrieveValueHtml('type'); ?>" data-set="<?php echo htmlspecialchars(json_encode($dataField->retrieveValue('set'))); ?>"><?php echo $dataField->retrieveValueHtml('name'); ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="col-xs-2">
			<select name="flow_nodes[][filters][][operator]" class="form-control input_flow_nodes_filters_operator">
			<?php foreach(\Flux\Flow::retrieveFilterOperators() AS $flow_operator_id => $flow_operator) { ?>
				<option value="<?php echo $flow_operator_id; ?>" data-type="<?php echo $flow_operator['type']; ?>"><?php echo $flow_operator['name']; ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="col-xs-5">
			<div class="filter_value_container"></div>
		</div>
		<div class="col-xs-2">
			<button type="button" class="btn btn-danger btn-remove-filter">Remove</button>
		</div>
	</div>
</div>
<div style="display:none;" id="dummy_setter">
	<div class="form-group form-group-setter">
		<div class="col-xs-4">
			<input type="text" name="flow_nodes[][setters][][dataField]" class="form-control" />
		</div>
		<div class="col-xs-2">
			<select name="flow_nodes[][setters][][operator]" class="form-control">
			<?php foreach(\Flux\Flow::retrieveSetterOperators() AS $flow_operator_id => $flow_operator) { ?>
				<option value="<?php echo $flow_operator_id; ?>"><?php echo $flow_operator['name']; ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="col-xs-4">
			<input type="text" name="flow_nodes[][setters][][value]" class="form-control" />
		</div>
		<div class="col-xs-2">
			<button type="button" class="btn btn-danger btn-remove-setter">Remove</button>
		</div>
	</div>
</div>
<div style="display:none;" id="dummy_form_group">
	<div class="flow-node">
		<div class="form-group">
			<div class="col-xs-4">
				<input type="hidden" name="flow_nodes[][guid]" class="input_flow_nodes_guid" value="" />
				<input type="hidden" name="flow_nodes[][ref]" class="input_flow_nodes_ref" value="" />
				<input type="hidden" name="flow_nodes[][type]" class="input_flow_nodes_type" value="<?php echo \Flux\Flow::FLOW_NODE_TYPE_RULE_FIRST; ?>" />
				<input type="hidden" name="flow_nodes[][active]" class="input_flow_nodes_active" value="1" />
				<div class="input-group flow-node-type">
					<div class="input-group-btn">
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							<span class="glyphicon"></span> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu node-type-ul" role="menu">
							<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_RULE_FIRST; ?>" data-glyphicon-type="glyphicon glyphicon-align-left">Rule</a></li>
							<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_OFFER; ?>" data-glyphicon-type="glyphicon glyphicon-import">Offer</a></li>
							<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_FLOW; ?>" data-glyphicon-type="glyphicon glyphicon-indent-left">Flow</a></li>
							<li><a href="#" data-node-type="<?php echo \Flux\Flow::FLOW_NODE_TYPE_URL; ?>" data-glyphicon-type="glyphicon glyphicon-share-alt">URL</a></li>
						</ul>
					</div>
					<input type="text" name="flow_nodes[][label]" class="form-control input_flow_nodes_label" placeholder="" value="" />
				</div>
			</div>
			<div class="col-xs-4">
				<div class="input-group-btn-bag">
					<select name="flow_nodes[][offer_id]" class="form-control row_offer_type_<?php echo \Flux\Flow::FLOW_NODE_TYPE_OFFER; ?>" style="display:none;">
						<?php foreach($offers AS $offer) { ?>
							<option value="<?php echo $offer->retrieveValueHtml('_id'); ?>"><?php echo $offer->retrieveValueHtml('name'); ?></option>
						<?php } ?>
					</select>
					<select name="flow_nodes[][flow_id]" class="form-control row_offer_type_<?php echo \Flux\Flow::FLOW_NODE_TYPE_FLOW; ?>" style="display:none;">
						<?php foreach($flows AS $flow_item) { ?>
							<option value="<?php echo $flow_item->retrieveValueHtml('_id'); ?>"><?php echo $flow_item->retrieveValueHtml('name'); ?></option>
						<?php } ?>
					</select>
					<div class="input-group row_offer_type_<?php echo \Flux\Flow::FLOW_NODE_TYPE_URL; ?>" style="display:none;">
						<input name="flow_nodes[][url]" class="form-control" placeholder="URL" />
						<div class="input-group-btn">
							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataFieldModal">
								<span class="glyphicon glyphicon-info-sign"></span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-4">
				<button type="button" class="btn btn-info btn-options" data-toggle="button">Options</button>
				<button type="button" class="btn btn-info btn-filters" data-toggle="button">Filters</button>
				<button type="button" class="btn btn-info btn-setters" data-toggle="button">Setters</button>
				<div class="btn-group btn-group-actions">
					<button type="button" class="btn btn-primary dropdown-toggle btn-actions" data-toggle="dropdown">
						Action <span class="caret"></span>
					</button>
					<ul class="dropdown-menu action-type-ul" role="menu">
						<li><a href="#" data-action-type="1">Add sibling before</a></li>
						<li><a href="#" data-action-type="2">Add sibling after</a></li>
						<li class="divider"></li>
						<li><a href="#" data-action-type="4">Inactivate</a></li>
						<li class="divider"></li>
						<li><a href="#" data-action-type="3">Remove</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="panel-container">
			<div class="panel panel-info collapse collapse-filters">
				<div class="panel-heading">
					<h3 class="panel-title">Filters</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="col-xs-12">
							<button type="button" class="btn btn-primary btn-add-filter">Add Filter</button>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-info collapse collapse-options">
				<div class="panel-heading">
					<h3 class="panel-title">Options</h3>
				</div>
				<div class="panel-body">
					<div class="form-group form-group-exclusive">
						<div class="col-xs-12">
							<label class="btn btn-default btn-exclusive">
								<input type="hidden" name="flow_nodes[][exclusive]" value="0" /><span class="glyphicon glyphicon-forward"></span> <span class="btn-text">Not Exclusive</span>
							</label>
						</div>
					</div>
					<div class="form-group form-group-cap">
						<div class="col-xs-4">
							<div class="input-group input-group-cap">
								<input type="number" min="0" name="flow_nodes[][cap]" class="form-control cap-toggle" value="" placeholder="Amount" />
								<span class="input-group-addon">per</span>
							</div>
							<span class="label label-success label-cap-on">Cap On</span>
							<span class="label label-success label-cap-count"></span>
						</div>
						<div class="col-xs-4" style="vertical-align:bottom;">
							<input type="number" min="0" name="flow_nodes[][cap_time_amount]" class="form-control" value="" placeholder="" />
						</div>
						<div class="col-xs-4">
							<select name="flow_nodes[][cap_time]" class="form-control">
								<?php foreach(\Flux\Flow::retrieveCapTimes() AS $cap_time_id => $cap_time) { ?>
									<option value="<?php echo $cap_time_id; ?>"><?php echo $cap_time['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group form-group-weight">
						<div class="col-xs-12">
							<input type="number" min="0" name="flow_nodes[][weight]" class="form-control weight-toggle" value="" placeholder="Weight" />
							<span class="label label-success label-weight-on">
							Weighted
							</span>
							<span class="label label-success label-weight-count" style="display:none;">
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-info collapse collapse-setters">
				<div class="panel-heading">
					<h3 class="panel-title">Setters</h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="col-xs-12">
							<button type="button" class="btn btn-primary btn-add-setter">Add Setter</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function generateGUID() {
	var guid = 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
		return v.toString(16);
	});
	return guid;
}
$(function() {
	var localTabStorageName = <?php echo json_encode('flow_tab_' . $flow->retrieveValue('_id')); ?>;
	$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
		sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
	});
	var lastTab = sessionStorage.getItem(localTabStorageName);
	if (lastTab) {
		$('a[href='+lastTab+']').tab('show');
	} else {
		$('ul.nav-pills a:first').tab('show');
	}

	var flow_type_names = <?php echo json_encode(\Flux\Flow::retrieveFlowTypeNames()); ?>;

	$('#flow_master_container').on('redraw_table', function (e) {
		e.preventDefault();
		var $flow_master_container = $(this);

		var $flow_nodes = $flow_master_container.find('.flow-node');
		if($flow_nodes.size() <= 0) {
			$('#flow_master_container').trigger('add_default_node');
			//somehow the last node got deleted, which should be prevented, so add it
		}

		//get the flow nodes again, just in case we just added one in the above code
		$flow_nodes = $flow_master_container.find('.flow-node');
		$flow_nodes.each(function(idx, flow_node) {
			var $flow_node = $(flow_node);
			/*
			if(idx == 0) {
				$flow_node.find('.btn-actions').addClass('disabled');
				$flow_node.find('.btn-group-actions').hide();
				$flow_node.find('.form-group-weight').hide();
			}
			*/
			$flow_node.trigger('redraw_node');
		});
		updateRefValues();
	});

	$('#flow_master_container').on('add_default_node', function(e) {
		e.preventDefault();
		var guid = generateGUID();

		var $new_node = $('#dummy_form_group div:first').clone(true);

		var node_ref = '0';

		$new_node.find('.input_flow_nodes_ref').val(node_ref);
		$new_node.find('.input_flow_nodes_guid').val(guid);
		$new_node.find('.input_flow_nodes_type').val('<?php echo json_encode(\Flux\Flow::FLOW_NODE_TYPE_OFFER); ?>');
		$(this).append($new_node);
		$('#flow_master_container').trigger('redraw_table');
	});

	$('#flow_master_container').on('add_node', '.flow-node', function(e, action_type, node_type) {
		e.preventDefault();
		var guid = generateGUID();

		var $new_node = $('#dummy_form_group div:first').clone(true);

		$new_node.find('.input_flow_nodes_type').val(node_type);

		var $flow_node = $(this);
		var ref_string = $flow_node.find('.input_flow_nodes_ref').val();
		var ref_array = ref_string.split('-');

		if(action_type == 'before') {
			ref_array.pop();
			ref_array.push('X');
			var new_ref_name = ref_array.join('-');
			$new_node.find('.input_flow_nodes_ref').val(new_ref_name);
			$new_node.find('.input_flow_nodes_guid').val(guid);
			$flow_node.before($new_node);
		} else if(action_type == 'after') {
			ref_array.pop();
			ref_array.push('X');
			var new_ref_name = ref_array.join('-');
			$new_node.find('.input_flow_nodes_ref').val(new_ref_name);
			$new_node.find('.input_flow_nodes_guid').val(guid);
			var $flow_nodes_and_children = findSelfAndChildren($flow_node);
			var $after_node = $flow_nodes_and_children.last();
			$after_node.after($new_node);
		} else {
			//is child
			ref_array.push('X');
			var new_ref_name = ref_array.join('-');
			$new_node.find('.input_flow_nodes_ref').val(new_ref_name);
			$new_node.find('.input_flow_nodes_guid').val(guid);
			$flow_node.after($new_node);
		}
		$('#flow_master_container').trigger('redraw_table');
	});

	$('#flow_master_container').on('remove_node', '.flow-node', function(e) {
		$flow_node = $(this);
		var $flow_nodes_to_remove = findSelfAndChildren($flow_node);
		$flow_nodes_to_remove.remove();
		$('#flow_master_container').trigger('redraw_table');
	});

	$('#flow_master_container').on('activate_node', '.flow-node', function(e) {
		$flow_node = $(this);
		var $active_input = $flow_node.find('.input_flow_nodes_active');
		var active_input_val = $active_input.val();
		if(active_input_val == '1') {
			$active_input.val('0');
		} else {
			$active_input.val('1');
		}
		active_input_val = $active_input.val();
		$('#flow_master_container').trigger('redraw_table');
	});

	function findParent($flow_node) {
		var ref_string = $flow_node.find('.input_flow_nodes_ref').val();
		var ref_array = ref_string.split('-');
		var ref_parent_array = ref_array.slice(0, -1);
		var ref_parent_string = ref_parent_array.join('-');

		var $parent_node = $flow_node.prevAll('.flow-node').has('.input_flow_nodes_ref[value=' + ref_parent_string + ']').first();
		return $parent_node;
	}

	function findAllParents($flow_node) {
		var $parent_nodes = $();

		var ref_string = $flow_node.find('.input_flow_nodes_ref').val();
		var ref_array = ref_string.split('-');
		while(ref_array.length > 1) {
			var ref_parent_string = ref_array.join('-');
			var $parent_flow_node = $('#flow_master_container .flow-node').has('.input_flow_nodes_ref[value=' + ref_parent_string + ']')
			$parent_nodes = $parent_nodes.add(findParent($parent_flow_node));
			ref_array = ref_array.slice(0, -1);
		}

		return $parent_nodes;
	}

	function findSelfAndChildren($flow_node) {
		var ref_string = $flow_node.find('.input_flow_nodes_ref').val();
		var $flow_nodes = $flow_node.nextAll('.flow-node').has('.input_flow_nodes_ref[value^=' + ref_string + '-]');
		$flow_nodes = $flow_nodes.add($flow_node);
		return $flow_nodes;
	}

	function updateRefValues() {
		var ref_array_master = [];
		var ref_array_largest = [];
		$('#flow_master_container').find('.flow-node').each(function(idx, flow_node) {
			var $flow_node = $(flow_node);
			var ref_string = $flow_node.find('.input_flow_nodes_ref').val();
			var ref_array = ref_string.split('-');
			var ref_parent_array = ref_array.slice(0, -1);
			var depth = ref_parent_array.length;
			if(typeof ref_array_master[depth] == 'undefined') {
				ref_array_master[depth] = 0;
			} else {
				ref_array_master[depth] += 1;
			}
			ref_array_master = ref_array_master.slice(0, depth + 1);
			var ref_parent_string = ref_array_master.join('-');
			$flow_node.find('.input_flow_nodes_ref').val(ref_parent_string);
		});
	}

	$('#flow_master_container').on('redraw_node', '.flow-node', function(e) {
		//make sure the right information is showing based on the node type (rule, weighted, offer, form)
		e.preventDefault();
		var $flow_node = $(this);

		var node_type = $flow_node.find('.input_flow_nodes_type').val();

		var $flow_node_type = $flow_node.find('.flow-node-type');
		var glyphicon_class = $flow_node_type.find('[data-node-type=' + node_type + ']').data('glyphicon-type');
		$flow_node_type.find('.glyphicon').removeClass(function (index, css) {
			return (css.match (/glyphicon-[\S]+/g) || []).join(' ');
		});
		$flow_node_type.find('.glyphicon').addClass(glyphicon_class);

		var ref_string = $flow_node.find('.input_flow_nodes_ref').val();
		var depth = ref_string.split('-').length - 1;
		$flow_node_type.removeClass(function (index, css) {
			return (css.match (/depth-[0-9]+/g) || []).join(' ');
		});
		$flow_node_type.addClass('depth-' + depth);

		$flow_node.find('.panel-container').removeClass(function (index, css) {
			return (css.match (/depth-[0-9]+/g) || []).join(' ');
		});
		$flow_node.find('.panel-container').addClass('depth-' + depth);

		var $flow_node_selectboxes = $flow_node.find('.input-group-btn-bag');
		$flow_node_selectboxes.find('[class*=row_offer_type_]').hide();
		$flow_node_selectboxes.find('.row_offer_type_' + node_type).show();
		$flow_node_type.find('.input_flow_nodes_label').attr('placeholder', flow_type_names[node_type] + ' Label');

		//check for inactive stuff
		var node_active = $flow_node.find('.input_flow_nodes_active').val();
		if(node_active == '0') {
			$flow_node.addClass('inactive');
			$flow_node.find('.action-type-ul li a[data-action-type=4]').html('Activate');
		} else {
			$flow_node.removeClass('inactive');
			$flow_node.find('.action-type-ul li a[data-action-type=4]').html('Inactivate');
			//as long as any of my parents aren't inactive, remove the class
			var $parent_nodes = findAllParents($flow_node);
			var is_parent_inactive = false;
			$parent_nodes.each(function(parent_idx, parent_flow_node) {
				var $parent_flow_node = $(parent_flow_node);
				var parent_node_active = $parent_flow_node.find('.input_flow_nodes_active').val();
				if(parent_node_active == '0') {
					is_parent_inactive = true;
				}
			});
			if(is_parent_inactive == false) {
				$flow_node.removeClass('inactive-child');
			} else {
				$flow_node.addClass('inactive-child');
			}
		}


		//check for rules for rule/child
		var rule_node_types = ['<?php echo json_encode(\Flux\Flow::FLOW_NODE_TYPE_RULE_FIRST); ?>'];
		if($.inArray(node_type, rule_node_types) >= 0) {
			//ensure I have at least one child, if not then create me
			var $children_flow_nodes = $('#flow_master_container').find('.flow-node').has('.input_flow_nodes_ref[value^=' + ref_string + '-]');

			if($children_flow_nodes.length <= 0) {
				$flow_node.trigger('add_node', ['child', <?php echo json_encode(\Flux\Flow::FLOW_NODE_TYPE_OFFER); ?>]);
			}

		} else {
			//i'm a child, so remove all my children
			var $children_flow_nodes = $('#flow_master_container').find('.flow-node').has('.input_flow_nodes_ref[value^=' + ref_string + '-]');
			$children_flow_nodes.each(function(child_idx, child_flow_node) {
				$child_flow_node = $(child_flow_node);
				$child_flow_node.remove();
			});
		}

		//redraw cap
		$flow_node.find('.cap-toggle').trigger('change');

		//redraw weight
		$flow_node.find('.weight-toggle').trigger('change');

		//redraw filters, options and setters
		$flow_node.find('.collapse-filters').trigger('redraw_filters');
		$flow_node.find('.collapse-options').trigger('redraw_options');
		$flow_node.find('.collapse-setters').trigger('redraw_setters');

		//update all guids
		var guid = $flow_node.find('.input_flow_nodes_guid').val();
		$flow_node.find(':input[name^=flow_nodes]').each(function(idx, input_element) {
			this.name = this.name.replace(/^flow_nodes\[[a-zA-Z0-9]*\]/, function(str, p1) {
				return 'flow_nodes[' + guid + ']';
			});
		});
	});

	$('#flow_master_container').on('click', '.node-type-ul li a', function(e){
		e.preventDefault();
		var $this = $(this);
		var node_type = $this.data('node-type');
		var $flow_node = $this.closest('.flow-node');
		$flow_node.find('.input_flow_nodes_type').val(node_type);
		$flow_node.trigger('redraw_node');
	});

	$('#flow_master_container').on('click', '.action-type-ul li a', function(e){
		e.preventDefault();
		var $this =  $(this);
		var action_type = $this.data('action-type');
		var $flow_node = $this.closest('.flow-node');
		var node_type = $flow_node.find('.input_flow_nodes_type').val();
		if(action_type == 1) {
			$flow_node.trigger('add_node', ['before', node_type]);
		} else if(action_type == 2) {
			$flow_node.trigger('add_node', ['after', node_type]);
		} else if(action_type == 3) {
			$flow_node.trigger('remove_node');
		} else if(action_type == 4) {
			$flow_node.trigger('activate_node');
		}
	});

	$('#flow_master_container').on('click', '.btn-filters', function(e) {
		e.preventDefault();
		var $btn_filters = $(this);
		var $flow_node = $btn_filters.closest('.flow-node');
		var $filter_div = $flow_node.find('.collapse-filters');
		if($filter_div.is(':visible')) {
			$filter_div.slideUp(400);
		} else {
			$filter_div.slideDown(400);
		}
	});

	$('#flow_master_container').on('click', '.btn-add-filter', function(e) {
		e.preventDefault();
		var $dummy_filter = $('#dummy_filter div:first').clone(true);
		var $panel_body = $(this).closest('.panel-body');
		$panel_body.append($dummy_filter);
		var $flow_node = $panel_body.closest('.flow-node');
		$flow_node.trigger('redraw_node');
	});

	$('#flow_master_container').on('click', '.btn-remove-filter', function(e) {
		e.preventDefault();
		var $this = $(this);
		var $form_group = $this.closest('.form-group');
		var $flow_node = $form_group.closest('.flow-node');
		$form_group.remove();
		$flow_node.trigger('redraw_node');
	});

	$('#flow_master_container').on('change keyup', '.cap-toggle', function(e) {
		e.preventDefault();
		var $this = $(this);
		var $btn_label = $this.closest('.form-group-cap').find('.label-cap-on');
		if($this.val().length > 0) {
			$btn_label.text('Cap On').removeClass('label-danger').addClass('label-success');
		} else {
			$btn_label.text('Cap Off').removeClass('label-success').addClass('label-danger');
		}
		var $collapse_options = $this.closest('.collapse-options');
		$collapse_options.trigger('redraw_options');
	});

	$('#flow_master_container').on('change keyup', '.weight-toggle', function(e) {
		e.preventDefault();
		var $this = $(this);
		var $btn_label = $this.closest('.form-group-weight').find('.label-weight-on');
		if($this.val().length > 0) {
			$btn_label.text('Weight On').removeClass('label-danger').addClass('label-success');
		} else {
			$btn_label.text('Weight Off').removeClass('label-success').addClass('label-danger');
		}
		var $collapse_options = $this.closest('.collapse-options');
		$collapse_options.trigger('redraw_options');
	});

	$('#flow_master_container').on('redraw_filters', '.collapse-filters', function(e) {
		var $collapse_filters = $(this);
		var number_of_filters = 0;
		$collapse_filters.find('.form-group-filter').each(function(idx, flow_group_filter) {
			number_of_filters++;
			var $flow_group_filter = $(flow_group_filter);
			$flow_group_filter.trigger('redraw_filter');
			$flow_group_filter.find(':input[name^=flow_nodes]').each(function(idx2, input_element) {
				//e.g. flow_nodes[0-0][filters][][dataField]
				this.name = this.name.replace(/\[filters\]\[[0-9]*\]/, function(str, p1) {
					return '[filters][' + idx + ']';
				});
			});
		});
		if(number_of_filters > 0) {
			$collapse_filters.closest('.flow-node').find('.btn-filters').removeClass('btn-info').addClass('btn-success');
		} else {
			$collapse_filters.closest('.flow-node').find('.btn-filters').removeClass('btn-success').addClass('btn-info');
		}
	});

	$('#flow_master_container').on('redraw_filter', '.form-group-filter', function(e) {
		e.preventDefault();
		var $form_group_filter = $(this);
		var $flow_node = $form_group_filter.closest('.flow-node');
		var ref_string = $flow_node.find('.input_flow_nodes_ref').val();
		var $select_tag = $form_group_filter.find('.input_flow_nodes_filters_dataField');
		var dataField_type = $select_tag.find(':selected').data('type');
		var operator_type = $form_group_filter.find('.input_flow_nodes_filters_operator :selected').data('type');

		var $filter_value_container = $form_group_filter.find('.filter_value_container');
		//remove anything from previous setting
		//but not before saving the values from the select
		var previous_value = $filter_value_container.find(':input').val();
		//use == null instead of === null below because we want a loose comparison intentionally
		if(
			(previous_value == null)
			|| (previous_value.length <= 0)
		) {
			previous_value = [];
		} else {
			previous_value = previous_value instanceof Array ? previous_value : [previous_value];
		}
		$filter_value_container.empty();

		if(dataField_type == 'set') {
			//get the set json
			var set_json = $select_tag.find(':selected').data('set');

			if(operator_type == '0') {
				//nothing
			} else if(operator_type == '2') {
				var $selectbox = $('<select name="flow_nodes[][filters][][value][]" class="form-control" multiple></select>');
				$filter_value_container.append($selectbox);
				$selectbox.selectize({
					valueField: 'value',
					labelField: 'text',
					searchField: ['text', 'value'],
					options : set_json,
					maxItems: 2
				});
				var selectize_control = $selectbox[0].selectize;
				for (index = 0; index < previous_value.length; ++index) {
					selectize_control.addItem(previous_value[index]);
				}
			} else if(operator_type == 'm') {
				var $selectbox = $('<select name="flow_nodes[][filters][][value][]" class="form-control" multiple></select>');
				$filter_value_container.append($selectbox);
				$selectbox.selectize({
					valueField: 'value',
					labelField: 'text',
					searchField: ['text', 'value'],
					options : set_json
				});
				var selectize_control = $selectbox[0].selectize;
				for (index = 0; index < previous_value.length; ++index) {
					selectize_control.addItem(previous_value[index]);
				}
			} else {
				var $selectbox = $('<select name="flow_nodes[][filters][][value][]" class="form-control"></select>');
				$filter_value_container.append($selectbox);
				$selectbox.selectize({
					valueField: 'value',
					labelField: 'text',
					searchField: ['text', 'value'],
					options : set_json
				});
				var selectize_control = $selectbox[0].selectize;
				for (index = 0; index < previous_value.length; ++index) {
					selectize_control.addItem(previous_value[index]);
				}
				<?php /*
				$selectbox.selectize({
					options: [
						{key: 'brian@thirdroute.com', value: 'Brian Reavis'},
						{email: 'nikola@tesla.com', name: 'Nikola Tesla'},
						{email: 'someone@gmail.com'}
					],
					render: {
						item: function(item, escape) {
							return '<div>' +
								(item.name ? '<span class="name">' + escape(item.name) + '</span>' : '') +
								(item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
							'</div>';
						},
						option: function(item, escape) {
							var label = item.name || item.email;
							var caption = item.name ? item.email : null;
							return '<div>' +
								'<span class="label">' + escape(label) + '</span>' +
								(caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
							'</div>';
						}
					},
				});
				*/ ?>
			}
		} else {
			if(operator_type == '0') {
				//nothing
			} else if(operator_type == '2') {
				var $selectbox = $('<select name="flow_nodes[][filters][][value][]" class="form-control" multiple></select>');
				$selectbox.val(previous_value);
				$filter_value_container.append($selectbox);
				$selectbox.selectize({
					delimiter: ',',
					persist: false,
					create: function(input) {
						return {
							value: input,
							text: input
						}
					},
					maxItems: 2
				});
				var selectize_control = $selectbox[0].selectize;
				for (index = 0; index < previous_value.length; ++index) {
					selectize_control.addOption({'value' : previous_value[index], 'text' : previous_value[index]});
					selectize_control.addItem(previous_value[index]);
				}
			} else if(operator_type == 'm') {
				var $selectbox = $('<select name="flow_nodes[][filters][][value][]" class="form-control" multiple></select>');
				$selectbox.val(previous_value);
				$filter_value_container.append($selectbox);
				$selectbox.selectize({
					delimiter: ',',
					persist: false,
					create: function(input) {
						return {
							value: input,
							text: input
						}
					}
				});
				var selectize_control = $selectbox[0].selectize;
				for (index = 0; index < previous_value.length; ++index) {
					selectize_control.addOption({'value' : previous_value[index], 'text' : previous_value[index]});
					selectize_control.addItem(previous_value[index]);
				}
			} else {
				var $inputbox = $('<input type="text" name="flow_nodes[][filters][][value][]" class="form-control" value="" />');
				//just depend on the toString of array to set the value here
				$inputbox.val(previous_value);
				$filter_value_container.append($inputbox);
				/*
				var $selectbox = $('<select name="flow_nodes[][filters][][value]" class="form-control"></select>');
				$filter_value_container.append($selectbox);
				$selectbox.selectize({
					delimiter: ',',
					persist: false,
					create: function(input) {
						return {
							value: input,
							text: input
						}
					}
				});
				*/
			}
		}

		//$('#flow_master_container').trigger('redraw_table');
	});

	$('#flow_master_container').on('change', '.input_flow_nodes_filters_dataField', function(e) {
		e.preventDefault();
		var $select_tag = $(this);
		var $flow_node = $select_tag.closest('.flow-node');
		$flow_node.trigger('redraw_node');
	});

	$('#flow_master_container').on('change', '.input_flow_nodes_filters_operator', function(e) {
		e.preventDefault();
		var $select_tag = $(this);
		var $flow_node = $select_tag.closest('.flow-node');
		$flow_node.trigger('redraw_node');
	});

	$('#flow_master_container').on('click', '.btn-options', function(e) {
		e.preventDefault();
		var $btn_filters = $(this);
		var $flow_node = $btn_filters.closest('.flow-node');
		var $filter_div = $flow_node.find('.collapse-options');
		if($filter_div.is(':visible')) {
			$filter_div.slideUp(400);
		} else {
			$filter_div.slideDown(400);
		}
	});

	$('#flow_master_container').on('click', '.btn-exclusive', function(e) {
		e.preventDefault();
		if($(this).find('input').val() == '1') {
			$(this).find('input').val('0');
		} else {
			$(this).find('input').val('1');
		}
		$(this).trigger('update_btn');
	});

	$('#flow_master_container').on('update_btn', '.btn-exclusive', function(e) {
		e.preventDefault();
		if($(this).find('input').val() == '1') {
			$(this).find('span.glyphicon').removeClass('glyphicon-forward').addClass('glyphicon-step-forward');
			$(this).find('span.btn-text').html('Exclusive');
			$(this).addClass('btn-success').removeClass('btn-default');
		} else {
			$(this).find('span.glyphicon').removeClass('glyphicon-step-forward').addClass('glyphicon-forward');
			$(this).find('span.btn-text').html('Not Exclusive');
			$(this).addClass('btn-default').removeClass('btn-success');
		}
	});

	$('#flow_master_container').on('redraw_options', '.collapse-options', function(e) {
		e.preventDefault();
		var $collapse_options = $(this);
		var $flow_node = $collapse_options.closest('.flow-node');
		var guid = $flow_node.find('.input_flow_nodes_guid').val();

		var options_on = false;

		if($flow_node.find('.cap-toggle').val().length > 0) {
			options_on = true;
		}

		if($flow_node.find('.weight-toggle').val().length > 0) {
			options_on = true;
		}

		$flow_node.find('.btn-exclusive').trigger('update_btn');

		if(options_on === true) {
			$flow_node.find('.btn-options').removeClass('btn-info').addClass('btn-success');
		} else {
			$flow_node.find('.btn-options').removeClass('btn-success').addClass('btn-info');
		}
	});

	$('#flow_master_container').on('click', '.btn-setters', function(e) {
		e.preventDefault();
		var $btn_setters = $(this);
		var $flow_node = $btn_setters.closest('.flow-node');
		var $setter_div = $flow_node.find('.collapse-setters');
		if($setter_div.is(':visible')) {
			$setter_div.slideUp(400);
		} else {
			$setter_div.slideDown(400);
		}
	});

	$('#flow_master_container').on('click', '.btn-add-setter', function(e) {
		e.preventDefault();
		var $dummy_setter = $('#dummy_setter div:first').clone(true);
		var $panel_body = $(this).closest('.panel-body');
		$panel_body.append($dummy_setter);
		var $flow_node = $panel_body.closest('.flow-node');
		$flow_node.trigger('redraw_node');
	});

	$('#flow_master_container').on('click', '.btn-remove-setter', function(e) {
		e.preventDefault();
		var $this = $(this);
		var $form_group = $this.closest('.form-group');
		var $flow_node = $form_group.closest('.flow-node');
		$form_group.remove();
		$flow_node.trigger('redraw_node');
	});

	$('#flow_master_container').on('redraw_setters', '.collapse-setters', function(e) {
		var $collapse_setters = $(this);
		var $flow_node = $collapse_setters.closest('.flow-node');
		var guid = $flow_node.find('.input_flow_nodes_guid').val();
		var number_of_setters = 0;
		$collapse_setters.find('.form-group-setter').each(function(idx, flow_group_setter) {
			number_of_setters++;
			var $flow_group_setter = $(flow_group_setter);
			$flow_group_setter.find(':input[name^=flow_nodes]').each(function(idx2, input_element) {
				//e.g. flow_nodes[0-0][setters][][dataField]
				this.name = this.name.replace(/\[setters\]\[[0-9]*\]/, function(str, p1) {
					return '[setters][' + idx + ']';
				});
			});
		});

		if(number_of_setters > 0) {
			$flow_node.find('.btn-setters').removeClass('btn-info').addClass('btn-success');
		} else {
			$flow_node.find('.btn-setters').removeClass('btn-success').addClass('btn-info');
		}
	});


	$('#flow_master_container').trigger('redraw_table');

});
</script>