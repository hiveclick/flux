<?php
    /* @var $offer Gun\Offer */
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
    $flows = $this->getContext()->getRequest()->getAttribute("flows", array());
    $verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());

    $conversion_data_field = \Gun\DataField::retrieveDataFieldFromKeyName(\Gun\DataField::DATA_FIELD_EVENT_CONVERSION_NAME);
?>
<div id="header">
    <h2><a href="/offer/offer-search">Offers</a> <small>New Offer</small></h2>
</div>
<div class="help-block">Use this form to add a new offer to the system assigned to an advertiser</div>
<br/>
<form class="form-horizontal" name="offer_form" method="POST" action="" autocomplete="off">
    <input type="hidden" name="status" value="<?php echo \Gun\Offer::OFFER_STATUS_ACTIVE ?>" />

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
                    <option value="<?php echo $vertical->getName() ?>" <?php echo in_array($vertical->getId(), $offer->getVerticals()) ? "selected=\"SELECTED\"" : "" ?>><?php echo $vertical->getName() ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="client_id">Advertising Client</label>
        <div class="col-sm-10">
            <div class="input-group">
                <select class="form-control" name="client_id" id="client_id" required placeholder="Advertising Client">
                    <?php foreach($clients AS $client) { ?>
                    <option value="<?php echo $client->getId(); ?>"<?php echo $offer->getClientId() == $client->getId() ? ' selected' : ''; ?>><?php echo $client->getName(); ?> (<?php echo $client->getId() ?>)</option>
                    <?php } ?>
                </select>
                <div class="input-group-btn">
                    <a href="" target="_blank" class="btn btn-info" role="button">
                        <span class="glyphicon glyphicon-share"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="payout">Payout</label>
        <div class="col-sm-10">
            <div class="input-group">
                <input type="hidden" name="events[0][event_id]" value="<?php echo !is_null($conversion_data_field) ? $conversion_data_field->getId() : 0 ?>" />
                <input type="hidden" name="events[0][modifier_id]" value="<?php echo \Gun\DataField::DATA_FIELD_MODIFIER_SET ?>" />
                <input type="hidden" name="events[0][field]" value="payout" />
                <span class="input-group-addon">$</span>
                <input type="text" name="events[0][value]" id="payout" class="form-control" value="<?php echo number_format($offer->getPayout(), 2) ?>">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="revenue">Revenue</label>
        <div class="col-sm-10">
            <div class="input-group">
                <input type="hidden" name="events[1][event_id]" value="<?php echo !is_null($conversion_data_field) ? $conversion_data_field->getId() : 0 ?>" />
                <input type="hidden" name="events[1][modifier_id]" value="<?php echo \Gun\DataField::DATA_FIELD_MODIFIER_SET ?>" />
                <input type="hidden" name="events[1][field]" value="revenue" />
                <span class="input-group-addon">$</span>
                <input type="text" name="events[1][value]" id="revenue" class="form-control" value="0.00">
            </div>
        </div>
    </div>

    <div class="help-block">Choose if you want to direct traffic to a flow or to an external url</div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="redirect_type">Redirect Type</label>
        <div class="col-sm-10">
            <select class="form-control" name="redirect_type" id="redirect_type" required placeholder="Redirect Type">
                <?php foreach(\Gun\Offer::retrieveRedirectTypes() AS $redirect_type_id => $redirect_type_name) { ?>
                <option value="<?php echo $redirect_type_id; ?>"<?php echo $offer->getRedirectType() == $redirect_type_id ? ' selected' : ''; ?>><?php echo $redirect_type_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group"  id="redirect_url_form_group">
        <label class="col-sm-2 control-label hidden-xs" for="redirect_url">Redirect URL</label>
        <div class="col-sm-10">
            <div class="input-group">
                <input type="text" id="redirect_url" name="redirect_url" class="form-control" placeholder="Redirect URL" value="<?php echo $offer->getRedirectUrl() ?>" />
                <div class="input-group-btn">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataFieldModal">
                        <span class="glyphicon glyphicon-info-sign"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" id="ref_flow_form_group">
        <label class="col-sm-2 control-label hidden-xs" for="flow_id">Redirect Flow</label>
        <div class="col-sm-10">
            <select class="form-control" name="flow_id" id="flow_id" placeholder="Redirect Flow">
                <?php foreach ($flows AS $flow) { ?>
                <option value="<?php echo $flow->getId(); ?>"<?php echo $offer->getFlowId() == $flow->getId() ? ' selected' : ''; ?>><?php echo $flow->getName() ?> (<?php echo $flow->getId() ?>)</option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group" id="folder_name_form_group">
        <label class="col-sm-2 control-label hidden-xs" for="folder_name">Folder Name</label>
        <div class="col-sm-10">
            <input type="text" id="folder_name" name="folder_name" class="form-control" placeholder="Enter folder name on server..." value="<?php echo $offer->getFolderName() ?>" />
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
    $('#verticals').selectize();

    $('#redirect_type').on('change', function() {
        if($(this).val() == <?php echo json_encode(\Gun\Offer::REDIRECT_TYPE_OFFER); ?>) {
            $('#redirect_url_form_group').show();
            $('#ref_flow_form_group').hide();
        } else if($(this).val() == <?php echo json_encode(\Gun\Offer::REDIRECT_TYPE_FLOW); ?>) {
            $('#redirect_url_form_group').hide();
            $('#ref_flow_form_group').show();
        } else {
            $('#redirect_url_form_group').hide();
            $('#ref_flow_form_group').hide();
        }
    }).trigger('change');
});
//-->
</script>