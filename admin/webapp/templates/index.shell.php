<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=yes" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php echo $this->getTitle() ?></title>
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

<link rel="icon" href="favicon.ico" />
<link href="/scripts/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" />
<link href="/scripts/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<link href="/scripts/pnotify/jquery.pnotify.default.css" rel="stylesheet" type="text/css" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/scripts/selectize/js/standalone/selectize.min.js"></script>
<script type="text/javascript" src="/scripts/moment.min.js"></script>
<script type="text/javascript" src="/scripts/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.pnotify.min.js"></script>
<script type="text/javascript" src="/scripts/validator.js"></script>
<script type="text/javascript" src="/scripts/jquery.rad.js"></script>

<script type="text/javascript">
$(function() {
    $('.selectize').selectize();
    $('.dropdown-menu').on('click', function(e) {
        if($(this).hasClass('checkbox-menu')) {
            e.stopPropagation();
        }
    });
});
</script>
</head>

<body>
    <nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <?php /* <a class="navbar-brand" href="#">Gun</a> */ ?>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li class='<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/', '/index')) ? "active":"" ;?>'><a href='/'>Home</a></li>
                <li class='dropdown<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/client/client-search', '/client/client-wizard', '/client/client', '/admin/user-search', '/admin/user', '/admin/user-wizard')) ? " active":"" ;?>'>
                    <a class="dropdown-toggle" href='#' data-toggle="dropdown">Clients <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/client/client-search' ? "active":"" ;?>'><a href='/client/client-search'><span class="glyphicon glyphicon-book"></span> Clients</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/admin/user-search' ? "active":"" ;?>'><a href='/admin/user-search'><span class="glyphicon glyphicon-user"></span> Users</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/client/client-wizard' ? "active":"" ;?>'><a href='/client/client-wizard'><span class="glyphicon glyphicon-plus-sign"></span> Add Client</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/admin/user-wizard' ? "active":"" ;?>'><a href='/admin/user-wizard'><span class="glyphicon glyphicon-plus-sign"></span> Add User</a></li>
                    </ul>
                </li>
                <li class='dropdown<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/offer/offer-search', '/campaign/campaign-search', '/offer/offer', '/offer/offer-wizard', '/campaign/campaign-wizard', '/campaign/campaign')) ? " active":"" ;?>'>
                    <a class="dropdown-toggle" href='#' data-toggle="dropdown">Offers <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/offer/offer-search' ? "active":"" ;?>'><a href='/offer/offer-search'><span class="glyphicon glyphicon-import"></span> Offers</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/campaign/campaign-search' ? "active":"" ;?>'><a href='/campaign/campaign-search'><span class="glyphicon glyphicon-tag"></span> Campaigns</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/offer/offer-wizard' ? "active":"" ;?>'><a href='/offer/offer-wizard'><span class="glyphicon glyphicon-plus-sign"></span> Add Offer</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/campaign/campaign-wizard' ? "active":"" ;?>'><a href='/campaign/campaign-wizard'><span class="glyphicon glyphicon-plus-sign"></span> Add Campaign</a></li>
                    </ul>
                </li>
                <li class='dropdown<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/flow/flow-search', '/flow/flow', '/flow/flow-wizard')) ? " active":"" ;?>'>
                    <a class="dropdown-toggle" href='#' data-toggle="dropdown">Flows <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/flow/flow-search' ? "active":"" ;?>'><a href='/flow/flow-search'><span class="glyphicon glyphicon-indent-left"></span> Flows</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/flow/flow-wizard' ? "active":"" ;?>'><a href='/flow/flow-wizard'><span class="glyphicon glyphicon-plus-sign"></span> Add Flow</a></li>
                    </ul>
                </li>
                <li class='dropdown<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/export/export', '/export/export-wizard', '/export/export-search', '/export/split-search', '/export/split-wizard', '/export/split')) ? " active":"" ;?>'>
                    <a class="dropdown-toggle" href='#' data-toggle="dropdown">Exports <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/export/split-search' ? "active":"" ;?>'><a href='/export/split-search'><span class="glyphicon glyphicon-filter"></span> Splits</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/export/export-search' ? "active":"" ;?>'><a href='/export/export-search'><span class="glyphicon glyphicon-export"></span> Exports</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/export/split-wizard' ? "active":"" ;?>'><a href='/export/split-wizard'><span class="glyphicon glyphicon-plus-sign"></span> Add Split</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/export/export-wizard' ? "active":"" ;?>'><a href='/export/export-wizard'><span class="glyphicon glyphicon-plus-sign"></span> Add Export</a></li>
                    </ul>
                </li>
                <li class='dropdown<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/datafield-search', '/admin/datafield', '/admin/datafield-wizard', '/admin/report-column-search', '/admin/report-column')) ? " active":"" ;?>'>
                    <a class="dropdown-toggle" href='#' data-toggle="dropdown">Administrative <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/admin/vertical-search' ? "active":"" ;?>'><a href='/admin/vertical-search'><span class="glyphicon glyphicon-list"></span> Verticals</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/admin/datafield-search' ? "active":"" ;?>'><a href='/admin/datafield-search'><span class="glyphicon glyphicon-wrench"></span> Data Fields</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/admin/report-column-search' ? "active":"" ;?>'><a href='/admin/report-column-search'><span class="glyphicon glyphicon-stats"></span> Report Columns</a></li>
                    </ul>
                </li>
                <li class='dropdown<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/revenue-report', '/upsell-report', '/spy-report')) ? " active":"" ;?>'>
                    <a class="dropdown-toggle" href='#' data-toggle="dropdown">Reports <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/revenue-report' ? "active":"" ;?>'><a href='/revenue-report'><span class="glyphicon glyphicon-usd"></span> Revenue Report</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/upsell-report' ? "active":"" ;?>'><a href='/upsell-report'><span class="glyphicon glyphicon-share-alt"></span> Upsell Report</a></li>
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/spy-report' ? "active":"" ;?>'><a href='/spy-report'><span class="glyphicon glyphicon-search"></span> Spy Report</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class='dropdown<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/help', '/logout')) ? " active":"" ;?>'>
                    <a class="dropdown-toggle" href='#' data-toggle="dropdown"><?php echo is_object($this->getContext()->getUser()->getUserDetails()) ? $this->getContext()->getUser()->getUserDetails()->getEmail() : 'user' ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class='<?php echo $_SERVER['SCRIPT_NAME'] == '/logout' ? "active":"" ;?>'><a href='/logout'><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="col-md-10">
        <div class="container-fluid">
            <div class="header-message-container-error">
            <?php foreach($this->getErrors()->getErrorArray() AS $msg) { ?>
                <div class="alert alert-danger">
                <a class="close" data-dismiss="alert" href="#">x</a>
                <?php echo $msg->getMessage(); ?>
                </div>
            <?php } ?>
            </div>
            <div class="header-message-container-success">
            <?php /*
            <?php foreach($this->getMessages() AS $msg) { ?>
                <div class="alert alert-success">
                <a class="close" data-dismiss="alert" href="#">x</a>
                <?php echo $msg; ?>
                </div>
            <?php } ?>
            */ ?>
            </div>

            <?php echo $template["content"] ?>
        </div>
    </div>
</body>
</html>
