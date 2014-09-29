<?php
    /* @var $export \Flux\Export */
    $client_export = $this->getContext()->getRequest()->getAttribute("client_export", array());
?>
<div class="help-block">Exports that have been sent to this client recently</div>
<br/>
<table id="export_table" class="table table-hover table-bordered table-striped table-responsive table-condensed">
    <thead>
        <tr>
        	<th>Id</th>
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
<form id="export_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/export/export">
    <input type="hidden" name="client_export_id" value="<?php echo $client_export->getId() ?>">
    <input type="hidden" name="sord" value="desc">
    <input type="hidden" name="sort" value="export_date">
</form>
<script>
//<!--
$(document).ready(function() {
    $('#export_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#export_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/export/export?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
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
            	if (cellData == 100) {
                	var div = '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" style="width: ' + ((rowData.num_records_successful/rowData.num_records)*100) + '%;"></div><div class="progress-bar progress-bar-danger" role="progressbar" style="width: ' + ((rowData.num_records_error/rowData.num_records)*100) + '%;"></div></div>';
                } else {
            		var div = '<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' + cellData + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + cellData + '%;"></div></div>';
                }
                $(td).html(div);
            }},
            { name: "export_date", data: "export_date", sClass: 'text-center', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(moment.unix(cellData.sec).calendar());
            }},
            { name: "num_records", data: "num_records", sClass: 'text-right', createdCell: function (td, cellData, rowData, row, col) {
                $(td).html($.number(cellData));
            }}
      	]
    });
});
//-->
</script>