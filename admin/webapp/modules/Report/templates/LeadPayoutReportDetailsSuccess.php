<?php
    /* @var $revenue_report \Flux\ReportLead */
	$report_lead = $this->getContext()->getRequest()->getAttribute('report_lead', array());
	$clients = $this->getContext()->getRequest()->getAttribute('clients', array());
	$leads = $this->getContext()->getRequest()->getAttribute('leads', array());
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">Lead Payout Report Details</h4>
</div>
<div class="modal-body">
    <div  style="height:600px;overflow:auto;">
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Lead</th>
                <th>Payout</th>
                <th>Revenue</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php
                /* @var $lead \Flux\ReportLead */ 
                foreach ($leads as $key => $lead) { 
            ?>
            <tr>
                <td><?php echo $key + 1 ?></td>
                <td><?php echo date('m/d/Y', $lead->getReportDate()->sec) ?></td>
                <td>
                    <?php echo $lead->getLead()->getLeadName() ?>
                    <div class="small text-muted"><?php echo $lead->getLead()->getEmail() ?></div>
                </td>
                <td>$<?php echo number_format($lead->getPayout(), 2, null, ',') ?></td>
                <td>$<?php echo number_format($lead->getRevenue(), 2, null, ',') ?></td>
                <td><?php echo $lead->getDispositionMessage() ?></td>
            </tr>
            <?php } ?>
            
        </tbody>
        
    </table>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>