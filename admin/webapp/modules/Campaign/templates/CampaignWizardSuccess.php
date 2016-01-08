<?php
	/* @var $campaign \Flux\Campaign */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$traffic_sources = $this->getContext()->getRequest()->getAttribute("traffic_sources", array());
?>
<div class="page-header">
   <h1>New Campaign</h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/campaign/campaign-search">Campaigns</a></li>
	<li class="active">Create New Campaign</li>
</ol>

<!-- Page Content -->
<div class="help-block">Associate an offer to a client to create a campaign to use for tracking traffic</div>
<br/>
<div id="tab-content-container" class="tab-content">
	<form class="form-horizontal" name="campaign_form" id="campaign_form" method="POST" action="/api" autocomplete="off">
		<input type="hidden" name="func" value="/campaign/campaign" />
		<input type="hidden" name="payout" value="0" />
		<input type="hidden" name="status" value="<?php echo \Flux\Campaign::CAMPAIGN_STATUS_ACTIVE ?>" />
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="description">Description</label>
			<div class="col-sm-10">
				<textarea id="description" name="description" class="form-control" placeholder="Enter descriptive text about where you will run this campaign"><?php echo $campaign->getDescription(); ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="client_id">Affiliate</label>
			<div class="col-sm-10">
				<select class="form-control" name="client[client_id]" id="client_id" placeholder="Choose an owner of this campaign...">
					<optgroup label="Administrators">
						<?php
							/* @var $client \Flux\Client */
							foreach ($clients AS $client) { 
						?>
							<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN) { ?>
								<option value="<?php echo $client->getId(); ?>"<?php echo $campaign->getClient()->getClientId() == $client->getId() ? ' selected="selected"' : ''; ?>><?php echo $client->getName() ?></option>
							<?php } ?>
						<?php } ?>
					</optgroup>
					<optgroup label="Affiliates">
						<?php
							/* @var $client \Flux\Client */
							foreach ($clients AS $client) { 
						?>
							<?php if ($client->getClientType() == \Flux\Client::CLIENT_TYPE_AFFILIATE) { ?>
								<option value="<?php echo $client->getId(); ?>"<?php echo $campaign->getClient()->getClientId() == $client->getId() ? ' selected="selected"' : ''; ?>><?php echo $client->getName() ?></option>
							<?php } ?>
						<?php } ?>
					</optgroup>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="traffic_source">Traffic Source</label>
			<div class="col-sm-10">
				<select class="form-control" name="traffic_source[traffic_source_id]" id="traffic_source" placeholder="Traffic Source">
					<?php
						/* @var $traffic_source \Flux\TrafficSource */
						foreach ($traffic_sources AS $traffic_source) { 
					?>
						<option value="<?php echo $traffic_source->getId() ?>" <?php echo $campaign->getTrafficSource()->getTrafficSourceId() == $traffic_source->getId() ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $traffic_source->getName(), 'description' => $traffic_source->getDescription(), 'icon' => $traffic_source->getIcon(), 'username' => $traffic_source->getUsername()))) ?>"><?php echo $traffic_source->getName() ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="offer_id">Offer</label>
			<div class="col-sm-10">
				<select class="form-control" name="offer[offer_id]" id="offer_id" placeholder="Select an offer to redirect to...">
					<option value=""></option>
					<?php
						/* @var $offer \Flux\Offer */ 
						foreach ($offers AS $offer) { 
					?>
						<option value="<?php echo $offer->getId() ?>" <?php echo $campaign->getOffer()->getOfferId() == $offer->getId() ? 'selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => (string)$offer->getId(), 'name' => $offer->getName(), 'url' => $offer->getDefaultCampaign()->getRedirectUrl(), 'optgroup' => $offer->getVertical()->getVerticalName()))) ?>"><?php echo $offer->getName() ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="offer_id"></label>
			<div class="col-sm-10">
				<select class="form-control" name="landing_page" id="landing_page" placeholder="Select a landing page to redirect to..."></select>
			</div>
		</div>
		
		
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="redirect_link">Redirect Link</label>
			<div class="col-sm-10">
				<textarea name="redirect_link" id="redirect_link" class="form-control" placeholder="Select an offer and landing page above to generate a redirect link..."><?php echo $campaign->getRedirectLink() ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" name="__save" class="btn btn-success" value="Create Campaign" />
			</div>
		</div>		
		
	</form>
</div>

<script>
//<!--
$(document).ready(function() {
	$('#client_id').selectize();

	$('#traffic_source').selectize({
		valueField: 'value',
		labelField: 'name',
		searchField: ['name', 'description'],
		render: {
			item: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="/images/traffic-sources/' + escape(item.icon) + '_48.png" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h4 class="media-heading">' + escape(item.name) + '</h4>';
				ret_val += '<div class="text-muted">' + escape(item.description) + '</div>';
				ret_val += '<div class="text-muted small">' + escape(item.username) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			},
			option: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="/images/traffic-sources/' + escape(item.icon) + '_48.png" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h4 class="media-heading">' + escape(item.name) + '</h4>';
				ret_val += '<div class="text-muted">' + escape(item.description) + '</div>';
				ret_val += '<div class="text-muted small">' + escape(item.username) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		}

	});

	$('#offer_id').selectize({
		valueField: '_id',
		dropdownWidthOffset: 200,
		allowEmptyOption: true,
		labelField: 'name',
		searchField: ['name'],
		optgroups: [
			<?php foreach ($verticals as $vertical) { ?>
			{ label: '<?php echo $vertical->getName() ?>', value: '<?php echo $vertical->getName() ?>'},
			<?php } ?>
		],
		lockOptgroupOrder: true,
		render: {
			item: function(item, escape) {
				return '<div>' + escape(item.name) + '</div>';
			},
			option: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(item.url) + '" width="64" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
				ret_val += '<div class="text-muted small">' + escape(item.url) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		},
		onChange: function(value) {
			if (!value.length) return;
			$.rad.get('/api', {func:'/offer/offer', _id:value}, function(data) {
				if (data.record) {
					$('#landing_page').selectize()[0].selectize.clearOptions();
					$.each(data.record.landing_pages, function(i, item) {
						$('#landing_page').selectize()[0].selectize.addOption(item);
						$('#landing_page').selectize()[0].selectize.refreshOptions();
						if (data.record.landing_pages.length == 1) {
							$('#landing_page').selectize()[0].selectize.addItem(item.url);
							$('#landing_page').selectize()[0].selectize.refreshItems();
						}
					});
					$('#landing_page').selectize()[0].selectize.blur();
					
				}
			});
		}
	});

	$('#landing_page').selectize({
		valueField: 'url',
		labelField: 'name',
		searchField: ['name'],
		render: {
			item: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(item.url) + '" width="64" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
				ret_val += '<div class="text-muted small">' + escape(item.url) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			},
			option: function(item, escape) {
				var ret_val = '<div class="media"><div class="media-left pull-left media-top">';
				ret_val += '<img class="media-object img-thumbnail" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1280x1024&p2i_size=64x64&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '163e945a6c976b6b' ?>&p2i_url=' + escape(item.url) + '" width="64" border="0" />';
				ret_val += '</div><div class="media-body">';
				ret_val += '<h5 class="media-heading">' + escape(item.name) + '</h5>';
				ret_val += '<div class="text-muted small">' + escape(item.url) + '</div>';
				ret_val += '</div></div>';
				return ret_val;
			}
		},
		onChange: function(value) {
			if (!value.length) return;
			var data = this.options[value];
			$('#redirect_link').val(value + '?_id=#_id#');
		}
	});

	$('#campaign_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Campaign added', 'The campaign has been added to the system successfully');
			location.href = '/campaign/campaign?_id=' + data.record._id;
		}
	},{keep_form:true});
});
//-->
</script>