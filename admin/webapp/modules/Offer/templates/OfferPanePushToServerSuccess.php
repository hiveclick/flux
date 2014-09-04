<?php
    /* @var $offer \Gun\Offer */
    $offer = $this->getContext()->getRequest()->getAttribute('offer');
    $servers = $this->getContext()->getRequest()->getAttribute('servers', array());
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Push offer to server</h4>
</div>
<form class="form-horizontal" id="offer_server_form" name="offer_server_form" method="POST" action="/api" autocomplete="off">
    <input type="hidden" name="func" value="/Offer/PushToServer" />
    <input type="hidden" name="offer_id" value="<?php echo $offer->getId() ?>" />
    <div class="modal-body">
        <div class="help-block col-md-12">
            Select a server to connect with and create this offer on:
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-sm-2 control-label" for="server_id">Server</label>
                <div class="col-sm-10">
                    <select name="server_id" id="server_id">
                    <?php foreach ($servers as $server) { ?>
                        <option value="<?php echo $server->getId() ?>"><?php echo $server->getHostname() ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="docroot_dir">Folder</label>
                <div class="col-sm-10">
                    <input type="text" id="modal_docroot_dir" name="docroot_dir" class="form-control" value="" placeholder="Docroot Dir (/var/www/sites/...)" />
                    <small class="help-block">Specifies the folder on the server where this offer is located</small>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="domain">Domain</label>
                <div class="col-sm-10">
                    <input type="text" id="modal_domain_name" name="domain" class="form-control" value="" />
                    <small class="help-block">Specifies the domain to use in the VirtualHost file</small>
                </div>
            </div>
            <hr />
            <div class="help-block">
                Select which configuration options you'd like to include with this offer
            </div>
            <div class="form-group">
                <div class="col-sm-9">
                    <label class="control-label" for="modal_recreate_lib_1">Recreate /lib folder</label>
                    <small class="help-block">Pushes new changes to the frontend shared library files</small>
                </div>
                <div class="col-sm-2">
                    <input type="hidden" name="recreate_lib_folder" value="0" />
                    <input type="checkbox" id="modal_recreate_lib_1" name="recreate_lib_folder" value="1" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9">
                    <label class="control-label" for="modal_create_skeleton">Create skeleton folders</label>
                    <small class="help-block">Instructs the server to generate a default site using pre-generated folders if they do not exist</small>
                </div>
                <div class="col-sm-2">
                    <input type="hidden" name="create_skeleton_folder" value="0" />
                    <input type="checkbox" id="modal_create_skeleton" name="create_skeleton_folder" value="1" />
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-9">
                    <label class="control-label" for="modal_generate_virtualhost">Generate VirtualHost</label>
                    <small class="help-block">Generates a VirtualHost file for apache and loads it for you</small>
                </div>
                <div class="col-sm-2">
                    <input type="hidden" name="generate_virtualhost" value="0" />
                    <input type="checkbox" id="modal_generate_virtualhost" name="generate_virtualhost" value="1" />
                </div>
            </div>
        </div>
        <div class="clearfix" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create on server</button>
    </div>
</form>

<script>
//<!--
$(document).ready(function() {
	$('#server_id').selectize();
	
	$('#modal_recreate_lib_1').bootstrapSwitch({
    	onText: 'Yes',
    	offText: 'No'
    });

    $('#modal_create_skeleton').bootstrapSwitch({
    	onText: 'Yes',
    	offText: 'No'
    });
    
    $('#modal_generate_virtualhost').bootstrapSwitch({
    	onText: 'Yes',
    	offText: 'No'
    });

    $('#offer_server_form').form(function(data) {
        if (data.record) {
            $.rad.notify('Offer saved to server', 'The offer\'s folder was successfully created on the server')
        }
    }, {keep_form:true});
});
//-->
</script>