<?php
	/* @var $campaign \Flux\Campaign */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
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
    					<option value="<?php echo $offer->getId() ?>" <?php echo $campaign->getOffer()->getOfferId() == $offer->getId() ? 'selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('url' => $offer->getFormattedRedirectUrl()))) ?>"><?php echo $offer->getName() ?></option>
    				<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="redirect_link">Redirect Link</label>
			<div class="col-sm-10">
				<textarea name="redirect_link" id="redirect_link" class="form-control"><?php echo $campaign->getRedirectLink() ?></textarea>
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

	var $offer_selectize = $('#offer_id').selectize({
		onChange: function(value) {
			if (!value.length) return;
			var data = this.options[value];
			$('#redirect_link').val(data.url);
		}
	});
	//$offer_selectize[0].selectize.trigger('change');

	$('#campaign_form').form(function(data) {
	    if (data.record) {
	        $.rad.notify('Campaign added', 'The campaign has been added to the system successfully');
	        location.href = '/campaign/campaign?_id=' + data.record._id;
	    }
	},{keep_form:true});
});
//-->
</script>