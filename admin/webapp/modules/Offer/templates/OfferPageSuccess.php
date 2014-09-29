<?php
    /* @var $offer_page Flux\OfferPage */
    $offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
?>
<!-- Include this script here, instead of the preview pane because the preview pane is loaded via javascript and the websnapr won't load correctly -->
<div id="header">
    <div class="pull-right visible-xs">
        <button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <h2><a href="/offer/offer?_id=<?php echo $offer_page->getOfferId() ?>"><?php echo $offer_page->getOffer()->getName() ?></a> <small><?php echo $offer_page->getName() ?> (<?php echo $offer_page->getPageName() ?>)</small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
    <ul class="nav nav-pills" id="offer_tabs">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Main</a></li>
        <li><a id="tabs-a-flow" href="#tabs-flow" data-toggle="tab" data-url="/offer/offer-page-pane-flow?_id=<?php echo $offer_page->getId() ?>">Flow</a></li>
        <li><a id="tabs-a-edit" href="#tabs-edit" data-toggle="tab" data-url="/offer/offer-page-pane-edit?_id=<?php echo $offer_page->getId() ?>">Edit HTML</a></li>
        <li><a id="tabs-a-preview" href="#tabs-preview" data-toggle="tab" data-url="/offer/offer-page-pane-preview?_id=<?php echo $offer_page->getId() ?>">Preview</a></li>
    </ul>
</div>

<div id="tab-content-container" class="tab-content">
    <div id="tabs-main" class="tab-pane active">
        <div class="help-block">These are the main settings for this offer page.</div>
        <br/>
        <form class="form-horizontal" name="offer_form" method="POST" action="" autocomplete="off">
            <input type="hidden" name="_id" value="<?php echo $offer_page->getId() ?>" />
            <input type="hidden" name="offer_id" value="<?php echo $offer_page->getOfferId() ?>" />
            <input type="hidden" name="preview_url" value="<?php echo $offer_page->getPreviewUrl() ?>" />
            <input type="hidden" name="file_path" value="<?php echo $offer_page->getFilePath() ?>" />
            <input type="hidden" name="priority" value="<?php echo $offer_page->getPriority() ?>" />
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $offer_page->getName() ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="page_name">Description</label>
                <div class="col-sm-10">
                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter brief description about this page..."><?php echo $offer_page->getDescription() ?></textarea>
                </div>
            </div>

            <hr />
            <div class="help-block">Enter filename of this page located on the server.  This is how we can associate clicks to this page.</div>
            <p />
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="page_name">Page Name</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="page_name" class="form-control" required placeholder="Page Filename" value="<?php echo $offer_page->getPageName() ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="__save" class="btn btn-success" value="Save" />
                    <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Page" />
                </div>
            </div>

        </form>
    </div>
    <div id="tabs-preview" class="tab-pane"></div>
    <div id="tabs-flow" class="tab-pane"></div>
    <div id="tabs-edit" class="tab-pane"></div>
</div>
<script>
//<!--
$(document).ready(function() {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        e.preventDefault();
        var hash = this.hash;
        if ($(this).attr("data-url")) {
            // only load the page the first time
            if ($(hash).html() == '') {
                // ajax load from data-url
                $(hash).load($(this).attr("data-url"));
            }
        }
    }).on('show.bs.tab', function (e) {
    	try {
    	    sessionStorage.setItem(localTabStorageName, $(e.target).attr('href'));
	    } catch (err) { }
    });

    $('#btn_delete').click(function() {
        if (confirm('Are you sure you want to delete this page and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/offer/offer-page/<?php echo $offer_page->getId() ?>' }, function(data) {
                $.rad.notify('Page Removed', 'This page has been removed from the system.');
            });
        }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('offer_page_tab_' . $offer_page->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (location.hash) {
        var hash = location.hash, hashPieces = hash.split('?'), activeTab = $('[href=' + hashPieces[0] + ']');
    }
    if (activeTab) {
        activeTab.tab('show');
    } else {
        if (lastTab) {
            $('a[href='+lastTab+']').tab('show');
        } else {
            $('ul.nav-pills a:first').tab('show');
        }
    }
});
//-->
</script>