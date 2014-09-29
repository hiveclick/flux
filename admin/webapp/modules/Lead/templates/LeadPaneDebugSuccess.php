<?php
	/* @var $lead \Flux\Lead */
	$lead = $this->getContext()->getRequest()->getAttribute('lead', array());
?>
<div class="help-block">This page will help you debug the lead to determine any problems when testing offers and flows</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#leadDataAccordion">Data</a>
        </div>
    </div>
    <div id="leadDataAccordion" class="panel-body panel-collapse collapse out">
        <table class="table table-hover table-bordered table-striped table-responsive lead-data-table">
            <thead>
                <tr>
        			<th>Data Field</th>
        			<th>Request Name</th>
        			<th>Type</th>
        			<th>Value</th>
        		</tr>
        	</thead>
        	<tbody>
        	<?php
        	   /* @var $lead_event \Flux\LeadEvent */ 
        	   foreach ($lead->getD() as $key => $value) { 
                    $data_field = \Flux\DataField::retrieveDataFieldFromName($key);
            ?>
                <tr>
                    <td>
                        <?php if (!is_null($data_field)) { ?>
                            <a href="/admin/data-field?_id=<?php echo $data_field->getId() ?>"><?php echo $data_field->getName() ?></a>
                        <?php } else { ?> 
                            <mark><i><?php echo $key ?></i></mark> <span class="label label-danger">Unmatched data field</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (!is_null($data_field)) { ?>
                            <?php echo implode(", ", array_merge(array('<strong>' . $data_field->getKeyName() . '</strong>'), $data_field->getRequestName())) ?>
                        <?php } else { ?> 
                            <mark><i><?php echo $key ?></i></mark>
                        <?php } ?>
                    </td>
                    <td>
        			     <?php if (is_array($value)) { ?>
        			         Array
        		         <?php } else if (is_string($value)) { ?>
        		             String
        		         <?php } else if (is_object($value) && $value instanceof \MongoDate) { ?>
        		              MongoDate
        		         <?php } else if (is_object($value) && $value instanceof \MongoId) { ?>
        		              MongoId
        		         <?php } else if (is_object($value) && $value instanceof \Flux\LeadEvent) { ?>
        		              LeadEvent
        		         <?php } else if (is_object($value)) { ?>
        		              Object <span class="label label-warning">Unknown object (<?php echo get_class($value) ?>)</span>
        		         <?php } else if (is_numeric($value)) { ?>
        		              <?php echo $value ?>
        		         <?php } else { ?>
        		              Unknown <span class="label label-danger">Unknown data type<?php echo !is_null($data_field) ? ', should be ' . $data_field->getFieldTypeName() : '' ?></span>
        		         <?php } ?>
                    </td>
                    <td>
                        <?php if (is_array($value)) { ?>
        			         <?php echo implode(", ", $value) ?>
        		        <?php } else if (is_string($value)) { ?>
                            <?php echo $value ?>
                        <?php } else { ?>
                            <?php echo var_dump($value) ?>
                        <?php } ?>
                    </td>
        		</tr>
        	<?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#leadEventAccordion">Events</a>
        </div>
    </div>
    <div id="leadEventAccordion" class="panel-body panel-collapse collapse out">
        <table class="table table-hover table-bordered table-striped table-responsive lead-event-table">
            <thead>
                <tr>
        			<th>Data Field</th>
        			<th>Event Time</th>
        			<th>Request Name</th>
        			<th>Payout</th>
        			<th>Revenue</th>
        			<th>Offer</th>
        			<th>Client</th>
        			<th>Value</th>
        		</tr>
        	</thead>
        	<tbody>
        	<?php
        	   /* @var $lead_event \Flux\LeadEvent */ 
        	   foreach ($lead->getE() as $key => $lead_event) { 
            ?>
                <tr>
                    <td>
                        <?php if ($lead_event->getDatafield()->getId() > 0) { ?>
                            <a href="/admin/data-field?_id=<?php echo $lead_event->getDataField()->getId() ?>"><?php echo $lead_event->getDatafield()->getName() ?></a>
                        <?php } else { ?>
                            <?php echo $lead_event->getDatafieldId() ?> <span class="label label-danger">Unmatched data field</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($lead_event->getT() instanceof \MongoDate) { ?>
                            <?php echo date('m/d/Y g:i:s a', $lead_event->getT()->sec) ?>
                        <?php } else { ?>
                            <mark><?php echo $lead_event->getT() ?></mark> <span class="label label-danger">Date missing or not \MongoDate object</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo implode(", ", array_merge(array('<strong>' . $lead_event->getDatafield()->getKeyName() . '</strong>'), $lead_event->getDatafield()->getRequestName())) ?>
                    </td>
                    <td>$<?php echo number_format($lead_event->getPayout(), 2, null, ',') ?></td>
                    <td>$<?php echo number_format($lead_event->getRevenue(), 2, null, ',') ?></td>
                    <td>
                        <?php if ($lead_event->getOfferId() > 0) { ?>
                            <?php if ($lead_event->getOffer()->getId() == $lead_event->getOfferId()) { ?>
                                <?php echo $lead_event->getOffer()->getName() ?> (<?php echo $lead_event->getOfferId() ?>)
                            <?php } else { ?>
                                <?php echo $lead_event->getOfferId() ?> <span class="label label-danger">Unmatched Offer Id</span>
                            <?php } ?>
                        <?php } else { ?>
                            <?php echo $lead_event->getOfferId() ?> <span class="label label-danger">Invalid Offer Id</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($lead_event->getClientId() > 0) { ?>
                            <?php if ($lead_event->getClient()->getId() == $lead_event->getClientId()) { ?>
                                <?php echo $lead_event->getClient()->getName() ?> (<?php echo $lead_event->getClientId() ?>)
                            <?php } else { ?>
                                <?php echo $lead_event->getClientId() ?> <span class="label label-danger">Unmatched Client Id</span>
                            <?php } ?>
                        <?php } else { ?>
                            <?php echo $lead_event->getClientId() ?> <span class="label label-danger">Invalid Client Id</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (is_array($lead_event->getValue())) { ?>
        			         <?php echo implode(", ", $lead_event->getValue()) ?>
        		        <?php } else if (is_string($lead_event->getValue())) { ?>
                            <?php echo $lead_event->getValue() ?>
                        <?php } else if (is_numeric($lead_event->getValue())) { ?>
                            <?php echo $lead_event->getValue() ?>
                        <?php } else { ?>
                            <?php echo var_dump($lead_event->getValue()) ?>
                        <?php } ?>
                    </td>
        		</tr>
        	<?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#leadRawAccordion">Raw Lead</a>
        </div>
    </div>
    <div id="leadRawAccordion" class="panel-body panel-collapse collapse out">
        <pre><?php echo var_export($lead, true) ?></pre>
    </div>
</div>
<script>
//<!--
$(document).ready(function() {
    $('.lead-data-table').dataTable({
    	autoWidth: false,
		pageLength: 15,
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
	});

    $('.lead-tracking-table').dataTable({
    	autoWidth: false,
		pageLength: 15,
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
	});

    $('.lead-event-table').dataTable({
    	autoWidth: false,
		pageLength: 15,
    	searching: false,
    	paging: true,
    	dom: 'Rfrtpi',
	});
});
//-->
</script>