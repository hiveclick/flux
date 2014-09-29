<?php
    /* @var $client \Flux\Client */
    $client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/client/client-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Client</a>
    </div>
   <h2>Clients</h2>
</div>
<div class="help-block">Clients are owners of exports, offers, and campaigns</div>
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Search Filters</a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <form id="client_search_form" method="GET" action="/api">
                    <input type="hidden" name="func" value="/client/client">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="keywords" class="form-control" placeholder="search by name" value="" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<table id="client_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script>
//<!--
$(document).ready(function() {
	$('#client_search_form').on('submit', function(e) {
        $('#client_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });

    $('#client_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#client_search_form').serializeObject();
            }
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "status", data: "_status_name" }
      	]
    });
});
//-->
</script>