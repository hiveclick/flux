<?php
	/* @var $export \Flux\Export */
	$export = $this->getContext()->getRequest()->getAttribute("export", array());
	/* @var $export_queue \Flux\ExportQueue */
	$export_queue = $this->getContext()->getRequest()->getAttribute("export_queue", array());
?>
<div class="help-block">View a sample of the data included in this export</div>
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
				<form id="export_queue_search_form" method="GET" action="/api">
					<input type="hidden" name="items_per_page" value="100">
					<input type="hidden" name="func" value="/export/export-queue">
					<input type="hidden" name="export_id" value="<?php echo $export->getId() ?>">
					<input type="hidden" name="sord" value="asc">
					<input type="hidden" name="sort" value="_id">
					<div class="form-group">
						<div class="">
							<input type="text" class="form-control" name="lead_id" id="lead_id" placeholder="Search by lead" value="<?php echo $export_queue->getLeadId() ?>" />
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
<table id="export_queue_table" class="table table-hover table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>#</th>
			<th>Lead</th>
			<th>Name</th>
			<th>Email</th>
			<th>Url</th>
			<th>Last Attempt</th>
			<th>Error</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>
//<!--
$(document).ready(function() {

	$('#export_queue_search_form').on('submit', function(e) {
		$('#export_queue_table').DataTable().clearPipeline().draw();
		e.preventDefault();
	});
	
	$('#export_queue_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
			url: '/api',
			data: function() {
				return $('#export_queue_search_form').serializeObject();
			}
		}),
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
		columns: [
			{ name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/export/export-queue?export_id=<?php echo $export->getId() ?>&_id=' + rowData._id + '">' + cellData + '</a>');
			}},
			{ name: "lead_id", data: "lead_id", createdCell: function (td, cellData, rowData, row, col) {
				if (cellData) {
			 	   $(td).html('<a href="/lead/lead?_id=' + cellData + '">' + cellData + '</span>');
				} else {
					$(td).html('-- not found --');
				}
			}},
			{ name: "name", data: "qs", createdCell: function (td, cellData, rowData, row, col) {
				if (rowData.qs.fn != undefined) {
					var name = rowData.qs.fn;
					if (rowData.qs.ln != undefined) {
						name += ' ' + rowData.qs.ln;
					} else if (rowData.qs.lastname != undefined) {
						name += ' ' + rowData.qs.lastname;
					}
					$(td).html(name);
				} else if (rowData.qs.firstname != undefined) {
					var name = rowData.qs.firstname;
					if (rowData.qs.ln != undefined) {
						name += ' ' + rowData.qs.ln;
					} else if (rowData.qs.lastname != undefined) {
						name += ' ' + rowData.qs.lastname;
					}
					$(td).html(name);
				} else if (rowData.qs.FirstName != undefined) {
					var name = rowData.qs.FirstName;
					if (rowData.qs.LastName != undefined) {
						name += ' ' + rowData.qs.LastName;
					}
					$(td).html(name);
				} else {
					$(td).html('<i class="text-muted">missing</i>');
				}
			}},
			{ name: "email", data: "qs", createdCell: function (td, cellData, rowData, row, col) {
				if (rowData.qs.em != undefined) {
					$(td).html(rowData.qs.em);
				} else if (rowData.qs.email != undefined) {
					$(td).html(rowData.qs.email);
				} else if (rowData.qs.Email != undefined) {
					$(td).html(rowData.qs.Email);
				} else {
					$(td).html('<i class="text-muted">missing</i>');
				}
			}},
			{ name: "url", data: "url", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(cellData);
			}},
			{ name: "last_sent_time", data: "last_sent_time", createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == 0) {
					$(td).html('<i class="text-muted">not sent yet</i>');
				} else {
					$(td).html(moment.unix(cellData.sec).calendar());
				}
			}},
			{ name: "is_error", data: "is_error", sClass: "text-center", createdCell: function (td, cellData, rowData, row, col) {
				if (cellData) {
			 	   $(td).html('<span class="text-danger">Yes</span>');
				} else {
					$(td).html('');
				}
			}}
	  	]
	});
});
//-->
</script>
