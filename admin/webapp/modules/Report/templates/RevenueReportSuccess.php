<?php
	$revenue_report = $this->getContext()->getRequest()->getAttribute('revenue_report', array());
	$offers = $this->getContext()->getRequest()->getAttribute('offers', array());
	$campaigns = $this->getContext()->getRequest()->getAttribute('campaigns', array());
?>
<div class="clearfix">
	<div class="col-lg-11  col-md-10  col-sm-9" id="header">
		 <h2><a href="/report/revenue-report">Revenue Report</a> <small> Track your money</small></h2>
	</div>
</div>
<div class="help-block">Easily track which offers and campaigns are making you the most money</div>
<br/>
<form class="form-horizontal" id="revenue_report_form" name="revenue_report_form" method="GET" action="/api" autocomplete="off">
	<input type="hidden" name="func" value="/report/revenue-report" />

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for="__sr_id">Saved Report</label>
		<div class="col-sm-10 col-xs-12">
			<select name="__sr_id" id="__sr_id" class="form-control">
				<option value="">&nbsp;</option>
				<?php foreach(\Flux\SavedReport::retrieveReadableReports() AS $savedReport) { ?>
				<option data-querystring="<?php echo $savedReport->retrieveValueHtml('report_querystring'); ?>" value="<?php echo $savedReport->retrieveValueUrl('_id'); ?>"><?php echo $savedReport->retrieveValueHtml('name'); ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label hidden-xs" for=""></label>
		<div class="col-sm-10 col-xs-12">
			<a data-toggle="collapse" href="#report_filters" id="report_filters_btn">Show Filters &#9660;</a>
		</div>
	</div>

	<div id="report_filters" class="panel-collapse collapse">
		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="date_range">Report Date</label>
			<div class="col-sm-5 col-xs-6">
				<select name="date_range" id="date_range" class="form-control" placeholder="Report Date">
					<?php foreach(\Flux\RevenueReport::retrieveDateRanges() AS $date_range_id => $date_range_name) { ?>
					<option value="<?php echo $date_range_id; ?>"<?php echo $revenue_report->getDateRange() == $date_range_id ? ' selected="selected"' : ''; ?>><?php echo $date_range_name; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-5 col-xs-6">
				<select name="tz_modifier" class="form-control" placeholder="Timezone">
					<?php foreach(\Flux\Timezone::retrieveTimezonesFormatted() AS $timezone_id => $timezone_string) { ?>
						<option value="<?php echo $timezone_id; ?>"><?php echo $timezone_string; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group custom-range" style="display:none;">
			<label class="col-sm-2 control-label hidden-xs" for="start_time">Custom Date</label>
			<div class="col-sm-5 col-xs-6">
				<div class="input-group">
					<input type="text" id="start_time" name="start_time" placeholder="Start Date" class="form-control" value="<?php echo $revenue_report->retrieveValue('start_time'); ?>" />
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				</div>
			</div>
			<div class="col-sm-5 col-xs-6">
				<div class="input-group">
					<input type="text" id="end_time" name="end_time" placeholder="End Date" class="form-control" value="<?php echo $revenue_report->retrieveValue('end_time'); ?>" />
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="event_window">Event Window</label>
			<div class="col-sm-10">
				<select class="form-control" name="event_window" id="event_window">
					<?php foreach(\Flux\RevenueReport::getEventWindows() AS $event_window_id => $event_window_name) { ?>
					<option value="<?php echo $event_window_id; ?>"<?php echo ($revenue_report->retrieveValue('event_window') === $event_window_id) ? ' selected' : ''; ?>><?php echo $event_window_name; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="offer_id">Offers</label>
				<div class="col-sm-10">
				<select class="form-control selectize" name="offer_id[]" id="offer_id" multiple placeholder="Any Offer">
					<?php foreach($offers AS $offer) { ?>
					<option value="<?php echo $offer->retrieveValue('_id'); ?>"<?php echo in_array((string) $offer->retrieveValue('_id'), (array)$revenue_report->retrieveValue('offer_id')) ? ' selected' : ''; ?>><?php echo $offer->retrieveValue('name'); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="campaign_id">Campaigns</label>
				<div class="col-sm-10">
				<select class="form-control selectize" name="campaign_id[]" id="campaign_id" multiple placeholder="Any Campaign">
					<?php foreach($campaigns AS $campaign) { ?>
					<option value="<?php echo $campaign->retrieveValue('_id'); ?>"<?php echo in_array((string) $campaign->retrieveValue('_id'), (array)$revenue_report->retrieveValue('campaign_id')) ? ' selected' : ''; ?>><?php echo $campaign->getClient()->retrieveValue('name') . ' &ndash; ' . $campaign->getOffer()->retrieveValue('name'); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="event_id">Events</label>
				<div class="col-sm-10">
				<select class="form-control selectize" name="event_id[]" id="event_id" multiple placeholder="Any Event">
					<?php foreach(\Flux\DataField::retrieveActiveEvents() AS $event) { ?>
					<option value="<?php echo $event->retrieveValue('_id'); ?>"<?php echo in_array((string) $event->retrieveValue('_id'), (array)$revenue_report->retrieveValue('event_id')) ? ' selected' : ''; ?>><?php echo $event->retrieveValue('name'); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="group_id">Breakdown</label>
			<div class="col-sm-10">
				<select class="form-control selectize" name="group_id[]" id="group_id" multiple placeholder="No Breakdown">
					<?php foreach(\Flux\RevenueReport::getGroups() AS $revenue_group) { ?>
					<option value="<?php echo $revenue_group['group_id']; ?>"<?php echo $revenue_report->retrieveValue('group_id') == $revenue_group['group_id'] ? ' selected' : ''; ?>><?php echo htmlspecialchars($revenue_group['text']); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label hidden-xs" for="column_id">Columns</label>
			<div class="col-sm-10">
				<select class="form-control selectize" name="column_id[]" id="column_id" multiple placeholder="No Columns">
					<?php foreach(\Flux\RevenueReport::getColumns() AS $revenue_column) { ?>
					<option value="<?php echo $revenue_column['column_id']; ?>"<?php echo in_array((string) $revenue_column['column_id'], (array)$revenue_report->retrieveValue('column_id')) ? ' selected' : ''; ?>><?php echo htmlspecialchars($revenue_column['text']); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="run_report" class="btn btn-primary btn-report" value="1">Run Report</button>
			<button type="submit" name="run_export" class="btn btn-primary btn-report" value="1">Export Report</button>
			<button type="button" name="open_save_modal" class="btn btn-primary" value="1" data-toggle="modal" data-target="#saveReportModal">Save Report</button>
		</div>
	</div>

</form>

<table id="revenue_table" class="table table-striped table-bordered table-hover table-condensed table-responsive">
	<thead>
		<tr id="revenue_thead_tr">
		</tr>
	</thead>
	<tbody id="revenue_tbody"></tbody>
</table>

<!-- Saved Report Modal -->
<div class="modal fade" id="saveReportModal" tabindex="-1" role="dialog" aria-labelledby="saveReportModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="saveReportModalLabel">Save Report</h4>
			</div>
			<form id="saved_report_form" name="saved_report_form" class="form-horizontal" method="POST" action="/api" autocomplete="off">
				<div class="modal-body">
					<br />
					<input type="hidden" name="func" value="/report/saved-report" />
					<input type="hidden" name="status" value="<?php echo \Flux\SavedReport::REPORT_STATUS_ACTIVE ?>" />
					<input type="hidden" name="user_id" value="<?php echo $this->getUserDetails()->getId() ?>" />
					<input type="hidden" name="report_querystring" value="" />
					<div class="form-group">
						<label class="col-sm-3 control-label hidden-xs" for="date_range">Report</label>
						<div class="col-sm-8">
							<select name="_id" class="form-control saved-report-select">
								<option value="" style="font-style:italic;">New Report</option>
								<optgroup label="Saved Reports">
									<?php foreach(\Flux\SavedReport::retrieveUserReports($this->getUserDetails()->getId()) AS $savedReport) { ?>
									<option value="<?php echo $savedReport->getId() ?>"><?php echo $savedReport->getName() ?></option>
									<?php } ?>
								</optgroup>
							</select>
						</div>
					</div>
					<div class="form-group" class="">
						<label class="col-sm-3 control-label hidden-xs" for="date_range">Name</label>
						<div class="col-sm-8">
							<input type="text" name="name" class="form-control" value="" placeholder="Name" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label hidden-xs" for="type">Type</label>
						<div class="col-sm-8">
							<select name="type" class="form-control" placeholder="Type" required>
								<?php foreach(\Flux\SavedReport::retrieveTypes() AS $type_id => $type_name) { ?>
								<option value="<?php echo $type_id; ?>"><?php echo $type_name; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="run_save" class="btn btn-primary btn-report-save" value="1">Save Report</button>
					<button type="submit" name="delete_save" class="btn btn-danger btn-report-save" value="1" style="display:none;">Delete Report</button>
					<button type="button" name="cancel_save" class="btn btn-default" value="1" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Saved Report Modal -->

<script>
//<!--
$(document).ready(function() {
	$('#column_id,#group_id,#event_id,#campaign_id,#offer_id').selectize();

	//default initial time
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
		if ($(this).val() == <?php echo \Flux\RevenueReport::DATE_RANGE_CUSTOM; ?>) {
			$('.custom-range').show();
		} else {
			$('.custom-range').hide();
		}
	}).trigger('change');

	$('[name=__sr_id]').on('change', function() {
		var query_string = $(this).find(':selected').data('querystring');
		if($.type(query_string) === 'string') {
			query_string = decodeURIComponent(query_string);
			var query_string_array = String(query_string).replace(/^&/, '').replace(/&$/, '').split('&');
			var fixStr = function(str) {
				return decodeURIComponent(str);
			};
			$('form[name=revenue_report_form] :input').each(function() {
				if(this.hasOwnProperty('selectize')){
					var selectize_control = this.selectize;
					selectize_control.clear();
				}
			});
			$.each(query_string_array, function(idx, pair_string) {
				console.log(pair_string);
				var pair_array = pair_string.split('=');
				var pair_key = decodeURIComponent(pair_array[0]);
				var pair_value = (pair_array.length < 2) ? '' : decodeURIComponent(pair_array[1]);
				var $el = $('[name="'+pair_key+'"]');
				if($el.length > 0) {
					var type = $el.attr('type');
					switch(type){
						case 'checkbox':
							$el.attr('checked', 'checked');
							break;
						case 'radio':
							$el.filter('[value="'+pair_value+'"]').attr('checked', 'checked');
							break;
						default:
							var domobj = $el[0];
							if(domobj.hasOwnProperty('selectize')) {
								var selectize_control2 = domobj.selectize;
								selectize_control2.addItem(pair_value);
							} else {
								$el.val(pair_value);
							}
							break;
					}
				}
			});
		}
	});

	var revenue_groups = <?php echo json_encode(\Flux\RevenueReport::getGroups()); ?>;
	var revenue_columns = <?php echo json_encode(\Flux\RevenueReport::getColumns()); ?>;
	var revenue_request;
	var save_report_request;
	var report_exporting = false;
	var columns_checked_array;

	$('#revenue_report_form').form(function(data) {
		$('#report_filters').collapse('hide');
		$('#revenue_table').trigger('update_columns');
		$('#revenue_tbody').empty();
		if (data.entries) {
			var newrows;
			var row_ids = [];

			var groupselect = '<select class=""><option value="">&nbsp;</option>';
			$.each(revenue_groups, function(index2, value2) {
				groupselect += '<option value="' + value2.group_id + '">' + value2.text + ' </option>';
			});
			groupselect += '</select>';


			var columns_for_display = [];
			if (columns_checked_array && (columns_checked_array instanceof Array)) {
				$.each(columns_checked_array, function(key, value) {
					var revenue_column_array = $.grep(revenue_columns, function(e) { return e.column_id == value; });
					var revenue_column = revenue_column_array[0];
					columns_for_display.push(revenue_column);
				});
			}

			$.each(data.entries, function(index, value) {
				newrows += '<tr>';

				//group column
				if(value._id == undefined) {
					newrows += '<td><i>None</i></td>';
				} else {
					//_id should be an object, but we'll double check anyway
					$.each(value._id, function(id_key, id_value) {
						if(id_value == undefined) {
							newrows += '<td><i>None</i></td>';
						} else {
							newrows += '<td>' + id_value + '</td>';
						}
					});
				}

				$.each(columns_for_display, function(index2, value2) {
					var col_class = 'revenue_td_' + value2.column_id;
					var column_name = 'c' + value2.column_id;

					newrows += '<td class="' + col_class + '">';

					if(value.hasOwnProperty(column_name)) {
						if(value2.format_type == <?php echo json_encode(\Flux\ReportColumn::COLUMN_FORMAT_PERCENTAGE); ?>) {
							newrows += (value[column_name] * 100).toFixed(2) + '%';
						} else {
							newrows += value[column_name];
						}
					}

					newrows += '</td>';
				});

				//actions column
				newrows += '<td>' + groupselect + '</td>';

				newrows += '</tr>';

			});
			$('#revenue_tbody').prepend(newrows);
		}
	});

	$('#report_filters').on('hide.bs.collapse', function () {
		$('#report_filters_btn').html('Show Filters &#9660;');
	});

	$('#report_filters').on('show.bs.collapse', function () {
		$('#report_filters_btn').html('Hide Filters &#9650;');
	});

	$('form[name=saved_report_form] :input.saved-report-select').on('change', function() {
		if($(this).val().length > 0) {
			$('form[name=saved_report_form] [name=name]').val($('form[name=saved_report_form] [name=_id] option:selected').text());
			$('form[name=saved_report_form] [name=delete_save]').show();
		} else {
			$('form[name=saved_report_form] [name=delete_save]').hide();
		}
	});

	$('#saved_report_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Report Saved', 'This report has been added to your saved reports');
		}
	}, {
		prepare: function() {
			var report_querystring = $('form[name=revenue_report_form] :input').not(':input[name=__sr_id]').serialize();
			$('form[name=saved_report_form] input[name=report_querystring]').val(encodeURIComponent(report_querystring));
		}
	});

	$('#revenue_table').on('update_columns', function() {
		$('#revenue_thead_tr').empty();

		columns_checked_array = $('#group_id').val();
		if (columns_checked_array && (columns_checked_array instanceof Array)) {
			$.each(columns_checked_array, function(index, value) {
				var revenue_group_array = $.grep(revenue_groups, function(e) { return e.group_id == value; });
				var revenue_group = revenue_group_array[0];
				$('#revenue_thead_tr').append('<th class="revenue_th_' + revenue_group.group_id + '">' + revenue_group.text + '</th>');
			});
		} else {
			$('#revenue_thead_tr').append('<th class="revenue_th_gnull">Grand Total</th>');
		}

		columns_checked_array = $('#column_id').val();
		if (columns_checked_array && (columns_checked_array instanceof Array)) {
			$.each(columns_checked_array, function(index, value) {
				var revenue_column_array = $.grep(revenue_columns, function(e) { return e.column_id == value; });
				var revenue_column = revenue_column_array[0];
				$('#revenue_thead_tr').append('<th class="revenue_th_' + revenue_column.column_id + '">' + revenue_column.text + '</th>');
			});
		}
		$('#revenue_thead_tr').append('<th class="revenue_th_actions">Breakdown</th>');
	});
});
//-->
</script>