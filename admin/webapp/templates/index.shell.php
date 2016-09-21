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
		<script>
			//<!--
			$.rad.ajax.options.defaults.host = '<?php echo MO_API_URL ?>';
			//-->
		</script>
	</head>
	<body>
		<nav class="navbar navbar-fixed-top navbar-inverse navbar-collapse" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/index"><img class="visible-xs" alt="Brand" src="/images/logo-brand.png" /> <span class="hidden-xs"><?php echo \Flux\Preferences::getPreference('BRAND_NAME', 'flux') ?></span></a>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="main-navbar">
					<form class="navbar-form navbar-left hidden-xs hidden-sm" role="search" method="GET" action="/lead/lead-search">
						<div class="form-group">
							<input type="text" class="selectize" id="nav_search" name="keywords" style="width:300px;" size="35" placeholder="search leads" value="">
						</div>
					</form>
				
					<?php if ($this->getMenu() !== null) { ?>
						<ul class="nav navbar-nav navbar-right">
						<?php
							/* @var $page Zend\Navigation\Page */
							foreach ($this->getMenu()->getPages() as $page) {
						?>
							<?php if (is_null($page->getPermission()) || ($page->getPermission() == $this->getContext()->getUser()->getUserDetails()->getUserType())) { ?>						
								<?php if (count($page->getPages()) > 0) { ?>
									<li class="dropdown">
										<a class="hidden-xs dropdown-toggle <?php echo $page->getClass() ?>" data-hover="dropdown" data-delay="1000" data-close-others="true" role="button" href="<?php echo $page->getHref() ?>"><?php echo $page->getLabel() ?><span class="caret"></span></a>
										<a class="visible-xs dropdown-toggle" data-toggle="dropdown" role="button" href="#"><?php echo $page->getLabel() ?><span class="caret"></span></a>
										<ul class="dropdown-menu" role="menu">
										<?php
											/* @var $child_page \Zend\Navigation\Page */
											foreach ($page->getPages() as $child_page) {
										?>
											<?php if (is_null($child_page->getPermission()) || ($child_page->getPermission() == $this->getContext()->getUser()->getUserDetails()->getUserType())) { ?>
												<?php if ($child_page->getLabel() != '') { ?>
													<li><a href="<?php echo $child_page->getHref() ?>" class="<?php echo $child_page->getClass() ?>"><?php echo $child_page->getLabel() ?></a></li>
												<?php } else { ?>
													<li class="divider"></li>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										</ul>
									</li>
								<?php } else { ?>
									<li><a href="<?php echo $page->getHref() ?>" role="button" aria-expanded="false"><?php echo $page->getLabel() ?></a></li>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						</ul>
					<?php } ?>
				</div>
			</div>
		</nav>
		
		<div>
			<?php if (!$this->getErrors()->isEmpty()) { ?>
				<div class="alert alert-warning alert-dismissible" role="alert">
  					<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
					<?php echo $this->getErrors()->getAllErrors(); ?>
				</div>
			<?php } ?>
			<!-- Insert body here -->
			<?php echo $template["content"] ?>
		</div>
		<div class="footer small hidden-xs">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li><a href="/default/index">dashboard</a></li>
					<li><a href="/report/dashboard">reports</a></li>
					<li><a href="/default/logout">logout</a></li>
				</ul>
				<p class="navbar-text navbar-right">Flux Lead Manager. All Rights Reserved&nbsp;&nbsp;&nbsp;&nbsp;</p>
			</div>
		</div>
	</body>
</html>
<script>
//<!--
$(document).ready(function() {
	$('#nav_search').selectize({
		valueField: 'url',
		labelField: 'name',
		searchField: ['description','name'],
		options: [],
		dropdownWidthOffset: 100,
		optgroupField: 'optgroup',
		optgroups: [
			{ label: 'leads', value: 'leads' },
			{ label: 'offers', value: 'offers' },
			{ label: 'campaigns', value: 'campaigns'},
			{ label: 'fulfillments', value: 'fulfillments'},
			{ label: 'lead splits', value: 'lead splits'}
		],
		create: false,
		render: {
			optgroup_header: function(item, escape) {
				return '<b class="optgroup-header">' +
					escape(item.label) +
				   '</b>';
			  },
			option: function(item, escape) {
				return '<div>' +
					'<a href="' + escape(item.url) + '">' +
					'<span class="title">' +
						'<span class="name">' + escape(item.name) + '</span>' +
					'</span>' +
					'<span class="description">' + escape(item.description) + '</span>' +
					'<span class="description">' + escape(item.meta) + '</span>' +
					'</a>' +
				'</div>';
			}
		},
		load: function(query, callback) {
			if (!query.length) return callback();
			this.clearOptions();
			$.ajax({
				url: '/api',
				type: 'GET',
				dataType: 'json',
				data: {
					func: '/search',
					keywords: query
				},
				error: function() {
					callback();
				},
				success: function(res) {
					callback(res.entries);
				}
			});
		},
		onItemAdd: function(value,item) {
			// Redirect to whatever was selected
			location.href = value;
		}
	});
});
//-->
</script>