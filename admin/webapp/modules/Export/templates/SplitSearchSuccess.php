<?php
	/* @var $split \Flux\Split */
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
?>
<div id="header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_split_modal" href="/export/split-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Split</a>
	</div>
   <h2>Splits</h2>
</div>
<div class="help-block">Exports define how a client can receive data from a split</div>
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
				<form id="split_search_form" method="GET" action="/api">
					<div class="form-group">
						<div class="col-sm-12">
							<input type="text" name="keywords" class="form-control" placeholder="search by name" value="<?php echo $split->getKeywords() ?>" />
						</div>
					</div>
					<input type="hidden" name="func" value="/export/split">
				</form>
			</div>
		</div>
	</div>
</div>
<table id="split_table" class="table table-hover table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Name</th>
			<th>Status</th>
			<th>Queue #</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<!-- edit split modal -->
<div class="modal fade" id="edit_split_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {
	$('#split_search_form').on('submit', function(e) {
		$('#split_table').DataTable().clearPipeline().draw();
		e.preventDefault();
	});
	
	$('#split_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
			url: '/api',
			data: $('#split_search_form').serializeObject()
		}),
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
		columns: [
			{ name: "name", data: "name", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/export/split?_id=' + rowData._id + '">' + cellData + '<div class="small text-muted">' + rowData.description + '</div></a>');
			}},
			{ name: "status_name", data: "_status_name" },
			{ name: "queue_count", data: "queue_count", sClass: "text-right", createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == '0') {
					$(td).html('<span class="text-muted">' + $.number(cellData) + '</span>');
				} else {
					$(td).html($.number(cellData));
				}
			}}
	  	]
	});

	$('#edit_split_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
});
//-->
</script>