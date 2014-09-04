<?php
    /* @var DomainGroup \Gun\DomainGroup */
    $domain_group = $this->getContext()->getRequest()->getAttribute("domain_group", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/admin/domain-group-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Domain Group</a>
    </div>
   <h2>Domain Groups</h2>
</div>
<div class="help-block">Domain Groups allow you to categorize email addresses by domains</div>
<br/>
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Search Filters</a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <form id="domain_group_search_form" method="GET" action="/api">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="keywords" class="form-control" placeholder="search by name" value="" />
                        </div>
                    </div>
                    <input type="hidden" name="func" value="/admin/domain-group">
                </form>
            </div>
        </div>
    </div>
</div>
<table id="domain_group_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Domains</th>
        </tr>
    </thead>
</table>
<script>
//<!--
$(document).ready(function() {
	$('#domain_group_search_form').on('submit', function(e) {
        $('#domain_group_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });

	$('#domain_group_table').DataTable({
		autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#domain_group_search_form').serializeObject();
            }
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/admin/domain-group?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "description", data: "description" },
            { name: "domains", data: "domains" }
        ],
    });
});
//-->
</script>