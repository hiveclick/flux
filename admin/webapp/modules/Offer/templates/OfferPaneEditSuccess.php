<?php
	/* @var $offer Flux\Offer */
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$flows = $this->getContext()->getRequest()->getAttribute("flows", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
	$splits = $this->getContext()->getRequest()->getAttribute("splits", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Edit Offer</h4>
</div>
<form action="/api" id="offer_form" method="PUT">
	<input type="hidden" name="func" value="/offer/offer" />
	<input type="hidden" name="_id" value="<?php echo $offer->getId() ?>" />
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic Settings</a></li>
			<li role="presentation" class=""><a href="#advanced" role="tab" data-toggle="tab">Advanced Settings</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="basic">
				<div class="help-block">These are the main settings for this offer.</div>
				<div class="form-group">
					<label class="control-label hidden-xs" for="name">Name</label>
					<input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $offer->getName() ?>" />
				</div>
				
				<div class="form-group">
					<label class="control-label hidden-xs" for="client_id">Advertiser</label>
					<select class="form-control selectize" name="client[client_id]" id="client_id" placeholder="Advertising Client">
						<?php foreach ($clients AS $client) { ?>
						<option value="<?php echo $client->getId(); ?>"<?php echo $offer->getClient()->getClientId() == $client->getId() ? ' selected' : ''; ?>><?php echo $client->getName(); ?></option>
						<?php } ?>
					</select>
				</div>
		
				<div class="form-group">
					<label class="control-label hidden-xs" for="default_campaign_id">Default Campaign</label>
					<select class="form-control" name="default_campaign_id" id="default_campaign_id" placeholder="Default Campaign">
						<?php foreach($campaigns AS $campaign) { ?>
						<option value="<?php echo $campaign->getId(); ?>"<?php echo $offer->getDefaultCampaignId() == $campaign->getId() ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('campaign_key' => $campaign->getKey(), 'description' => $campaign->getDescription(), 'client_name' => $campaign->getClient()->getClientName()))) ?>"><?php echo $campaign->getDescription() ?></option>
						<?php } ?>
					</select>
				</div>
				
				<hr />

				<div class="form-group">
					<label class="control-label hidden-xs" for="name">Verticals</label>
					<select id="verticals" name="verticals[]" class="form-control selectize" require placeholder="Verticals" multiple>
						<?php foreach ($verticals as $vertical) { ?>
							<option value="<?php echo $vertical->getName() ?>" <?php echo in_array($vertical->getName(), $offer->getVerticals()) ? "selected=\"SELECTED\"" : "" ?>><?php echo $vertical->getName() ?></option>
						<?php } ?>
					</select>
				</div>
				
				<div class="form-group">
					<label class="control-label hidden-xs" for="payout">Payout</label>
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" id="payout" name="payout" class="form-control" placeholder="Enter payout" value="<?php echo number_format($offer->getPayout(), 2) ?>" />
					</div>
				</div>
				
				<hr />
	
				<div class="form-group">
					<label class="control-label hidden-xs" for="status">Status</label>
					<select class="form-control selectize" name="status" id="status" placeholder="Status">
						<option value="<?php echo \Flux\Offer::OFFER_STATUS_ACTIVE ?>"<?php echo $offer->getStatus() == \Flux\Offer::OFFER_STATUS_ACTIVE ? ' selected' : ''; ?>>Active</option>
						<option value="<?php echo \Flux\Offer::OFFER_STATUS_INACTIVE ?>"<?php echo $offer->getStatus() == \Flux\Offer::OFFER_STATUS_INACTIVE ? ' selected' : ''; ?>>Inactive</option>
					</select>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade in" id="advanced">
				<div class="help-block">Select how traffic is sent to this offer and configure advanced settings.</div>
				<div class="form-group">
					<label class="control-label hidden-xs" for="redirect_type">Redirect Type</label>
					<select class="form-control selectize" name="redirect_type" id="redirect_type" placeholder="Redirect Type">
						<option value="<?php echo \Flux\Offer::REDIRECT_TYPE_HOSTED ?>"<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_HOSTED ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Hosted', 'value' => \Flux\Offer::REDIRECT_TYPE_HOSTED, 'description' => 'Send traffic to a landing page you own that uses FluxFE'))) ?>">Hosted</option>
        				<option value="<?php echo \Flux\Offer::REDIRECT_TYPE_REDIRECT ?>"<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_REDIRECT ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Redirect', 'value' => \Flux\Offer::REDIRECT_TYPE_REDIRECT, 'description' => 'Send traffic to another site and fire events with placed pixels'))) ?>">Redirect</option>
        				<option value="<?php echo \Flux\Offer::REDIRECT_TYPE_POST ?>"<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_POST ? ' selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => 'Host & Post', 'value' => \Flux\Offer::REDIRECT_TYPE_POST, 'description' => 'Fulfill traffic received through an API and respond with JSON'))) ?>">Incoming Post</option>
					</select>
				</div>
				<hr />
				<div id="hosted_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_HOSTED ? '': 'display:none;' ?>">
				
					<div class="form-group" id="domain_name_form_group">
						<label class="control-label hidden-xs" for="folder_name">Domain Name</label>
						<input type="text" id="domain_name" name="domain_name" class="form-control" placeholder="Domain to landing page (www.offer-domain.com)" value="<?php echo $offer->getDomainName() ?>" />
					</div>
				
					<div class="form-group" id="folder_name_form_group">
						<label class="control-label hidden-xs" for="folder_name">Folder Name</label>
						<input type="text" id="folder_name" name="folder_name" class="form-control" placeholder="Folder name containing offer pages (v1, v2, etc)" value="<?php echo $offer->getFolderName() ?>" />
					</div>
					
					<div class="form-group" id="docroot_dir_form_group">
						<label class="control-label hidden-xs" for="docroot_dir">Document Root</label>
						<div class="input-group">
							<input type="text" id="docroot_dir" name="docroot_dir" class="form-control" placeholder="Document root folder (/var/www/sites/...)" value="<?php echo $offer->getDocrootDir() ?>" />
							<div class="input-group-btn">
								<a type="button" class="btn btn-info" href="/offer/offer-pane-push-to-server?_id=<?php echo $offer->getId() ?>" data-toggle="modal" data-target="#pushToServerModal">push to server</a>
							</div>
						</div>
					</div>
				</div>
				
				<div id="post_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_POST ? '': 'display:none;' ?>">
            		<div class="form-group" id="domain_name_form_group">
            			<label class="control-label hidden-xs" for="split_id">Host &amp; Post Split</label>
        				<select id="split_id" name="split[split_id]" class="form-control" placeholder="select a split to use for fulfillment...">
        				    <?php 
        				        /* @var $split \Flux\Split */
        				        foreach ($splits as $split) {
        				    ?>
        				        <option value="<?php echo $split->getId() ?>" <?php echo $offer->getSplit()->getSplitId() == $split->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('_id' => $split->getId(), 'name' => $split->getName(), 'description' => $split->getDescription()))) ?>"><?php echo $split->getName() ?></option>
        				    <?php } ?>
        				</select>
            		</div>
            	</div>
	
				<div id="redirect_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_REDIRECT ? '': 'display:none;' ?>">
					 <div class="form-group">
						<label class="control-label hidden-xs" for="redirect_url">Redirect URL</label>
						<textarea id="redirect_url" name="redirect_url" rows="4" class="form-control" placeholder="Redirect URL"><?php echo $offer->getRedirectUrl() ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" name="__save" class="btn btn-primary" value="Save Offer" />
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

	$('#default_campaign_id').selectize({
		valueField: 'campaign_key',
		labelField: 'description',
		searchField: ['client_name', 'description', 'campaign_key'],
		dropdownWidthOffset: 150,
		render: {
			item: function(item, escape) {
	            return '<div style="padding-right:25px;">' +
	                '<b>' + escape(item.campaign_key) + '</b> <span class="pull-right label label-success">' + escape(item.client_name) + '</span><br />' +
	                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
	            '</div>';
			},
			option: function(item, escape) {
				return '<div style="padding-right:25px;">' +
	                '<b>' + escape(item.campaign_key) + '</b> <span class="pull-right label label-success">' + escape(item.client_name) + '</span><br />' +
	                (item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
	            '</div>';
			}
		}
	});

	$('#client_id,#status,#verticals').selectize();

	$('#pushToServerModal').on('shown.bs.modal', function(e) {
		$('#modal_domain_name').val($('#domain_name').val());
		$('#modal_docroot_dir').val($('#docroot_dir').val());
		$('#modal_folder_name').val($('#folder_name').val());
	});

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
		if (data.record) {
			$.rad.notify('Offer Updated', 'The offer has been updated successfully');
		}
	},{keep_form: 1});
});
//-->
</script>