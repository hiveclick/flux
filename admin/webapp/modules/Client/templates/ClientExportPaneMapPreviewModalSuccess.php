<?php
    /* @var $client_export \Flux\ClientExport */
    $client_export = $this->getContext()->getRequest()->getAttribute("client_export", array());
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel">Mapping Preview</h4>
</div>
<div class="modal-body">
    <?php if ($client_export->getExportClass()->getClientExportType() == \Flux\Export\ExportAbstract::CLIENT_EXPORT_TYPE_POST) { ?>
        This export will POST to the following URL:
        <p />
        <pre><?php echo $client_export->getPostUrl() ?></pre>
        <p />
        with these fields set:
        <p />
        <table class="table table-responsive table-bordered">
            <thead>
                <?php foreach ($client_export->getMapping() as $client_export_mapping) { ?>
                    <tr>
                        <td><strong><?php echo $client_export_mapping->getFieldName() == '' ? $client_export_mapping->getDataField()->getKeyName() : $client_export_mapping->getFieldName() ?></strong></td>
                        <td>
                            <?php if ($client_export_mapping->getMappingFunc() != \Flux\ClientExportMap::getDefaultMappingFunc()) { ?>
                                <div class="custom-function">
                                    <button class="btn btn-sm btn-info btn-show-code pull-right">show</button>
                                    <em>- custom function -</em>
                                    
                                    
                                    <div class="code-preview collapse"><div class="clearfix"></div><pre><?php echo $client_export_mapping->getMappingFunc() ?></pre></div>
                                </div>
                            <?php } else { ?>
                                <?php echo $client_export_mapping->getDataField()->getKeyName() ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </thead>
        </table>
    <?php } else { ?>
        This export will FTP to the following host:
        <p />
        <pre><?php echo $client_export->getFtpUsername() ?>@<?php echo $client_export->getFtpHostname() ?></pre>
        <p />
        with these fields set:
        <p />
        <table class="table table-responsive table-bordered">
            <thead>
                <?php foreach ($client_export->getMapping() as $client_export_mapping) { ?>
                    <tr>
                        <td><strong><?php echo $client_export_mapping->getFieldName() == '' ? $client_export_mapping->getDataField()->getKeyName() : $client_export_mapping->getFieldName() ?></strong></td>
                        <td>
                            <?php if ($client_export_mapping->getMappingFunc() != \Flux\ClientExportMap::getDefaultMappingFunc()) { ?>
                                <div class="custom-function">
                                    <button class="btn btn-sm btn-info btn-show-code pull-right">show</button>
                                    getValue("<em><?php echo $client_export_mapping->getDataField()->getKeyName() ?></em>") with <em>custom function</em>
                                    
                                    
                                    <div class="code-preview collapse"><div class="clearfix"></div><pre><?php echo $client_export_mapping->getMappingFunc() ?></pre></div>
                                </div>
                            <?php } else { ?>
                                getValue("<em><?php echo $client_export_mapping->getDataField()->getKeyName() ?></em>")
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </thead>
        </table>
    <?php } ?>
</div>
<script>
//<!--
$(document).ready(function() {
    $('.btn-show-code').click(function() {
        var clicked_btn = $(this);
    	$(this).closest('.custom-function').find('.code-preview').toggle(0, function() {
            if ($(this).is(':visible')) {
            	clicked_btn.text('hide');                
           } else {
        	   clicked_btn.text('show');                
           }        
       });
    });
});
//-->
</script>
