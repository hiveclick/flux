<?php
/* @var $server \Flux\Server */
$server = $this->getContext()->getRequest()->getAttribute('server', array());
$servers = $this->getContext()->getRequest()->getAttribute('servers', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Browse Server</h4>
</div>
<div class="modal-body">
	You can use this wizard to browse a remote server
	<p />
	<select name="server_id" id="server_id" placeholder="select a server to connect to...">
	    <option value=""></option>
	    <?php
	        /* @var $server_item \Flux\Server */ 
	        foreach ($servers as $server_item) { 
        ?>
	        <option value="<?php echo $server_item->getId() ?>" <?php echo ($server_item->getId() == $server->getId()) ? 'selected' : '' ?>><?php echo $server_item->getHostname() ?></option>
	    <?php } ?>
	</select>
	
	<p />
	<iframe style="border:1px solid #C8C8C8;" id="server_explorer_iframe" src="<?php echo ($server->getId() > 0) ? '/admin/server-explorer?_id=' . $server->getId() : '' ?>" width="100%" height="500" frameborder="0"></iframe>
	<input type="text" class="form-control" id="current_folder_name" name="current_folder_name" value="<?php echo $server->getFolderName() ?>" />
</div>
<div class="modal-footer">
    <?php if ($server->getHtmlInputElementId() != '') { ?>
        <button type="button" id="save_folder" class="btn btn-primary" data-dismiss="modal">Save Folder</button>
    <?php } ?>
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script>
//<!--
$(document).ready(function() {
     $('#server_id').selectize({
    	 onChange: function(value) {
  			if (!value.length) return;
  			$('#server_explorer_iframe').attr('src', '/admin/server-explorer?_id=' + value);
    	 }
     });

     $('#save_folder').click(function() {
    	 if ('<?php echo $server->getHtmlInputElementId() ?>' != '') {
 		     $('#<?php echo $server->getHtmlInputElementId() ?>').val($('#current_folder_name').val());
 		     
    	 }
     });
});
//-->
</script>