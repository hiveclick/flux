<?php
	/* @var $export \Flux\Export */
	$export = $this->getContext()->getRequest()->getAttribute("export", array());
	$client_exports = $this->getContext()->getRequest()->getAttribute("client_exports", array());
	$splits = $this->getContext()->getRequest()->getAttribute("splits", array());
?>
<div id="header">
   <h2>Exports</h2>
</div>
<div class="help-block">Exports define how a client can receive data from a split</div>
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
				<form id="export_search_form" method="GET" action="/api">
					<input type="hidden" name="items_per_page" value="100">
					<input type="hidden" name="func" value="/export/export">
					<input type="hidden" name="sord" value="desc">
					<input type="hidden" name="sort" value="export_date">
					<div class="form-group">
						<div class="">
							<input type="text" class="form-control" name="description" id="description" placeholder="Search by name" value="<?php echo $export->getName() ?>" />
						</div>
					</div>
					<div style="display:none;" id="advanced_search_div">
						<fieldset>
							<legend>Advanced Search</legend>
							<div class="form-group col-sm-12">
								<label class="control-label hidden-xs" for="name">Export Type</label>
								<div class="">
									<select class="form-control selectize" name="export_type_array[]" id="export_type" multiple placeholder="Filter by Export Type">
										<?php foreach (\Flux\Export::retrieveExportTypes() as $export_type_id => $export_type_name) { ?>
											<option value="<?php echo $export_type_id ?>" selected><?php echo $export_type_name ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label hidden-xs" for="name">Split</label>
									<div class="">
										<select class="form-control selectize" name="split_id_array[]" id="split_id" multiple placeholder="All Splits">
											<?php foreach($splits as $split) { ?>
												<option value="<?php echo $split->getId() ?>" <?php echo in_array($split->getId(), $export->getSplitIdArray()) ? 'selected' : '' ?>><?php echo $split->getName() ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label hidden-xs" for="name">Client</label>
									<div class="">
										<select class="form-control selectize" name="client_export_id_array[]" id="client_export_id" multiple placeholder="All Exports">
											<?php foreach($client_exports as $client_export) { ?>
												<option value="<?php echo $client_export->getId() ?>" <?php echo in_array($client_export->getId(), $export->getClientExportIdArray()) ? 'selected' : '' ?>><?php echo $client_export->getName() ?></option>
											<?php } ?>
										</select>
									</div>
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
<table id="export_table" class="table table-hover table-bordered table-striped table-responsive table-condensed">
	<thead>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Split</th>
			<th>Export</th>
			<th>Progress</th>
			<th>Date</th>
			<th># Records</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>
//<!--
$(document).ready(function() {
	$('#split_id,#client_export_id,#export_type').selectize();

	$('#show_advanced').click(function() {
		$('#advanced_search_div').slideToggle();
	});
	
	$('#export_search_form').on('submit', function(e) {
		$('#export_table').DataTable().clearPipeline().draw();
		e.preventDefault();
	});
	
	$('#export_table').DataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: 15,
		ajax: $.fn.dataTable.pageCache({
			url: '/api',
			data: function() {
				return $('#export_search_form').serializeObject();
			}
		}),
		searching: false,
		paging: true,
		dom: 'Rfrtpi',
		columns: [
			{ name: "id", data: "_id", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/export/export?_id=' + rowData._id + '">' + cellData + '</a>');
			}},
			{ name: "name", data: "name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/export/export?_id=' + rowData._id + '">' + cellData + '</a>');
			}},
			{ name: "_split_name", data: "_split_name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/export/split?_id=' + rowData.split_id + '">' + cellData + '</a>');
			}},
			{ name: "_client_export_name", data: "_client_export_name", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/client/client?_id=' + rowData._client_id + '">' + cellData + ' (' + rowData._client_name + ')</a>');
			}},
			{ name: "percent_complete", data: "percent_complete", defaultContent: '', createdCell: function (td, cellData, rowData, row, col) {
				if (cellData == 100) {
					var div = '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" style="width: ' + ((rowData.num_records_successful/rowData.num_records)*100) + '%;"></div><div class="progress-bar progress-bar-danger" role="progressbar" style="width: ' + ((rowData.num_records_error/rowData.num_records)*100) + '%;"></div></div>';
				} else {
					var div = '<div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' + cellData + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + cellData + '%;"></div></div>';
				}
				$(td).html(div);
			}},
			{ name: "export_date", data: "export_date", defaultContent: '', sClass: 'text-center', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html(moment.unix(cellData.sec).calendar());
			}},
			{ name: "num_records", data: "num_records", defaultContent: '', sClass: 'text-right', createdCell: function (td, cellData, rowData, row, col) {
				$(td).html($.number(cellData));
			}}
	  	]
	});
});
//-->
</script>