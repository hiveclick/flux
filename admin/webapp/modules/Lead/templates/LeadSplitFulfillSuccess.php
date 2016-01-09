<?php
	/* @var $lead_split \Flux\LeadSplit */
	$lead_split = $this->getContext()->getRequest()->getAttribute('lead_split', array());
	$clients = $this->getContext()->getRequest()->getAttribute('clients', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Fulfill Lead</h4>
</div>
<form id="lead_fulfill_manual_form" action="/api" method="POST">
	<input type="hidden" name="func" value="/export/manual-fulfill-custom" />
	<input type="hidden" name="test" value="0" />
	<input type="hidden" name="override_fulfillment" value="0" />
	<input type="hidden" name="lead_split[_id]" value="<?php echo $lead_split->getId() ?>" />
	<div class="modal-body">
		<div class="help-block">You can fulfill this item by choosing a fulfillment below</div>
		<div class="form-group">
			<select id="fulfillment_id" name="fulfillment[_id]" class="form-control selectize">
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
								<option value="<?php echo $fulfillment->getId() ?>" <?php echo $lead_split->getSplit()->getSplit()->getFulfillment()->getId() == $fulfillment->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$fulfillment->getId(), 'name' => $fulfillment->getName(), 'description' => $fulfillment->getDescription(), 'fulfillment_type' => $fulfillment->getExportType(), 'tracking_url' => $fulfillment->getTrackingUrl()))) ?>"><?php echo $fulfillment->getName() ?></option>
							<?php } ?>
						</optgroup>
					<?php } ?>
				<?php } ?>
			</select>
			<div style="padding-left:10px;">
				<label><input type="checkbox" name="test" id="test_1" value="1" /> I only want to test the fulfillment, only show me what would be sent</label>
			</div>
		</div>
		<div style="display:none;" id="fulfillment_log_div">
			<hr />
			<div id="fulfillment_result_debug">
				<div class="help-block">This is what would be sent to the fulfillment if it was not a test</div>
				<div role="tabpanel">

					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#qs" role="tab" data-toggle="tab">Request</a></li>
						<li role="presentation"><a href="#request" role="tab" data-toggle="tab">Raw Request</a></li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="qs">
							<div class="help-block">This is what will be posted to the advertiser at <b id="debug_url"></b></div>
							<div style="height:400px;overflow:auto;">
								<table class="table">
									<thead>
										<th>Parameter</th>
										<th>Value</th>
									</thead>
									<tbody id="debug_qs"></tbody>
								</table>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="request">
							<div class="help-block">This is the raw post url that will be sent to the advertiser</div>
							<textarea id="debug_request" rows="10" class="form-control" readonly></textarea>
						</div>
					</div>
				</div>
			</div>
			<div id="fulfillment_result_error">
				<div class="help-block">The results of your fulfillment request are below</div>
				<div class="alert alert-danger alert-dismissible" role="alert"></div>
				<div id="override_fulfillment_div" class="hidden">
					<label><input type="checkbox" name="override_fulfillment" value="1" /> I know this has already been fulfilled, override it and force to to be sent</label>
				</div>
			</div>
			<div id="fulfillment_result_success">
				<div class="help-block">The results of your fulfillment request are below</div>
				<div class="alert alert-success alert-dismissible" role="alert">
					Fulfillment was sent successfully
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary">Fulfill Lead</button>
	</div>
</form>
<script>
//<!--
$('#fulfillment_id', '#lead_fulfill_manual_form').selectize({
	valueField: '_id',
	labelField: 'name',
	searchField: ['name', 'description'],
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
				'<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
				'</div>';
		}
	}
});

$('#lead_fulfill_manual_form').form(
	function(data) {
		if (data.record) {
			if ($('#test_1').is(':checked')) {
				$('#debug_request', '#lead_fulfill_manual_form').val(data.record.debug.request);
				if (data.record.debug.url != '') {
					$('#debug_url', '#lead_fulfill_manual_form').html(data.record.debug.url);
					$('#debug_qs', '#lead_fulfill_manual_form').html('');
					$.each(data.record.debug.params, function(i, item) {
						var tr = $('<tr />').appendTo($('#debug_qs', '#lead_fulfill_manual_form'));
						$('<td />').html(i).appendTo(tr);
						$('<td />').html(item).appendTo(tr);
					});
				} else {
					var tr = $('<tr />').appendTo($('#debug_qs'));
					$('<td colspan="2" />').html('<pre>' + data.record.debug.request + '</pre>').appendTo(tr);
				}
				$('#fulfillment_result_debug', '#lead_fulfill_manual_form').show();
				$.rad.notify('Test Complete', 'The test was sent and you can see the results above');
			} else {
				if (data.record.is_error) {
					$('#fulfillment_result_error .alert', '#lead_fulfill_manual_form').html(data.record.error_message);
					$('#fulfillment_result_error', '#lead_fulfill_manual_form').show();
					$.rad.notify.error('Lead Submitted', 'The lead had errors while trying to be fulfilled');

					if (data.record.disposition == '<?php echo \Flux\LeadSplit::DISPOSITION_ALREADY_FULFILLED ?>') {
						$('#override_fulfillment_div').removeClass('hidden');
					}
					
					$('#attempts #lead_split_item_<?php echo $lead_split->getId() ?>').trigger('attempt_add', data.record);
					$('#attempts #lead_split_item_<?php echo $lead_split->getId() ?>').trigger('disposition_change', data.record);
				} else {
					$('#fulfillment_result_success', '#lead_fulfill_manual_form').show();
					$.rad.notify('Lead Submitted', 'The lead has been submitted to the export successfully');

					$('#lead_split_fulfill_modal').modal('hide');
					$('#attempts #lead_split_item_<?php echo $lead_split->getId() ?>').trigger('attempt_add', data.record);
					$('#attempts #lead_split_item_<?php echo $lead_split->getId() ?>').trigger('disposition_change', data.record);
				}
			}
		}
	}, {
	prepare: function() {
		$('#fulfillment_result_success', '#lead_fulfill_manual_form').hide();
		$('#fulfillment_result_error', '#lead_fulfill_manual_form').hide();
		$('#fulfillment_result_debug', '#lead_fulfill_manual_form').hide();
		$('#fulfillment_log_div', '#lead_fulfill_manual_form').show();
	}	
});
//-->
</script>