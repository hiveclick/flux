<?php
	/* @var $offer Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$flows = $this->getContext()->getRequest()->getAttribute("flows", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$splits = $this->getContext()->getRequest()->getAttribute("splits", array());

	$conversion_data_field = \Flux\DataField::retrieveDataFieldFromKeyName(\Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME);
?>
<div class="page-header">
	<h1>New Offer</h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/offer/offer-search">Offers</a></li>
	<li class="active">Add New Offer</li>
</ol>

<!-- Page Content -->
<div class="help-block">Use this form to add a new offer to the system assigned to an advertiser</div>
<br/>
<form class="form-horizontal" id="offer_form" name="offer_form" method="POST" action="/api" autocomplete="off">
	<input type="hidden" name="func" value="/offer/offer" />
	<input type="hidden" name="status" value="<?php echo \Flux\Offer::OFFER_STATUS_ACTIVE ?>" />

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
		<div class="col-sm-10">
			<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $offer->getName() ?>" />
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="name">Verticals</label>
		<div class="col-sm-10">
			<select id="verticals" name="verticals[]" class="form-control selectize" require placeholder="Verticals" multiple>
				<?php foreach ($verticals as $vertical) { ?>
					<option value="<?php echo $vertical->getName() ?>" <?php echo in_array($vertical->getId(), $offer->getVerticals()) ? "selected=\"SELECTED\"" : "" ?>><?php echo $vertical->getName() ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="client_id">Advertising Client</label>
		<div class="col-sm-10">
			<select class="form-control" name="client[client_id]" id="client_id" placeholder="Advertising Client">
				<optgroup label="Administrators">
			        <?php
    					/* @var $client \Flux\Client */
    					foreach ($clients AS $client) { 
    				?>
    				    <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
    					    <option value="<?php echo $client->getId(); ?>"<?php echo $offer->getClient()->getClientId() == $client->getId() ? ' selected' : ''; ?>><?php echo $client->getName() ?></option>
    					<?php } ?>
    				<?php } ?>
			    </optgroup>
				<optgroup label="Advertisers">
			        <?php
    					/* @var $client \Flux\Client */
    					foreach ($clients AS $client) { 
    				?>
    				    <?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
    					    <option value="<?php echo $client->getId(); ?>"<?php echo $offer->getClient()->getClientId() == $client->getId() ? ' selected' : ''; ?>><?php echo $client->getName() ?></option>
    					<?php } ?>
    				<?php } ?>
			    </optgroup>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="payout">Payout</label>
		<div class="col-sm-10">
		    <input type="hidden" name="events[0][event_id]" value="<?php echo !is_null($conversion_data_field) ? $conversion_data_field->getId() : 0 ?>" />
			<input type="hidden" name="events[0][modifier_id]" value="<?php echo \Flux\DataField::DATA_FIELD_MODIFIER_SET ?>" />
			<input type="hidden" name="events[0][field]" value="payout" />
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" name="events[0][value]" id="payout" class="form-control" value="<?php echo number_format($offer->getPayout(), 2) ?>">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="revenue">Revenue</label>
		<div class="col-sm-10">
		    <input type="hidden" name="events[1][event_id]" value="<?php echo !is_null($conversion_data_field) ? $conversion_data_field->getId() : 0 ?>" />
			<input type="hidden" name="events[1][modifier_id]" value="<?php echo \Flux\DataField::DATA_FIELD_MODIFIER_SET ?>" />
			<input type="hidden" name="events[1][field]" value="revenue" />
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" name="events[1][value]" id="revenue" class="form-control" value="0.00">
			</div>
		</div>
	</div>

	<div class="help-block">Choose if you want to direct traffic to a flow or to an external url</div>

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="redirect_type">Offer Type</label>
		<div class="col-sm-10">
			<select class="form-control" name="redirect_type" id="redirect_type" placeholder="Redirect Type">
				<option value="<?php echo \Flux\Offer::REDIRECT_TYPE_HOSTED ?>"<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_HOSTED ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Hosted', 'value' => \Flux\Offer::REDIRECT_TYPE_HOSTED, 'description' => 'Send traffic to a landing page you own that uses FluxFE'))) ?>">Hosted</option>
				<option value="<?php echo \Flux\Offer::REDIRECT_TYPE_REDIRECT ?>"<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_REDIRECT ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Redirect', 'value' => \Flux\Offer::REDIRECT_TYPE_REDIRECT, 'description' => 'Send traffic to another site and fire events with placed pixels'))) ?>">Redirect</option>
				<option value="<?php echo \Flux\Offer::REDIRECT_TYPE_POST ?>"<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_POST ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Host & Post', 'value' => \Flux\Offer::REDIRECT_TYPE_POST, 'description' => 'Fulfill traffic received through an API and respond with JSON'))) ?>">Incoming Post</option>
			</select>
		</div>
	</div>

	<div id="redirect_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_REDIRECT ? '': 'display:none;' ?>">
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="redirect_url">Redirect URL</label>
			<div class="col-sm-10">
				<textarea id="redirect_url" name="redirect_url" rows="4" class="form-control" placeholder="Redirect URL"><?php echo $offer->getRedirectUrl() ?></textarea>
			</div>
		</div>
	</div>
	
	<div id="post_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_POST ? '': 'display:none;' ?>">
		<div class="form-group" id="domain_name_form_group">
			<label class="col-sm-2 control-label hidden-xs" for="split_id">Host &amp; Post Split</label>
			<div class="col-sm-10">
				<select id="split_id" name="split[split_id]" class="form-control" placeholder="select a split to use for fulfillment...">
				    <?php 
				        /* @var $split \Flux\Split */
				        foreach ($splits as $split) {
				    ?>
				        <option value="<?php echo $split->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $split->getId(), 'name' => $split->getName(), 'description' => $split->getDescription()))) ?>"><?php echo $split->getName() ?></option>
				    <?php } ?>
				</select>
			</div>
		</div>
	</div>

	<div id="hosted_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_HOSTED ? '': 'display:none;' ?>">		
		<div class="form-group" id="domain_name_form_group">
			<label class="col-sm-2 control-label hidden-xs" for="folder_name">Domain Name</label>
			<div class="col-sm-10">
				<input type="text" id="domain_name" name="domain_name" class="form-control" placeholder="Domain to landing page (www.offer-domain.com)" value="<?php echo $offer->getDomainName() ?>" />
			</div>
		</div>
	
		<div class="form-group" id="folder_name_form_group">
			<label class="col-sm-2 control-label hidden-xs" for="folder_name">Folder Name</label>
			<div class="col-sm-10">
				<input type="text" id="folder_name" name="folder_name" class="form-control" placeholder="Folder name containing offer pages (v1, v2, etc)" value="<?php echo $offer->getFolderName() ?>" />
			</div>
		</div>
		
		<div class="form-group" id="docroot_dir_form_group">
			<label class="col-sm-2 control-label hidden-xs" for="docroot_dir">Document Root</label>
			<div class="col-sm-10">
				<input type="text" id="docroot_dir" name="docroot_dir" class="form-control" placeholder="Document root folder (/var/www/sites/...)" value="<?php echo $offer->getDocrootDir() ?>" />
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" name="__save" class="btn btn-success" value="Save" />
		</div>
	</div>

</form>
<script>
//<!--
$(document).ready(function() {
    $('#split_id').selectize();

    $('#redirect_type').selectize({
    	valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		render: {
			option: function(item, escape) {           
	            return '<div style="border-bottom: 1px dotted #C8C8C8;">' +
	                '<b>' + escape(item.name) + '</b><br />' +
	                '<span class="text-muted small">' + escape(item.description) + ' </span>' +
	            '</div>';
			},
			item: function(item, escape) {           
    	            return '<div>' +
                    '<b>' + escape(item.name) + '</b><br />' +
                    '<span class="text-muted small">' + escape(item.description) + ' </span>' +
                '</div>';
    		}
		}
    });
	
	$('#verticals,#client_id').selectize();

	$('#redirect_type').on('change', function() {
		$('#redirect_form_group').hide();
		$('#hosted_form_group').hide();
		$('#post_form_group').hide();
		if($(this).val() == <?php echo json_encode(\Flux\Offer::REDIRECT_TYPE_HOSTED); ?>) {
			$('#hosted_form_group').show();
		} else if($(this).val() == <?php echo json_encode(\Flux\Offer::REDIRECT_TYPE_REDIRECT); ?>) {
			$('#redirect_form_group').show();
		} else if($(this).val() == <?php echo json_encode(\Flux\Offer::REDIRECT_TYPE_POST); ?>) {
			$('#post_form_group').show();
		}
	}).trigger('change');

	$('#offer_form').form(function(data) {
		if (data.meta.insert_id) {
    	    $.rad.notify('Offer Added', 'The new offer has been added to the system');
    	    location.href = '/offer/offer?_id=' + data.meta.insert_id;
		}
	});
});
//-->
</script>