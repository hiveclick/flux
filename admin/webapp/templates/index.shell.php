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
        <div class="wrapper">
            <div class="box">
                <div class="row row-offcanvas row-offcanvas-left">
                    <!-- main sidebar -->
                      <div class="column col-sm-2 col-xs-1 sidebar-offcanvas" id="sidebar">
                          <div>
                               <ul class="nav">
                                  <li><a href="#" data-toggle="offcanvas" class="visible-xs text-center"><i class="glyphicon glyphicon-chevron-right"></i></a></li>
                            </ul>

                            <ul class="nav hidden-xs" id="lg-menu">
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/', '/index.php')) ? " active":"" ;?>"><a href="/"><i class="glyphicon glyphicon-th-large"></i> Dashboard</a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/offer/offer-search', '/offer/offer', '/offer/offer-wizard')) ? " active":"" ;?>"><a href="/offer/offer-search"><i class="glyphicon glyphicon-import"></i> Offers</a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/campaign/campaign-search', '/campaign/campaign-wizard', '/campaign/campaign')) ? " active":"" ;?>"><a href="/campaign/campaign-search"><i class="glyphicon glyphicon-tag"></i> Campaigns</a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/lead/lead-search', '/lead/lead')) ? " active":"" ;?>"><a href="/lead/lead-search"><i class="glyphicon glyphicon-check"></i> Leads</a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/export/export', '/export/export-wizard', '/export/export-search')) ? " active":"" ;?>"><a href="/export/export-search"><i class="glyphicon glyphicon-export"></i> Exports</a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/export/split-search', '/export/split-wizard', '/export/split')) ? " active":"" ;?>"><a href="/export/split-search"><i class="glyphicon glyphicon-filter"></i> Splits</a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/client/client-search', '/client/client-wizard', '/client/client')) ? " active":"" ;?>"><a href="/client/client-search"><i class="glyphicon glyphicon-book"></i> Clients</a></li>
                            </ul>
                            <ul class="nav hidden-xs" id="sidebar-footer">
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/revenue-report', '/upsell-report', '/spy-report')) ? " active":"" ;?>"><a href="#" id="nav-reports"><i class="glyphicon glyphicon-usd"></i> Reports</a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/user-search', '/admin/user', '/admin/user-wizard', '/admin/data-field-search', '/admin/data-field', '/admin/data-field-wizard', '/admin/report-column-wizard', '/admin/report-column-search', '/admin/report-column', '/admin/vertical-wizard', '/admin/vertical-search', '/admin/vertical')) ? " active":"" ;?>"><a href="#" id="nav-admin"><i class="glyphicon glyphicon-wrench"></i> Admin</a></li>
                            </ul>

                              <!-- tiny only nav-->
                            <ul class="nav visible-xs" id="xs-menu">
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/', '/index.php')) ? " active":"" ;?>"><a href="/" class="text-center"><i class="glyphicon glyphicon-th-large"></i></a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/offer/offer-search', '/offer/offer', '/offer/offer-wizard')) ? " active":"" ;?>"><a href="/offer/offer-search" class="text-center"><i class="glyphicon glyphicon-import"></i></a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/campaign/campaign-search', '/campaign/campaign-wizard', '/campaign/campaign')) ? " active":"" ;?>"><a href="/campaign/campaign-search" class="text-center"><i class="glyphicon glyphicon-tag"></i></a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/lead/lead-search', '/lead/lead')) ? " active":"" ;?>"><a href="/lead/lead-search" class="text-center"><i class="glyphicon glyphicon-check"></i></a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/export/export', '/export/export-wizard', '/export/export-search')) ? " active":"" ;?>"><a href="/export/export-search" class="text-center"><i class="glyphicon glyphicon-export"></i></a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/export/split-search', '/export/split-wizard', '/export/split')) ? " active":"" ;?>"><a href="/export/split-search" class="text-center"><i class="glyphicon glyphicon-filter"></i></a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/client/client-search', '/client/client-wizard', '/client/client')) ? " active":"" ;?>"><a href="/client/client-search" class="text-center"><i class="glyphicon glyphicon-book"></i></a></li>
                            </ul>
                            <ul class="nav visible-xs" id="xs-sidebar-footer">
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/revenue-report', '/upsell-report', '/spy-report')) ? "active":"" ;?>"><a href="#" class="text-center" id="nav-reports-sm"><i class="glyphicon glyphicon-usd"></i></a></li>
                                <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/user-search', '/admin/user', '/admin/user-wizard', '/admin/data-field-search', '/admin/data-field', '/admin/data-field-wizard', '/admin/report-column-wizard', '/admin/report-column-search', '/admin/report-column', '/admin/vertical-wizard', '/admin/vertical-search', '/admin/vertical')) ? " active":"" ;?>"><a href="#" class="text-center" id="nav-admin-sm"><i class="glyphicon glyphicon-wrench"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- secondary sidebar -->
                    <div class="column col-sm-2 col-xs-1" style="<?php echo !in_array($_SERVER['SCRIPT_NAME'], array('/admin/apc', '/admin/server-search', '/admin/server', '/admin/server-wizard', '/admin/user-search', '/admin/user', '/admin/user-wizard', '/admin/data-field-search', '/admin/data-field', '/admin/data-field-wizard', '/admin/report-column-wizard', '/admin/report-column-search', '/admin/report-column', '/admin/vertical-wizard', '/admin/vertical-search', '/admin/vertical', '/admin/domain-group-wizard', '/admin/domain-group-search', '/admin/domain-group')) ? "display:none;":"" ;?>" id="sidebar-secondary-admin">
                        <ul class="nav hidden-xs">
                            <li class="separator"><h5>Admin</h5></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/data-field-search', '/admin/data-field', '/admin/data-field-wizard')) ? "active":"" ;?>"><a href="/admin/data-field-search"><i class="glyphicon glyphicon-list"></i> Data fields</a></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/vertical-wizard', '/admin/vertical-search', '/admin/vertical')) ? "active":"" ;?>"><a href="/admin/vertical-search"><i class="glyphicon glyphicon-wrench"></i> Verticals</a></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/domain-group-wizard', '/admin/domain-group-search', '/admin/domain-group')) ? "active":"" ;?>"><a href="/admin/domain-group-search"><i class="glyphicon glyphicon-th-list"></i> Domain Groups</a></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/report-column-wizard', '/admin/report-column-search', '/admin/report-column')) ? "active":"" ;?>"><a href="/admin/report-column-search"><i class="glyphicon glyphicon-stats"></i> Report Columns</a></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/server-search', '/admin/server', '/admin/server-wizard')) ? "active":"" ;?>"><a href="/admin/server-search"><i class="glyphicon glyphicon-hdd"></i> Servers</a></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/user-search', '/admin/user', '/admin/user-wizard')) ? "active":"" ;?>"><a href="/admin/user-search"><i class="glyphicon glyphicon-user"></i> Users</a></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/admin/apc')) ? "active":"" ;?>"><a href="/admin/apc"><i class="glyphicon glyphicon-cloud"></i> APC</a></li>
                            <li><a href="/logout"><i class="glyphicon glyphicon-share"></i> Logout</a></li>
                        </ul>
                          <!-- tiny only nav-->
                        <ul class="nav visible-xs" id="xs-menu">
                            <li><a href="/admin/data-field-search"><i class="glyphicon glyphicon-list"></i> </a></li>
                            <li><a href="/admin/vertical-search"><i class="glyphicon glyphicon-wrench"></i> </a></li>
                            <li><a href="/admin/domain-group-search"><i class="glyphicon glyphicon-th-list"></i></a></li>
                            <li><a href="/admin/report-column-search"><i class="glyphicon glyphicon-stats"></i> </a></li>
                            <li><a href="/admin/server-search"><i class="glyphicon glyphicon-hdd"></i> </a></li>
                            <li><a href="/admin/user-search"><i class="glyphicon glyphicon-user"></i> </a></li>
                            <li><a href="/logout"><i class="glyphicon glyphicon-share"></i> </a></li>
                        </ul>
                    </div>

                    <div class="column col-sm-2 col-xs-1" style="<?php echo !in_array($_SERVER['SCRIPT_NAME'], array('/report/revenue-report', '/report/spy-report')) ? "display:none;":"" ;?>" id="sidebar-secondary-reports">
                        <ul class="nav hidden-xs">
                            <li class="separator"><h5>Reports</h5></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/report/revenue-report')) ? "active":"" ;?>"><a href="/report/revenue-report"><i class="glyphicon glyphicon-usd"></i> Revenue Report</a></li>
                            <li><a href="/report/revenue-report"><i class="glyphicon glyphicon-share-alt"></i> Upsell Report</a></li>
                            <li class="<?php echo in_array($_SERVER['SCRIPT_NAME'], array('/report/spy-report')) ? "active":"" ;?>"><a href="/report/spy-report"><i class="glyphicon glyphicon-search"></i> Spy Report</a></li>
                        </ul>
                          <!-- tiny only nav-->
                        <ul class="nav visible-xs" id="xs-menu">
                            <li><a href="/report/revenue-report"><i class="glyphicon glyphicon-usd"></i> </a></li>
                            <li><a href="/report/revenue-report"><i class="glyphicon glyphicon-share-alt"></i> </a></li>
                            <li><a href="/report/spy-report"><i class="glyphicon glyphicon-search"></i> </a></li>
                        </ul>
                    </div>
                    <!-- /sidebar -->

                    <!-- main right col -->
                    <div class="column" id="main">
                        <!-- content -->
                          <div class="row container-fluid">
                            <!-- main col left -->
                            <?php echo $template['content'] ?>
                            <div class="clearfix"></div>
                         </div>
                    </div>
                    <!-- /main -->

                </div>
            </div>
        </div>
    </body>
<script>
//<!--
$(document).ready(function() {
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
});
//-->
</script>
</html>