<?php
	$offers = $this->getContext()->getRequest()->getAttribute("offers", array());
	$spy_report = $this->getContext()->getRequest()->getAttribute("spy_report", array());
	$spy_columns = $this->getContext()->getRequest()->getAttribute("spy_columns", array());

	$selected_columns = (array)$spy_report->retrieveValue('column_id');
?>
<div class="clearfix">
	<div class="col-lg-11  col-md-10  col-sm-9" id="header">
		 <h2><a href="/report/spy-report">Spy Report</a> <small> Inspect your traffic</small></h2>
	</div>
</div>
<div class="help-block">Easily spy on the current traffic received on this offer</div>
<br/>
<form class="form-horizontal" id="spy_form" name="spy_form" method="GET" action="/report/spy-report" autocomplete="off" role="form">
	<input type="hidden" name="sort" value="_e.*.t" />
	<input type="hidden" name="sord" value="desc" />
	<input type="hidden" name="last_end_time" value="" />

	<div class="form-group">
		<label class="col-sm-2 control-label" for="date_range">Report Date</label>
		<div class="col-sm-5 col-xs-6">
			<select name="date_range" class="form-control">
				<?php foreach(\Flux\SpyReport::retrieveDateRanges() AS $date_range_id => $date_range_name) { ?>
				<option value="<?php echo $date_range_id; ?>"<?php echo $spy_report->getDateRange() == $date_range_id ? ' selected="selected"' : ''; ?>><?php echo $date_range_name; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-sm-5 col-xs-6">
			<select name="tz_modifier" class="form-control">
				<?php foreach(\Flux\Timezone::retrieveTimezonesFormatted() AS $timezone_id => $timezone_string) { ?>
					<option value="<?php echo $timezone_id; ?>"><?php echo $timezone_string; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group custom-range">
		<label class="col-sm-2 control-label" for="date_range">Custom Date</label>
		<div class="col-sm-5 col-xs-6">
			<div class="input-group">
				<input type="text" id="start_time" name="start_time" placeholder="Start Date" class="form-control" value="<?php echo $spy_report->retrieveValue('start_time'); ?>" />
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
		</div>
		<div class="col-sm-5 col-xs-6">
			<div class="input-group">
				<input type="text" id="end_time" name="end_time" placeholder="End Date" class="form-control" value="<?php echo $spy_report->retrieveValue('end_time'); ?>" />
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="date_range">Offers</label>
		<div class="col-sm-10">
			<select class="form-control selectize" name="offer_id[]" id="offer_id" multiple placeholder="No Offers">
				<?php foreach($offers AS $offer) { ?>
				<option value="<?php echo $offer->getId() ?>"<?php echo in_array((string) $offer->getId(), (array)$spy_report->retrieveValue('offer_id')) ? ' selected' : ''; ?>><?php echo htmlspecialchars($offer->getName()); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="date_range">Columns</label>
		<div class="col-sm-10">
			<select class="form-control selectize" name="column_id[]" id="column_id" multiple placeholder="No Columns">
				<?php foreach($spy_columns AS $spy_column) { ?>
				<option value="<?php echo $spy_column['datafield']; ?>"<?php echo in_array((string) $spy_column['datafield'], (array)$spy_report->retrieveValue('column_id')) ? ' selected' : ''; ?>><?php echo htmlspecialchars($spy_column['text']); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="date_range">Limit</label>
		<div class="col-sm-10">
			<select name="limit" class="form-control" placeholder="Limit">
				<option value="10">Limit 10</option>
				<option value="100">Limit 100</option>
				<option value="1000">Limit 1000</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="btn-group" data-toggle="buttons">
				<input type="submit" name="run_spy" value="Spy Stopped" id="run_spy" class="btn btn-warning" />
			</div>
			<button type="button" class="btn btn-primary btn-spy-export" name="export" value="1">Export Leads</button>
		</div>
	</div>
	<table id="spy_table" class="table table-striped table-bordered table-hover table-condensed table-responsive">
		<thead>
			<tr id="spy_thead_tr">
				<th>Id</th>
				<th>Offer</th>
				<th>Client</th>
				<?php foreach($spy_columns AS $spy_column) { ?>
					<th><?php echo $spy_column['text'] ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody id="spy_tbody"></tbody>
	</table>
</form>
<script>
//<!--
$(document).ready(function() {
	var spy_timeout;
	var default_start_time = moment().startOf('day');
	var default_end_time = moment().endOf('day');
	$('#start_time').datetimepicker({
		'defaultDate' : default_start_time,
		'useSeconds' : true,
		'format' : <?php echo json_encode(MO_DEFAULT_DATETIME_FORMAT_MOMENT_JS); ?>
	});
	$('#end_time').datetimepicker({
		'defaultDate' : default_end_time,
		'useSeconds' : true,
		'format' : <?php echo json_encode(MO_DEFAULT_DATETIME_FORMAT_MOMENT_JS); ?>
	});

	$('[name=date_range]').on('change', function(){
		if ($(this).val() == <?php echo \Flux\SpyReport::DATE_RANGE_CUSTOM; ?>) {
			$('.custom-range').show();
		} else {
			$('.custom-range').hide();
		}
	}).trigger('change');

	var oTable = $('#spy_table').dataTable({
		autoWidth: false,
		serverSide: true,
		pageLength: $('#offer_spy_form input[name=limit]').val(),
		ajax: function(d, callback, settings) {
			$.rad.get('/report/spy-report', $('#spy_form').serialize(), function(data) {
				if ($('#run_spy').val() == 'Spy Running') {
					spy_timeout = setTimeout(function(){
						$('#spy_table').DataTable().ajax.reload();
					}, 5000);
				}

				if (data.entries) {
					callback({
						data: data.entries,
						recordsTotal: data.pagination.total_rows,
						recordsFiltered: data.pagination.total_rows
					});
				} else {
					callback({
						data: {},
						recordsTotal: data.pagination.total_rows,
						recordsFiltered: data.pagination.total_rows
					});
				}
			});

		},
		searching: false,
		paging: false,
		deferRender: true,
		deferLoading: 0, // This keeps the table from trying to load until we click the Spy Running button
		dom: 'ti',
		columns: [
			{ name: "_id", data: "_id" },
			{ name: "_offer_name", data: "_offer_name" },
			{ name: "_client_name", data: "_client_name" },
			<?php foreach($spy_columns AS $spy_column) { ?>
				<?php if ($spy_column['storage_type'] == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
				{ name: "<?php echo $spy_column['datafield'] ?>", data: "_d.<?php echo $spy_column['datafield'] ?>" },
				<?php } else { ?>
				{ name: "<?php echo $spy_column['datafield'] ?>", data: function data( row, type, set, meta ) {
					var ret_val;
					$.each(row._e, function(i, item) {
						if (item.n == <?php echo $spy_column['datafield'] ?>) {
							// This is our event
							<?php if ($spy_column['type'] == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
							ret_val = moment.unix(item.t.sec).calendar();
							<?php } else { ?>
							ret_val = item.n;
							<?php } ?>
						}
					});
					return ret_val;
				} },
				<?php } ?>
			<?php } ?>
	  	],
	  	columnDefs: [
			 {
				targets: [ 0,1,2 ], // Show the first column by default
				visible: true,
				orderable: false
			 },
			 {
				 targets: "_all", // Hide all other columns by default
				 visible: false,
				 defaultContent: "",
				 orderable: false
		 	 }

		]
	});

	/*
	 * Actually run the spy report
	 */
	$('#run_spy').click(function() {
		if ($('#run_spy').val() == 'Spy Stopped') {
			// Change the Spy Running button
			$('#run_spy').val('Spy Running');
			$('#run_spy').removeClass('btn-warning');
			$('#run_spy').addClass('btn-success');
			$('#column_id').trigger('update_columns');
			// Submit the form to start the spy report
			$('#spy_table').DataTable().ajax.reload();
		 } else {
			// Stop any running spy reports
			if(spy_timeout) {
				clearTimeout(spy_timeout);
			}
			// Clear our last run time variable
			$('[name=last_end_time]').val(0);
			// Change the Spy Running button
			$('#run_spy').val('Spy Stopped');
			$('#run_spy').removeClass('btn-success');
			$('#run_spy').addClass('btn-warning');
		 }
	});

	/*
	 * Export the leads to a download page
	 */
	var spy_exporting = false;
	$('.btn-spy-export').on('click' ,function(){
		if(spy_exporting === false) {
			spy_exporting = true;
			//$('#wait-animation').show();
			document.location.href = '/lead/spy?type=export&' + $('form[name=offer_spy_form]').serialize();
			//$('#wait-animation').hide();
			spy_exporting = false;
		}
	});

	/*
	 * Load the default columns from the LocalStorage
	 */
	var selectize_object = $('#column_id').selectize();
	var selectize_control = selectize_object[0].selectize;
	var spyColumnStorageName = <?php echo json_encode('spy_report_column'); ?>;
	var lastColumnStorageSetting = localStorage.getItem(spyColumnStorageName);
	lastColumnStorageSetting = JSON.parse(lastColumnStorageSetting);
	if (lastColumnStorageSetting && (lastColumnStorageSetting instanceof Array)) {
		$.each(lastColumnStorageSetting, function(key, value) {
			// Add the column to the selectize control
			selectize_control.addItem(value);
			// Enable the column for datatables

			col = $('#spy_table').DataTable().column(value + ":name");
			col.visible(true);
		});
	}

	$('#offer_id').selectize();

	/*
	 * Handle when columns are added or removed in the selectize
	 */
	$('#column_id').on('update_columns', function(e) {
		e.preventDefault();
		var columns_checked_array = $(this).val();
		if (columns_checked_array && (columns_checked_array instanceof Array)) {
			// Hide all the columns, then reshow the ones that are selected
			$('#spy_table').DataTable().columns().visible(false);
			// Alwasy enable the first column
			col = $('#spy_table').DataTable().column(0);
			col.visible(true);
			col = $('#spy_table').DataTable().column(1);
			col.visible(true);
			col = $('#spy_table').DataTable().column(2);
			col.visible(true);
			$.each(columns_checked_array, function(index, value) {
				// Enable the column for datatables
				col = $('#spy_table').DataTable().column(value + ":name");
				col.visible(true);
			});
		}
		localStorage.setItem(spyColumnStorageName, JSON.stringify(columns_checked_array));
	}).change(function(value) {
		$('#column_id').trigger('update_columns');
	});
});
//-->
</script>