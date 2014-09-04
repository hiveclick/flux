<?php
    /* @var $vertical Gun\DataField */
    $vertical = $this->getContext()->getRequest()->getAttribute("vertical", array());
?>
<div id="header">
    <div class="pull-right visible-xs">
        <button class="navbar-toggle collapsed visible-xs" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <h2><a href="/admin/vertical-search">Verticals</a> <small><?php echo $vertical->getName() ?></small></h2>
</div>
<div id="tabs" class="navbar-collapse collapse">
    <ul id="vertical_tabs" class="nav nav-pills">
        <li class="active"><a id="tabs-a-main" href="#tabs-main" data-toggle="tab">Vertical</a></li>
        <li><a id="tabs-a-offers" href="#tabs-offers" data-toggle="tab" data-url="/admin/vertical-pane-offers?_id=<?php echo $vertical->getId() ?>">Offers</a></li>
    </ul>
</div>

<div id="tab-content-container" class="tab-content">
    <div id="tabs-main" class="tab-pane active">
        <div class="help-block">Change information about this vertical or view the offers that use it</div>
           <br />
        <form class="form-horizontal" name="vertical_form" method="POST" action="" autocomplete="off" role="form">
            <input type="hidden" name="_id" value="<?php echo $vertical->getId() ?>" />

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $vertical->getName() ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="description">Description</label>
                <div class="col-sm-10">
                       <textarea name="description" id="description" class="form-control" placeholder="Enter Description..."><?php echo $vertical->getDescription() ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="status">Status</label>
                <div class="col-sm-10">
                    <select class="form-control" name="status" id="status" required placeholder="Status">
                        <?php foreach(\Gun\Vertical::retrieveStatuses() AS $status_id => $status_name) { ?>
                        <option value="<?php echo $status_id; ?>"<?php echo $vertical->getStatus() == $status_id ? ' selected' : ''; ?>><?php echo $status_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="__save" class="btn btn-success" value="Save" />
                    <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Vertical" />
                </div>
            </div>
        </form>
    </div>
    <div id="tabs-offers" class="tab-pane"></div>
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
        if (confirm('Are you sure you want to delete this vertical and completely remove it from the system?')) {
            $.rad.del('/api', { func: '/admin/vertical/<?php echo $vertical->getId() ?>' }, function(data) {
                $.rad.notify('Vertical Removed', 'This vertical has been removed from the system.');
            });
        }
    });

    // Store the last clicked tab so it can be loaded on page refreshes
    var localTabStorageName = <?php echo json_encode('vertical_tab_' . $vertical->getId()); ?>;
    var lastTab = sessionStorage.getItem(localTabStorageName);
    if (lastTab) {
        $('a[href='+lastTab+']').tab('show');
    } else {
        $('ul.nav-pills a:first').tab('show');
    }
});
//-->
</script>