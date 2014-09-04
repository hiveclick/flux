<?php
    /* @var $offer \Gun\Offer */
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/offer/offer-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Offer</a>
    </div>
   <h2>Offers</h2>
</div>
<div class="help-block">These are all the offers in the system.  Choose one to change settings on it and view reports for it</div>
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
                <form id="offer_search_form" method="GET" action="/api">
                    <input type="hidden" name="func" value="/offer/offer">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" name="keywords" class="form-control" placeholder="search by name or vertical" value="" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="pane">
    <table id="offer_table" class="table table-hover table-bordered table-striped table-responsive">
        <thead>
            <tr>
                <th>Name</th>
                <th>Advertising Client</th>
                <th>Payout</th>
                <th>Verticals</th>
                <th>Status</th>
                <th>Clicks</th>
                <th>Conversions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
//<!--
$(document).ready(function() {
	$('#offer_search_form').on('submit', function(e) {
        $('#offer_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });

    $('#offer_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#offer_search_form').serializeObject();
            }
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
            { name: "payout", data: "payout", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('$' + $.number(cellData, 2));
            }},
            { name: "verticals", data: "verticals", createdCell: function (td, cellData, rowData, row, col) {
            	var cell_html = '';
            	if (cellData instanceof Array) {
                    $.each(cellData, function(i,item) {
                    	cell_html += '<span class="badge alert-info">' + item + '</span> ';
                    });
            	}
                $(td).html(cell_html);
            }},
            { name: "status_name", data: "_status_name" },
            { name: "daily_clicks", data: "daily_clicks", sClass: "text-right", createdCell: function (td, cellData, rowData, row, col) {
                if (cellData == '0') {
                    $(td).html('<span class="text-muted">' + $.number(cellData) + '</span>');
                } else {
                	$(td).html($.number(cellData));
                }
            }},
            { name: "daily_conversions", data: "daily_conversions", sClass: "text-right", createdCell: function (td, cellData, rowData, row, col) {
            	if (cellData == '0') {
                    $(td).html('<span class="text-muted">' + $.number(cellData) + '</span>');
                } else {
                	$(td).html($.number(cellData));
                }
            }}
      	]
    });
});
//-->
</script>