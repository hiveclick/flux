<?php
    /* @var $campaign Flux\Campaign */
    $campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
    $offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<div id="header">
   <h2><a href="/campaign/campaign-search">Campaigns</a> <small>New Campaign</small></h2>
</div>
<div class="help-block">Associate an offer to a client to create a campaign to use for tracking traffic</div>
<br/>
<div id="tab-content-container" class="tab-content">
    <form class="form-horizontal" name="campaign_form" method="POST" action="" autocomplete="off">
        <input type="hidden" name="status" value="<?php echo \Flux\Campaign::CAMPAIGN_STATUS_ACTIVE ?>" />
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="description">Name</label>
            <div class="col-sm-10">
                <textarea id="description" name="description" class="form-control" placeholder="Enter Descriptive Name"><?php echo $campaign->getDescription(); ?></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="offer_id">Offer</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <select class="form-control" name="offer_id" id="offer_id" required placeholder="Offer">
                        <?php foreach($offers AS $offer_record) { ?>
                        <option value="<?php echo $offer_record->getId(); ?>"<?php echo $campaign->getOfferId() == $offer_record->getId() ? ' selected="selected"' : ''; ?>><?php echo $offer_record->getName() ?></option>
                        <?php } ?>
                    </select>
                    <div class="input-group-btn">
                        <a href="" target="_blank" class="btn btn-default" role="button">
                            <span class="glyphicon glyphicon-share"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="client_id">Publisher Client</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <select class="form-control" name="client_id" id="client_id" required placeholder="Publisher Client">
                        <?php foreach($clients AS $client_record) { ?>
                            <option value="<?php echo $client_record->getId(); ?>"<?php echo $campaign->getClientId() == $client_record->getId() ? ' selected="selected"' : ''; ?>><?php echo $client_record->getName() ?></option>
                        <?php } ?>
                    </select>
                    <div class="input-group-btn">
                        <a href="" target="_blank" class="btn btn-default" role="button">
                            <span class="glyphicon glyphicon-share"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" name="__save" class="btn btn-success" value="Create Campaign" />
            </div>
        </div>

    </form>
</div>