<?php
    /* @var $user \Gun\User */
    $user = $this->getContext()->getRequest()->getAttribute("user", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/admin/user-wizard"><span class="glyphicon glyphicon-plus"></span> Add New User</a>
    </div>
   <h2>Users</h2>
</div>
<div class="help-block">Users have access to log into the system and make changes</div>
<br/>
<form id="user_search_form" method="GET" action="/api">
    <input type="hidden" name="func" value="/admin/user">
</form>
<table id="user_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Type</th>
            <th>Status</th>
            <th>Client</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
//<!--
$(document).ready(function() {
    $('#user_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: $('#user_search_form').serializeObject()
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "email", data: "email", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/admin/user?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "name", data: "name" },
            { name: "user_type", data: "_user_type_name" },
            { name: "status", data: "_status_name" },
            { name: "client_id", data: "_client_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData.client_id + '">' + cellData + '</a>');
            }},
      	]
    });
});
//-->
</script>