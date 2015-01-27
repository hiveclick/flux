<?php
	/* @var $client_export_map \Flux\ClientExportMap */
	$client_export_map = $this->getContext()->getRequest()->getAttribute("client_export_map", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Mapping Options</h4>
</div>
<form id="client_export_map_modal_form" method="POST" action="">
	<input type="hidden" id="client_export_map_modal_column_id" name="column_id" value="<?php echo $client_export_map->getColumnId() ?>" />
	<input type="hidden" id="client_export_map_modal_datafield_id" name="data_field_id" value="<?php echo $client_export_map->getDataFieldId() ?>" />
	<input type="hidden" id="client_export_map_modal_default_value" name="default_value" value="<?php echo $client_export_map->getDefaultValue() ?>" />
	<div class="modal-body">
		<div class="help-block">Define a custom function that you can use to convert this field to a value that the API accepts</div>
		<p />
		<div class="help-text">
			<span class="text-success">
			/**<br />
			&nbsp;* Custom mapping function<br />
			&nbsp;* $value - Value from mapping<br />
			&nbsp;* $lead - \Flux\Lead object<br />
			&nbsp;*/<br />
			</span>
			<strong>
			$mapping_func = function ($value, $lead) {
			</strong>
		</div>
		<div class="col-sm-offset-1">
			<textarea id="client_export_map_modal_mapping_func" name="mapping_func" rows="12" class="form-control" placeholder="return $value;"><?php echo $client_export_map->getMappingFunc() ?></textarea>
		</div>
		<div class="help-text"><strong>}</strong></div>
	</div>
	<div class="modal-footer">
		<button type="button" id="btn_validate" class="btn btn-info">Validate</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	/* Validate the mapping function */
	$('#btn_validate').click(function() {
		var params = $('#client_export_map_modal_form').serialize();
		params += "&func=/client/client-export-map-validate";
		console.log(params);
		$.rad.post('/api', params, function(data) {
			$.rad.notify('Function validated', 'Validation was successful for this function');
		});
	});
	
	
	/* Handle a form submit by converting it to a text representative and hidden input fields on the main page */	
	$('#client_export_map_modal_form').on('submit', function(event) {
		var position = $('#client_export_map_modal_column_id').val();
		$('#mapping_func-' + position).val($('#client_export_map_modal_mapping_func').val());
		$('.map_options-' + position).attr('href', '/client/fulfillment-pane-map-options-modal?' + $(this).serialize());
		if (window.btoa($('#client_export_map_modal_mapping_func').val()) == '<?php echo base64_encode(\Flux\ClientExportMap::getDefaultMappingFunc()) ?>' || $('#client_export_map_modal_mapping_func').val() == '') {
			$('.map_options-' + position).removeClass('btn-warning').addClass('btn-info').text('Options');
			$('.map_alert-' + position).hide();
		} else {
 			$('.map_options-' + position).removeClass('btn-info').addClass('btn-warning').text('Options*');
  			$('.map_alert-' + position).fadeIn();
		}
		// Hide the modal
		$('#map_options_modal').modal('hide');
		event.preventDefault();
	});
});
//-->
</script>