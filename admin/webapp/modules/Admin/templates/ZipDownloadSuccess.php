<?php
	/* @var $zip Flux\Zip */
	$zip = $this->getContext()->getRequest()->getAttribute("zip", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Download Zip Updates</h4>
</div>
<form id="zip_download_form_<?php echo $zip->getId() ?>" method="POST" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/zip-download-start" />
	<div class="modal-body">
	    <div class="help-block">You can download updates to the zipcode database here.  Updates are available every few months.</div>
	    <div class="text-center">
            <input type="submit" class="btn btn-info" value="Download Updates" />
	    </div>
	    <p />
	    <div id="zip_download_status" class="text-center" style="display:none;">
            <div class="progress">
                <div class="progress-bar progress-bar-striped" id="zip_download_progress" role="progressbar" style="width: 0%;"></div>
            </div>
            <div id="zip_download_message" class="text-muted small"></div>
	    </div>
    </div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
    $('#zip_download_form_<?php echo $zip->getId() ?>').form(function(data) {
        $.rad.notify('Zip Database Updated', 'The zipcode database has been updated successfully');
        $(document).oneTime(1000, function() { findZipProgress(); });
    });

    findZipProgress();
});

function findZipProgress() {
	$.getJSON('/api', {func: '/admin/zip-progress' }, function(data) {
		if (data.record) {
			if (data.record.is_complete || data.record.progress == 100) {
				$('#zip_download_progress').removeClass('active');
				$('#zip_download_progress').css('width', '100%');
				$('#zip_download_message').html(data.record.message);
			} else {
				$('#zip_download_status').show();
				$('#zip_download_progress').addClass('active');
				$('#zip_download_progress').css('width', (data.record.progress + '%'));	
			    $('#zip_download_message').html(data.record.message);
				$(document).oneTime(3000, function() { findZipProgress(); });
			}
		} else {
			$(document).oneTime(3000, function() { findZipProgress(); });
		}
	});
}
//-->
</script>