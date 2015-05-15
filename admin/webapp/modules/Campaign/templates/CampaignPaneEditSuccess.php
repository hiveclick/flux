<?php
	/* @var $campaign \Flux\Campaign */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
	$traffic_sources = $this->getContext()->getRequest()->getAttribute("traffic_sources", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Edit Campaign #<?php echo $campaign->getId() ?></h4>
</div>
<form action="/api" id="campaign_form" method="PUT">
	<input type="hidden" name="func" value="/campaign/campaign" />
	<input type="hidden" name="_id" value="<?php echo $campaign->getId() ?>" />
	<div class="modal-body">
	    <!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#basic" role="tab" data-toggle="tab">Basic Settings</a></li>
			<li role="presentation" class=""><a href="#redirects" role="tab" data-toggle="tab">Redirects</a></li>
			<li role="presentation" class=""><a href="#whitelisted_ips" role="tab" data-toggle="tab">Whitelisted IPs</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="basic">
			    <div class="help-block">These are the main settings for this campaign.</div>
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="description">Description</label>
        			<textarea id="description" name="description" class="form-control" placeholder="Enter Descriptive Name"><?php echo $campaign->getDescription(); ?></textarea>
        		</div>
        		
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="status">Status</label>
        			<select class="form-control" name="status" id="status" placeholder="Status">
        				<option value="<?php echo \Flux\Campaign::CAMPAIGN_STATUS_ACTIVE ?>"<?php echo $campaign->getStatus() == \Flux\Campaign::CAMPAIGN_STATUS_ACTIVE ? ' selected="selected"' : ''; ?>>Active</option>
        				<option value="<?php echo \Flux\Campaign::CAMPAIGN_STATUS_INACTIVE ?>"<?php echo $campaign->getStatus() == \Flux\Campaign::CAMPAIGN_STATUS_INACTIVE ? ' selected="selected"' : ''; ?>>Inactive</option>
        			</select>
        		</div>		  
        	
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="client_id">Affiliate</label>
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
        		
        		<div class="form-group">
            		<label class="control-label hidden-xs" for="payout">Payout</label>
        			<div class="input-group">
        				<span class="input-group-addon">$</span>
        				<input type="text" name="payout" id="payout" placeholder="enter payout to publisher (leave blank to use offer payout of $<?php echo number_format($campaign->getOffer()->getOffer()->getPayout(), 2) ?>)" class="form-control" value="<?php echo $campaign->getPayout() > 0 ? number_format($campaign->getPayout(), 2) : '' ?>">
        			</div>
            	</div>
        		
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="traffic_source">Traffic Source</label>
        			<select class="form-control" name="traffic_source[traffic_source_id]" id="traffic_source" placeholder="Choose where the traffic is originating from...">
        			    <option value=""></option>
        				<?php
        					/* @var $traffic_source \Flux\TrafficSource */
        					foreach ($traffic_sources AS $traffic_source) { 
        				?>
        					<option value="<?php echo $traffic_source->getId() ?>" <?php echo $campaign->getTrafficSource()->getTrafficSourceId() == $traffic_source->getId() ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('name' => $traffic_source->getName(), 'description' => $traffic_source->getDescription(), 'icon' => $traffic_source->getIcon(), 'username' => $traffic_source->getUsername()))) ?>"><?php echo $traffic_source->getName() ?></option>
        				<?php } ?>
        			</select>
        		</div>
        	</div>
        	<div role="tabpanel" class="tab-pane fade in" id="redirects">
        	    <div class="help-block">These urls define how traffic comes into this campaign and where it goes</div>
        	    <div class="form-group">
        			<label class="control-label hidden-xs" for="offer_id">Offer</label>
        			<select class="form-control" name="offer[offer_id]" id="offer_id" placeholder="Select an offer to redirect to...">
        			    <option value=""></option>
        				<?php
        					/* @var $offer \Flux\Offer */ 
        					foreach ($offers AS $offer) { 
        				?>
        					<option value="<?php echo $offer->getId(); ?>"<?php echo $campaign->getOffer()->getOfferId() == $offer->getId() ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('_id' => $offer->getId(), 'name' => $offer->getName(), 'url' => $offer->getDefaultCampaign()->getRedirectUrl(), 'optgroup' => $offer->getVertical()->getVerticalName()))) ?>"><?php echo $offer->getName() ?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="form-group">
        			<select class="form-control" name="landing_page" id="landing_page" placeholder="Select a landing page to redirect to...">
                        <?php
        					/* @var $landing_page \Flux\Link\LandingPage */ 
        					foreach ($campaign->getOffer()->getOffer()->getLandingPages() as $landing_page) { 
        				?>
        					<option value="<?php echo $landing_page->getUrl() ?>" <?php echo (strpos($campaign->getRedirectLink(), $landing_page->getUrl()) === 0) ? 'selected' : '' ?> data-data="<?php echo htmlentities(json_encode(array('name' => $landing_page->getId(), 'url' => $landing_page->getUrl()))) ?>"><?php echo $landing_page->getName() ?></option>
        				<?php } ?>
        			</select>
        		</div>
        	    
        	    <div class="form-group">
        			<label class="control-label hidden-xs" for="redirect_link">Redirect Link</label>
        			<textarea name="redirect_link" rows="3" id="redirect_link" class="form-control" placeholder="enter a url to redirect traffic to (like http://www.metalhiplawsuits.us/index.php?_id=#_id#)..."><?php echo $campaign->getRedirectLink() ?></textarea>
        		</div>
        		
        		<hr />
        		
        		<div class="alert alert-info">
        			Use the link below in Adwords to direct traffic to this campaign.  You can customize this link on the instructions tab.
        		</div>
        		
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="redirect_link">Adwords Url</label>
        			<textarea id="adwords_url" class="form-control" placeholder="<?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/r?' . \Flux\DataField::DATA_FIELD_REF_CAMPAIGN_KEY . '=' . $campaign->getId() ?>&__clear=1"><?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/r?' . \Flux\DataField::DATA_FIELD_REF_CAMPAIGN_KEY . '=' . $campaign->getId() ?>&__clear=1</textarea>
        		</div>
        		<hr />
        		<div class="form-group">
        			<label class="control-label hidden-xs" for="tracking_pixel">Tracking Pixel</label>
        			<textarea id="tracking_pixel" name="tracking_pixel" class="form-control" rows="4" placeholder="Enter the tracking pixel (HTML or Javascript) from the ad network..."><?php echo $campaign->getTrackingPixel() ?></textarea>
        		</div>
        	</div>
        	<div role="tabpanel" class="tab-pane fade in" id="whitelisted_ips">
        	    <div class="help-block">If you want to limit incoming traffic, you can specify a list of whitelisted IPs</div>
        	    <div class="form-group">
        			<label class="control-label hidden-xs" for="redirect_link">Whitelisted IPs</label>
        			<input type="text" name="whitelist_ips[]" id="whitelist_ips" class="form-control" placeholder="enter IPs to whitelist" value="<?php echo implode(",", $campaign->getWhitelistIps()) ?>" />
        		</div>
        	</div>
    	</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" name="__save" class="btn btn-primary" value="Save Campaign" />
	</div>
</form>
	
<script>
//<!--
$(document).ready(function() {
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
	
    $('#whitelist_ips').selectize({
    	delimiter: ',',
		persist: false,
		allowEmptyOption: true,
		create: function(input) {
			return {
				value: input,
				text: input
			}
		}
    });
	
	$('#client_id,#status').selectize();

	$('#campaign_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Campaign Updated', 'The campaign has been updated successfully');
		}
	},{keep_form: 1});

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
			            console.log('adding landing page ' + item.url);
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
});
//-->
</script>