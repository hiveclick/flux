<?php
    /* @var $split \Gun\Split */
    $split = $this->getContext()->getRequest()->getAttribute("split", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/export/split-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Split</a>
    </div>
   <h2>Splits</h2>
</div>
<div class="help-block">Exports define how a client can receive data from a split</div>
<br/>
<table id="split_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Queue #</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<form id="split_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/export/split">
</form>
<script>
//<!--
$(document).ready(function() {
    $('#split_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#split_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/export/split?_id=' + rowData._id + '">' + cellData + '<div class="small text-muted">' + rowData.description + '</div></a>');
            }},
            { name: "status_name", data: "_status_name" },
            { name: "queue_count", data: "queue_count", sClass: "text-right", createdCell: function (td, cellData, rowData, row, col) {
            	if (cellData == '0') {
                    $(td).html('<span class="text-muted">' + $.number(cellData) + '</span>');
                } else {
                	$(td).html($.number(cellData));
                }
            }}
      	]
    });
});
//-->
</script>