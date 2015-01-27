<?php
	/* @var $daemon \Flux\Daemon */
	$daemon = $this->getContext()->getRequest()->getAttribute("daemon", array());
?>
<div id="header">
	<div class="pull-right">
		<a data-toggle="modal" data-target="#edit_daemon_modal" href="/admin/daemon-wizard" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add New Daemon</a>
	</div>
   <h2>Daemons</h2>
</div>
<div class="help-block">Daemons are scripts that run constantly in the background checking leads, splits and exports</div>
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
				<form id="daemon_search_form" method="GET" action="/api">
					<input type="hidden" name="func" value="/admin/daemon">
					<div class="col-sm-12">
						<div class="form-group">
							<input type="text" name="keywords" class="form-control" placeholder="search by name, request name, or tags" value="<?php echo $daemon->getKeywords() ?>" />
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
<table id="daemon_table" class="table table-hover table-bordered table-striped table-responsive">
	<thead>
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Class Name</th>
			<th># Threads</th>
			<th>PID</th>
			<th>Last Run</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<!-- edit daemon modal -->
<div class="modal fade" id="edit_daemon_modal"><div class="modal-dialog modal-lg"><div class="modal-content"></div></div></div>
<script>
//<!--
$(document).ready(function() {

	$('#daemon_search_form').on('submit', function(e) {
		$('#daemon_table').DataTable().clearPipeline().draw();
		e.preventDefault();
	});

	$('#daemon_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
			url: '/api',
			data: function() {
				return $('#daemon_search_form').serializeObject();
			}
		}),
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
		columns: [
			{ name: "name", data: "name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a data-toggle="modal" data-target="#edit_daemon_modal" href="/admin/daemon-wizard?_id=' + rowData._id + '">' + cellData + '<div class="small text-muted">' + rowData.description + '</div></a>');
			}},
			{ name: "type", data: "type", defaultContent: '', className: "text-center" },
			{ name: "class_name", data: "class_name", defaultContent: '', className: "text-center" },
			{ name: "threads", data: "threads", defaultContent: '', className: "text-center" },
			{ name: "pid", data: "pid", defaultContent: '', className: "text-center" },
			{ name: "start_time", data: "start_time", defaultContent: '', sClass: 'text-center', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(moment.unix(cellData.sec).calendar());
			}}
	  	]
	});

	$('#edit_daemon_modal').on('hide.bs.modal', function(e) {
    	$(this).removeData('bs.modal');
    });
});
//-->
</script>