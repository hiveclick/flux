<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
	$lead_pages = $this->getContext()->getRequest()->getAttribute('lead_pages', array());
?>
<div class="help-block">You can view the various pages that this user visited below</div>
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
				<form id="lead_page_search_form" method="GET" action="/api">
					<input type="hidden" name="func" value="/lead/lead-page">
					<input type="hidden" name="lead_id" value="<?php echo $lead->getId() ?>">
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

<table id="lead_page_table" class="table table-hover table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Url</th>
			<th>Page</th>
			<th>Domain</th>
			<th>Enter Time</th>
			<th>Load #</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<!-- Push offer to server modal -->
<div class="modal fade" id="lead_page_view_modal">
	<div class="modal-dialog">
		<div class="modal-content"></div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
//<!--
$(document).ready(function() {
	$('body').on('hidden.bs.modal', '.modal', function () {
		  $(this).removeData('bs.modal');
		});
	
	$('#lead_page_search_form').on('submit', function(e) {
		$('#client_table').DataTable().clearPipeline().draw();
		e.preventDefault();
	});

	$('#lead_page_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
			url: '/api',
			data: function() {
				return $('#lead_page_search_form').serializeObject();
			}
		}),
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
		columns: [
			{ name: "href", data: "href", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(cellData);
			}},
			{ name: "page", data: "page", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(cellData);
			}},
			{ name: "domain", data: "domain", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(cellData);
			}},
			{ name: "entrance_time", data: "entrance_time", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(moment.unix(cellData.sec).calendar());
			}},
			{ name: "load_count", data: "load_count", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(cellData);
			}},
			{ name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/lead/lead-pane-page?_id=' + rowData._id + '" data-toggle="modal" data-target="#lead_page_view_modal">view cookie</a>');
			}}
	  	]
	});
});
//-->
</script>