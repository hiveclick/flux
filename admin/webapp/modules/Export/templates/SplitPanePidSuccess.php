<?php
	$split = $this->getContext()->getRequest()->getAttribute("split", array());
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
	<h2><a href="/export/split-search">Splits</a> <small><?php echo $split->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse" class="navbar-collapse collapse">
	<ul id="split_tabs" class="nav nav-pills">
		<li><a id="tabs-a-main" href="/export/split?_id=<?php echo $split->getId() ?>">Split</a></li>
		<li><a id="tabs-a-positions" href="/export/split-pane-position?_id=<?php echo $split->getId() ?>">Positions</a></li>
		<li class="active"><a id="tabs-a-process" href="/export/split-pane-pid?_id=<?php echo $split->getId() ?>">Processes</a></li>
		<li><a id="tabs-a-spy" href="/export/split-pane-spy?_id=<?php echo $split->getId() ?>">Queue</a></li>
	</ul>
</div>
<div class="help-block">Shows the process information for this split and allows you to reset it</div>
<br/>
<div class="panel panel-default">
	<div class="panel-heading">Process Information</div>
	<div class="panel-body">
		This split was last run at <?php echo date('m/d/Y g:i:s a', $split->getLastRunTime()->sec) ?>.  If it is frozen, you can attempt to clear the PID information and restart it.
		
		<p />
		<form id="split_clear_pid_form" method="POST" action="/api">
			<input type="hidden" name="func" value="/export/split-clear-pid" />
			<input type="hidden" name="_id" value="<?php echo $split->getId() ?>" />
			<input type="submit" class="btn btn-danger" name="btn_submit" value="clear pid" />
		</form>
	</div>
</div>
<script>
//<!--
$(document).ready(function() {
	$('#split_clear_pid_form').form(function(data) {
		if (data.record) {
			$.rad.notify('Split reset', 'This split has been reset and will resume normal operation within a few minutes');
		}
	});
});
//-->
</script>
