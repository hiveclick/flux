<?php
    /* @var $export \Gun\Export */
    $export = $this->getContext()->getRequest()->getAttribute("export", array());
    $client_exports = $this->getContext()->getRequest()->getAttribute("client_exports", array());
    $splits = $this->getContext()->getRequest()->getAttribute("splits", array());
?>
<div id="header">
   <h2>Exports</h2>
</div>
<div class="help-block">Exports define how a client can receive data from a split</div>
<br/>
<div class="panel-group" id="accordion">
    <div class="panel panel-default" style="overflow:visible;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Search Filters</a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <form id="export_search_form" method="GET" action="/api">
                    <input type="hidden" name="items_per_page" value="100">
                    <input type="hidden" name="func" value="/export/export">
                    <input type="hidden" name="sord" value="desc">
                    <input type="hidden" name="sort" value="export_date">
                    <div class="form-group">
                        <label class="control-label hidden-xs" for="name">Split</label>
                        <div class="">
                            <select class="form-control selectize" name="split_id_array[]" id="split_id" multiple placeholder="All Splits">
                                <?php foreach($splits as $split) { ?>
                                    <option value="<?php echo $split->getId() ?>"><?php echo $split->getName() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label hidden-xs" for="name">Client</label>
                        <div class="">
                            <select class="form-control selectize" name="client_export_id_array[]" id="client_export_id" multiple placeholder="All Exports">
                                <?php foreach($client_exports as $client_export) { ?>
                                    <option value="<?php echo $client_export->getId() ?>"><?php echo $client_export->getName() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-info" name="btn_submit" value="filter results" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<table id="export_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Split</th>
            <th>Client</th>
            <th>Progress</th>
            <th>Date</th>
            <th># Records</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
//<!--
$(document).ready(function() {
    $('#split_id,#client_export_id').selectize();

    $('#export_search_form').on('submit', function(e) {
        $('#export_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });
	
    $('#export_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#export_search_form').serializeObject();
            }
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/export/export?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "_split_name", data: "_split_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/export/split?_id=' + rowData.split_id + '">' + cellData + '</a>');
            }},
            { name: "_client_export_name", data: "_client_export_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData._client_id + '">' + cellData + ' (' + rowData._client_name + ')</a>');
            }},
            { name: "percent_complete", data: "percent_complete", createdCell: function (td, cellData, rowData, row, col) {
            	var div = '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' + cellData + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + cellData + '%;"></div></div>';
                
                $(td).html(div);
            }},
            { name: "export_date", data: "export_date", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(moment.unix(cellData.sec).calendar());
            }},
            { name: "num_records", data: "num_records", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html($.number(cellData));
            }}
      	]
    });
});
//-->
</script>