<?php
    /* @var $export_queue Flux\ExportQueue */
    $export_queue = $this->getContext()->getRequest()->getAttribute("export_queue", array());
?>
<div id="header">
    <div class="pull-right visible-xs">
        <button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <h2><a href="/export/export?_id=<?php echo $export_queue->getExportId() ?>">Export #<?php echo $export_queue->getExportId() ?></a> <small>Queue Item #<?php echo $export_queue->getId() ?></small></h2>
</div>
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
							<dt><?php echo $key ?>:</dt><dd><?php echo $value ?>&nbsp;</dd>
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
						<dt>Lead:</dt><dd><a href="/lead/lead?_id=<?php echo $export_queue->getLeadId() ?>"><?php echo $export_queue->getLead()->getId() ?>&nbsp;</a></dd>
						<p />
						<dt>Export:</dt><dd><a href="/export/export?_id=<?php echo $export_queue->getExportId() ?>"><?php echo $export_queue->getExport()->getName() ?>&nbsp;</a></dd>
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
						<dt>Last Attempt:</dt><dd><?php echo date('m/d/Y g:i s', $export_queue->getLastSentTime()->sec) ?>&nbsp;</dd>
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