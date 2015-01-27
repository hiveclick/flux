<?php
	/* @var $split_position \Flux\SplitPosition */
	$split_position = $this->getContext()->getRequest()->getAttribute("split_position", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	$domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<?php if ($split_position->getId() > 0) { ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Update Feed in Position <?php echo $split_position->getPosition() ?></h4>
	</div>
	<form id="split_position_form_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>" method="PUT" action="/api">
		<input type="hidden" name="func" value="/export/split-position" />
		<input type="hidden" name="_id" value="<?php echo $split_position->getId() ?>" />
		<input type="hidden" name="split_id" value="<?php echo $split_position->getSplitId() ?>" />
		<div class="modal-body">
			<div class="help-block col-md-12">
				Use this form to update an existing feed in Position <?php echo $split_position->getPosition() ?>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="client_export_id">Feed</label>
				<div class="col-md-10">
					<select class="form-control" name="client_export_id" id="client_export_id_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>" required>
						<?php foreach ($clients AS $client) { ?>
							<optgroup label="<?php echo $client->getName() ?>">
							<?php foreach ($client->getClientExports() as $client_export) { ?>
								<option value="<?php echo $client_export->getId(); ?>" <?php echo ($split_position->getClientExportId() == $client_export->getId()) ? "selected='selected'" : "" ?>><?php echo $client_export->getName() ?> (<?php echo $client->getName() ?>)</option>
							<?php } ?>
							</optgroup>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="position">Position</label>
				<div class="col-md-10">
					<select class="form-control" name="position" id="position_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>" required>
						<?php for ($i=1;$i<5;$i++) { ?>
							<option value="<?php echo $i ?>" <?php echo ($split_position->getPosition() == $i) ? "selected='selected'" : "" ?>><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="client_export_id">Cap</label>
				<div class="col-md-4">
					<div class="input-group">
						<input type="text" name="cap" value="<?php echo $split_position->getCap() ?>" size="5" class="form-control" placeholder="0" />
						<span class="input-group-addon">/day</span>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="client_export_id">Revenue</label>
				<div class="col-md-3">
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" name="revenue" value="<?php echo number_format($split_position->getRevenue(), 2) ?>" size="5" class="form-control" placeholder="0.00" />
					</div>
				</div>
			</div>

			<div class="clearfix" />
		</div>
		<div class="modal-footer">
			<button type="button" id="btn_delete_<?php echo $split_position->getId() ?>" class="btn btn-danger">Delete Feed</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Update Feed</button>
		</div>
	</form>


	<script>
	//<!--
	$(document).ready(function() {
		$('#btn_delete_<?php echo $split_position->getId() ?>').click(function() {
			if (confirm('Are you sure you want to remove this feed from the split?')) {
				$.rad.del('/api', { func: '/export/split-position/<?php echo $split_position->getId() ?>' }, function(data) {
					$.rad.notify('Feed Removed', 'This feed has been removed from the split.');
				});
			}
		});

		$('#split_position_form_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').form(function(data) {
			if (data.record) {
				$.rad.notify('Feed Updated', 'The feed has been updated on this split for position #<?php echo $split_position->getPosition() ?>');
				$('#split_position_modal').modal('hide');
			}
		});

		$('#position_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
		$('#client_export_id_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
		$('#domain_group_id_modal_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
		$('#data_field_id_modal_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
	});
	//-->
	</script>
<?php } else { ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Add Feed to Split</h4>
	</div>
	<form id="split_position_form_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>" method="POST" action="/api">
		<input type="hidden" name="func" value="/export/split-position" />
		<input type="hidden" name="split_id" value="<?php echo $split_position->getSplitId() ?>" />
		<div class="modal-body">
			<div class="help-block col-md-12">
				Use this form to add a new feed to the split
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="client_export_id">Feed</label>
				<div class="col-md-10">
					<select class="form-control" name="client_export_id" id="client_export_id_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>" required>
						<?php foreach ($clients AS $client) { ?>
							<optgroup label="<?php echo $client->getName() ?>">
							<?php foreach ($client->getClientExports() as $client_export) { ?>
								<option value="<?php echo $client_export->getId(); ?>" <?php echo ($split_position->getClientExportId() == $client_export->getId()) ? "selected='selected'" : "" ?>><?php echo $client_export->getName() ?> (<?php echo $client->getName() ?>)</option>
							<?php } ?>
							</optgroup>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="position">Position</label>
				<div class="col-md-10">
					<select class="form-control" name="position" id="position_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>" required>
						<?php for ($i=1;$i<5;$i++) { ?>
							<option value="<?php echo $i ?>" <?php echo ($split_position->getPosition() == $i) ? "selected='selected'" : "" ?>><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="cap">Cap</label>
				<div class="col-md-4">
					<div class="input-group">
						<input type="text" name="cap" value="<?php echo $split_position->getCap() ?>" size="5" class="form-control" placeholder="0" />
						<span class="input-group-addon">/day</span>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-2 control-label" for="revenue">Revenue</label>
				<div class="col-md-3">
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" name="revenue" value="<?php echo number_format($split_position->getRevenue(), 2) ?>" size="5" class="form-control" placeholder="0.00" />
					</div>
				</div>
			</div>

			<div class="clearfix" />
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Add Feed</button>
		</div>
	</form>


	<script>
	//<!--
	$(document).ready(function() {
		$('#split_position_form_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').form(function(data) {
			if (data.record) {
				$.rad.notify('Feed Added', 'The feed has been added to this split');
				$('#split_position_modal').modal('hide');
			}
		});

		$('#position_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
		$('#client_export_id_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
		$('#domain_group_id_modal_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
		$('#data_field_id_modal_<?php echo $split_position->getId() ?>_<?php echo $split_position->getPosition() ?>').selectize();
	});
	//-->
	</script>
<?php } ?>