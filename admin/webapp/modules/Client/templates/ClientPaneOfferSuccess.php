<?php
    /* @var $client \Flux\Client */
    $client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div class="help-block">These are the offers owned by this client</div>
<br/>
<table id="client_offer_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Client</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<form id="client_offer_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/offer/offer">
    <input type="hidden" name="client_id" value="<?php echo $client->getId() ?>">
</form>
<script>
//<!--
$(document).ready(function() {
    $('#client_offer_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#client_offer_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/offer/offer?_id=' + rowData._id + '">' + rowData.name + '</a>');
            }},
            { name: "client_name", data: "_client_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData.client_id + '">' + cellData + '</a>');
            }},
            { name: "status_name", data: "_status_name" }
      	]
    });
});
//-->
</script>