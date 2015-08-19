<?php
	/* @var $split_queue \Flux\SplitQueue */
	$split_queue = $this->getContext()->getRequest()->getAttribute("split_queue", array());
	$datafields = $this->getContext()->getRequest()->getAttribute("datafields", array());
?>
<?php if (!$split_queue) { ?>
    <h1>No more leads on split</h1>
<?php } else {?>
    <div class="page-header">
    	<!-- Actions -->
    	<div class="pull-right">
    		<div class="visible-sm visible-xs">
    			<div class="btn-group">
      				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Actions <span class="caret"></span></button>
    				<ul class="dropdown-menu dropdown-menu-right" role="menu">
    					<li><a href="/export/fulfill-next-lead?_id=<?php echo $split_queue->getId() ?>">mark as fulfilled</a></li>
    				</ul>
    			</div>
    		</div>
    		<div class="hidden-sm hidden-xs">
    			<a class="btn btn-info" href="/export/fulfill-next-lead?_id=<?php echo $split_queue->getId() ?>">mark as fulfilled</a>
    		</div>
    	</div>
    	<h1>View Next Lead</h1>
    </div>
    <!-- Add breadcrumbs -->
    <ol class="breadcrumb">
    	<li><a href="/export/split-search">Splits</a></li>
    	<li><a href="/export/split?_id=<?php echo $split_queue->getSplit()->getSplitId() ?>"><?php echo $split_queue->getSplit()->getSplitName() ?></a></li>
    	<li class="active">Lead #<?php echo $split_queue->getId() ?></li>
    </ol>
    
    <!-- Page Content -->
    <div class="help-block">You can view a lead on this screen and see how it was tracked</div>
    <br/>
    <div class="col-md-8">
    	<div class="panel panel-default">
    		<div class="panel-heading">Data Information</div>
    		<div class="panel-body">
    		    <div class="form-group">
                    <label for="name">Fullname: </label>
                    <input type="text" class="form-control" name="name" value="<?php echo $split_queue->getLead()->getLead()->getValue('name') ?>" id="name" />
                </div>
                <div class="form-group">
                    <label for="firstname">Firstname: </label>
                    <input type="text" class="form-control" name="firstname" value="<?php echo $split_queue->getLead()->getLead()->getValue('fn') != '' ? $split_queue->getLead()->getLead()->getValue('fn') : substr($split_queue->getLead()->getLead()->getValue('name'), 0, strpos($split_queue->getLead()->getLead()->getValue('name'), ' ')) ?>" id="firstname" />
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname: </label>
                    <input type="text" class="form-control" name="lastname" value="<?php echo $split_queue->getLead()->getLead()->getValue('ln') != '' ? $split_queue->getLead()->getLead()->getValue('ln') : substr($split_queue->getLead()->getLead()->getValue('name'), strrpos($split_queue->getLead()->getLead()->getValue('name'), ' ') + 1) ?>" id="lastname" />
                </div>
                <div class="form-group">
                    <label for="email">Email: </label>
                    <input type="text" class="form-control" name="email" value="<?php echo $split_queue->getLead()->getLead()->getValue('em') ?>" id="email" />
                </div>
                <div class="form-group">
                    <label for="a1">Address: </label>
                    <input type="text" class="form-control" name="a1" value="<?php echo $split_queue->getLead()->getLead()->getValue('a1') ?>" id="a1" />
                </div>
                <div class="form-group">
                    <label for="cy">City: </label>
                    <input type="text" class="form-control" name="cy" value="<?php echo $split_queue->getLead()->getLead()->getDerivedCity() ?>" id="cy" />
                </div>
                <div class="form-group">
                    <label for="st">State: </label>
                    <input type="text" class="form-control" name="st" value="<?php echo $split_queue->getLead()->getLead()->getDerivedState() ?>" id="st" />
                </div>
                <div class="form-group">
                    <label for="zi">Zip: </label>
                    <input type="text" class="form-control" name="zi" value="<?php echo $split_queue->getLead()->getLead()->getValue('zi') ?>" id="zi" />
                </div>
                <div class="form-group">
                    <label for="ph">Phone: </label>
                    <div class="row">
                        <div class="col-md-3"><input type="text" class="form-control" name="ph" value="<?php echo $split_queue->getLead()->getLead()->getValue('ph') ?>" id="ph" /></div>
                        <div class="col-md-3"><input type="text" class="form-control" name="stripped_ph" value="<?php echo preg_replace('/[^0-9]/', '', $split_queue->getLead()->getLead()->getValue('ph')) ?>" id="stripped_ph" /></div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="area_code" value="<?php echo substr(preg_replace('/[^0-9]/', '', $split_queue->getLead()->getLead()->getValue('ph')), 0, 3) ?>" id="area_code" />
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="prefix_ph" value="<?php echo substr(preg_replace('/[^0-9]/', '', $split_queue->getLead()->getLead()->getValue('ph')), 3, 3) ?>" id="prefix_ph" />
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="suffix_ph" value="<?php echo substr(preg_replace('/[^0-9]/', '', $split_queue->getLead()->getLead()->getValue('ph')), 6) ?>" id="suffix_ph" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <?php
    				 $known_fields = array('fn', 'ln', 'a1', 'cy', 'st', 'zi', 'em', 'name', 'ph'); 
    				 foreach ($split_queue->getLead()->getLead()->getD() as $key => $value) { 
    			?>
    				<?php if (!in_array($key, $known_fields)) { ?>
    					<?php	 							 
    						 $data_field = \Flux\DataField::retrieveDataFieldFromKeyName($key); 
    			        ?>
    			        <div class="form-group">
        			        <?php if (!is_null($data_field)) { ?>
                                <label for="<?php echo $key ?>"><?php echo $data_field->getName() ?>: </label>
                                <?php if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE) { ?>
                                    <input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo date('m/d/Y', $value->sec) ?>" id="<?php echo $key ?>" />
                                <?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME) { ?>
                                    <input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo date('m/d/Y g:i:s a', $value->sec) ?>" id="<?php echo $key ?>" />
                                <?php } else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) { ?>
                                    <?php if (is_array($value)) { ?>
                                        <input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo implode(', ', $value) ?>" id="<?php echo $key ?>" />
                                    <?php } else if (is_string($value)) { ?>
                                        <input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo $value ?>" id="<?php echo $key ?>" />
        							<?php } ?>
               					<?php } else if (is_array($value)) { ?>
        		                    <input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo implode(', ', $value) ?>" id="<?php echo $key ?>" />
        		     			<?php } else { ?>
        			                <input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo $value ?>" id="<?php echo $key ?>" />
        			    		<?php } ?>
                            <?php } else { ?>
                                <label for="<?php echo $key ?>"><?php echo $key ?>: </label>
    					        <input type="text" class="form-control" name="<?php echo $key ?>" value="<?php echo is_array($value) ? implode(", ", $value) : $value ?>" id="<?php echo $key ?>" />
    				        <?php } ?>
    				    </div>
                    <?php } ?>
    			<?php } ?>
    		</div>
    	</div>
    </div>
    <div class="col-md-4">
    	<div class="panel panel-default">
    		<div class="panel-heading">
    			Tracking Information
    		</div>
    		<div class="panel-body word-break">
    		    <div class="form-group">
                    <label for="_id">Id: </label>
                    <input type="text" class="form-control" name="_id" value="<?php echo $split_queue->getId() ?>" id="_id" />
                </div>
    		    <div class="form-group">
                    <label for="lead_id">Lead Id: </label>
                    <input type="text" class="form-control" name="lead_id" value="<?php echo $split_queue->getLead()->getLeadId() ?>" id="lead_id" />
                </div>
                <div class="form-group">
                    <label for="created">Created: </label>
                    <input type="text" class="form-control" name="created" value="<?php echo date('m/d/Y g:i:s a', $split_queue->getId()->getTimestamp()) ?>" id="created" />
                </div>
    			<hr />
    			<div class="form-group">
                    <label for="offer">Offer: </label>
                    <input type="text" class="form-control" name="offer" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getOffer()->getOfferName() ?>" id="offer" />
                </div>
                <div class="form-group">
                    <label for="client">Client: </label>
                    <input type="text" class="form-control" name="client" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getClient()->getClientName() ?>" id="client" />
                </div>
                <div class="form-group">
                    <label for="campaign">Campaign: </label>
                    <input type="text" class="form-control" name="campaign" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getCampaign()->getCampaignId() ?>" id="campaign" />
                </div>
    			<hr />
    			<div class="form-group">
                    <label for="s1">Sub Id #1: </label>
                    <input type="text" class="form-control" name="s1" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getS1() ?>" id="s1" />
                </div>
                <div class="form-group">
                    <label for="s2">Sub Id #2: </label>
                    <input type="text" class="form-control" name="s2" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getS2() ?>" id="s2" />
                </div>
                <div class="form-group">
                    <label for="s3">Sub Id #3: </label>
                    <input type="text" class="form-control" name="s3" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getS3() ?>" id="s3" />
                </div>
                <div class="form-group">
                    <label for="s4">Sub Id #4: </label>
                    <input type="text" class="form-control" name="s4" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getS4() ?>" id="s4" />
                </div>
                <div class="form-group">
                    <label for="s5">Sub Id #5: </label>
                    <input type="text" class="form-control" name="s5" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getS5() ?>" id="s5" />
                </div>
                <div class="form-group">
                    <label for="uid">Unique Id: </label>
                    <input type="text" class="form-control" name="uid" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getUid() ?>" id="uid" />
                </div>
    			<hr />
    			<div class="form-group">
                    <label for="ip">IP: </label>
                    <input type="text" class="form-control" name="ip" value="<?php echo $split_queue->getLead()->getLead()->getTracking()->getIp() ?>" id="ip" />
                </div>
                <div class="form-group">
                    <label for="referer">Referer: </label>
                    <input type="text" class="form-control" name="referer" value="<?php echo urldecode($split_queue->getLead()->getLead()->getTracking()->getRef()) ?>" id="referer" />
                </div>
    		</div>
    	</div>
    </div>
    
    <script>
    //<!--
    $(document).ready(function() {
    
    });
    //-->
    </script>
<?php } ?>