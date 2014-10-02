<?php
/* Required lines used to instantiate the lead and setup the php environment */
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/init.php');
/* This will save the lead to the session AND to the database (by passing in the [true]) */
\FluxFE\Lead::getInstance()->save(true);

/* This line will output debugging information about the lead that you may find useful */
\FluxFE\Lead::debug();

/* Add redirect code here to go to the next page */
if (isset($_POST['btn_submit'])) {
    $page = \FluxFE\Lead::getInstance()->findNextPage(); // Put the page you want to redirect to here
    header('Location: ' . $page);
}
?>
<?php include('../pages/index.php') ?>