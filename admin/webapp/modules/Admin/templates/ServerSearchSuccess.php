<?php
    /* @var $server \Gun\Server */
    $server = $this->getContext()->getRequest()->getAttribute("server", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/admin/server-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Server</a>
    </div>
   <h2>Servers</h2>
</div>
<div class="help-block">Servers host offers and paths.  A server is used when deploying a new offer</div>
<br/>
<form id="server_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/admin/server">
</form>
<table id="server_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Hostname</th>
            <th>Username</th>
            <th>Password</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
//<!--
$(document).ready(function() {
    $('#server_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#server_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "hostname", data: "hostname", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/admin/server?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "root_username", data: "root_username" },
            { name: "root_password", data: "root_password" },
            { name: "status", data: "_status_name" }
      	]
    });
});
//-->
</script>