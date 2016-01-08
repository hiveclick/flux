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
	<h4 class="modal-title">Edit <?php echo $offer->getName() ?> Offer</h4>
</div>
<form action="/api" id="offer_form" method="PUT">
	<input type="hidden" name="func" value="/offer/offer" />
	<input type="hidden" name="_id" value="<?php echo $offer->getId() ?>" />
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic Settings</a></li>
			<li role="presentation" class=""><a href="#advanced" role="tab" data-toggle="tab">Advanced Settings</a></li>
			<li role="presentation" class=""><a href="#entry" role="tab" data-toggle="tab">Entry Points</a></li>
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
				
				<div class="form-group">
					<label class="control-label hidden-xs" for="payout">Payout</label>
					<div class="input-group">
						<span class="input-group-addon">$</span>
						<input type="text" name="payout" id="payout" placeholder="enter default payout to publishers..." class="form-control" value="<?php echo $offer->getPayout() > 0 ? number_format($offer->getPayout(), 2) : '' ?>">
					</div>
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
					<label class="control-label hidden-xs" for="name">Vertical</label>
					<select id="vertical" name="vertical[vertical_id]" class="form-control selectize" placeholder="Vertical">
						<?php foreach ($verticals as $vertical) { ?>
							<option value="<?php echo $vertical->getId() ?>" <?php echo $offer->getVertical()->getVerticalId() == $vertical->getName() ? "selected" : "" ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$vertical->getId(), 'name' => $vertical->getName(), 'description' => $vertical->getDescription()))) ?>"><?php echo $vertical->getName() ?></option>
						<?php } ?>
					</select>
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
								<option value="<?php echo $split->getId() ?>" <?php echo $offer->getSplit()->getSplitId() == $split->getId() ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$split->getId(), 'name' => $split->getName(), 'description' => $split->getDescription()))) ?>"><?php echo $split->getName() ?></option>
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
			<div role="tabpanel" class="tab-pane fade in" id="entry">
				<div class="help-block">These are the various entry points (landing pages) into this offer.  Campaigns can redirect to any of these pages.</div>
				<div id="landing_pages">
					<?php 
						foreach ($offer->getLandingPages() as $key => $landing_page) {
					?>
						<div class="media">
							<div class="media-left pull-left">
								<img class="media-object img-thumbnail page_thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=120x120&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=<?php echo urlencode($landing_page->getUrl()) ?>" border="0" width="120" data-url="<?php echo $landing_page->getUrl() ?>" />
							</div>
							<div class="media-body">
								<h4 class="media-heading"><input type="text" class="form-control" placeholder="Enter a nickname for this landing page" name="landing_pages[<?php echo $key ?>][name]" value="<?php echo $landing_page->getName() ?>" /></h4>
								<textarea name="landing_pages[<?php echo $key ?>][url]" class="form-control" placeholder="Enter the url to this landing page" rows="2"><?php echo $landing_page->getUrl() ?></textarea>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-success hidden" id="add_landing_page_btn">Add Landing Page</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" name="__save" class="btn btn-primary" value="Save Offer" />
	</div>
</form>

<!-- Landing Page div -->
<div class="media hidden" id="dummy-landing-page-div">
	<div class="media-left pull-left">
		<img class="media-object img-thumbnail page_thumbnail" src="/images/no_preview.png" border="0" alt="" width="120" data-url="" />
	</div>
	<div class="media-body">
		<h4 class="media-heading"><input type="text" class="form-control" placeholder="Enter a nickname for this landing page" name="landing_pages[dummy_id][name]" value="" /></h4>
		<textarea name="landing_pages[dummy_id][url]" class="form-control" placeholder="Enter the url to this landing page" rows="2"></textarea>
	</div>
</div>

<script>
//<!--
$(document).ready(function() {
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		if ($(e.target).attr('href') == '#entry') {
			$('#add_landing_page_btn').removeClass('hidden');
		} else {
			$('#add_landing_page_btn').addClass('hidden');
		}
	});

	
	$('#add_landing_page_btn').on('click', function() {
		var index_number = $('#landing_pages > .media').length;
		var $landing_page_div = $('#dummy-landing-page-div').clone(true);
		$landing_page_div.removeAttr('id');
		$landing_page_div.html(function(i, oldHTML) {
			oldHTML = oldHTML.replace(/dummy_id/g, (index_number + 1));
			return oldHTML;
		});
		
		$('#landing_pages').append($landing_page_div);
		$landing_page_div.removeClass('hidden');
	});
	
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

	$('#client_id,#status').selectize();

	$('#vertical').selectize({
		valueField: '_id',
		allowEmptyOption: true,
		labelField: 'name',
		searchField: ['name','description'],
		render: {
			item: function(item, escape) {
				return '<div>' + escape(item.name) + '</div>';
			},
			option: function(item, escape) {
				var ret_val = '<div style="border-bottom: 1px dotted #C8C8C8;">' +
				'<b>' + escape(item.name) + '</b><br />' +
				(item.description ? '<span class="text-muted small">' + escape(item.description) + ' </span>' : '') +
				'</div>';
				return ret_val;
			}
		}
	});

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
			$('#edit_modal').modal('hide');
		}
	},{keep_form: 1});
});
//-->
</script>