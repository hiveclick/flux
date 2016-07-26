<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title><?php echo $this->getTitle() ?></title>
		<link rel="icon" href="favicon.ico" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!-- Default site css -->
		<script type="text/javascript" src="/js/main.min.js"></script>
		<link href="/css/main.min.css" rel="stylesheet">
	</head>
	<body>		
		<div class="container-fluid">
			<?php if (!$this->getErrors()->isEmpty()) { ?>
				<div class="alert alert-warning alert-dismissible" role="alert">
  					<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
					<?php echo $this->getErrors()->getAllErrors(); ?>
				</div>
			<?php } ?>
			<!-- Insert body here -->
			<?php echo $template["content"] ?>
		</div>
	</body>
</html>