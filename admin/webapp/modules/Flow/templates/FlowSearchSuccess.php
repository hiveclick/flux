<?php
    /* @var $flow \Gun\Flow */
    $flow = $this->getContext()->getRequest()->getAttribute("flow", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/flow/flow-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Flow</a>
    </div>
   <h2>Flows</h2>
</div>
<div class="help-block">These are all the flows in the system.  Choose one to change settings on it and view reports for it</div>
<br/>
<table id="flow_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<form id="flow_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/flow/flow">
</form>
<script>
//<!--
$(document).ready(function() {
    $('#flow_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#flow_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/flow/flow?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "status_name", data: "_status_name" }
      	]
    });
});
//-->
</script>