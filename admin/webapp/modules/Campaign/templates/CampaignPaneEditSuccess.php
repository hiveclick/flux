<?php
	/* @var $user Flux\User */
	$campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<div class="help-block">View this campaign and generate urls to use for tracking</div>
<br />
<form class="form-horizontal" id="campaign_form" name="campaign_form" method="PUT" action="/api" autocomplete="off">
	<input type="hidden" name="func" value="/campaign/campaign" />
	<input type="hidden" name="_id" value="<?php echo $campaign->getId() ?>" />
	
	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="description">Name</label>
		<div class="col-sm-10">
			<textarea id="description" name="description" class="form-control" placeholder="Enter Descriptive Name"><?php echo $campaign->getDescription(); ?></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs">Campaign Key</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" readonly value="<?php echo $campaign->getId() ?>" />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
		<div class="col-sm-10">
			<select class="form-control" name="status" id="status" required placeholder="Status">
				<?php foreach(\Flux\Campaign::retrieveStatuses() AS $status_id => $status_name) { ?>
					<option value="<?php echo $status_id; ?>"<?php echo $campaign->retrieveValue('status') == $status_id ? ' selected="selected"' : ''; ?>><?php echo $status_name; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>		  

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="client_id">Publisher Client</label>
		<div class="col-sm-10">
			<select class="form-control" name="client_id" id="client_id" required placeholder="Publisher Client">
				<?php
					/* @var $client \Flux\Client */
					foreach ($clients AS $client) { 
				?>
					<option value="<?php echo $client->getId(); ?>"<?php echo $campaign->getClientId() == $client->getId() ? ' selected="selected"' : ''; ?>><?php echo $client->getName() ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="offer_id">Offer</label>
		<div class="col-sm-10">
			<select class="form-control" name="offer_id" id="offer_id" required placeholder="Offer">
				<?php
					/* @var $offer \Flux\Offer */ 
					foreach ($offers AS $offer) { 
				?>
					<option value="<?php echo $offer->getId(); ?>"<?php echo $campaign->getOfferId() == $offer->getId() ? ' selected="selected"' : ''; ?> data-data="<?php echo htmlentities(json_encode(array('url' => $offer->getFormattedRedirectUrl()))) ?>"><?php echo $offer->getName() ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="redirect_link">Redirect Link</label>
		<div class="col-sm-10">
            <textarea name="redirect_link" rows="3" id="redirect_link" class="form-control"><?php echo $campaign->getRedirectLink() ?></textarea>
		</div>
	</div>
	
	<hr />
	
	<div class="alert alert-info">
		Use the link below in Adwords to direct traffic to this campaign.  You can customize this link on the instructions tab.
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="redirect_link">Adwords Url</label>
		<div class="col-sm-10">
			<textarea id="adwords_url" class="form-control"><?php echo (defined("MO_REALTIME_URL") ? MO_REALTIME_URL : "") . '/r?' . \Flux\DataField::DATA_FIELD_REF_CAMPAIGN_KEY . '=' . $campaign->getId() ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" name="__save" class="btn btn-success" value="Save" />
			<input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Campaign" />
		</div>
	</div>
</form>
	
<script>
//<!--
$(document).ready(function() {
	$('#client_id,#status').selectize();

	$('#campaign_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Campaign Updated', 'The campaign has been updated successfully');
		}
	},{keep_form: 1});

	var $offer_selectize = $('#offer_id').selectize();
	$offer_selectize[0].selectize.on('change', function() {
		$.each($offer_selectize[0].selectize.options, function(i, item) {
			if (item.value == $offer_selectize[0].selectize.getValue()) {
				$('#redirect_link').val(item.url);
			}
		});
	});
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		e.preventDefault();
		var hash = this.hash;
		if ($(this).attr("data-url")) {
			// only load the page the first time
			if ($(hash).html() == '') {
				// ajax load from data-url
				$(hash).load($(this).attr("data-url"));
			}
		}
	}).on('show.bs.tab', function (e) {
		try {
			sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
		} catch (err) { }
	});

	$('#btn_delete').click(function() {
		if (confirm('Are you sure you want to delete this campaign and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/campaign/campaign/<?php echo $campaign->getId() ?>' }, function(data) {
				$.rad.notify('Campaign Removed', 'This campaign has been removed from the system.');
			});
		}
	});

	// Store the last clicked tab so it can be loaded on page refreshes
	var localTabStorageName = <?php echo json_encode('campaign_tab_' . $campaign->getId()); ?>;
	var lastTab = sessionStorage.getItem(localTabStorageName);
	if (lastTab) {
		$('a[href='+lastTab+']').tab('show');
	} else {
		$('ul.nav-pills a:first').tab('show');
	}
});
//-->
</script>