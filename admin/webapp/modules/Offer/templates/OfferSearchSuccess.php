<?php
    /* @var $offer \Flux\Offer */
    $offer = $this->getContext()->getRequest()->getAttribute("offer", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
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
                        <div class="">
                            <input type="text" name="keywords" class="form-control" placeholder="search by name or vertical" value="" />
                        </div>
                    </div>
                    <div style="display:none;" id="advanced_search_div">
                    	<fieldset>
                    		<legend>Advanced Search</legend>
                    		<div class="form-group col-sm-6">
		                        <label class="control-label hidden-xs" for="name">Status</label>
		                        <div class="">
		                            <select class="form-control selectize" name="status_array[]" id="status_array" multiple placeholder="All Statuses">
		                                <?php foreach (\Flux\Offer::retrieveStatuses() as $status_id => $status_name) { ?>
		                                    <option value="<?php echo $status_id ?>" <?php echo ((count($offer->getStatusArray()) == 0 && $status_id == \Flux\Offer::OFFER_STATUS_ACTIVE) || in_array($status_id, $offer->getStatusArray())) ? "selected" : "" ?>><?php echo $status_name ?></option>
		                                <?php } ?>
		                            </select>
		                        </div>
		                    </div>
		                    <div class="form-group col-sm-6">
		                    	<label class="control-label hidden-xs" for="name">Client</label>
		                        <div class="">
		                            <select class="form-control selectize" name="client_id_array[]" id="client_id_array" multiple placeholder="All Clients">
		                                <?php foreach ($clients as $client) { ?>
		                                    <option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $offer->getClientIdArray()) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
		                                <?php } ?>
		                            </select>
		                        </div>
		                    </div>
	                    </fieldset>
                    </div>
                    <div class="text-center">
                    	<input type="button" class="btn btn-warning" id="show_advanced" name="show_advanced" value="show advanced filters" />
                        <input type="submit" class="btn btn-info" name="btn_submit" value="filter results" />
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

    $('#status_array,#client_id_array').selectize();

	$('#show_advanced').click(function() {
		$('#advanced_search_div').slideToggle();
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
            { name: "name", data: "name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/offer/offer?_id=' + rowData._id + '">' + rowData.name + '</a>');
            }},
            { name: "client_name", data: "_client_name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData.client_id + '">' + cellData + '</a>');
            }},
            { name: "payout", data: "payout", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('$' + $.number(cellData, 2));
            }},
            { name: "verticals", data: "verticals", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
            	var cell_html = '';
            	if (cellData instanceof Array) {
                    $.each(cellData, function(i,item) {
                    	cell_html += '<span class="badge alert-info">' + item + '</span> ';
                    });
            	}
                $(td).html(cell_html);
            }},
            { name: "status_name", data: "_status_name", defaultContent: '' },
            { name: "daily_clicks", data: "daily_clicks", defaultContent: '', sClass: "text-right", createdCell: function (td, cellData, rowData, row, col) {
                if (cellData == '0') {
                    $(td).html('<span class="text-muted">' + $.number(cellData) + '</span>');
                } else {
                	$(td).html($.number(cellData));
                }
            }},
            { name: "daily_conversions", data: "daily_conversions", defaultContent: '', sClass: "text-right", createdCell: function (td, cellData, rowData, row, col) {
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