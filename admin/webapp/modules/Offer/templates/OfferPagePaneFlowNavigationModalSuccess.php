<?php 
    /* @var $offer_page_flow \Flux\OfferPageFlow */
    $offer_page_flow = $this->getContext()->getRequest()->getAttribute('offer_page_flow', array());
    $offer_pages = $this->getContext()->getRequest()->getAttribute("offer_pages", array());
    $offer_page = $this->getContext()->getRequest()->getAttribute("offer_page", array());
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel">Manage Destination Page</h4>
</div>
<form id="offer_page_flow_navigation_modal_form" method="POST" action="">
	<input type="hidden" id="offer_page_flow_navigation_modal_rule_position" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][position]" value="<?php echo $offer_page_flow->getPosition() ?>" />
    <div class="modal-body">
    	<div class="form-group">
            <label>
                I want to redirect to
            </label>
            <select id="navigation_type" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][navigation][navigation_type]" class="span2 navigation_type">
                <option value="1" <?php echo ($offer_page_flow->getNavigation()->getNavigationType() == '1' ? "SELECTED" : "") ?>>another page in this offer</option>
                <option value="2" <?php echo ($offer_page_flow->getNavigation()->getNavigationType() == '2' ? "SELECTED" : "") ?>>an external redirect url</option>
            </select>
        </div>
        <div class="panel panel-default">
        	<div class="navigation_type_1" style="display:<?php echo ($offer_page_flow->getNavigation()->getNavigationType() == '2' ? "none;" : "block;") ?>">
        		<div class="panel-heading"><h3 class="panel-title">Choose the destination page:</h3></div>
        		<div class="panel-body" id="destination_page_local">
	        		<select name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][navigation][destination_offer_page_id]" class="selectize offer_page_flow_navigation">
	                    <?php 
	                        /* @var $page \Flux\OfferPage */
	                        foreach ($offer_pages as $page) {
	                    ?>
	                        <option value="<?php echo $page->getId() ?>" data-data="<?php echo htmlentities(json_encode(array('_id' => $page->getId(), 'name' => $page->getName(), 'description' => $page->getDescription(), 'page_name' => $page->getPageName(), 'preview_url' => $page->getPreviewUrl()))) ?>" <?php echo ($page->getId() == $offer_page_flow->getNavigation()->getDestinationOfferPageId()) ? 'SELECTED' : '' ?>><?php echo $page->getName() ?></option>
	                    <?php } ?>
	                </select>
        		</div>
        	</div>
        	<div class="navigation_type_2" style="display:<?php echo ($offer_page_flow->getNavigation()->getNavigationType() == '1' ? "none;" : "block;") ?>">
        		<div class="panel-heading"><h3 class="panel-title">Set the destination url:</h3></div>
        		<div class="panel-body offer_page_flow_navigation_remote_url" id="destination_page_remote">
        			<textarea class="form-control offer_page_flow_navigation_remote_url" name="offer_page_flows[<?php echo $offer_page_flow->getPosition() ?>][navigation][remote_url]" rows="5" cols="25"><?php echo $offer_page_flow->getNavigation()->getRemoteUrl() ?></textarea>
        		</div>
        	</div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>
<script>
//<!--
$(document).ready(function() {
	$selectize_navigation_options = {
    	valueField: '_id',
        labelField: 'name',
        searchField: ['name', 'description'],
    	render: {
            item: function(item, escape) {
            	return '<div>' +
                	'<div class="media">' +
                    '<span class="pull-left thumbnail">' + 
             	    (item.preview_url ? '<img width="75" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=' + escape(item.preview_url) + '" border="0" />' : '<img src="/images/no_preview.png" border="0" />') +
              	   '</span>' + 
              	    '<div class="media-body">' +
                    '<div class="title">' + 
                    '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                    '</div>' +
                    '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                    '<div class="text-success small">' + item.page_name + '</div>' +
                    '</div>' +
                    '</div>' + 
                    '</div>';
            },
            option: function(item, escape) {
                return '<div>' +
                	'<div class="media">' +
                    '<span class="pull-left thumbnail">' + 
             	    (item.preview_url ? '<img width="75" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=' + escape(item.preview_url) + '" border="0" />' : '<img src="/images/no_preview.png" border="0" />') +
              	    '</span>' + 
              	    '<div class="media-body">' +
                    '<div class="title">' + 
                    '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                    '</div>' +
                    '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                    '<div class="text-success small">' + item.page_name + '</div>' +
                    '</div>' +
                    '</div>' + 
                    '</div>';
            }
        },
    };

	/* Render existing filter type select box */
	$('#offer_page_flow_navigation_modal_form .navigation_type').selectize().on('change', function() {
		if($(this).val() == <?php echo json_encode(\Flux\OfferPageFlowNavigation::NAVIGATION_TYPE_LOCAL); ?>) {
			$('.navigation_type_1').show();
			$('.navigation_type_2').hide();
		} else {
			$('.navigation_type_2').show();
			$('.navigation_type_1').hide();
		}
	});
	
	/* Render existing setter type select box */
	$('#offer_page_flow_navigation_modal_form .offer_page_flow_navigation').selectize($selectize_navigation_options);

    /* Handle a form submit by converting it to a text representative and hidden input fields on the main page */
    $('#offer_page_flow_navigation_modal_form').on('submit', function(event) {
    	var position = '<?php echo $offer_page_flow->getPosition() ?>';
        var input_html = $('<div></div>');
        var nav_text = '';
        // Generate the setter text
        $.each($(this).find(':input'), function(i, item) {
        	input_html.append($('<input type="hidden" />').attr('name', $(item).attr('name')).attr('value', $(item).val()));
        	if ($(item).hasClass('navigation_type')) {
        		if ($(item).val() == '1') {
        			nav_text += 'Redirect to another page in this offer:<p />';
        		} else if ($(item).val() == '2') {
        			nav_text += 'Redirect to an external url:<p />';
        	    }
        	} else if ($(item).hasClass('offer_page_flow_navigation')) {
            	if ($('#navigation_type').val() == '1') {
            		nav_text += '<div><div class="media">';
            		nav_text += $(item)[0].selectize.getItem($(item)[0].selectize.getValue()).find('.media').html()
            		nav_text += '</div></div>';
            	}
        	} else if ($(item).hasClass('offer_page_flow_navigation_remote_url')) {
        		if ($('#navigation_type').val() == '2') {
        			nav_text += '<div><div class="media"><span class="pull-left thumbnail">';
        			nav_text += '<img width="128" src="http://api.page2images.com/directlink?p2i_device=6&p2i_screen=1024x768&p2i_size=150x150&p2i_key=<?php echo defined('MO_PAGE2IMAGES_API') ? MO_PAGE2IMAGES_API : '108709d8d7ae991c' ?>&p2i_url=' + $(item).val() + '" border="0" />';
        			nav_text += '</span></div><div class="media-body">';
        			nav_text += '<div style="word-break: break-all;" class="small">' + $(item).val() + '</div>';
        			nav_text += '</div></div>';            		
        		}
        	}
        });
        // Save the hidden form elements into the setter div on the main page
        $('.offer_page_flow_navigation_div-' + position).html(input_html.html() + nav_text);
        // Serialize this form and change the add/modify setter button to pass the serialized values
        $('.add_navigation_btn-' + position).attr('href', '/offer/offer-page-pane-flow-navigation-modal?' + $(this).serialize() + '&offer_page_id=<?php echo $offer_page->getId() ?>');
        // Hide the modal
        $('#flow_navigation_modal').modal('hide');
        $('#changes_alert').show();
        event.preventDefault();
    });
});
//-->
</script>