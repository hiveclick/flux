<?php
    /* @var $report_column \Gun\ReportColumn */
    $report_column = $this->getContext()->getRequest()->getAttribute("report_column", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/admin/report-column-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Report Column</a>
    </div>
   <h2>Report Columns</h2>
</div>
<div class="help-block">Report columns allow you to define which data fields can be used for aggregations in reports</div>
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
                <form id="report_column_search_form" method="GET" action="/api">
                    <input type="hidden" name="func" value="/admin/report-column">
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
<table id="report_column_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Column Type</th>
            <th>Format</th>
            <th>Sum Type</th>
            <th>Operator Type</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
//<!--

$(document).ready(function() {
	$('#report_column_search_form').on('submit', function(e) {
        $('#report_column_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });

	$('#report_column_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#report_column_search_form').serializeObject();
            }
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/admin/report-column?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "column_type", data: "_column_type_name" },
            { name: "format_type", data: "_format_type_name" },
            { name: "sum_type", data: "_sum_type_name", createdCell: function (td, cellData, rowData, row, col) {
                if (rowData.format_type == <?php echo json_encode(\Gun\ReportColumn::REPORT_COLUMN_TYPE_GROUP_DATAFIELD) ?>) {
                	$(td).html(cellData);
                } else {
                	$(td).html('--');
                }
            }},
            { name: "operator_type", data: "_operator_type_name", createdCell: function (td, cellData, rowData, row, col) {
            	if (rowData.format_type == <?php echo json_encode(\Gun\ReportColumn::REPORT_COLUMN_TYPE_GROUP_DATAFIELD) ?>) {
            		$(td).html('--');
                } else {
                	$(td).html(cellData);
                }
            }}
        ]
    });
});
//-->
</script>