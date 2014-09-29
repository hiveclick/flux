<?php
    /* @var $reportColumn Flux\ReportColumn */
    $reportColumn = $this->getContext()->getRequest()->getAttribute("report_column", array());
?>
<div id="header">
   <h2><a href="/admin/report-column-search">Report Columns</a> <small>New Report Column</small></h2>
</div>
<div class="help-block">Report columns define which data fields can be aggregated in reports</div>
<br/>
<form class="form-horizontal" name="report_column_form" method="POST" action="" autocomplete="off" role="form">

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
        <div class="col-sm-10">
            <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $reportColumn->retrieveValue('name'); ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
        <div class="col-sm-10">
            <select class="form-control" name="status" id="status" required placeholder="Status">
                <?php foreach(\Flux\ReportColumn::retrieveStatuses() AS $status_id => $status_name) { ?>
                <option value="<?php echo $status_id; ?>"<?php echo $reportColumn->retrieveValue('status') == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="type">Type</label>
        <div class="col-sm-10">
            <select class="form-control" name="type" id="type" required placeholder="Type">
                <?php foreach(\Flux\ReportColumn::retrieveColumnTypes() AS $type_id => $type_name) { ?>
                <option value="<?php echo $type_id; ?>"<?php echo $reportColumn->retrieveValue('type') == $type_id ? ' selected' : ''; ?>><?php echo $type_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label hidden-xs" for="format_type">Format Type</label>
        <div class="col-sm-10">
            <select class="form-control" name="format_type" id="format_type" required placeholder="Format Type">
                <?php foreach(\Flux\ReportColumn::retrieveFormatTypes() AS $format_type_id => $format_type_name) { ?>
                <option value="<?php echo $format_type_id; ?>"<?php echo $reportColumn->retrieveValue('format_type') == $format_type_id ? ' selected' : ''; ?>><?php echo $format_type_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save" />
        </div>
    </div>
</form>