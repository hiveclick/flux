<?php
    $client_export = $this->getContext()->getRequest()->getAttribute("client_export", array());
    $scheduling = $client_export->getScheduling();
?>
<div class="help-block">Schedule when an export should be sent to this client</div>
<br/>
<form class="form-horizontal" id="export_scheduling_form" name="export_scheduling_form" method="PUT" action="/api" autocomplete="off" role="form">
    <input type="hidden" name="func" value="/client/client-export-scheduling" />
    <input type="hidden" name="_id" value="<?php echo $client_export->getId() ?>" />
        
    <div class="form-group">
        <div class="col-sm-6">
            <label class="" for="scheduling[interval]">How often do you want to send the data?</label>
        </div>
        <div class="col-sm-6">
            <select name="scheduling[interval]" id="scheduling_interval">
                <optgroup label="Realtime">
                    <option value="immediately" <?php echo (isset($scheduling['interval']) && $scheduling['interval'] == 'immediately') ? "selected" : "" ?>>Immediately</option>
                </optgroup>
                <optgroup label="Batched">
                    <option value="daily" <?php echo (isset($scheduling['interval']) && $scheduling['interval'] == 'daily') ? "selected" : "" ?>>Daily</option>
                    <option value="weekly" <?php echo (isset($scheduling['interval']) && $scheduling['interval'] == 'weekly') ? "selected" : "" ?>>Weekly</option>
                    <option value="monthly_first" <?php echo (isset($scheduling['interval']) && $scheduling['interval'] == 'monthly_first') ? "selected" : "" ?>>Monthly on the 1st</option>
                    <option value="monthly" <?php echo (isset($scheduling['interval']) && $scheduling['interval'] == 'monthly') ? "selected" : "" ?>>Monthly on the 31st</option>
                </optgroup>
            </select>
        </div>
    </div>
    
    <p />
    <div class="form-group">
        <div class="col-sm-6">
            <label class="" for="scheduling[days]">Select the days that this user can accept data?</label>
        </div>
        <div class="col-sm-6">
            <select name="scheduling[days][]" id="scheduling_days" multiple>
                <option value="0" <?php echo isset($scheduling['days']) && in_array('0', $scheduling['days']) ? "selected" : "" ?>>Sunday</option>
                <option value="1" <?php echo isset($scheduling['days']) && in_array('1', $scheduling['days']) ? "selected" : "" ?>>Monday</option>
                <option value="2" <?php echo isset($scheduling['days']) && in_array('2', $scheduling['days']) ? "selected" : "" ?>>Tuesday</option>
                <option value="3" <?php echo isset($scheduling['days']) && in_array('3', $scheduling['days']) ? "selected" : "" ?>>Wednesday</option>
                <option value="4" <?php echo isset($scheduling['days']) && in_array('4', $scheduling['days']) ? "selected" : "" ?>>Thursday</option>
                <option value="5" <?php echo isset($scheduling['days']) && in_array('5', $scheduling['days']) ? "selected" : "" ?>>Friday</option>
                <option value="6" <?php echo isset($scheduling['days']) && in_array('6', $scheduling['days']) ? "selected" : "" ?>>Saturday</option>
            </select>
        </div>
    </div>
    

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="btn_submit" class="btn btn-success" value="Save Scheduling" />
        </div>
    </div>

</form>
<script>
//<!--
$(document).ready(function() {
    $('#scheduling_interval').selectize();
	$('#scheduling_days').selectize();
    
    $('#export_scheduling_form').form(function(data) {
        if (data.record) {
            $.rad.notify('Scheduling updated', 'The scheduling has been saved to the export');
        }
    },{keep_form:true});

});
//-->
</script>