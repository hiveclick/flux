<?php
    /* @var $export Gun\Export */
    $export = $this->getContext()->getRequest()->getAttribute("export", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
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
    <h2><a href="/export/export-search">Exports</a> <small><?php echo $export->getSplitName() ?> on <?php echo date('m/d/Y', $export->getExportDate()->sec) ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
    <ul id="export_tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Export</a></li>
        <li><a id="tabs-a-log" href="#tabs-log" data-toggle="tab" data-url="/export/export-pane-log?_id=<?php echo $export->getId() ?>">Log</a></li>
        <li><a id="tabs-a-spy" href="#tabs-spy" data-toggle="tab" data-url="/export/export-pane-spy?_id=<?php echo $export->getId() ?>">Spy</a></li>
    </ul>
</div>
<div id="tab-content-container" class="tab-content">
    <div id="tabs-main" class="tab-pane active">
        <div class="help-block">View the progress of this export and a sample of the data in the queue</div>
        <br/>
        <div style="width:100%;" class="clearfix small">
            <div style="width:20%;border-Right:2px solid gray;float:left;padding:2px;">
                Preparing
                <?php if (is_object($export->getStartTime())) { ?>
                    <br /><?php echo date('m/d/Y g:i a', $export->getStartTime()->sec) ?>
                <?php } ?>
            </div>
            <div class="text-center" style="width:30%;border-Right:2px solid gray;float:left;padding:2px;">
                Finding Records
                <?php if ($export->getFindingRecordsTime() > 0) { ?>
                    <br /><?php echo number_format($export->getFindingRecordsTime(), 2, null, ',') ?> seconds
                <?php } ?>
            </div>
            <div class="text-center" style="width:40%;border-Right:2px solid gray;float:left;padding:2px;">
                Sending
                <?php if ($export->getSendingRecordsTime() > 0) { ?>
                    <br /><?php echo number_format($export->getSendingRecordsTime(), 2, null, ',') ?> seconds
                <?php } ?>
            </div>
            <div class="text-right" style="width:10%;float:left;padding:2px;">
                Finished
                <?php if (is_object($export->getEndTime())) { ?>
                    <br /><?php echo date('m/d/Y g:i a', $export->getEndTime()->sec) ?>
                <?php } ?>
            </div>
        </div>
        <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo number_format($export->getPercentComplete(), 0) ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo number_format($export->getPercentComplete(),0) ?>%;"></div>
        </div>
        
       
        <h2><?php echo number_format($export->getNumRecords(), 0, null, ',') ?> records</h2>
        Split: <a href="/export/split?_id=<?php echo $export->getSplitId() ?>"><?php echo $export->getSplitName() ?></a><p />
        Client: <a href="/client/client?_id=<?php echo $export->getClientId() ?>"><?php echo $export->getClientName() ?></a>
        <p />
        Created: <?php echo date('m/d/Y g:i a', $export->getCreated()->sec) ?><p />
    </div>
    <div id="tabs-log" class="tab-pane"></div>
    <div id="tabs-spy" class="tab-pane"></div>
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
        if (confirm('Are you sure you want to delete this export and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/export/export/<?php echo $export->getId() ?>' }, function(data) {
                $.rad.notify('Export Removed', 'This export has been removed from the system.');
            });
        }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('export_tab_' . $export->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (lastTab) {
        $('a[href='+lastTab+']').tab('show');
    } else {
        $('ul.nav-pills a:first').tab('show');
    }
});
//-->
</script>