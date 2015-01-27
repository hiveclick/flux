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
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>


        <!-- Datatables plugins for table sorting and filtering -->
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/colvis/1.1.0/css/dataTables.colVis.css"">
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script type="text/javascript" charset="utf8" src="/scripts/datatables/dataTables.colReorder.js"></script>
        <script type="text/javascript" charset="utf8" src="/scripts/datatables/dataTables.pageCache.js"></script>
        <!-- <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/colvis/1.1.0/js/dataTables.colVis.min.js"></script> -->


        <!-- Selectize plugin for select boxes and comma-delimited fields -->
        <link href="/scripts/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/selectize/js/standalone/selectize.min.js" type="text/javascript" ></script>
        
        <!-- CKeditor WYSIWYG editor -->
        <script src="/scripts/ckeditor/ckeditor.js"></script>

        <!-- Moment plugin for formatting dates -->
        <script type="text/javascript" src="/scripts/moment.min.js"></script>
        
        <!-- Bootstrap switch for checkboxes and radiobuttons -->
        <link href="/scripts/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/bootstrap-switch.min.js" type="text/javascript" ></script>
        
        <!-- Datetime picker used on the reports -->
        <link href="/scripts/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/bootstrap-datetimepicker.min.js" type="text/javascript" ></script>

        <!-- Number format plugin for formatting currency and numbers -->
        <script src="/scripts/jquery.number.min.js" type="text/javascript" ></script>
        
        <!-- Smart resize plugin used for chart redrawing -->
        <script src="/scripts/jquery.smartresize.js" type="text/javascript" ></script>

        <!-- RAD plugins used for ajax requests, notifications, and form submission -->
        <link href="/scripts/pnotify/pnotify.custom.min.css" rel="stylesheet" type="text/css" />
        <script src="/scripts/pnotify/pnotify.custom.min.js" type="text/javascript" ></script>
        <script src="/scripts/rad/validator.js" type="text/javascript"></script>
        <script src="/scripts/rad/jquery.rad.js" type="text/javascript"></script>

        <!-- Default site css -->
        <link href="/scripts/styles.css" rel="stylesheet">

        <style type="text/css">
            <?php /* The padding below matches the padding on bootstrap container class */ ?>
            ul.scrollable-menu {
                height: auto;
                max-height: 200px;
                overflow-x: hidden;
            }
            ul.checkbox-menu {
                padding: 5px 10px 0;
                width: 100%;
            }
        </style>
    </head>
    <body>
    	<nav class="navbar-collapse navbar-inverse" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">flux</a>
					
				</div>
				<p class="navbar-text navbar-right hidden-xs"><a href="/logout" class="navbar-link"><?php echo $this->getContext()->getUser()->getUserDetails()->getName() ?>, Logout</a></p>
			</div>
		</nav>
		<nav class="navbar-collapse navbar-default" role="navigation">
			<!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="navbar-collapse-1">
				<?php if ($this->getMenu() !== null) { ?>
					<ul class="nav navbar-nav">
					<?php
						/* @var $page Zend\Navigation\Page */
						foreach ($this->getMenu()->getPages() as $page) {
					?>
						<?php if ($page->hasChildren()) { ?>
							<li class="dropdown""><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $page->getLabel() ?> <span class="caret"></span></a>
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
		</nav>
		
		<div class="container-fluid">
			<?php if (!$this->getErrors()->isEmpty()) { ?>
				<div class="error">
					<img src="/theme/global/images/icons/ico_critical.gif" border="0" align="absmiddle" style="float:left;padding:2px;">
					<?php echo $this->getErrors()->getAllErrors(); ?>
				</div>
			<?php } ?>
			<!-- Insert body here -->
			<?php echo $template["content"] ?>
		</div>
		
    </body>
<script>
//<!--
$(document).ready(function() {
	/*
    $('#nav-admin,#nav-admin-sm').click(function() {
        $('#sidebar-secondary-reports').hide();
        $('#sidebar-secondary-admin').toggle();
    });
    $('#nav-reports,#nav-reports-sm').click(function() {
        $('#sidebar-secondary-admin').hide();
        $('#sidebar-secondary-reports').toggle();
    });
    $('[data-toggle=offcanvas]').click(function() {
          $(this).toggleClass('visible-xs text-center');
        $(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
        $('.row-offcanvas').toggleClass('active');
        $('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
        $('#sidebar-footer').toggleClass('hidden-xs').toggleClass('visible-xs');
        $('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
        $('#xs-sidebar-footer').toggleClass('visible-xs').toggleClass('hidden-xs');
        $('#btnShow').toggle();
    });
    */
});
//-->
</script>
</html>