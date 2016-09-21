<?php
	$offer = $this->getContext()->getRequest()->getAttribute("offer", array());
	$spy_report = $this->getContext()->getRequest()->getAttribute("spy_report", array());
	$datafields = $this->getContext()->getRequest()->getAttribute("datafields", array());

	$selected_columns = (array)$spy_report->retrieveValue('column_id');
?>
<div class="help-block">Easily spy on the current traffic received on this offer</div>
<br/>
<form class="form-horizontal" id="offer_spy_form" name="offer_spy_form" method="GET" action="/lead/spy" autocomplete="off" role="form">
	<input type="hidden" name="last_end_time" value="" />
	<input type="hidden" name="offer_id[]" value="<?php echo $offer->getId() ?>" />
	<input type="hidden" name="sort" value="_e.*.t" />
	<input type="hidden" name="sord" value="desc" />

	<div class="form-group">
		<label class="col-sm-2 control-label" for="date_range">Report Date</label>
		<div class="col-sm-5 col-xs-6">
			<select name="date_range" id="date_range" class="form-control">
				<?php foreach(\Flux\SpyReport::retrieveDateRanges() AS $date_range_id => $date_range_name) { ?>
				<option value="<?php echo $date_range_id; ?>"<?php echo $spy_report->getDateRange() == $date_range_id ? ' selected="selected"' : ''; ?>><?php echo $date_range_name; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-sm-5 col-xs-6">
			<select name="tz_modifier" id="tz_modifier" class="form-control">
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
		<label class="col-sm-2 control-label" for="date_range">Columns</label>
		<div class="col-sm-10">
			<select class="form-control selectize" name="column_id[]" id="column_id" multiple placeholder="No Columns">
				<?php foreach($datafields AS $datafield) { ?>
					<option value="<?php echo $datafield->getKeyName() ?>"<?php echo in_array($datafield->getKeyName(), $selected_columns) ? ' selected' : ''; ?>><?php echo $datafield->getName() ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="items_per_page">Limit</label>
		<div class="col-sm-10">
			<select name="items_per_page" id="items_per_page" class="form-control" placeholder="Limit">
				<option value="10">Limit 10</option>
				<option value="100">Limit 100</option>
				<option value="1000">Limit 1000</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="btn-group" data-toggle="buttons">
				<input type="button" name="run_spy" value="Spy Stopped" id="run_spy" class="btn btn-warning" />
			</div>
			<button type="button" class="btn btn-primary btn-spy-export" name="export" value="1">Export Leads</button>
		</div>
	</div>
	<table id="spy_table" class="table table-striped table-bordered table-hover table-condensed table-responsive">
		<thead>
			<tr id="spy_thead_tr">
				<th>Id</th>
				<?php foreach($datafields AS $datafield) { ?>
					<th><?php echo $datafield->getName() ?></th>
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
			$.rad.get('/lead/spy', $('#offer_spy_form').serialize(), function(data) {
				if ($('#run_spy').val() == 'Spy Running') {
					spy_timeout = setTimeout(function(){
						$('#spy_table').DataTable().ajax.reload();
					}, 5000);
				}

				callback({
					data: data.entries ? data.entries : [],
					recordsTotal: data.pagination.total_rows,
					recordsFiltered: data.pagination.total_rows
				});
			});

		},
		searching: false,
		paging: false,
		deferRender: true,
		deferLoading: 0, // This keeps the table from trying to load until we click the Spy Running button
		dom: 'ti',
		columns: [
			{ name: "_id", data: "_id", createdCell: function (td, cellData, rowData, row, col) {
				$(td).html('<a href="/lead/lead?_id=' + cellData + '">' + cellData + '</a>');
			}},
			<?php foreach($datafields AS $datafield) { ?>
				<?php if ($datafield->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) { ?>
					{ name: "<?php echo $datafield->getKeyName() ?>", data: "_d.<?php echo $datafield->getKeyName() ?>" },
				<?php } else if ($datafield->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) { ?>
					{ name: "<?php echo $datafield->getKeyName() ?>", data: "_t.<?php echo $datafield->getKeyName() ?>", createdCell: function (td, cellData, rowData, row, col) {
						<?php if ($datafield->getKeyName() == \Flux\DataField::DATA_FIELD_REF_CLIENT_ID) { ?>
							if (cellData.name) {
								$(td).html('<a href="/client/client?_id=' + cellData._id + '">' + cellData.name + '</a>');
							} else {
								$(td).html('<em class="text-muted">-- not set --</em>');
							}
						<?php } else if ($datafield->getKeyName() == \Flux\DataField::DATA_FIELD_REF_OFFER_ID) { ?>
							if (cellData.name) {
								$(td).html('<a href="/offer/offer?_id=' + cellData._id + '">' + cellData.name + '</a>');
							} else {
								$(td).html('<em class="text-muted">-- not set --</em>');
							}
						<?php } else { ?>
							$(td).html(cellData);
						<?php } ?>
					}},
				
				<?php } else if ($datafield->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) { ?>
					{ name: "<?php echo $datafield->getKeyName() ?>", data: function data( row, type, set, meta ) {
						var ret_val;
						$.each(row._e, function(i, item) {
							if (item.n == '<?php echo $datafield->getKeyName() ?>') {
								// This is our event
								<?php if ($datafield->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
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
				targets: [ 0 ], // Show the first column by default
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
	var spyColumnStorageName = <?php echo json_encode('spy_report_column_' . $offer->retrieveValueHtml('_id')); ?>;
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

	$('#tz_modifier,#date_range,#items_per_page').selectize();
});
//-->
</script>