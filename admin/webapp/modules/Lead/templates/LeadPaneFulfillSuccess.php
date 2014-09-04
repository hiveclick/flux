<?php
	/* @var $lead \Gun\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="help-block">You can fulfill this lead manually to various lead providers on this page</div>
<br/>
<div class="panel-group" id="accordion">
    <div class="panel panel-default" style="overflow:visible;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Test Fulfillment</a>
            </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse out">
            <div class="panel-body">
                To fulfill this lead as a test use the following links:
                <p />
                <button id="fulfill_to_test_email" class="btn-info btn">Send as an email</button>
                <button id="fulfill_to_test_post" class="btn-info btn">Send as a POST</button>
            </div>
        </div>
    </div>
    <div class="panel panel-default" style="overflow:visible;">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Send to Rosie at HMLM</a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse out">
            <div class="panel-body">
                To fulfill this lead to Rosie at HMLM Law Group as an email, simply click the button below:
                <p />
                <button id="fulfill_to_rosie_hip" class="btn-info btn">Send to Rosie as Hip Lead</button>
                <button id="fulfill_to_rosie_tvm" class="btn-info btn">Send to Rosie/Randi as TVM Lead</button>
                <button id="fulfill_to_rosie_risperdal" class="btn-info btn">Send to Rosie/Randi as Risperdal Lead</button>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Send to Avid Ads as SSRI</a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse out">
            <div class="panel-body">
                To fulfill this lead to Howard East at Avid Ads as a POST request, simply click the button below:
                <p />
                <button id="fulfill_to_avid_ads_ssri" class="btn-info btn">Send to Avid Ads as SSRI Lead</button>
                <button id="fulfill_to_avid_ads_lowt" class="btn-info btn">Send to Avid Ads as Low-T Lead</button>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Send to Diablomedia</a>
            </h4>
        </div>
        <div id="collapseFour" class="panel-collapse collapse out">
            <div class="panel-body">
                To fulfill this lead to Diablomedia as a POST request, simply click the button below:
                <p />
                <button id="fulfill_to_diablo_mesh" class="btn-info btn">Send to Diablomedia as Mesh</button>
                <button id="fulfill_to_diablo_hip" class="btn-info btn">Send to Diablomedia as Hip</button>
                <!--
                <button id="fulfill_to_diablo_stryker" class="btn-info btn">Send to Diablomedia as Stryker Hip</button>
                <button id="fulfill_to_diablo_vehicle" class="btn-info btn">Send to Diablomedia as Vehicle Injury</button>
                -->
            </div>
        </div>
    </div>
</div>
<p />

<script>
//<!--
$('document').ready(function() {
	$('#fulfill_to_test_email,#fulfill_to_test_post').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-test-email', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Test Email', 'This lead has been sent to mark@doublesplash.com')
            }
        });
    });
	
    $('#fulfill_to_rosie_hip').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-rosie-hip', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Rosie', 'This lead has been sent to Rosie at HMLM Law Group')
            }
        });
    });

    $('#fulfill_to_rosie_tvm').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-rosie-tvm', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Rosie/Randi', 'This lead has been sent to Rosie/Randi at HMLM Law Group')
            }
        });
    });

    $('#fulfill_to_rosie_risperdal').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-rosie-risperdal', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Rosie/Randi', 'This lead has been sent to Rosie/Randi at HMLM Law Group')
            }
        });
    });

    $('#fulfill_to_avid_ads_lowt').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-avid-ads-lowt', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Howard East', 'This lead has been sent to Howard East at Avid Ads')
            }
        });
    });	

    $('#fulfill_to_avid_ads_ssri').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-avid-ads-ssri', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Howard East', 'This lead has been sent to Howard East at Avid Ads')
            }
        });
    });	

    $('#fulfill_to_diablo_mesh').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-diablo-mesh', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Diablomedia', 'This lead has been sent to Diablomedia as Mesh')
            }
        });
    });	

    $('#fulfill_to_diablo_hip').click(function() {
        $.rad.post('/api', { func: '/lead/manual-fulfill-diablo-hip', _id:  '<?php echo $lead->getId() ?>' }, function(data) {
            if (data.record) {
                $.rad.notify('Lead fulfilled to Diablomedia', 'This lead has been sent to Diablomedia as Hip')
            }
        });
    });	
});
//-->
</script>