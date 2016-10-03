<?php
	/* @var $ubot_queue \Flux\UbotQueue */
	$ubot_queue = $this->getContext()->getRequest()->getAttribute("ubot_queue", array());
?>
<?php if (!$ubot_queue) { ?>
	<h1>No more comments to fulfill</h1>
<?php } else {?>	
	<!-- Page Content -->
	<div class="help-block">You can view a fulfillable comment on this screen and see how it was tracked</div>
	<br/>
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">Data Information</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="id">Id: </label>
					<input type="text" class="form-control" name="id" value="<?php echo $ubot_queue->getId() ?>" id="id" />
				</div>
				<div class="form-group">
					<label for="ubot">Ubot Script: </label>
					<input type="text" class="form-control" name="ubot" value="<?php echo $ubot_queue->getUbot()->getName() ?>" id="ubot" />
				</div>
				<div class="form-group">
					<label for="filename">Ubot Filename: </label>
					<input type="text" class="form-control" name="filename" value="<?php echo $ubot_queue->getUbot()->getUbot()->getScriptFilename() ?>" id="filename" />
				</div>
				<div class="form-group">
					<label for="keyword">Keywords <span class="small text-muted">(keyword)<span>: </label>
					<input type="text" class="form-control" name="keyword" value="<?php echo $ubot_queue->getKeyword() ?>" id="keyword" />
				</div>
				<div class="form-group">
					<label for="link">Link <span class="small text-muted">(link)<span>: </label>
					<input type="text" class="form-control" name="link" value="<?php echo $ubot_queue->getLink() ?>" id="link" />
				</div>
				<div class="form-group">
					<label for="email">Email <span class="small text-muted">(email)<span>: </label>
					<input type="text" class="form-control" name="email" value="<?php echo $ubot_queue->getEmail() ?>" id="email" />
				</div>
				<div class="form-group">
					<label for="name">Name <span class="small text-muted">(name)<span>: </label>
					<input type="text" class="form-control" name="name" value="<?php echo $ubot_queue->getName() ?>" id="name" />
				</div>
				<div class="form-group">
					<label for="comment">Comment <span class="small text-muted">(comment)<span>: </label>
					<textarea name="comment" id="comment" class="form-control"><?php echo $ubot_queue->getFormattedComment() ?></textarea>
				</div>
				<div class="form-group">
					<label for="comment">Link Name <span class="small text-muted">(linkname)<span>: </label>
					<textarea name="linkname" id="linkname" class="form-control"><?php echo $ubot_queue->getFormattedLink() ?></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				Script Information
			</div>
			<div class="panel-body word-break">
				<div class="form-group">
					<label for="username">Username <span class="small text-muted">(username)<span>: </label>
					<input type="text" class="form-control" name="username" value="<?php echo $ubot_queue->getUbot()->getUbot()->getUsername() ?>" id="username" />
				</div>
				<div class="form-group">
					<label for="password">Password <span class="small text-muted">(password)<span>: </label>
					<input type="text" class="form-control" name="password" value="<?php echo $ubot_queue->getUbot()->getUbot()->getPassword() ?>" id="password" />
				</div>
				<div class="form-group">
					<label for="login_url">Login Url <span class="small text-muted">(login_url)<span>: </label>
					<input type="text" class="form-control" name="login_url" value="<?php echo $ubot_queue->getUbot()->getUbot()->getLoginUrl() ?>" id="login_url" />
				</div>
				<div class="form-group">
					<label for="url">Url <span class="small text-muted">(url)<span>: </label>
					<input type="text" class="form-control" name="url" value="<?php echo $ubot_queue->getUrl() ?>" id="url" />
				</div>
			</div>
		</div>
	</div>
	
	<script>
	//<!--
	$(document).ready(function() {
	
	});
	//-->
	</script>
<?php } ?>