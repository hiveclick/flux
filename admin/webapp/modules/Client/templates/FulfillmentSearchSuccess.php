<?php
	/* @var $client_export \Flux\ClientExport */
	$client_export = $this->getContext()->getRequest()->getAttribute("client_export", array());
?>
<div id="header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_fulfillment_modal" href="/client/fulfillment-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Handler</a>
	</div>
   <h2>Fulfillment Handlers</h2>
</div>
<div class="help-block">Define how this clients can receive data through various feeds</div>
<div class="panel-group" id="accordion">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Search Filters</a>
			</h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse in">
			<div class="panel-body">
				<form id="fulfillment_search_form" method="GET" action="/api">
					<input type="hidden" name="func" value="/client/client-export">
					<div class="form-group">
						<div class="col-sm-12">
							<input type="text" name="keywords" class="form-control" placeholder="search by name" value="<?php echo $client_export->getKeywords() ?>" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<table id="fulfillment_table" class="table table-hover table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Name</th>
			<th>Status</th>
			<th>Client</th>
			<th>Type</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<!-- edit fulfillment modal -->
<div class="modal fade" id="edit_fulfillment_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>

<!-- Map Custom Function modal -->
<div class="modal fade" id="map_options_modal"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script>
//<!--
$(document).ready(function() {
	$('#fulfillment_search_form').on('submit', function(e) {
		$('#fulfillment_table').DataTable().clearPipeline().draw();
		e.preventDefault();
	});
	
	$('#fulfillment_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
			url: '/api',
			data: function() {
				return $('#fulfillment_search_form').serializeObject();
			}
		}),
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
		columns: [
			{ name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/client/fulfillment?_id=' + rowData._id + '">' + cellData + '</a>');
			}},
			{ name: "status_name", data: "_status_name" },
			{ name: "_client_name", data: "_client_name", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/client/client-search?keywords=' + cellData + '">' + cellData + '</a>');
			}},
			{ name: "_export_type_name", data: "_export_type_name", createdCell: function (td, cellData, rowData, row, col) {
				if (rowData.export_type == <?php echo json_encode(\Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_FTP) ?>) {
					$(td).html(cellData + ' <small>(' + rowData.ftp_username + '@' + rowData.ftp_hostname + ')</small>');
				} else if (rowData.export_type == <?php echo json_encode(\Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_EMAIL) ?>) {
					email_addresses = [];
					$.each(rowData.email_address, function(i, item) {
						email_addresses.push(item);
					});
					$(td).html(cellData + ' <small>(' + email_addresses.join(', ') + ')</small>');
				} else if (rowData.export_type == <?php echo json_encode(\Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_POST) ?>) {
					$(td).html(cellData);
				} else if (rowData.export_type == <?php echo json_encode(\Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_INFUSIONSOFT) ?>) {
					$(td).html(cellData + ' <small>(' + rowData.infusionsoft_host + ')</small>');
				}
			}}
	  	]
	});

	$('#edit_fulfillment_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });

	$('#map_options_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
});
//-->
</script>