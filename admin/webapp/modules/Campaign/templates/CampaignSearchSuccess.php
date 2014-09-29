<?php
    /* @var $campaign \Flux\Campaign */
    $campaign = $this->getContext()->getRequest()->getAttribute("campaign", array());
    $offers = $this->getContext()->getRequest()->getAttribute("offers", array());
    $clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div id="header">
    <div class="pull-right">
        <a href="/campaign/campaign-wizard"><span class="glyphicon glyphicon-plus"></span> Add New Campaign</a>
    </div>
   <h2>Campaigns</h2>
</div>
<div class="help-block">These are all the campaigns assigned to the clients and offers</div>
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
                <form id="campaign_search_form" method="GET" action="/api">
                    <input type="hidden" name="func" value="/campaign/campaign-search">
                    <div class="form-group">
                        <input type="text" name="keywords" class="form-control" placeholder="search by campaign id" value="" />
                    </div>
                    <div style="display:none;" id="advanced_search_div">
                    	<fieldset>
                    		<legend>Advanced Search</legend>
                    		<div class="form-group col-sm-6">
		                        <label class="control-label hidden-xs" for="name">Offer</label>
		                        <div class="">
		                            <select class="form-control selectize" name="offer_id_array[]" id="offer_id_array" multiple placeholder="All Offers">
		                                <?php foreach($offers as $offer) { ?>
		                                    <option value="<?php echo $offer->getId() ?>" <?php echo in_array($offer->getId(), $campaign->getOfferIdArray()) ? "selected" : "" ?>><?php echo $offer->getName() ?></option>
		                                <?php } ?>
		                            </select>
		                        </div>
		                    </div>
		                    <div class="form-group col-sm-6">
		                    	<label class="control-label hidden-xs" for="name">Client</label>
		                        <div class="">
		                            <select class="form-control selectize" name="client_id_array[]" id="client_id_array" multiple placeholder="All Clients">
		                                <?php foreach ($clients as $client) { ?>
		                                    <option value="<?php echo $client->getId() ?>" <?php echo in_array($client->getId(), $campaign->getClientIdArray()) ? "selected" : "" ?>><?php echo $client->getName() ?></option>
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
<table id="campaign_table" class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Campaign</th>
            <th>Client</th>
            <th>Offer</th>
            <th>Campaign Key</th>
            <th>Status</th>
            <th>Clicks</th>
            <th>Conversions</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script>
//<!--
$(document).ready(function() {
	$('#offer_id_array,#client_id_array').selectize();

	$('#show_advanced').click(function() {
		$('#advanced_search_div').slideToggle();
	});
	
	$('#campaign_search_form').on('submit', function(e) {
        $('#campaign_table').DataTable().clearPipeline().draw();
        e.preventDefault();
    });
	
    $('#campaign_table').DataTable({
    	autoWidth: false,
    	serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
            url: '/api',
            data: function() {
                return $('#campaign_search_form').serializeObject();
            },
            method: 'POST'
        }),
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
    	columns: [
            { name: "description", data: "description", createdCell: function (td, cellData, rowData, row, col) {
            	$(td).html('<a href="/campaign/campaign?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "client_id", data: "_client_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/client/client?_id=' + rowData.client_id + '">' + cellData + '</a>');
            }},
            { name: "offer_id", data: "_offer_name", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/offer/offer?_id=' + rowData.offer_id + '">' + cellData + '</a>');
            }},
            { name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
                $(td).html('<a href="/campaign/campaign?_id=' + rowData._id + '">' + cellData + '</a>');
            }},
            { name: "status", data: "_status_name" },
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