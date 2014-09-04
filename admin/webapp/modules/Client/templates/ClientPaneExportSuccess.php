<?php
    /* @var $client \Gun\Client */
    $client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div class="help-block">Define how this client can receive data through various feeds</div>
<br />

<table id="client_export_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Client</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<form id="client_export_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/client/client-export">
    <input type="hidden" name="client_id" value="<?php echo $client->getId() ?>">
</form>
<script>
//<!--
$(document).ready(function() {
    $('#client_export_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#client_export_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client-export?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "status_name", data: "_status_name" },
            { name: "_client_name", data: "_client_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData.client_id + '">' + cellData + '</a>');
            }},
            { name: "_export_type_name", data: "_export_type_name", createdCell: function (td, cellData, rowData, row, col) {
                if (rowData.export_type == <?php echo json_encode(\Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_FTP) ?>) {
                    $(td).html(cellData + ' <small>(' + rowData.ftp_username + '@' + rowData.ftp_hostname + ')</small>');
                } else if (rowData.export_type == <?php echo json_encode(\Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_EMAIL) ?>) {
                    email_addresses = [];
                    $.each(rowData.email_address, function(i, item) {
                    	email_addresses.push(item);
                    });
                    $(td).html(cellData + ' <small>(' + email_addresses.join(', ') + ')</small>');
                } else if (rowData.export_type == <?php echo json_encode(\Gun\Export\ExportAbstract::CLIENT_EXPORT_TYPE_POST) ?>) {
                	$(td).html(cellData);
                }
            }}
      	]
    });
});
//-->
</script>