<?php
	/* @var $client \Flux\Client */
	$client = $this->getContext()->getRequest()->getAttribute("client", array());
?>
<div id="header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_client_modal" href="/client/client-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Client</a>
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
							<input type="text" name="keywords" class="form-control" placeholder="search by name" value="<?php echo $client->getKeywords() ?>" />
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
			<th>Type</th>
			<th>Status</th>
			<th>Email</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<!-- edit client modal -->
<div class="modal fade" id="edit_client_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

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
				$(td).html('<a data-toggle="modal" data-target="#edit_client_modal" href="/client/client-wizard?_id=' + rowData._id + '">' + cellData + '</a>');
			}},
			{ name: "client_type", data: "client_type", createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == '1') {
					$(td).html('Primary Administrator');
				} else if (cellData == '2') {
					$(td).html('Secondary Administrator');
				} else if (cellData == '3') {
					$(td).html('Affiliate');
				} else {
					$(td).html('Unknown Type (' + cellData + ')');
				}
			}},
			{ name: "status", data: "_status_name" },
			{ name: "email", data: "email" },
			{ name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a class="btn btn-sm btn-default" href="/admin/user-search?client_id_array[]=' + cellData + '">Users</a> <a class="btn btn-sm btn-default" href="/offer/offer-search?client_id_array[]=' + cellData + '">Offers</a> <a class="btn btn-sm btn-default" href="/campaign/campaign-search?client_id_array[]=' + cellData + '">Campaigns</a> <a class="btn btn-sm btn-default" href="/client/client-export-search?client_id=' + cellData + '">Fulfillment</a>');
			}},
	  	]
	});

	$('#edit_client_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
});
//-->
</script>