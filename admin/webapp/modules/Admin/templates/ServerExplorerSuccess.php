<?php
	/* @var $server \Flux\Server */
	$server = $this->getContext()->getRequest()->getAttribute('server', array());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<!-- JQuery Plugins -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
	
	<!-- Bootstrap Plugins -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/cupertino/jquery-ui.css" />
	
	<!-- Dropzone Plugin -->
	<script src="/js/dropzone/dropzone.min.js"></script>
	<link rel="stylesheet" href="/js/dropzone/dropzone.css" />
	
	<style>
	body {
		margin: 5px;
		padding: 0px;
	}
	
	table {
		font-Size: 8pt;
		line-height: 8pt;
		color: #666666;
		margin: 5px;
		padding: 0px;
	}
	</style>
</head>
<body>
<?php 
	try { 
		$files = $server->getFiles();
?>
	<table cellpadding="2" cellspacing="0" border="0" width="99%">
		<tr>
			<td style="width:28px;"><a href="/admin/server-explorer?html_input_element_id=<?php echo $server->getHtmlInputElementId() ?>&_id=<?php echo $server->getId() ?>&folder_name=<?php echo dirname($server->getFolderName()) ?>"><img src="/images/folder.png" border="0" align="top" /></a></td>
			<td colspan="2"><a href="/admin/server-explorer?html_input_element_id=<?php echo $server->getHtmlInputElementId() ?>&_id=<?php echo $server->getId() ?>&folder_name=<?php echo dirname($server->getFolderName()) ?>"> ..</a></td>
		</tr>
		<?php 
			foreach ($files as $filename => $file) {
		?>
			<?php if ($file['type'] == 'directory') { ?>
				<tr>
					<td style="width:28px;"><a href="/admin/server-explorer?html_input_element_id=<?php echo $server->getHtmlInputElementId() ?>&_id=<?php echo $server->getId() ?>&folder_name=<?php echo $server->getFolderName() ?>/<?php echo $filename ?>"><img src="/images/folder.png" border="0" align="top" /></a></td>
					<td colspan="2"><a href="/admin/server-explorer?html_input_element_id=<?php echo $server->getHtmlInputElementId() ?>&_id=<?php echo $server->getId() ?>&folder_name=<?php echo $server->getFolderName() ?>/<?php echo $filename ?>"> <?php echo $filename ?></a></td>
				</tr>
			<?php } ?>
		<?php } ?>
		<?php 
			foreach ($files as $filename => $file) {
		?>
			<?php if ($file['type'] != 'directory') { ?>
				<tr>
					<?php if (trim($server->getHtmlInputElementId()) != '') { ?>
						<td style="width:24px;"><img src="/images/offer.png" border="0" align="top" /></td>
						<td><?php echo $filename ?></td>
						<td style="text-Align:right;"><?php echo number_format($file['size'], 0, null, ',') ?> bytes</td>
					<?php } else { ?>
						<td style="width:24px;"><img src="/images/offer.png" border="0" align="top" /></td>
						<td><?php echo $filename ?></td>
						<td style="text-Align:right;"><?php echo number_format($file['size'], 0, null, ',') ?> bytes</td>
					<?php } ?>
				</tr>
			<?php } ?>
		<?php } ?>
	</table>
<?php } catch (\Exception $e) { ?>
	<div class="alert alert-danger alert-dismissible fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
		<?php echo $e->getMessage() ?>
	</div>
<?php } ?>
<script>
//<!--
$(document).ready(function() {
	$('#ftp_explorer_loading_div', window.parent.document).hide();
	$('#current_folder_name', window.parent.document).val('<?php echo $server->getRootDir() ?><?php echo $server->getFolderName() ?>');
});
//-->
</script>
</body>
</html>