<?php
	/* @var $export Flux\Export */
	$export = $this->getContext()->getRequest()->getAttribute("export", array());
	$clients = $this->getContext()->getRequest()->getAttribute("clients", array());
?>
<div class="page-header">
	<h1><?php echo $export->getName() ?></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/export/export-search">Exports</a></li>
	<li class="active">Export #<?php echo $export->getId() ?></li>
</ol>

<!-- Page Content -->
<div class="col-md-8">
	<h2><?php echo number_format($export->getNumRecords(), 0, null, ',') ?> records</h2>
	<div class="help-block"><?php echo $export->getName() ?></div><p />
	Split: 
	<?php if ($export->getSplit()->getSplitId() > 0) { ?>
		<a href="/export/split?_id=<?php echo $export->getSplit()->getSplitId() ?>"><?php echo $export->getSplit()->getSplitName() ?></a>
	<?php } else { ?>
		<i class="text-muted">-- none --</i>
	<?php } ?>
	<p />
	Fulfillment: <a href="/admin/fulfillment?_id=<?php echo $export->getFulfillment()->getFulfillmentId() ?>"><?php echo $export->getFulfillment()->getFulfillmentName() ?></a><p />
	<p />
	Created: <?php echo date('m/d/Y g:i a', $export->getCreated()->sec) ?><p />
	<p />
	Records Exported: <?php echo number_format($export->getNumRecordsSuccessful(), 0, null, ',') ?> <span class="small">(<?php echo number_format((($export->getNumRecordsSuccessful() / ($export->getNumRecords() > 0 ? $export->getNumRecords() : 1))  * 100), 0) ?>%)</span><br />
	Records Failed: <?php echo number_format($export->getNumRecordsError(), 0, null, ',') ?> <span class="small">(<?php echo number_format((($export->getNumRecordsError() / ($export->getNumRecords() > 0 ? $export->getNumRecords() : 1)) * 100), 0) ?>%)</span><br />
</div>
<div class="well small col-md-4">
	<div style="width:100%;" class="clearfix small">
		<div style="width:20%;border-Right:2px solid gray;float:left;padding:2px;">
			Preparing
			<?php if (is_object($export->getStartTime())) { ?>
				<br /><?php echo date('m/d/Y g:i a', $export->getStartTime()->sec) ?>
			<?php } ?>
		</div>
		<div class="text-center" style="width:30%;border-Right:2px solid gray;float:left;padding:2px;">
			Finding Records
			<?php if ($export->getFindingRecordsTime() > 0) { ?>
				<br /><?php echo number_format($export->getFindingRecordsTime(), 2, null, ',') ?> seconds
			<?php } ?>
		</div>
		<div class="text-center" style="width:30%;border-Right:2px solid gray;float:left;padding:2px;">
			Sending
			<?php if ($export->getSendingRecordsTime() > 0) { ?>
				<br /><?php echo number_format($export->getSendingRecordsTime(), 2, null, ',') ?> seconds
			<?php } ?>
		</div>
		<div class="text-right" style="width:20%;float:left;padding:2px;">
			Finished
			<?php if (is_object($export->getEndTime())) { ?>
				<br /><?php echo date('m/d/Y g:i a', $export->getEndTime()->sec) ?>
			<?php } ?>
		</div>
	</div>
	<div class="progress">
		<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo number_format($export->getPercentComplete(), 0) ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo number_format($export->getPercentComplete(),0) ?>%;"></div>
	</div>
</div>
<div class="clearfix"></div>
<hr />
<div class="help-block">View a sample of the data included in this export</div>
<div class="panel panel-primary">
	<div id='export_queue-header' class='grid-header panel-heading clearfix'>
		<form id="export_queue_search_form" method="GET" action="/api">
			<input type="hidden" name="func" value="/export/export-queue">
			<input type="hidden" name="format" value="json" />
			<input type="hidden" id="page" name="page" value="1" />
			<input type="hidden" id="export_id" name="export_id" value="<?php echo $export->getId() ?>" />
			<input type="hidden" id="items_per_page" name="items_per_page" value="500" />
			<input type="hidden" id="sort" name="sort" value="name" />
			<input type="hidden" id="sord" name="sord" value="asc" />
			<div class="pull-right">
				<input type="text" class="form-control" placeholder="filter by lead" size="35" id="txtSearch" name="lead[lead_id]" value="" />
			</div>
		</form>
	</div>
	<div id="export_queue-grid"></div>
	<div id="export_queue-pager" class="panel-footer"></div>
</div>

<hr />
<div class="help-block">View the log associated with this export</div>
<br/>
<?php if (trim($export->getProcessingLog()) == '') { ?>
	<?php if (!file_exists($export->getLogFilename())) { ?>
		<div class="alert alert-warning" role="alert">There is no log because this export hasn't started processing yet or we could not find the processing file at <?php echo $export->getLogFilename() ?></div>
	<?php } else { ?>
		<div class="alert alert-warning" role="alert">There is no log because this export hasn't started processing yet</div>
	<?php } ?>
<?php } else { ?>
<pre class="code"><?php echo $export->getProcessingLog() ?></pre>
<?php } ?>

<script>
//<!--
$(document).ready(function() {
	var columns = [
		{id:'_id', name:'id', field:'_id', sort_field:'_id', def_value: ' ', sortable:true, type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return '<a href="/export/export-queue?export_id=<?php echo $export->getId() ?>&_id=' + dataContext._id + '">' + value + '</a>';
		}},
		{id:'lead_id', name:'item', field:'lead.lead_id', def_value: ' ', sortable:true, type: 'string', width:250, formatter: function(row, cell, value, columnDef, dataContext) {
			var ret_val = '<div style="line-height:16pt;">'
			ret_val += '<a href="/export/export-queue?export_id=<?php echo $export->getId() ?>&_id=' + dataContext._id + '">' + dataContext.lead.lead_id + '</a>';
			ret_val += '<div class="small text-muted">' + dataContext.lead.lead_id + '</div>';
			ret_val += '</div>';
			return ret_val;
		}},
		{id:'name', name:'name', field:'qs', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (dataContext.qs.fn != undefined) {
				var name = dataContext.qs.fn;
				if (dataContext.qs.ln != undefined) {
					name += ' ' + dataContext.qs.ln;
				} else if (dataContext.qs.lastname != undefined) {
					name += ' ' + dataContext.qs.lastname;
				}
				return name;
			} else if (dataContext.qs.firstname != undefined) {
				var name = dataContext.qs.firstname;
				if (dataContext.qs.ln != undefined) {
					name += ' ' + dataContext.qs.ln;
				} else if (dataContext.qs.lastname != undefined) {
					name += ' ' + dataContext.qs.lastname;
				}
				return name;
			} else if (dataContext.qs.FirstName != undefined) {
				var name = dataContext.qs.FirstName;
				if (dataContext.qs.LastName != undefined) {
					name += ' ' + dataContext.qs.LastName;
				}
				return name;
			} else {
				return '<i class="text-muted">missing</i>';
			}
		}},
		{id:'url', name:'url', field:'url', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			return value;
		}},
		{id:'last_sent_time', name:'last sent', field:'last_sent_time', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value == 0) {
				return '<i class="text-muted">not sent yet</i>';
			} else {
				return moment.unix(value.sec).calendar();
			}
		}},
		{id:'is_error', name:'error', field:'is_error', def_value: ' ', sortable:true, cssClass: 'text-center', type: 'string', formatter: function(row, cell, value, columnDef, dataContext) {
			if (value) {
		 		return '<span class="text-danger">Yes</span>';
			} else {
				return '';
			}
		}}
	];

  	slick_grid = $('#export_queue-grid').slickGrid({
		pager: $('#export_queue-pager'),
		form: $('#export_queue_search_form'),
		columns: columns,
		useFilter: false,
		cookie: '<?php echo $_SERVER['PHP_SELF'] ?>',
		pagingOptions: {
			pageSize: 25,
			pageNum: 1
		},
		slickOptions: {
			defaultColumnWidth: 150,
			forceFitColumns: true,
			enableCellNavigation: false,
			width: 800,
			rowHeight: 48
		}
	});

  	$("#txtSearch").keyup(function(e) {
		// clear on Esc
		if (e.which == 27) {
			this.value = "";
		} else if (e.which == 13) {
			$('#export_queue_search_form').trigger('submit');
		}
	});
		  	
	$('#export_queue_search_form').trigger('submit');
	
	$('#btn_delete,#btn_delete_sm').click(function() {
		if (confirm('Are you sure you want to delete this export and completely remove it from the system?')) {
			$.rad.del('/api', { func: '/export/export/<?php echo $export->getId() ?>' }, function(data) {
				$.rad.notify('Export Removed', 'This export has been removed from the system.');
			});
		}
	});
});
//-->
</script>