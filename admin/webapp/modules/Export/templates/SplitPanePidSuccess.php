<?php
    $split = $this->getContext()->getRequest()->getAttribute("split", array());
?>
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
