<?php
    /* @var $user Gun\User */
    $campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
    $offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<div id="header">
    <div class="pull-right visible-xs">
        <button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <h2><a href="/campaign/campaign-search">Campaigns</a> <small><?php echo $campaign->getClient()->getName() ?> &ndash; <?php echo $campaign->getOffer()->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
    <ul id="campaign_tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Campaign</a></li>
        <li><a id="tabs-a-reports" href="#tabs-reports" data-toggle="tab">Reports</a></li>
        <li><a id="tabs-a-instructions" href="#tabs-instructions" data-toggle="tab" data-url="/campaign/campaign-pane-instruction?_id=<?php echo $campaign->getId() ?>">Instructions</a></li>
    </ul>
</div>
<div id="tab-content-container" class="tab-content">
    <div id="tabs-main" class="tab-pane active">
        <div class="help-block">View this campaign and generate urls to use for tracking</div>
        <br />
        <form class="form-horizontal" name="campaign_form" method="PUT" action="" autocomplete="off">
            <input type="hidden" name="_id" value="<?php echo $campaign->getId() ?>" />
            
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
                            <a href="/offer/offer?_id=<?php echo $campaign->getOfferId() ?>" target="_blank" class="btn btn-default" role="button">
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
                            <a href="/client/client?_id=<?php echo $campaign->getClientId() ?>" target="_blank" class="btn btn-default" role="button">
                                <span class="glyphicon glyphicon-share"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
                <div class="col-sm-10">
                    <select class="form-control" name="status" id="status" required placeholder="Status">
                        <?php foreach(\Gun\Campaign::retrieveStatuses() AS $status_id => $status_name) { ?>
                        <option value="<?php echo $status_id; ?>"<?php echo $campaign->retrieveValue('status') == $status_id ? ' selected="selected"' : ''; ?>><?php echo $status_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
    
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="__save" class="btn btn-success" value="Save" />
                    <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Campaign" />
                </div>
            </div>
        </form>
    </div>
    <div id="tabs-instructions" class="tab-pane"></div>
    <div id="tabs-reports" class="tab-pane"></div>
</div>
<script>
//<!--
$(document).ready(function() {
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