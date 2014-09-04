<?php
    /* @var $vertical Gun\DataField */
    $vertical = $this->getContext()->getRequest()->getAttribute("vertical", array());
    $offers = $this->getContext()->getRequest()->getAttribute("offers", array());
?>
<div class="help-block">These are the offers that currently use this vertical</div>
<br />
<table class="table table-hover table-bordered table-striped table-responsive">
    <thead>
        <tr>
            <th>Name</th>
            <th>Client</th>
        </tr>
    </thead>
    <tbody>
        <?php
            /* @var $offer Gun\Offer */
            foreach($offers AS $offer) {
        ?>
        <tr>
            <td>
                <a href="/offer/offer?_id=<?php echo $offer->getId() ?>"><?php echo $offer->getName() ?></a>
            </td>
            <td>
               <a href="/client/client?_id=<?php echo $offer->getClientId() ?>"><?php echo $offer->getClient()->getName() ?></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>