<?php
    /* @var $client Flux\Client */
    $client = $this->getContext()->getRequest()->getAttribute("client", array());
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
    <h2><a href="/client/client-search">Clients</a> <small><?php echo $client->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
    <ul id="client_tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Client</a></li>
        <li class="dropdown">
            <a type="button" class="dropdown-toggle" data-toggle="dropdown">Users <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a id="tabs-a-users" href="#tabs-users" data-toggle="tab" data-url="/client/client-pane-user?_id=<?php echo $client->getId() ?>">View Users</a></li>
                <li><a id="tabs-a-newuser" href="/admin/user-wizard?client_id=<?php echo $client->getId() ?>">Add New User</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a type="button" class="dropdown-toggle" data-toggle="dropdown">Advertising Offers <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a id="tabs-a-offers" href="#tabs-offers" data-toggle="tab" data-url="/client/client-pane-offer?_id=<?php echo $client->getId() ?>">View Advertising Offers</a></li>
                <li><a id="tabs-a-newoffer" href="/offer/offer-wizard?client_id=<?php echo $client->getId() ?>">Add New Offer</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a type="button" class="dropdown-toggle" data-toggle="dropdown">Campaigns <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a id="tabs-a-campaigns" href="#tabs-campaigns" data-toggle="tab" data-url="/client/client-pane-campaign?_id=<?php echo $client->getId() ?>">View Campaigns</a></li>
                <li><a id="tabs-a-newcampaign" href="/campaign/campaign-wizard?client_id=<?php echo $client->getId() ?>">Add New Campaign</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a type="button" class="dropdown-toggle" data-toggle="dropdown">Exports <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a id="tabs-a-exports" href="#tabs-exports" data-toggle="tab" data-url="/client/client-pane-export?_id=<?php echo $client->getId() ?>">View Exports </a></li>
                <li><a id="tabs-a-newexport" href="/client/client-export-wizard?client_id=<?php echo $client->getId() ?>">Add New Export</a></li>
            </ul>
        </li>
    </ul>
</div>
<div id="tab-content-container" class="tab-content">
    <div id="tabs-main" class="tab-pane active">
        <div class="help-block">Clients can own offers, campaigns, and exports</div>
        <br/>
        <form class="form-horizontal" name="client_form" method="POST" action="" autocomplete="off">
            <input type="hidden" name="_id" value="<?php echo $client->getId() ?>" />
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $client->getName() ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="type">Client Type</label>
                <div class="col-sm-10">
                    <select class="form-control" name="type" id="type" required placeholder="Client Type">
                        <?php foreach(\Flux\Client::retrieveClientTypes() AS $type_id => $type_name) { ?>
                        <option value="<?php echo $type_id; ?>"<?php echo $client->getClientType() == $type_id ? ' selected' : ''; ?>><?php echo $type_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
                <div class="col-sm-10">
                    <select class="form-control" name="status" id="status" required placeholder="Status">
                        <?php foreach(\Flux\Client::retrieveStatuses() AS $status_id => $status_name) { ?>
                        <option value="<?php echo $status_id; ?>"<?php echo $client->getStatus() == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="email">Email</label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo $client->getEmail() ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="pixel">Global Client Pixel</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="text" id="pixel" name="pixel" class="form-control" placeholder="Global Client Pixel" value="<?php echo $client->getPixel() ?>" />
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dataFieldModal">
                                <span class="glyphicon glyphicon-info-sign"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="__save" class="btn btn-success" value="Save" />
                    <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Client" />
                </div>
            </div>

        </form>
    </div>
    <div id="tabs-users" class="tab-pane"></div>
    <div id="tabs-offers" class="tab-pane"></div>
    <div id="tabs-campaigns" class="tab-pane"></div>
    <div id="tabs-exports" class="tab-pane"></div>
</div>
<script>
//<!--
$(document).ready(function() {
	$('#status,#type').selectize();
	
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
        if (confirm('Are you sure you want to delete this client and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/client/client/<?php echo $client->getId() ?>' }, function(data) {
                $.rad.notify('Client Removed', 'This client has been removed from the system.');
            });
        }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('client_tab_' . $client->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (lastTab) {
        $('a[href='+lastTab+']').tab('show');
    } else {
        $('ul.nav-pills a:first').tab('show');
    }
});
//-->
</script>