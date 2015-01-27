<?php
	$export = $this->getContext()->getRequest()->getAttribute("export", array());
	$export->updateLog();
?>
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