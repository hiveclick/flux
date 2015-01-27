<?php
	$export = $this->getContext()->getRequest()->getAttribute("export", array());
?>
<div class="help-block">Map data fields to columns according to the specification from the client</div>
<br/>
<div class="form-group map-group-item" style="display:none;" id="dummy_map_div">
	<div class="col-sm-2">
		<label class="col-md-2 control-label" for="mapping[dummy_datafield_id][datafield_id]">Column&nbsp;#dummy_column_id</label>
	</div>
	<div class="col-sm-4">
		<select name="mapDummyReqName[dummy_datafield_id][datafield_id]" class="form-control">
			<?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $datafield) { ?>
			<option value="<?php echo $datafield->retrieveValueHtml('_id'); ?>"><?php echo $datafield->retrieveValueHtml('name'); ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-sm-3">
		<input type="text" name="mapDummyReqName[dummy_datafield_id][default]" class="form-control" value="" placeholder="default value" />
	</div>
	<div class="col-sm-2">
		<button class="btn btn-danger remove_map_btn" type="button">Remove</button>
	</div>
</div>
<form class="form-horizontal" id="export_map_form" name="export_map_form" method="PUT" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/export/export-map" />
	<input type="hidden" name="_id" value="<?php echo $export->getId() ?>" />
	<div id="map_groups">
		<?php
			if (is_array($export->getMapping())) {
				$counter = 0;
				foreach($export->getMapping() AS $export_map) {
		?>
		<div class="form-group map-group-item">
			<div class="col-sm-2">
				<label class="col-md-2 control-label" for="mapping[<?php echo $counter;?>][datafield_id]">Column&nbsp;#<?php echo $counter + 1 ?></label>
			</div>
			<div class="col-sm-4">
				<select name="mapping[<?php echo $counter;?>][datafield_id]" class="form-control">
					<?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $datafield) { ?>
					<option value="<?php echo $datafield->retrieveValueHtml('_id'); ?>"<?php echo $datafield->retrieveValue('_id') == $export_map['datafield_id'] ? ' selected' : ''; ?>><?php echo $datafield->retrieveValueHtml('name'); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-3">
				<input type="text" name="mapping[<?php echo $counter;?>][default]" class="form-control" value="<?php echo htmlspecialchars(isset($export_map['default']) ? $export_map['default'] : ""); ?>" placeholder="default value" />
			</div>
			<div class="col-sm-2">
				<button class="btn btn-danger remove_map_btn" type="button">Remove</button>
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
			<button type="button" class="btn btn-info" id="add_map_btn">Add Field</button>
			<input type="submit" name="__saveMapping" class="btn btn-success" value="Save Mapping" />
		</div>
	</div>

</form>
<script>
//<!--
$(document).ready(function() {
	$('#export_map_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Mapping updated', 'The mapping has been saved to the export');
		}
	},{keep_form:true});

	$('#add_map_btn').on('click', function() {
		var index_number = $('#map_groups > .map-group-item').length;
		var map_div = $('#dummy_map_div').clone();
		$('#map_groups').append(map_div);
		map_div.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/mapDummyReqName/g, 'mapping');
			oldHTML = oldHTML.replace(/dummy_datafield_id/g, index_number);
			oldHTML = oldHTML.replace(/dummy_column_id/g, (index_number + 1));
			return oldHTML;
		});
		map_div.show();
	});

	$('#map_groups').on('click', '.remove_map_btn', function() {
		$(this).closest('.form-group').remove();
	});
});
//-->
</script>