<?php
    /* @var $export \Gun\Export */
    $export = $this->getContext()->getRequest()->getAttribute("export", array());
?>
<div class="help-block">View a sample of the data included in this export</div>
<br/>
<table id="export_queue_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Queue #</th>
            <th>Url</th>
            <th>Name</th>
            <th>Email</th>
            <th>Last Attempt</th>
            <th>Response</th>
            <th>Error</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<form id="export_queue_search_form" method="GET" action="/api">
    <input type="hidden" name="items_per_page" value="100">
    <input type="hidden" name="func" value="/export/export-queue">
    <input type="hidden" name="export_id" value="<?php echo $export->getId() ?>">
</form>
<script>
//<!--
$(document).ready(function() {

    $('#export_queue_search_form').on('submit', function(e) {
        $('#export_queue_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });
	
    $('#export_queue_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#export_queue_search_form').serializeObject();
            }
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/export/export-queue?export_id=<?php echo $export->getId() ?>&_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "url", data: "url", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html(cellData);
            }},
            { name: "name", data: "qs", createdCell: function (td, cellData, rowData, row, col) {
                if (rowData.qs.fn != undefined) {
                    $(td).html(rowData.qs.fn + ' ' + rowData.qs.ln);
                } else {
                	$(td).html('<i class="text-muted">missing</i>');
                }
            }},
            { name: "email", data: "qs", createdCell: function (td, cellData, rowData, row, col) {
            	if (rowData.qs.em != undefined) {
                    $(td).html(rowData.qs.em);
                } else {
                	$(td).html('<i class="text-muted">missing</i>');
                }
            }},
            { name: "last_sent_time", data: "last_sent_time", createdCell: function (td, cellData, rowData, row, col) {
                if (cellData == 0) {
                	$(td).html('<i class="text-muted">not sent yet</i>');
                } else {
                    $(td).html(moment.unix(cellData.sec).calendar());
                }
            }},
            { name: "response", data: "response", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html(cellData);
            }},
            { name: "is_error", data: "is_error", createdCell: function (td, cellData, rowData, row, col) {
                if (cellData) {
             	   $(td).html('<span class="text-danger">Yes</span>');
                } else {
                	$(td).html('');
                }
            }}
      	]
    });
});
//-->
</script>
