<?php
	/* @var $export_queue Flux\ExportQueue */
	$export_queue = $this->getContext()->getRequest()->getAttribute("export_queue", array());
?>
<div class="page-header">
	<h1>Queue Item #<?php echo $export_queue->getId() ?></h1>
</div>
<!-- Add breadcrumbs -->
<ol class="breadcrumb">
	<li><a href="/export/export-search">Exports</a></li>
	<li><a href="/export/export?_id=<?php echo $export_queue->getExport()->getExportId() ?>">Export #<?php echo $export_queue->getExport()->getExportId() ?></a></li>
	<li class="active">Export Queue Item #<?php echo $export_queue->getId() ?></li>
</ol>

<!-- Page Content -->
<div id="tab-content-container" class="tab-content">
	<div id="tabs-main" class="tab-pane active">
		<div class="help-block">You can view a lead on this screen and see how it was tracked</div>
		<br/>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					Data
				</div>
				<div class="panel-body">
					<dl class="dl-horizontal">
						<?php foreach ($export_queue->getQs() as $key => $value) { ?>
							<dt><?php echo $key ?>:</dt>
							<dd><?php echo is_string($value) ? $value : var_export($value, true) ?>&nbsp;</dd>
						<?php } ?>
					</dl>
				</div>
			</div>
			<p />
			<div class="panel panel-default">
				<div class="panel-heading">
					Request
				</div>
				<div class="panel-body">
					<pre><?php echo $export_queue->getUrl() ?>?<?php echo http_build_query($export_queue->getQs(), '&') ?></pre>
				</div>
			</div>
			<p />
			<div class="panel panel-default">
				<div class="panel-heading">
					Response
				</div>
				<div class="panel-body">
					<pre><?php echo str_replace("<", "&lt;", $export_queue->getResponse()) ?></pre>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					Export Details
				</div>
				<div class="panel-body">
					<dl class="dl-horizontal">
						<dt>Lead:</dt><dd><a href="/lead/lead?_id=<?php echo $export_queue->getLead()->getLeadId() ?>"><?php echo $export_queue->getLead()->getLeadId() ?>&nbsp;</a></dd>
						<p />
						<dt>Export:</dt><dd><a href="/export/export?_id=<?php echo $export_queue->getExport()->getExportId() ?>"><?php echo $export_queue->getExport()->getExportName() ?>&nbsp;</a></dd>
					</dl>
				</div>
			</div>
			<p />
			<div class="panel panel-default">
				<div class="panel-heading">
					Queue Information
				</div>
				<div class="panel-body">
					<dl class="dl-horizontal">
						<dt>Url:</dt><dd><?php echo $export_queue->getUrl() ?>&nbsp;</dd>
						<dt>Last Attempt:</dt>
						<dd>
							<?php if ($export_queue->getLastSentTime() != null) { ?>
								<?php echo date('m/d/Y g:i s', $export_queue->getLastSentTime()->sec) ?>&nbsp;
							<?php } else { ?>
								<i>-- not sent yet --</i>
							<?php } ?>
						</dd>
						<dt>Response Time:</dt><dd><?php echo number_format($export_queue->getResponseTime(), 3) ?>s&nbsp;</dd>
					</dl>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
//<!--
$(document).ready(function() {
   
});
//-->
</script>