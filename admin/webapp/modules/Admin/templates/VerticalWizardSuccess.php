<?php
    /* @var $vertical Flux\Vertical */
    $vertical = $this->getContext()->getRequest()->getAttribute("vertical", array());
?>
<div id="header">
   <h2><a href="/admin/vertical-search">Verticals</a> <small>New Vertical</small></h2>
</div>
<div class="help-block">Create a new vertical that you can use to organize offers</div>
<br/>
<form class="form-horizontal" name="vertical_form" method="POST" action="" autocomplete="off" role="form">
    <input type="hidden" name="status" value="<?php echo \Flux\Vertical::VERTICAL_STATUS_ACTIVE ?>" />
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
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="__save" class="btn btn-success" value="Save" />
        </div>
    </div>
</form>