<?php
	/* @var $saved_search Flux\SavedSearch */
	$saved_search = $this->getContext()->getRequest()->getAttribute("saved_search", array());
	// Used for the lead search
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
	$data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$traffic_sources = $this->getContext()->getRequest()->getAttribute("traffic_sources", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($saved_search->getId()) ? 'Edit' : 'Add' ?> Saved Search</h4>
</div>
<form id="saved_search_form_<?php echo $saved_search->getId() ?>" method="<?php echo \MongoId::isValid($saved_search->getId()) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/saved-search" />
	<input type="hidden" name="is_global" value="0" />
	<?php if (\MongoId::isValid($saved_search->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $saved_search->getId() ?>" />
        <input type="hidden" name="user[user_id]" value="<?php echo \MongoId::isValid($saved_search->getUser()->getUserId()) ? $saved_search->getUser()->getUserId() : $this->getContext()->getUser()->getUserDetails()->getId()  ?>" />
	<?php } else { ?>
	    <input type="hidden" name="user[user_id]" value="<?php echo $this->getContext()->getUser()->getUserDetails()->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<div class="help-block">Select what type of saved search you want to create</div>
		<select name="search_type" id="search_type" placeholder="select the type of search you want to save">
		    <option value=""></option>
            <option value="<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('id' => \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD, 'name' => 'Leads', 'description' => 'Search for leads on the search all leads pages'))) ?>">Leads</option>
            <option value="<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ?>" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('id' => \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER, 'name' => 'Offers', 'description' => 'Search for offers based on keywords or by vertical'))) ?>">Offers</option>
            <option value="<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('id' => \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN, 'name' => 'Campaigns', 'description' => 'Search for campaigns by offer, client, or traffic source'))) ?>">Campaigns</option>
		</select>
		<!-- Lead Search settings -->
		<div id="saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>" class="<?php echo $saved_search->getSearchType() != \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ? 'hidden' : ''; ?>">
		    <hr />
			<div class="help-block">Select search criteria to use when searching for leads</div>
			<div class="form-group">
			    <label>Only show leads with the following fields set: </label>
				<select class="form-control selectize" name="query_string[required_fields][]" id="required_fields" multiple placeholder="No Fields" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ? '' : 'disabled'; ?>>
					<?php
						/* @var $data_field \Flux\DataField */ 
						foreach($data_fields AS $data_field) { 
					?>
						<?php if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
							<option value="<?php echo $data_field->getKeyName() ?>" <?php echo in_array($data_field->getKeyName(), $saved_search->getQueryStringParam('required_fields', array())) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
						<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
							<option value="<?php echo $data_field->getKeyName() ?>" <?php echo in_array($data_field->getKeyName(), $saved_search->getQueryStringParam('required_fields', array())) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
						<?php } else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
							<option value="<?php echo $data_field->getKeyName() ?>" <?php echo in_array($data_field->getKeyName(), $saved_search->getQueryStringParam('required_fields', array())) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $data_field->getName(), 'key_name' => $data_field->getKeyName(), 'description' => $data_field->getDescription(), 'tags' => $data_field->getTags(), 'request_names' => array_merge(array($data_field->getKeyName(), $data_field->getRequestName()))))) ?>"><?php echo $data_field->getName() ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>Filter leads by offer: </label>
				<select class="form-control selectize" name="query_string[offer_id_array][]" id="offer_id_array" multiple placeholder="Filter by offer" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ? '' : 'disabled'; ?>>
					<?php 
						/* @var $offer \Flux\Offer */
						foreach($offers as $offer) {
					?>
						<option value="<?php echo $offer->getId() ?>" <?php echo in_array($offer->getId(), $saved_search->getQueryStringParam('offer_id_array', array())) ? 'selected' : '' ?>><?php echo $offer->getName() ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>Filter leads by campaign: </label>
				<select class="form-control selectize" name="query_string[campaign_id_array][]" id="campaign_id_array" multiple placeholder="Filter by campaign" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ? '' : 'disabled'; ?>>
					<?php
						/* @var $campaign \Flux\Campaign */ 
						foreach ($campaigns as $campaign) { 
					?>
						<option value="<?php echo $campaign->getId() ?>" <?php echo in_array($campaign->getId(), $saved_search->getQueryStringParam('campaign_id_array', array())) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('campaign_key' => $campaign->getKey(), 'description' => $campaign->getDescription(), 'client_name' => $campaign->getClient()->getClientName()))) ?>"><?php echo $campaign->getId() ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<!-- Offer Search settings -->
		<div id="saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ?>" class="<?php echo $saved_search->getSearchType() != \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ? 'hidden' : ''; ?>">
		    <hr />
			<div class="help-block">Select search criteria to use when searching for offers</div>
			<div class="form-group">
				<label>Filter offers by name: </label>
				<input type="text" name="query_string[name]" value="<?php echo $saved_search->getQueryStringParam('name', '') ?>" placeholder="Enter search terms..." class="form-control" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ? '' : 'disabled'; ?> />
			</div>
		</div>
		<!-- Campaign Search settings -->
		<div id="saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>" class="<?php echo $saved_search->getSearchType() != \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ? 'hidden' : ''; ?>">
		    <hr />
			<div class="help-block">Select search criteria to use when searching for campaigns</div>
			<div class="form-group">
				<label>Filter campaigns by name: </label>
				<input type="text" name="query_string[keywords]" value="<?php echo $saved_search->getQueryStringParam('keywords', '') ?>" placeholder="Enter search terms..." class="form-control" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ? '' : 'disabled'; ?> />
			</div>
			<div class="form-group">
				<label>Filter campaigns by offer: </label>
				<select class="form-control selectize" name="query_string[offer_id_array][]" id="offer_id_array" multiple placeholder="Filter by offer" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ? '' : 'disabled'; ?>>
					<?php 
						/* @var $offer \Flux\Offer */
						foreach($offers as $offer) {
					?>
						<option value="<?php echo $offer->getId() ?>" <?php echo in_array($offer->getId(), $saved_search->getQueryStringParam('offer_id_array', array())) ? 'selected' : '' ?>><?php echo $offer->getName() ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>Filter campaigns by client: </label>
				<select class="form-control selectize" name="query_string[client_id_array][]" id="client_id_array" multiple placeholder="Filter by client" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ? '' : 'disabled'; ?>>
					<?php
						/* @var $client \Flux\Client */ 
						foreach ($clients as $client) { 
					?>
						<option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $saved_search->getQueryStringParam('client_id_array', array())) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
                <label>Filter campaigns by traffic source: </label>
				<select class="form-control selectize" name="query_string[traffic_source_id_array][]" id="traffic_source_id_array" multiple placeholder="Filter by traffic source" <?php echo $saved_search->getSearchType() == \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ? '' : 'disabled'; ?>>
					<?php
						/* @var $traffic_source \Flux\TrafficSource */ 
						foreach ($traffic_sources as $traffic_source) { 
					?>
						<option value="<?php echo $traffic_source->getId() ?>" <?php echo in_array($client->getId(), $saved_search->getQueryStringParam('traffic_source_id_array', array())) ? "selected" : "" ?> data-data="<?php echo htmlentities(json_encode(array('name' => $traffic_source->getName(), 'description' => $traffic_source->getDescription(), 'icon' => $traffic_source->getIcon(), 'username' => $traffic_source->getUsername()))) ?>"><?php echo $traffic_source->getName() ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<hr />
		<div class="form-group <?php echo ($saved_search->getSearchType() == 0) ? 'hidden' : ''; ?>" id="saved_search_name">
			<div class="help-block">Enter a nickname for this saved search that will be displayed in the menu</div>
			<input type="text" name="name" class="form-control" placeholder="Enter a name for this search..." value="<?php echo $saved_search->getName() ?>" />
			<div style="padding-left:10px;">
                <label><input type="checkbox" name="is_global" value="1" <?php echo $saved_search->getIsGlobal() ? 'checked'  : '' ?> /> Show this saved search to all users in the system</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($saved_search->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Saved Search" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#saved_search_form_<?php echo $saved_search->getId() ?>').form(function(data) {
		$.rad.notify('Saved Search Updated', 'The saved search has been added/updated in the system');
		$('#saved-search_search_form').trigger('submit');
		$('#edit_saved_search_modal').modal('hide');
	}, {keep_form:1});

	$('#search_type').selectize({
		valueField: 'id',
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
		},
		onChange: function(val) {
			// disable lead elements
			$('#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').addClass('hidden');
			$('input,select,textarea','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').attr('disabled', 'disabled');
			// disable offer elements
			$('#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ?>').addClass('hidden');
			$('input,select,textarea','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_OFFER ?>').attr('disabled', 'disabled');
		    // disable campaign elements
			$('#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').addClass('hidden');
			$('input,select,textarea','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').attr('disabled', 'disabled');

			$('#saved_qs_' + val).removeClass('hidden');
			$('input,select,textarea','#saved_qs_' + val).removeAttr('disabled');

		    if (val != '') {
		    	$('#saved_search_name').removeClass('hidden');
		    }
			
			if (val == '<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>') {
			    $('#required_fields','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').selectize()[0].selectize.enable();
			    $('#offer_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').selectize()[0].selectize.enable();
			    $('#campaign_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').selectize()[0].selectize.enable();
			} else if (val == '<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>') {
				$('#offer_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').selectize()[0].selectize.enable();
				$('#client_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').selectize()[0].selectize.enable();
				$('#traffic_source_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').selectize()[0].selectize.enable();
			}
		}
	});

	$('#required_fields','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').selectize({
		valueField: 'key_name',
		labelField: 'name',
		searchField: ['name', 'description', 'request_names'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
				var label = item.name || item.key;            
	            return '<div">' + escape(label) + '</div>';
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
		}
	});

	$('#offer_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	});

	$('#offer_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	});

	$('#campaign_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_LEAD ?>').selectize({
		valueField: 'campaign_key',
		labelField: 'description',
		searchField: ['client_name', 'description', 'campaign_key'],
		dropdownWidthOffset: 150,
		create: true,
		render: {
			item: function(item, escape) {
	            return '<div>' + escape(item.campaign_key) + '</div>';
			},
			option: function(item, escape) {
				return '<div style="padding-right:25px;">' +
	                '<b>' + escape(item.campaign_key) + '</b>' +
	                '<span class="pull-right label label-success">' + (item.client_name ? escape(item.client_name) : 'Unknown') + '</span>' + 
	    	        '<br />' +
	                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
	            '</div>';
			}
		}
	});

	$('#client_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').selectize({
		dropdownWidthOffset: 150,
		allowEmptyOption: true
	});

	$('#traffic_source_id_array','#saved_qs_<?php echo \Flux\SavedSearch::SAVED_SEARCH_TYPE_CAMPAIGN ?>').selectize({
    	allowEmptyOption: true,
    	dropdownWidthOffset: 150,
    	valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		render: {
			
			option: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="/images/traffic-sources/' + escape(item.icon) + '_48.png" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h4 class="media-heading small">' + escape(item.name) + '</h4>';
				ret_val += '<div class="text-muted small">' + escape(item.description) + '</div>';
				ret_val += '<div class="text-muted small">(' + escape(item.username) + ')</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		}
    });
});

<?php if (\MongoId::isValid($saved_search->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this saved search from the system?')) {
		$.rad.del({ func: '/admin/saved-search/<?php echo $saved_search->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this saved search', 'You have deleted this saved search.  You will need to refresh this page to see your changes.');
			$('#saved-search_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>