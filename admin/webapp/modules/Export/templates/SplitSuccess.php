<?php
    /* @var $split Flux\Split */
    $split = $this->getContext()->getRequest()->getAttribute("split", array());
    $offers = $this->getContext()->getRequest()->getAttribute("offers", array());
    $verticals = $this->getContext()->getRequest()->getAttribute("verticals", array());
    $domain_groups = $this->getContext()->getRequest()->getAttribute("domain_groups", array());
    $data_fields = $this->getContext()->getRequest()->getAttribute("data_fields", array());
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
    <h2><a href="/export/split-search">Splits</a> <small><?php echo $split->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse" class="navbar-collapse collapse">
    <ul id="split_tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Split</a></li>
        <li><a id="tabs-a-positions" href="#tabs-positions" data-toggle="tab" data-url="/export/split-pane-position?_id=<?php echo $split->getId() ?>">Positions</a></li>
        <li><a id="tabs-a-process" href="#tabs-process" data-toggle="tab" data-url="/export/split-pane-pid?_id=<?php echo $split->getId() ?>">Processes</a></li>
        <li><a id="tabs-a-report" href="#tabs-report" data-toggle="tab">Report</a></li>
        <li><a id="tabs-a-spy" href="#tabs-spy" data-toggle="tab" data-url="/export/split-pane-spy?_id=<?php echo $split->getId() ?>">Spy</a></li>
    </ul>
</div>
<div id="tab-content-container" class="tab-content">
    <div id="tabs-main" class="tab-pane active">
        <div class="help-block">Manage a split and it's rules that determine what exports receive data</div>
        <br/>
        <form class="form-horizontal" name="split_form" method="POST" action="" autocomplete="off" role="form">
               <input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
            <div class="form-group">
                <label class="col-md-2 control-label" for="name">Name</label>
                <div class="col-md-10">
                    <input type="text" id="name" name="name" class="form-control" required="required" value="<?php echo $split->getName() ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="name">Description</label>
                <div class="col-md-10">
                    <textarea name="description" id="description" class="form-control" placeholder="Enter Description..." required><?php echo $split->getDescription() ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="status">Status</label>
                <div class="col-md-10">
                    <select class="form-control" name="status" id="status" required>
                        <?php foreach(\Flux\Split::retrieveStatuses() AS $status_id => $status_name) { ?>
                        <option value="<?php echo $status_id; ?>"<?php echo $split->getStatus() == $status_id ? ' selected="selected"' : ''; ?>><?php echo $status_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-offset-2">
                <hr />
                <div class="help-block">Select filters below to define how this split will run</div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="vertical_id">Verticals</label>
                <div class="col-md-10">
                    <select class="form-control" name="vertical_id[]" id="vertical_id" multiple required placeholder="all verticals">
                        <?php foreach($verticals AS $vertical) { ?>
                            <option value="<?php echo $vertical->getId(); ?>"<?php echo in_array($vertical->getId(), $split->getVerticalId()) ? ' selected="selected"' : ''; ?>><?php echo $vertical->getName() ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="offer_id ">Offers</label>
                <div class="col-md-10">
                    <select class="form-control" name="offer_id[]" id="offer_id" required multiple placeholder="all offers">
                        <?php foreach($offers AS $offer) { ?>
                            <option value="<?php echo $offer->getId(); ?>"<?php echo in_array($offer->getId(), $split->getOfferId()) ? ' selected="selected"' : ''; ?>><?php echo $offer->getName() ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="domain_id">Domains</label>
                <div class="col-md-10">
                	<input type="hidden" name="domain_group_id[]" value="" />
                    <select class="form-control" name="domain_group_id[]" id="domain_group_id" required multiple placeholder="all domains">
                        <?php foreach($domain_groups AS $domain_group) { ?>
                            <option value="<?php echo $domain_group->getId(); ?>"<?php echo in_array($domain_group->getId(), $split->getDomainGroupId()) ? ' selected="selected"' : ''; ?>><?php echo $domain_group->getName() ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="domain_id ">Required Fields</label>
                <div class="col-md-10">
                    <select class="form-control" name="data_field_id[]" id="data_field_id" required multiple placeholder="no fields required">
                        <?php foreach($data_fields AS $data_field) { ?>
                            <option value="<?php echo $data_field->getId(); ?>"<?php echo in_array($data_field->getId(), $split->getDataFieldId()) ? ' selected="selected"' : ''; ?>><?php echo $data_field->getName() ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <input type="submit" name="save" class="btn btn-success" value="Save" />
                    <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Split" />
                </div>
            </div>
        </form>
    </div>
    <div id="tabs-positions" class="tab-pane"></div>
    <div id="tabs-process" class="tab-pane"></div>
    <div id="tabs-report" class="tab-pane"></div>
    <div id="tabs-spy" class="tab-pane"></div>
</div>
<script>
//<!--
$(document).ready(function() {
    $('#vertical_id').selectize();
    $('#domain_group_id').selectize();
    $('#offer_id').selectize();
    $('#data_field_id').selectize();

    $('#source').change(function() {
        if ($('#source').val() == '1') {
            $('#vertical_source').removeClass('hidden');
            $('#offer_source').addClass('hidden');
        } else {
            $('#vertical_source').addClass('hidden');
            $('#offer_source').removeClass('hidden');
        }
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
        if (confirm('Are you sure you want to delete this split and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/export/split/<?php echo $split->getId() ?>' }, function(data) {
                $.rad.notify('Split Removed', 'This split has been removed from the system.');
            });
        }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('split_tab_' . $split->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (lastTab) {
        $('a[href='+lastTab+']').tab('show');
    } else {
        $('ul.nav-pills a:first').tab('show');
    }
});
//-->
</script>