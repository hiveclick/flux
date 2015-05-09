<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><?php echo $this->getTitle() ?></title>
        <link rel="icon" href="favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Bootstrap and jQuery base classes -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

        <!-- Font Awesome library -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />

        <!-- Datatables plugins for table sorting and filtering 
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/colvis/1.1.0/css/dataTables.colVis.css"">
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script type="text/javascript" charset="utf8" src="/scripts/datatables/dataTables.colReorder.js"></script>
        <script type="text/javascript" charset="utf8" src="/scripts/datatables/dataTables.pageCache.js"></script>
        -->
        <!-- <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/colvis/1.1.0/js/dataTables.colVis.min.js"></script> -->

        <!-- Cookie plugin for storing column information in slickgrid -->
        <script src="/scripts/jquery.cookie.js" type="text/javascript" ></script>
        
        <!-- RAD plugins used for ajax requests, notifications, and form submission -->
        <link href="/scripts/pnotify/pnotify.custom.min.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/pnotify/pnotify.custom.min.js" type="text/javascript" ></script>
        <script src="/scripts/rad/jquery.rad.js" type="text/javascript"></script>
        
		<!-- Slick Grid plugins -->
		<script type="text/javascript" src="/scripts/slick-grid-1.4/lib/jquery.event.drag.min.2.0.js"></script>
		<script type="text/javascript" src="/scripts/slick-grid-1.4/slick.model.rad.js"></script>
		<script type="text/javascript" src="/scripts/slick-grid-1.4/slick.pager.rad.js"></script>
		<script type="text/javascript" src="/scripts/slick-grid-1.4/slick.columnpicker.rad.js"></script>
		<script type="text/javascript" src="/scripts/slick-grid-1.4/slick.grid.rad.js"></script>
		<script type="text/javascript" src="/scripts/slick-grid-1.4/jquery.slickgrid.rad.js"></script>
		<link rel="stylesheet" href="/scripts/slick-grid-1.4/css/slick.columnpicker.css"></link>
		<link rel="stylesheet" href="/scripts/slick-grid-1.4/css/slick.pager.css"></link>
		<link rel="stylesheet" href="/scripts/slick-grid-1.4/css/slick.ui.css"></link>
		<link rel="stylesheet" href="/scripts/slick-grid-1.4/css/slick.grid.css"></link>
        
        <!-- Selectize plugin for select boxes and comma-delimited fields -->
        <link href="/scripts/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/selectize/js/standalone/selectize.min.js" type="text/javascript"></script>
        
        <!-- CKeditor WYSIWYG editor -->
        <script src="/scripts/ckeditor/ckeditor.js"></script>

        <!-- Bootstrap dropdown plugin -->
		<script src="/scripts/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>
        
        <!-- Moment plugin for formatting dates -->
        <script type="text/javascript" src="/scripts/moment.min.js"></script>
        
        <!-- Bootstrap switch for checkboxes and radiobuttons -->
        <link href="/scripts/switch/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/switch/bootstrap-switch.min.js" type="text/javascript" ></script>
        
        <!-- Datetime picker used on the reports -->
        <link href="/scripts/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/datepicker/bootstrap-datetimepicker.min.js" type="text/javascript" ></script>

        <!-- Number format plugin for formatting currency and numbers -->
        <script src="/scripts/jquery.number.min.js" type="text/javascript" ></script>
        
        <!-- Number format plugin for formatting currency and numbers -->
        <script src="/scripts/jshashtable-2.1.js" type="text/javascript" ></script>
        <script src="/scripts/jquery.numberformatter-1.2.1.js" type="text/javascript" ></script>
        
        <!-- Smart resize plugin used for chart redrawing -->
        <script src="/scripts/jquery.smartresize.js" type="text/javascript" ></script>

        <!-- Timers used for firing events -->
        <script src="/scripts/timers/jquery.timers-1.2.js" type="text/javascript" ></script>
        
        <!-- Default site css -->
        <link href="/css/main.css" rel="stylesheet">
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
				<a class="navbar-brand" href="/index"><img class="visible-xs" alt="Brand" src="/images/logo-brand.png" /> <span class="hidden-xs">flux</span></a>
				<!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="main-navbar">
			    
					<?php if ($this->getMenu() !== null) { ?>
						<ul class="nav navbar-nav navbar-right">
						<?php
							/* @var $page Zend\Navigation\Page */
							foreach ($this->getMenu()->getPages() as $page) {
						?>
							<?php if ($page->hasChildren()) { ?>
								<li class="dropdown">
									<a class="hidden-xs dropdown-toggle <?php echo $page->getClass() ?>" data-hover="dropdown" data-delay="1000" data-close-others="true" role="button" href="/<?php echo $page->get('module') ?>/<?php echo $page->get('controller') ?><?php echo (count($page->get('params')) > 0) ? "?" : "" ?><?php echo http_build_query($page->get('params')) ?>"><?php echo $page->getLabel() ?><span class="caret"></span></a>
									<a class="visible-xs dropdown-toggle" data-toggle="dropdown" role="button" href="#"><?php echo $page->getLabel() ?><span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
									<?php
										/* @var $child_page \Zend\Navigation\Page */
										foreach ($page->getPages() as $child_page) {
									?>
										<?php if ($child_page->getLabel() != '') { ?>
											<li><a href="/<?php echo $child_page->get('module') ?>/<?php echo $child_page->get('controller') ?><?php echo (count($child_page->get('params')) > 0) ? "?" : "" ?><?php echo http_build_query($child_page->get('params')) ?>" class="<?php echo $child_page->getClass() ?>"><?php echo $child_page->getLabel() ?></a></li>
										<?php } else { ?>
											<li class="divider"></li>
										<?php } ?>
									<?php } ?>
									</ul>
								</li>
							<?php } else { ?>
								<li><a href="/<?php echo $page->get('module') ?>/<?php echo $page->get('controller') ?><?php echo (count($page->get('params')) > 0) ? "?" : "" ?><?php echo http_build_query($page->get('params')) ?>" role="button" aria-expanded="false"><?php echo $page->getLabel() ?></a></li>
							<?php } ?>
						<?php } ?>
						</ul>
					<?php } ?>
				</div>
			</div>
		</nav>
		
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
            { label: 'fulfillments', value: 'fulfillments'}
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
            location.replace(value);
        }
    });
});
//-->
</script>