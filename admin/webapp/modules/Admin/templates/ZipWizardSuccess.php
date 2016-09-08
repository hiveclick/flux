<?php
	/* @var $zip Flux\Zip */
	$zip = $this->getContext()->getRequest()->getAttribute("zip", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($zip->getId()) ? 'Edit' : 'Add' ?> Zipcode</h4>
</div>
<form class="" id="zip_form_<?php echo $zip->getId() ?>" method="<?php echo \MongoId::isValid($zip->getId()) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/zip" />
	<?php if (\MongoId::isValid($zip->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $zip->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div role="tabpanel">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#basic" aria-controls="home" role="tab" data-toggle="tab">Basic</a></li>
				<li role="presentation"><a href="#advanced" aria-controls="profile" role="tab" data-toggle="tab">Advanced</a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="basic">
					<div class="help-block">Create a new zip that you can use to organize offers</div>
					<div class="form-group">
						<label class="control-label" for="name">Zipcode</label>
						<input type="text" id="zipcode" name="zipcode" class="form-control" placeholder="Enter zipcode..." value="<?php echo $zip->getZipcode() ?>" />
					</div>
					<hr />
					<div class="form-group">
						<label class="control-label" for="city">City</label>
						<input type="text" id="city" name="city" class="form-control" placeholder="Enter city..." value="<?php echo $zip->getCity() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="state">State</label>
						<input type="text" id="state" name="state" class="form-control" placeholder="Enter state..." value="<?php echo $zip->getState() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="state_abbreviation">State Abbreviation</label>
						<input type="text" id="state_abbreviation" name="state_abbreviation" class="form-control" placeholder="Enter state abbreviation..." value="<?php echo $zip->getStateAbbreviation() ?>" />
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade in" id="advanced">
					<div class="help-block">These are advanced demographics associated with this zipcode</div>
					<div class="form-group">
						<label class="control-label" for="county">County</label>
						<input type="text" id="county" name="county" class="form-control" placeholder="Enter county..." value="<?php echo $zip->getCounty() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="county_abbreviation">County Abbreviation</label>
						<input type="text" id="county_abbreviation" name="county_abbreviation" class="form-control" placeholder="Enter county abbreviation..." value="<?php echo $zip->getCountyAbbreviation() ?>" />
					</div>
					<hr />
					<div class="form-group">
						<label class="control-label" for="community">Community</label>
						<input type="text" id="community" name="community" class="form-control" placeholder="Enter community..." value="<?php echo $zip->getCommunity() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="community_abbreviation">Community Abbreviation</label>
						<input type="text" id="community_abbreviation" name="community_abbreviation" class="form-control" placeholder="Enter community abbreviation..." value="<?php echo $zip->getCommunityAbbreviation() ?>" />
					</div>
					<hr />
					<div class="form-group">
						<label class="control-label" for="latitude">Latitude</label>
						<input type="text" id="latitude" name="latitude" class="form-control" placeholder="Enter latitude..." value="<?php echo $zip->getLatitude() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="longitude">Longitude</label>
						<input type="text" id="longitude" name="longitude" class="form-control" placeholder="Enter longitude..." value="<?php echo $zip->getLongitude() ?>" />
					</div>
					<div class="form-group">
						<label class="control-label" for="longitude">Accuracy</label>
						<select name="accuracy" id="accuracy_<?php echo $zip->getId() ?>">
							<option value="1" <?php echo ($zip->getAccuracy() == 1) ? "selected" : "" ?>>1 (estimated)</option>
							<option value="2" <?php echo ($zip->getAccuracy() == 2) ? "selected" : "" ?>>2</option>
							<option value="3" <?php echo ($zip->getAccuracy() == 3) ? "selected" : "" ?>>3</option>
							<option value="4" <?php echo ($zip->getAccuracy() == 4) ? "selected" : "" ?>>4</option>
							<option value="5" <?php echo ($zip->getAccuracy() == 5) ? "selected" : "" ?>>5</option>
							<option value="6" <?php echo ($zip->getAccuracy() == 6) ? "selected" : "" ?>>6 (exact)</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($zip->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Zip" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#zip_form_<?php echo $zip->getId() ?>').form(function(data) {
		$.rad.notify('Zipcode Updated', 'The zipcode has been added/updated in the system');
		$('#zip_search_form').trigger('submit');
		$('#edit_zip_modal').modal('hide');
	}, {keep_form:1});

	$('#accuracy_<?php echo $zip->getId() ?>').selectize();
});

<?php if (\MongoId::isValid($zip->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this zip from the system?')) {
		$.rad.del('/api', { func: '/admin/zip/<?php echo $zip->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this zipcode', 'You have deleted this zipcode.  You will need to refresh this page to see your changes.');
			$('#zip_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>