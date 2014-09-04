<?php
    /* @var $vertical \Gun\Vertical */
    $vertical = $this->getContext()->getRequest()->getAttribute("vertical", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/admin/vertical-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Vertical</a>
    </div>
   <h2>Verticals</h2>
</div>
<div class="help-block">Verticals allow you to categorize and sort offers</div>
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
                <form id="vertical_search_form" method="GET" action="/api">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="keywords" class="form-control" placeholder="search by name" value="" />
                        </div>
                    </div>
                    <input type="hidden" name="func" value="/admin/vertical">
                </form>
            </div>
        </div>
    </div>
</div>
<table id="vertical_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>ID</th>
        </tr>
    </thead>
</table>
<script>
//<!--
$(document).ready(function() {
	$('#vertical_search_form').on('submit', function(e) {
        $('#vertical_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });

	$('#vertical_table').DataTable({
		autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#vertical_search_form').serializeObject();
            }
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/admin/vertical?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "description", data: "description" },
            { name: "_id", data: "_id" }
        ],
    });
});
//-->
</script>