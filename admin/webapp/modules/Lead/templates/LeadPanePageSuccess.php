<?php
	/* @var $lead_page \Flux\LeadPage */
	$lead_page = $this->getContext()->getRequest()->getAttribute('lead_page', array());
	if (is_array($lead_page->getCookie())) {
	   $cookies = $lead_page->getCookie();
	} else {
	    $cookies = http_parse_cookie($lead_page->getCookie());
	}
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Lead Cookies</h4>
</div>
<div class="modal-body">
    <div class="col-md-12">
        <div class="help-block">This is the data stored in the cookie for this lead when they visited this page</div>
        <br/>
        <?php if (isset($cookies->cookies)) { ?>
            <?php foreach ($cookies->cookies as $key => $cookie) { ?>
            <label><?php echo $key ?></label>
            <textarea readonly style="width:100%;" rows="<?php echo intval(strlen($cookie) / 72) > 15 ? 15 : intval(strlen($cookie) / 72)  ?>" class="well"><?php echo $cookie ?></textarea>
            <?php } ?>
        <?php } else { ?>
            <em>No cookies found for this page</em>
        <?php } ?>
    </div>
    <div class="clearfix" />
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>