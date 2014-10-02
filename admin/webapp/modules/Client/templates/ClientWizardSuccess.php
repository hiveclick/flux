<?php
    /* @var $client Flux\Client */
    $client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div id="header">
   <h2><a href="/client/client-search">Clients</a> <small>New Client</small></h2>
</div>
<div class="help-block">Clients are used as advertisers or publishers to either manage offers or send traffic</div>
<br/>
<div id="tab-content-container" class="tab-content">
    <form class="form-horizontal" name="client_form" method="POST" action="" autocomplete="off">
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
            </div>
        </div>

    </form>
</div>
<script>
//<!--
$(document).ready(function() {
	$('#type,#status').selectize();
});
//-->
</script>