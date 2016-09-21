<?php
	/* @var $traffice_source Flux\TrafficSource */
	$traffic_source = $this->getContext()->getRequest()->getAttribute("traffic_source", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($traffic_source->getId()) ? 'Edit' : 'Add' ?> Traffic Source</h4>
</div>
<form id="traffic_source_form_<?php echo $traffic_source->getId() ?>" method="<?php echo \MongoId::isValid($traffic_source->getId()) ? 'PUT' : 'POST' ?>" action="/admin/traffic-source" autocomplete="off" role="form">
	<?php if (\MongoId::isValid($traffic_source->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $traffic_source->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div class="help-block">Create a new traffic source</div>
		<div class="form-group">
			<label class="control-label" for="name">Name</label>
			<input type="text" id="name" name="name" class="form-control" placeholder="Enter nickname..." value="<?php echo $traffic_source->getName() ?>" />
		</div>

		<div class="form-group">
			<label class="control-label" for="description">Description</label>
			<textarea id="description" name="description" class="form-control" placeholder="Enter description..."><?php echo $traffic_source->getDescription() ?></textarea>
		</div>
		
		<hr />
		<div class="help-block">Choose an icon to represent this traffic source</div>
			
		<div class="form-group">
			<select name="icon" id="icon" style="width:165px;">
				<option value="adwords" <?php echo ($traffic_source->getIcon() == 'adwords') ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Google', 'value' => 'adwords', 'description' => 'Google Adwords', 'icon' => 'adwords'))) ?>">Google</option>
				<option value="yahoo" <?php echo ($traffic_source->getIcon() == 'yahoo') ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Yahoo', 'value' => 'yahoo', 'description' => 'Yahoo Ad Manager', 'icon' => 'yahoo'))) ?>">Yahoo</option>
				<option value="bing" <?php echo ($traffic_source->getIcon() == 'bing') ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Bing', 'value' => 'bing', 'description' => 'Bing Ad Manager', 'icon' => 'bing'))) ?>">Bing</option>
				<option value="fb" <?php echo ($traffic_source->getIcon() == 'fb') ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Facebook', 'value' => 'fb', 'description' => 'Facebook Ads', 'icon' => 'fb'))) ?>">Facebook</option>
				<option value="trafficvance" <?php echo ($traffic_source->getIcon() == 'trafficvance') ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'TrafficVance', 'value' => 'trafficvance', 'description' => 'TrafficVance Banner Exchange', 'icon' => 'trafficvance'))) ?>">TrafficVance</option>
				<option value="email" <?php echo ($traffic_source->getIcon() == 'email') ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Obmedia', 'value' => 'email', 'description' => 'Obmedia Email Platform', 'icon' => 'email'))) ?>">Obmedia</option>
				<option value="revcontent" <?php echo ($traffic_source->getIcon() == 'revcontent') ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'RevContent', 'value' => 'revcontent', 'description' => 'Revcontent Banner Platform', 'icon' => 'revcontent'))) ?>">Revcontent</option>
			</select>
		</div>

		<hr />
		<div class="help-block">Enter the url, username and password for this traffic source so you don't have to memorize it</div>	
		
		<div class="form-group">
			<label class="control-label" for="url">Login url</label>
			<textarea id="url" name="url" class="form-control" placeholder="Enter url..."><?php echo $traffic_source->getUrl() ?></textarea>
		</div>
		
		<div class="form-group">
			<label class="control-label" for="username">Username</label>
			<input type="text" id="username" name="username" class="form-control" placeholder="Enter username..." value="<?php echo $traffic_source->getUsername() ?>" />
		</div>
		
		<div class="form-group">
			<label class="control-label" for="password">Password</label>
			<input type="text" id="password" name="password" class="form-control" placeholder="Enter password..." value="<?php echo $traffic_source->getPassword() ?>" />
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($traffic_source->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Traffic Source" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#traffic_source_form_<?php echo $traffic_source->getId() ?>').form(function(data) {
		$.rad.notify('Traffic Source Updated', 'The traffic source has been added/updated in the system');
		$('#traffic-source_search_form').trigger('submit');
		$('#edit_traffic_source_modal').modal('hide');
	}, {keep_form:1});

	$('#icon').selectize({
		valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		dropdownWidthOffset: 200,
		render: {
			item: function(item, escape) {
				return '<div class="text-center" style="margin-right:25px;">' +
					'<img class="img-thumbnail text-center" src="/images/traffic-sources/' + escape(item.icon) + '_128.png" border="0" />' +
				'</div>';
			},
			option: function(item, escape) {
				return '<div class="text-center col-md-4">' +
				'<img class="img-thumbnail text-center" src="/images/traffic-sources/' + escape(item.icon) + '_48.png" border="0" />' +
				'</div>';
			}
		}

	});
});

<?php if (\MongoId::isValid($traffic_source->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this user from the system?')) {
		$.rad.del('/admin/traffic-source/<?php echo $traffic_source->getId() ?>', { }, function(data) {
			$.rad.notify('You have deleted this traffic source', 'You have deleted this traffic source.  You will need to refresh this page to see your changes.');
			$('#traffic-source_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>