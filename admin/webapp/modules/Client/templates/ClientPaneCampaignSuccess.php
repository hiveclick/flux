<?php
    /* @var $client \Flux\Client */
    $client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div class="help-block">This client can send traffic to offers owned by other clients using campaigns</div>
<br/>
<table id="client_campaign_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Campaign</th>
            <th>Client</th>
            <th>Offer</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<form id="client_campaign_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/campaign/campaign">
    <input type="hidden" name="client_id" value="<?php echo $client->getId() ?>">
</form>
<script>
//<!--
$(document).ready(function() {
    $('#client_campaign_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#client_campaign_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/campaign/campaign?_id=' + rowData._id + '">' + rowData._client_name + ' &ndash; ' + rowData._offer_name + '</a>');
            }},
            { name: "client_name", data: "_client_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData.client_id + '">' + cellData + '</a>');
            }},
            { name: "offer_name", data: "_offer_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/offer/offer?_id=' + rowData.offer_id + '">' + cellData + '</a>');
            }},
            { name: "status_name", data: "_status_name" }
      	]
    });
});
//-->
</script>