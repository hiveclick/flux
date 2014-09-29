<?php
    /* @var $report_column Flux\ReportColumn */
    $report_column = $this->getContext()->getRequest()->getAttribute("report_column", array());
    $report_columns = $this->getContext()->getRequest()->getAttribute("report_columns", array());
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
    <h2><a href="/admin/report-column-search">Report Columns</a> <small><?php echo $report_column->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
    <ul id="report-column-tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Report Column</a></li>
        <li><a id="tabs-a-set" href="#tabs-set" data-toggle="tab">Set</a></li>
    </ul>
</div>
<div id="tab-content-container" class="tab-content">
   <div id="tabs-main" class="tab-pane active">
        <div class="help-block">Set options for this report column on how it can be used in reports</div>
        <br />
        <form class="form-horizontal" name="report_column_form" method="POST" action="" autocomplete="off" role="form">
            <input type="hidden" name="_id" value="<?php echo $report_column->getId() ?>" />
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $report_column->retrieveValue('name'); ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
                <div class="col-sm-10">
                    <select class="form-control" name="status" id="status" required placeholder="Status">
                        <?php foreach(\Flux\ReportColumn::retrieveStatuses() AS $status_id => $status_name) { ?>
                        <option value="<?php echo $status_id; ?>"<?php echo $report_column->retrieveValue('status') == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="type">Type</label>
                <div class="col-sm-10">
                    <select class="form-control" name="column_type" id="column_type" required placeholder="Type">
                        <?php foreach(\Flux\ReportColumn::retrieveColumnTypes() AS $type_id => $type_name) { ?>
                        <option value="<?php echo $type_id; ?>"<?php echo $report_column->getColumnType() == $type_id ? ' selected' : ''; ?>><?php echo $type_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="format_type">Format Type</label>
                <div class="col-sm-10">
                    <select class="form-control" name="format_type" id="format_type" required placeholder="Format Type">
                        <?php foreach(\Flux\ReportColumn::retrieveFormatTypes() AS $format_type_id => $format_type_name) { ?>
                        <option value="<?php echo $format_type_id; ?>"<?php echo $report_column->retrieveValue('format_type') == $format_type_id ? ' selected' : ''; ?>><?php echo $format_type_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div id="parameters_<?php echo \Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_DATAFIELD ?>" class="<?php echo $report_column->getColumnType() == \Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_DATAFIELD ? "" : "hidden" ?>">
                <div class="form-group">
                    <label class="col-sm-2 control-label hidden-xs" for="sum_type">Sum Type</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="sum_type" id="sum_type" required placeholder="Sum Type">
                            <?php foreach(\Flux\ReportColumn::retrieveSumTypes() AS $sum_type_id => $sum_type_name) { ?>
                            <option value="<?php echo $sum_type_id; ?>"<?php echo $report_column->retrieveValue('sum_type') == $sum_type_id ? ' selected' : ''; ?>><?php echo $sum_type_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label hidden-xs" for="parameter">Data Fields</label>
                    <div class="col-sm-10">
                        <select class="form-control selectize" multiple name="parameters[]" id="parameter_datafield" required placeholder="Data Fields">
                            <?php foreach(\Flux\DataField::retrieveActiveDataFields() AS $activeDataField) { ?>
                                <option value="<?php echo $activeDataField->retrieveValueHtml('_id'); ?>"<?php echo in_array($activeDataField->retrieveValue('_id'), $report_column->getParameters()) ? ' selected' : ''; ?>><?php echo $activeDataField->retrieveValueHtml('name'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div id="parameters_<?php echo \Flux\ReportColumn::REPORT_COLUMN_TYPE_CALCULATION ?>" class="<?php echo $report_column->getColumnType() == \Flux\ReportColumn::REPORT_COLUMN_TYPE_CALCULATION ? "" : "hidden" ?>">
                <div class="form-group">
                    <label class="col-sm-2 control-label hidden-xs" for="operator_type">Operator Type</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="operator_type" id="operator_type" required placeholder="Operator Type">
                            <?php foreach(\Flux\ReportColumn::retrieveOperatorTypes() AS $operator_type_id => $operator_type_name) { ?>
                            <option value="<?php echo $operator_type_id; ?>"<?php echo $report_column->retrieveValue('operator_type') == $operator_type_id ? ' selected' : ''; ?>><?php echo $operator_type_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label hidden-xs" for="parameter">Report Columns</label>
                    <div class="col-sm-10">
                        <select class="form-control selectize" multiple name="parameters[]" id="parameter_report_column" required placeholder="Report Columns">
                            <?php foreach ($report_columns AS $report_columnItem) { ?>
                                <option value="<?php echo $report_columnItem->retrieveValueHtml('_id'); ?>" <?php echo in_array($report_columnItem->retrieveValueHtml('_id'), $report_column->getParameters()) ? "SELECTED" : "" ?>><?php echo $report_columnItem->retrieveValueHtml('name'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="__save" class="btn btn-success" value="Save" />
                    <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Report Column" />
                </div>
            </div>
        </form>
   </div>
   <div id="tabs-set" class="tab-pane"></div>
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

    $('#column_type').on('change', function() {
        if ($('#column_type').val() == '<?php echo \Flux\ReportColumn::REPORT_COLUMN_TYPE_CALCULATION ?>') {
            $('#parameters_<?php echo \Flux\ReportColumn::REPORT_COLUMN_TYPE_CALCULATION ?>').removeClass('hidden');
            $('#parameters_<?php echo \Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_DATAFIELD ?>').addClass('hidden');
        } else {
        	$('#parameters_<?php echo \Flux\ReportColumn::REPORT_COLUMN_TYPE_CALCULATION ?>').addClass('hidden');
            $('#parameters_<?php echo \Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_DATAFIELD ?>').removeClass('hidden');
        }
    });

    $('#parameter_datafield').selectize();
    $('#parameter_report_column').selectize();

    $('#btn_delete').click(function() {
        if (confirm('Are you sure you want to delete this report column and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/admin/report-column/<?php echo $report_column->getId() ?>' }, function(data) {
                $.rad.notify('Report Column Removed', 'This report column has been removed from the system.');
            });
        }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('report_column_tab_' . $report_column->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (lastTab) {
        $('a[href='+lastTab+']').tab('show');
    } else {
        $('ul.nav-pills a:first').tab('show');
    }
});
//-->
</script>