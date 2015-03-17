<?php
	/* @var $campaign Flux\Campaign */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
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
	<form class="form-horizontal" name="campaign_form" method="POST" action="" autocomplete="off">
		<input type="hidden" name="status" value="<?php echo \Flux\Campaign::CAMPAIGN_STATUS_ACTIVE ?>" />
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="description">Description</label>
			<div class="col-sm-10">
				<textarea id="description" name="description" class="form-control" placeholder="Enter descriptive text about where you will run this campaign"><?php echo $campaign->getDescription(); ?></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="client_id">Publisher Client</label>
			<div class="col-sm-10">
				<select class="form-control" name="client[client_id]" id="client_id" placeholder="Publisher Client">
					<?php
					/* @var $client \Flux\Client */
					foreach ($clients AS $client) { 
				?>
					<option value="<?php echo $client->getId(); ?>"<?php echo $campaign->getClient()->getClientId() == $client->getId() ? ' selected="selected"' : ''; ?>><?php echo $client->getName() ?></option>
				<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="offer_id">Offer</label>
			<div class="col-sm-10">
				<select class="form-control" name="offer[offer_id]" id="offer_id" placeholder="Offer">
					<?php
					/* @var $offer \Flux\Offer */ 
					foreach ($offers AS $offer) { 
				?>
					<option value="<?php echo $offer->getId(); ?>" <?php echo $campaign->getOffer()->getOfferId() == $offer->getId() ? 'selected' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('url' => $offer->getFormattedRedirectUrl()))) ?>"><?php echo $offer->getName() ?></option>
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

	var $offer_selectize = $('#offer_id').selectize();
	$offer_selectize[0].selectize.on('change', function() {
		$.each($offer_selectize[0].selectize.options, function(i, item) {
			if (item.value == $offer_selectize[0].selectize.getValue()) {
				$('#redirect_link').val(item.url);
			}
		});
	});
	$offer_selectize[0].selectize.trigger('change');
});
//-->
</script>