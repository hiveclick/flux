<?php
/* Required lines used to instantiate the lead and setup the php environment */
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/init.php');
/* If you want to clear the lead whenever this page is loaded, use the following line */
\FluxFE\Lead::getInstance()->clear();

/* This line will output debugging information about the lead that you may find useful */
\FluxFE\Lead::debug();


/*
 * To get the current lead and use it, use \FluxFE\Lead::getInstance()
 *
 * Use $lead->save(true) to save this lead to the database
 * or $lead->save() to just save the lead to the session
 *
 * You can also use $lead->getValue('firstname') to get the
 * firstname (provided you have saved it before)
 *
 * $lead = \FluxFE\Lead::getInstance();
 * $lead->save(true);
 * echo $lead->getValue('firstname');
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title></title>
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" />
        <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
        <script type='text/javascript' src='http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js'></script>
    </head>
    <body>
        <div class="container">
            <!-- INSERT YOUR PAGE HERE -->
            <h1>Your first path page</h1>
            This is the first page to your path.  Replace this page with your own content and create more pages.
            <p />
            You can place shared php and html pages in this folder:
            <pre><?php echo $_SERVER['DOCUMENT_ROOT'] . '/pages/' ?></pre>
            <p />
            You can place shared images in this folder:
            <pre><?php echo $_SERVER['DOCUMENT_ROOT'] . '/images/' ?></pre>
            <p />
            You can place shared javascript in this folder:
            <pre><?php echo $_SERVER['DOCUMENT_ROOT'] . '/js/' ?></pre>
            <p />
            You can place shared CSS in this folder:
            <pre><?php echo $_SERVER['DOCUMENT_ROOT'] . '/css/' ?></pre>
            <p />
            When creating your path, it is best to place all your pages into the <code>pages</code> directory, then include them using the <code>v1</code>, <code>v2</code>, etc directories.  This way, you can easily re-order pages 
            without having to manually edit them.  Example index pages have been created for you in the <code>v1</code> folder.
        </div>
    </body>
</html>
