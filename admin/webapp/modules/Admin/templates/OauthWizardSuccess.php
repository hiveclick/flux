<div class="modal-header bg-success">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">OAuth Wizard</h4>
</div>
<div class="modal-body">
	<div class="help-block">You can authorize this application access to your Google Adwords Account so that it can pull statistics from it.  To begin, click the button below:</div>
	<p />
	<iframe id="oauth_request_iframe" style="width:100%;height:100%;"></iframe>
</div>
<div class="modal-footer">
	<button type="button" id="request_token_btn" class="btn btn-primary" data-dismiss="modal">Request Token</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script>
//<!--
$(document).ready(function() {
	$('#request_token_btn').click(function() {
		$('/api', {func: '/admin/oauth-request-auth-url'}, function(data) {
			if (data.record.authorization_url) {
				$('#oauth_request_iframe').attr('src', data.record.authorization_url);
			} else {
				$.rad.notify('Missing authorization url', 'No authorization url was returned in the response');
			}
		});
	});	
});
//-->
</script>
