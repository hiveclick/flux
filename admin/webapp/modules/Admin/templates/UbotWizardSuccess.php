<?php
	/* @var $ubot Flux\Ubot */
	$ubot = $this->getContext()->getRequest()->getAttribute("ubot", array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title"><?php echo \MongoId::isValid($ubot->getId()) ? 'Edit' : 'Add' ?> Ubot Script</h4>
</div>
<form class="" id="ubot_form_<?php echo $ubot->getId() ?>" method="<?php echo \MongoId::isValid($ubot->getId()) ? 'PUT' : 'POST' ?>" action="/api" autocomplete="off" role="form">
	<input type="hidden" name="func" value="/admin/ubot" />
	<?php if (\MongoId::isValid($ubot->getId())) { ?>
		<input type="hidden" name="_id" value="<?php echo $ubot->getId() ?>" />
	<?php } ?>
	<div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#basic" role="tab" data-toggle="tab">Basic</a></li>
			<li><a href="#urls" role="tab" data-toggle="tab">Urls</a></li>
			<li><a href="#login" role="tab" data-toggle="tab">Login</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="basic">
				<div class="help-block">Name this uBot Script</div>
				<div class="form-group">
					<label class="control-label" for="name">Name</label>
					<input type="text" name="name" class="form-control" value="<?php echo $ubot->getName() ?>" />
				</div>
				<div class="form-group">
					<label class="control-label" for="name">Description</label>
					<input type="text" name="description" class="form-control" value="<?php echo $ubot->getDescription() ?>" />
				</div>
				<hr />
				<div class="help-block">Set the filename and location of the uBot script on the remote server</div>
				<div class="form-group">
					<label class="control-label" for="script_filename">Script Filename</label>
					<textarea name="script_filename" class="form-control"><?php echo $ubot->getScriptFilename() ?></textarea>
				</div>
				<hr />

				<div class="form-group">
					<div class="row">
						<div class="col-xs-9">
							<label class="control-label" for="active_1">Active</label>
							<i class="help-block">Set whether this script should be used to generate comments in the queue</i>
						</div>
						<div class="col-xs-3 text-right">
							<input type="hidden" name="active" id="active_0" value="0" />
							<input type="checkbox" name="active" id="active_1" value="1" <?php echo $ubot->getActive() ? 'checked' : '' ?> />
						</div>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<label class="control-label" for="rss_url">RSS Url <span class="text-muted">(Optional)</span></label>
					<textarea name="rss_url" id="rss_url" class="form-control"><?php echo $ubot->getRssUrl() ?></textarea>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="urls">
				<div class="help-block">Enter the urls that can have comments added to them.  These urls may be generated from the RSS Url</div>
				<div class="form-group">
					<label class="control-label" for="urls">Urls</label>
					<textarea name="urls" id="urls_textarea" class="form-control" rows="25"><?php echo implode("\n", $ubot->getUrls()) ?></textarea>
				</div>
				<div class="text-center">
					<div class="btn btn-info" id="rss_refresh"><i class="fa fa-rss"></i> Refresh from RSS</div>
					<p />
					<div class="text-muted small">RSS Last Updated on <?php echo date('m/d/Y g:i a', $ubot->getLastRssUpdateAt()->sec) ?></div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="login">
				<div class="help-block">These additional fields are not required on all scripts, but fill them out if you have them</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="username">Username</label>
							<input type="text" name="username" class="form-control" value="<?php echo $ubot->getUsername() ?>" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="password">Password</label>
							<input type="text" name="password" class="form-control" value="<?php echo $ubot->getPassword() ?>" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="login_url">Login Url</label>
					<textarea name="login_url" class="form-control"><?php echo $ubot->getLoginUrl() ?></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php if (\MongoId::isValid($ubot->getId())) { ?>
			<input type="button" class="btn btn-danger" value="Delete Script" class="small" onclick="javascript:confirmDelete();" />
		<?php } ?>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save changes</button>
	</div>
</form>
<script>
//<!--
$(document).ready(function() {
	$('#ubot_form_<?php echo $ubot->getId() ?>').form(function(data) {
		$.rad.notify('Ubot Script Updated', 'The ubots script has been added/updated in the system');
		$('#ubot_search_form').trigger('submit');
		$('#edit_ubot_modal').modal('hide');
	}, {keep_form:1});

	$('#active_1').bootstrapSwitch({

	});

	$('#rss_refresh').on('click', function() {
		$.rad.get('/api', { func: '/admin/ubot-rss-refresh', _id: '<?php echo $ubot->getId() ?>', rss_url: $('#rss_url').val() }, function(data) {
			if (data.record) {
				urls = '';
				$.each(data.record.urls, function(i, url) {
					console.log(url);
					urls += (url + "\n");
				});
				$('#urls_textarea').val(urls);
			}
		});
	});
});

<?php if (\MongoId::isValid($ubot->getId())) { ?>
function confirmDelete() {
	if (confirm('Are you sure you want to delete this ubot script from the system?')) {
		$.rad.del('/api', { func: '/admin/ubot/<?php echo $ubot->getId() ?>' }, function(data) {
			$.rad.notify('You have deleted this ubot script', 'You have deleted this ubot script.');
			$('#ubot_search_form').trigger('submit');
		});
	}
}
<?php } ?>
//-->
</script>