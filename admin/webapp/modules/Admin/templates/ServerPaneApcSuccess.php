<?php 
/* @var $server \Flux\Server */
$server = $this->getContext()->getRequest()->getAttribute('server', array());
?>
<div class="help-block">Displays the APC cache running on this server.  Clear the cache when you push changes to this server.</div>
<br/>
<iframe src="http://<?php echo $server->getHostname() ?>/apc.php" width="100%" height="100%" frameborder="0" style="min-height:700px;"></iframe>