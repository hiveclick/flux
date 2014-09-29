<?php
    /* @var $offer Flux\Offer */
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
    $flows = $this->getContext()->getRequest()->getAttribute("flows", array());
    $verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
    $campaigns = $this->getContext()->getRequest()->getAttribute("campaigns", array());
?>
<div class="help-block">These are the main settings for this offer.</div>
<br/>
<form class="form-horizontal" name="offer_form" method="POST" action="" autocomplete="off">
    <input type="hidden" name="_id" value="<?php echo $offer->getId() ?>" />
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
        <div class="col-sm-10">
            <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $offer->getName() ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Verticals</label>
        <div class="col-sm-10">
            <select id="verticals" name="verticals[]" class="form-control selectize" require placeholder="Verticals" multiple>
                <?php foreach ($verticals as $vertical) { ?>
                    <option value="<?php echo $vertical->getName() ?>" <?php echo in_array($vertical->getName(), $offer->getVerticals()) ? "selected=\"SELECTED\"" : "" ?>><?php echo $vertical->getName() ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
        <div class="col-sm-10">
            <select class="form-control selectize" name="status" id="status" required placeholder="Status">
                <?php foreach(\Flux\Offer::retrieveStatuses() AS $status_id => $status_name) { ?>
                <option value="<?php echo $status_id; ?>"<?php echo $offer->getStatus() == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="client_id">Advertiser</label>
        <div class="col-sm-10">
            <select class="form-control selectize" name="client_id" id="client_id" required placeholder="Advertising Client">
                <?php foreach($clients AS $client) { ?>
                <option value="<?php echo $client->getId(); ?>"<?php echo $offer->getClientId() == $client->getId() ? ' selected' : ''; ?>><?php echo $client->getName(); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="default_campaign_id">Default Campaign</label>
        <div class="col-sm-10">
            <select class="form-control selectize" name="default_campaign_id" id="default_campaign_id" required placeholder="Default Campaign">
                <?php foreach($campaigns AS $campaign) { ?>
                <option value="<?php echo $campaign->getId(); ?>"<?php echo $offer->getDefaultCampaignId() == $campaign->getId() ? ' selected' : ''; ?>><?php echo $campaign->getDescription(); ?> (<?php echo $campaign->getClient()->getName() ?>)</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="payout">Payout</label>
        <div class="col-sm-10">
            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" id="payout" name="payout" class="form-control" required placeholder="Enter payout" value="<?php echo number_format($offer->getPayout(), 2) ?>" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="redirect_type">Redirect Type</label>
        <div class="col-sm-10">
            <select class="form-control selectize" name="redirect_type" id="redirect_type" required placeholder="Redirect Type">
                <?php foreach(\Flux\Offer::retrieveRedirectTypes() AS $redirect_type_id => $redirect_type_name) { ?>
                <option value="<?php echo $redirect_type_id; ?>"<?php echo $offer->getRedirectType() == $redirect_type_id ? ' selected' : ''; ?>><?php echo $redirect_type_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    
    <hr />
    <div id="hosted_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_HOSTED ? '': 'display:none;' ?>">
	    <div class="alert alert-info">
	        Use these settings to setup this offer on the remote server.  These settings should be unique for this offer so that tracking works
	    </div>
	    
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
	            <div class="input-group">
	                <input type="text" id="docroot_dir" name="docroot_dir" class="form-control" placeholder="Document root folder (/var/www/sites/...)" value="<?php echo $offer->getDocrootDir() ?>" />
	                <div class="input-group-btn">
	                    <a type="button" class="btn btn-info" href="/offer/offer-pane-push-to-server?_id=<?php echo $offer->getId() ?>" data-toggle="modal" data-target="#pushToServerModal">push to server</a>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	
	<div id="redirect_form_group" style="<?php echo $offer->getRedirectType() == \Flux\Offer::REDIRECT_TYPE_REDIRECT ? '': 'display:none;' ?>">
	
	</div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save" />
            <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Offer" />
        </div>
    </div>

</form>

<!-- Push offer to server modal -->
<div class="modal fade" id="pushToServerModal">
    <div class="modal-dialog">
        <div class="modal-content"></div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
//<!--
$(document).ready(function() {
    $('.selectize').selectize();

    $('#pushToServerModal').on('shown.bs.modal', function(e) {
        $('#modal_domain_name').val($('#domain_name').val());
        $('#modal_docroot_dir').val($('#docroot_dir').val());
        $('#modal_folder_name').val($('#folder_name').val());
    });

    $('#redirect_type').on('change', function() {
        if($(this).val() == <?php echo json_encode(\Flux\Offer::REDIRECT_TYPE_HOSTED); ?>) {
            $('#redirect_form_group').show();
            $('#hosted_form_group').hide();
        } else if($(this).val() == <?php echo json_encode(\Flux\Offer::REDIRECT_TYPE_REDIRECT); ?>) {
            $('#redirect_form_group').hide();
            $('#hosted_form_group').show();
        } else {
            $('#redirect_form_group').hide();
            $('#hosted_form_group').hide();
        }
    }).trigger('change');

    $('#btn_delete').click(function() {
        if (confirm('Are you sure you want to delete this offer and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/offer/offer/<?php echo $offer->getId() ?>' }, function(data) {
                $.rad.notify('Offer Removed', 'This offer has been removed from the system.');
                window.location.replace('/offer/offer-search');
            });
        }
    });
});
//-->
</script>