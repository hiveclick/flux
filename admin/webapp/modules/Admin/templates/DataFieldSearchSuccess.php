<?php
    /* @var $datafield \Flux\DataField */
    $datafield = $this->getContext()->getRequest()->getAttribute("datafield", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/admin/data-field-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Data Field</a>
    </div>
   <h2>Data Fields</h2>
</div>
<div class="help-block">Data Fields set what data can be collected on the offer pages via request names</div>
<br/>
<div class="panel-group" id="accordion">
    <div class="panel panel-default" style="overflow:visible;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Search Filters</a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <form id="datafield_search_form" method="GET" action="/api">
                    <input type="hidden" name="func" value="/admin/data-field">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="text" name="keywords" class="form-control" placeholder="search by name, request name, or tags" value="" />
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="storage_type_array" id="storage_type_array" required placeholder="only display selected storage types" multiple>
                                <?php foreach(\Flux\DataField::retrieveSettableStorageTypes() AS $storage_type_id => $storage_type_name) { ?>
                                <option value="<?php echo $storage_type_id; ?>"><?php echo $storage_type_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-info" name="btn_submit" value="filter results" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<table id="datafield_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Key</th>
            <th>Type</th>
            <th>Storage</th>
            <th>Tags</th>
            <th>Request Names</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
//<!--
$(document).ready(function() {
    $('#storage_type_array').selectize();

    $('#datafield_search_form').on('submit', function(e) {
        $('#datafield_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });

    $('#datafield_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#datafield_search_form').serializeObject();
            }
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "name", data: "name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/admin/data-field?_id=' + rowData._id + '">' + cellData + '<div class="small text-muted">' + rowData.description + '</div></a>');
            }},
            { name: "key_name", data: "key_name", defaultContent: '', className: "text-center" },
            { name: "field_type", data: "_field_type_name", defaultContent: '', className: "text-center" },
            { name: "storage_type", data: "_storage_type_name", defaultContent: '', className: "text-center" },
            { name: "tags", data: "tags", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
                var cell_html = '';
                if (cellData instanceof Array) {
	                $.each(cellData, function(i,item) {
	                	cell_html += '<span class="badge alert-info">' + item + '</span> ';
	                });
                }
                $(td).html(cell_html);
            }},
            { name: "request_name", data: "request_name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
                var cell_html = '<i class="badge alert-success">' + rowData.key_name + '</i> ';
                if (cellData instanceof Array) {
	                $.each(cellData, function(i,item) {
	                	cell_html += '<span class="badge alert-info">' + item + '</span> ';
	                });
                }
                $(td).html(cell_html);
            }}
      	]
    });
});
//-->
</script>