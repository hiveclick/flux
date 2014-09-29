<?php
    /* @var $offer_asset Flux\OfferAsset */
    $offer_asset = $this->getContext()->getRequest()->getAttribute("offer_asset", array());
?>
<?php if ($offer_asset->getId() == 0) { ?>
    <div id="header">
        <h2><a href="/offer/offer?_id=<?php echo $offer_asset->getOfferId() ?>"><?php echo $offer_asset->getOffer()->getName() ?></a> <small>New Asset</small></h2>
    </div>
    <div class="help-block">Use this form to add a new asset to this offer</div>
    <br/>
    <form class="form-horizontal" name="offer_asset_form" method="POST" action="" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="offer_id" value="<?php echo $offer_asset->getOfferId() ?>" />
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
            <div class="col-sm-10">
                <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $offer_asset->getName() ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="description">Description</label>
            <div class="col-sm-10">
                <textarea id="description" name="description" class="form-control" required placeholder="Enter Description..."><?php echo $offer_asset->getDescription() ?></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="asset_type">Asset Type</label>
            <div class="col-sm-10">
                <select id="asset_type" name="asset_type">
                    <option value="1" data-data="<?php echo htmlentities(json_encode(array('asset_type' => 1, 'name' => 'Banner', 'description' => 'Banners are used for impressions and can have different sizes', 'img' => '/images/asset_banner.png'))) ?>">Banner</option>
                    <option value="2" data-data="<?php echo htmlentities(json_encode(array('asset_type' => 2, 'name' => 'Wall Offer', 'description' => 'An offer wall displays multiple offers with smaller images', 'img' => '/images/asset_wall.png'))) ?>">Wall Offer</option>
                    <option value="3" data-data="<?php echo htmlentities(json_encode(array('asset_type' => 3, 'name' => 'Path Offer', 'description' => 'A path will display a single offer with a large image', 'img' => '/images/asset_path.png'))) ?>">Path Offer</option>
                    <option value="4" data-data="<?php echo htmlentities(json_encode(array('asset_type' => 4, 'name' => 'Email Creative (HTML)', 'description' => 'An email creative using HTML code', 'img' => '/images/asset_email_html.png'))) ?>">Email Creative (HTML)</option>
                    <option value="5" data-data="<?php echo htmlentities(json_encode(array('asset_type' => 5, 'name' => 'Email Creative (Text)', 'description' => 'An email creative using text links', 'img' => '/images/asset_email_txt.png'))) ?>">Email Creative (Text)</option>
                </select>
            </div>
        </div>
        
        <hr />
        
        <div id="banner-options" style="display:block;">
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="banner_size">Banner Size</label>
                <div class="col-sm-10">
                    <select id="banner_size" name="asset_type" class="form-control">
                        <option value="1">728x90 - Leaderboard</option>
                        <option value="2">336x280 - Large Rectangle</option>
                        <option value="3">300x250 - Medium Rectangle</option>
                        <option value="4">240x400 - Vertical Rectangle</option>
                        <option value="5">180x150 - Rectangle</option>
                        <option value="6">300x100 - 3:1 Rectangle</option>
                        <option value="7">468x60 - Full Banner</option>
                        <option value="8">234x60 - Half Banner</option>
                        <option value="9">120x240 - Vertical Banner</option>
                        <option value="10">160x600 - Wide Skyscraper</option>
                        <option value="11">120x600 - Skyscraper</option>
                        <option value="12">720x300 - Pop-under</option>
                        <option value="13">125x125 - Square Button</option>
                        <option value="14">120x90 - Button 1</option>
                        <option value="15">120x60 - Button 2</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Banner</label>
                <div class="col-sm-10">
                   <input type="file" name="image_data" class="form-control" />
                </div>
            </div>
        </div>
        
        <div id="wall-options" style="display:none;">
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ad_title" value="" placeholder="Ad Title..." />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_description">Description</label>
                <div class="col-sm-10">
                    <textarea name="ad_description" class="form-control" placeholder="Ad description..."></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_link">Link</label>
                <div class="col-sm-10">
                    <textarea name="ad_link" class="form-control" placeholder="Enter pop-out link..."></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Image (125x125)</label>
                <div class="col-sm-10">
                   <input type="file" class="form-control" name="image_data" value="" />
                </div>
            </div>
        </div>
        
        <div id="path-options" style="display:none;">
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ad_title" value="" placeholder="Ad Title..." />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_description">Description</label>
                <div class="col-sm-10">
                    <textarea name="ad_description" class="form-control" placeholder="Ad description..."></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_link">Link</label>
                <div class="col-sm-10">
                    <textarea name="ad_link" class="form-control" placeholder="Enter pop-out link..."></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Image (336x280)</label>
                <div class="col-sm-10">
                   <input type="file" class="form-control" name="image_data" value="" />
                </div>
            </div>
        </div>
        
        <div id="html-options" style="display:none;">
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ad_title" value="" placeholder="HTML Creative Name..." />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="html_source">Source</label>
                <div class="col-sm-10">
                    <textarea name="html_source" class="form-control" placeholder="Enter HTML Source..."></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Preview (125x125)</label>
                <div class="col-sm-10">
                   <input type="file" class="form-control" name="image_data" value="" />
                </div>
            </div>
        </div>
        
        <div id="text-options" style="display:none;">
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ad_title" value="" placeholder="Text Creative Name..." />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="text_source">Source</label>
                <div class="col-sm-10">
                    <textarea name="text_source" class="form-control" placeholder="Enter Text Source..."></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Preview (125x125)</label>
                <div class="col-sm-10">
                   <input type="file" class="form-control" name="image_data" value="" />
                </div>
            </div>
        </div>
    
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" name="__save" class="btn btn-success" value="Save" />
            </div>
        </div>
    
    </form>
    <script>
    //<!--
    $(document).ready(function() {
    	CKEDITOR.replace('html_source', {
    		startupMode: 'source',
    		allowedContent: true,
    		height: 450
    	});
    
    	CKEDITOR.replace('text_source', {
    		startupMode: 'source',
    		allowedContent: true,
    		height: 450
    	});
    	
        $('#banner_size').selectize();
    	
        $('#asset_type').selectize({
        	valueField: 'asset_type',
            labelField: 'name',
            searchField: ['name', 'description'],
        	render: {
                item: function(item, escape) {
                    return '<div>' +
                    '<div class="media">' +
                    '<span class="pull-left"><img src="' + escape(item.img ? item.img : '') + '" border="0" alt="' + escape(item.name ? item.name : 'No Name') + '" /></span>' +
                    '<div class="media-body">' +
                    '<span class="title">' + 
                    '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                    '</span>' +
                    '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                },
                option: function(item, escape) {
                	return '<div>' +
                	'<div class="media">' +
                	'<span class="pull-left"><img src="' + escape(item.img ? item.img : '') + '" border="0" alt="' + escape(item.name ? item.name : 'No Name') + '" /></span>' +
                	'<div class="media-body">' +
                    '<span class="title">' + 
                    '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                    '</span>' +
                    '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                    '</div>' + 
                    '</div>' + 
                    '</div>';
                }
            },
            onChange: function(value) {
            	$('#banner-options,#wall-options,#path-options,#html-options,#text-options').hide();
                if (value == 1) {
                    $('#banner-options').show();
                } else if (value == 2) {
                	$('#wall-options').show();
                } else if (value == 3) {
                	$('#path-options').show();
                } else if (value == 4) {
                	$('#html-options').show();
                } else if (value == 5) {
                	$('#text-options').show();
                }
            }
        });
    });
    //-->
    </script>
<?php } else { ?>
    <div id="header">
        <h2><a href="/offer/offer?_id=<?php echo $offer_asset->getOfferId() ?>"><?php echo $offer_asset->getOffer()->getName() ?></a> <small><?php echo $offer_asset->getName() ?></small></h2>
    </div>
    <div class="help-block">Use this form to update an existing asset on this offer</div>
    <br/>
    <form class="form-horizontal" id="offer_asset_form" name="offer_asset_form" method="POST" action="/offer/offer-asset-wizard" enctype="multipart/form-data">
        <input type="hidden" name="func" value="/offer/offer-asset" />
        <input type="hidden" name="_id" value="<?php echo $offer_asset->getId() ?>" />
        <input type="hidden" name="offer_id" value="<?php echo $offer_asset->getOfferId() ?>" />
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="name">Name</label>
            <div class="col-sm-10">
                <input type="text" id="name" name="name" class="form-control" required placeholder="Name" value="<?php echo $offer_asset->getName() ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="description">Description</label>
            <div class="col-sm-10">
                <textarea id="description" name="description" class="form-control" required placeholder="Enter Description..."><?php echo $offer_asset->getDescription() ?></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-2 control-label hidden-xs" for="asset_type">Asset Type</label>
            <div class="col-sm-10">
                <select id="asset_type" name="asset_type">
                    <option value="1" <?php echo $offer_asset->getAssetType() == 1 ? "SELECTED" : "" ?> data-data="<?php echo htmlentities(json_encode(array('asset_type' => 1, 'name' => 'Banner', 'description' => 'Banners are used for impressions and can have different sizes', 'img' => '/images/asset_banner.png'))) ?>">Banner</option>
                    <option value="2" <?php echo $offer_asset->getAssetType() == 2 ? "SELECTED" : "" ?> data-data="<?php echo htmlentities(json_encode(array('asset_type' => 2, 'name' => 'Wall Offer', 'description' => 'An offer wall displays multiple offers with smaller images', 'img' => '/images/asset_wall.png'))) ?>">Wall Offer</option>
                    <option value="3" <?php echo $offer_asset->getAssetType() == 3 ? "SELECTED" : "" ?> data-data="<?php echo htmlentities(json_encode(array('asset_type' => 3, 'name' => 'Path Offer', 'description' => 'A path will display a single offer with a large image', 'img' => '/images/asset_path.png'))) ?>">Path Offer</option>
                    <option value="4" <?php echo $offer_asset->getAssetType() == 4 ? "SELECTED" : "" ?> data-data="<?php echo htmlentities(json_encode(array('asset_type' => 4, 'name' => 'Email Creative (HTML)', 'description' => 'An email creative using HTML code', 'img' => '/images/asset_email_html.png'))) ?>">Email Creative (HTML)</option>
                    <option value="5" <?php echo $offer_asset->getAssetType() == 5 ? "SELECTED" : "" ?> data-data="<?php echo htmlentities(json_encode(array('asset_type' => 5, 'name' => 'Email Creative (Text)', 'description' => 'An email creative using text links', 'img' => '/images/asset_email_txt.png'))) ?>">Email Creative (Text)</option>
                </select>
            </div>
        </div>
        
        <hr />
        <div id="preview"></div>
        <div id="banner-options" style="display:<?php echo $offer_asset->getAssetType() == 1 ? 'block' : 'none' ?>;">
            <div class="form-group asset_preview asset_preview_banner text-center">
                <div class="asset_preview_img">
                    <a id="offer_asset_banner_link" href="<?php echo $offer_asset->getAdLink() ?>">
                        <img id="offer_asset_banner_preview" src="data:image/png;base64,<?php echo $offer_asset->getImageData() ?>" border="0" <?php echo ($offer_asset->getBannerWidth() > 0) ? 'width="' . $offer_asset->getBannerWidth() . '"' : '' ?> <?php echo ($offer_asset->getBannerHeight() > 0) ? 'height="' . $offer_asset->getBannerHeight() . '"' : '' ?> />
                    </a>
                </div>
            </div>
            <br />
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="banner_size">Banner Size</label>
                <div class="col-sm-10">
                    <select id="banner_size" name="banner_size" class="form-control" <?php echo $offer_asset->getAssetType() != 1 ? 'disabled' : '' ?>>
                        <optgroup label="Custom Size">
                            <option value="0" <?php echo $offer_asset->getBannerSize() == 0 ? 'selected' : '' ?>>Custom Size</option>
                        </optgroup>
                        <optgroup label="Standard Size">
                            <option value="1" <?php echo $offer_asset->getBannerSize() == 1 ? 'selected' : '' ?>>728x90 - Leaderboard</option>
                            <option value="2" <?php echo $offer_asset->getBannerSize() == 2 ? 'selected' : '' ?>>336x280 - Large Rectangle</option>
                            <option value="3" <?php echo $offer_asset->getBannerSize() == 3 ? 'selected' : '' ?>>300x250 - Medium Rectangle</option>
                            <option value="4" <?php echo $offer_asset->getBannerSize() == 4 ? 'selected' : '' ?>>240x400 - Vertical Rectangle</option>
                            <option value="5" <?php echo $offer_asset->getBannerSize() == 5 ? 'selected' : '' ?>>180x150 - Rectangle</option>
                            <option value="6" <?php echo $offer_asset->getBannerSize() == 6 ? 'selected' : '' ?>>300x100 - 3:1 Rectangle</option>
                            <option value="7" <?php echo $offer_asset->getBannerSize() == 7 ? 'selected' : '' ?>>468x60 - Full Banner</option>
                            <option value="8" <?php echo $offer_asset->getBannerSize() == 8 ? 'selected' : '' ?>>234x60 - Half Banner</option>
                            <option value="9" <?php echo $offer_asset->getBannerSize() == 9 ? 'selected' : '' ?>>120x240 - Vertical Banner</option>
                            <option value="10" <?php echo $offer_asset->getBannerSize() == 10 ? 'selected' : '' ?>>160x600 - Wide Skyscraper</option>
                            <option value="11" <?php echo $offer_asset->getBannerSize() == 11 ? 'selected' : '' ?>>120x600 - Skyscraper</option>
                            <option value="12" <?php echo $offer_asset->getBannerSize() == 12 ? 'selected' : '' ?>>720x300 - Pop-under</option>
                            <option value="13" <?php echo $offer_asset->getBannerSize() == 13 ? 'selected' : '' ?>>125x125 - Square Button</option>
                            <option value="14" <?php echo $offer_asset->getBannerSize() == 14 ? 'selected' : '' ?>>120x90 - Button 1</option>
                            <option value="15" <?php echo $offer_asset->getBannerSize() == 15 ? 'selected' : '' ?>>120x60 - Button 2</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_link">Link</label>
                <div class="col-sm-10">
                    <textarea name="ad_link" class="form-control" placeholder="Enter pop-out link..." <?php echo $offer_asset->getAssetType() != 1 ? 'disabled' : '' ?>><?php echo $offer_asset->getAdLink() ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Banner</label>
                <div class="col-sm-10">
                   <input type="file" id="banner_image_data" name="image_data" class="" <?php echo $offer_asset->getAssetType() != 1 ? 'disabled' : '' ?> />
                </div>
            </div>
        </div>
        
        <div id="wall-options" style="display:<?php echo $offer_asset->getAssetType() == 2 ? 'block' : 'none' ?>;">
            <div class="asset_preview asset_preview_wall text-center">
                <div class="asset_preview_img thumbnail">
                    <a id="offer_asset_wall_link" href="<?php echo $offer_asset->getAdLink() ?>">
                        <img id="offer_asset_wall_preview" src="data:image/png;base64,<?php echo $offer_asset->getImageData() ?>" border="0" />
                        <div id="offer_asset_wall_title" class="asset_preview_title"><?php echo $offer_asset->getAdTitle() ?></div>
                    </a>
                    <small id="offer_asset_wall_description" class="asset_preview_description"><?php echo $offer_asset->getAdDescription() ?></small>
                </div>
            </div>
        
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ad_title_wall" name="ad_title" value="<?php echo $offer_asset->getAdTitle() ?>" placeholder="Ad Title..." <?php echo $offer_asset->getAssetType() != 2 ? 'disabled' : '' ?> />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_description">Description</label>
                <div class="col-sm-10">
                    <textarea name="ad_description" class="form-control" id="ad_description_wall" placeholder="Ad description..." <?php echo $offer_asset->getAssetType() != 2 ? 'disabled' : '' ?>><?php echo $offer_asset->getAdDescription() ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_link">Link</label>
                <div class="col-sm-10">
                    <textarea name="ad_link" class="form-control" id="ad_link_wall" placeholder="Enter pop-out link..." <?php echo $offer_asset->getAssetType() != 2 ? 'disabled' : '' ?>><?php echo $offer_asset->getAdLink() ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Image (125x125)</label>
                <div class="col-sm-10">
                   <input type="file" id="image_data_wall" name="image_data" <?php echo $offer_asset->getAssetType() != 2 ? 'disabled' : '' ?> />
                </div>
            </div>
        </div>
        
        <div id="path-options" style="display:<?php echo $offer_asset->getAssetType() == 3 ? 'block' : 'none' ?>;">
            <div class="asset_preview asset_preview_path text-center">
                <div class="asset_preview_img thumbnail">
                    <a id="offer_asset_path_link" href="<?php echo $offer_asset->getAdLink() ?>">
                        <img id="offer_asset_path_preview" src="data:image/png;base64,<?php echo $offer_asset->getImageData() ?>" border="0" width="400" />
                        <div id="offer_asset_path_title" class="asset_preview_title"><?php echo $offer_asset->getAdTitle() ?></div>
                    </a>
                    <small id="offer_asset_path_description" class="asset_preview_description"><?php echo $offer_asset->getAdDescription() ?></small>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ad_title_path" name="ad_title" value="<?php echo $offer_asset->getAdTitle() ?>" placeholder="Ad Title..." <?php echo $offer_asset->getAssetType() != 3 ? 'disabled' : '' ?> />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_description">Description</label>
                <div class="col-sm-10">
                    <textarea name="ad_description" class="form-control" id="ad_description_path" placeholder="Ad description..." <?php echo $offer_asset->getAssetType() != 3 ? 'disabled' : '' ?>><?php echo $offer_asset->getAdDescription() ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_link">Link</label>
                <div class="col-sm-10">
                    <textarea name="ad_link" class="form-control" id="ad_link_path" placeholder="Enter pop-out link..." <?php echo $offer_asset->getAssetType() != 3 ? 'disabled' : '' ?>><?php echo $offer_asset->getAdLink() ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Image (336x280)</label>
                <div class="col-sm-10">
                   <input type="file" class="form-control" id="image_data_path" name="image_data" <?php echo $offer_asset->getAssetType() != 3 ? 'disabled' : '' ?> />
                </div>
            </div>
        </div>
        
        <div id="html-options" style="display:<?php echo $offer_asset->getAssetType() == 4 ? 'block' : 'none' ?>;">
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ad_title" value="<?php echo $offer_asset->getAdTitle() ?>" placeholder="HTML Creative Name..." <?php echo $offer_asset->getAssetType() != 4 ? 'disabled' : '' ?> />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="html_source">Source</label>
                <div class="col-sm-10">
                    <textarea name="html_source" class="form-control" placeholder="Enter HTML Source..." <?php echo $offer_asset->getAssetType() != 4 ? 'disabled' : '' ?>><?php echo $offer_asset->getHtmlSource() ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Preview (125x125)</label>
                <div class="col-sm-10">
                   <input type="file" class="form-control" name="image_data" <?php echo $offer_asset->getAssetType() != 4 ? 'disabled' : '' ?> />
                </div>
            </div>
        </div>
        
        <div id="text-options" style="display:<?php echo $offer_asset->getAssetType() == 5 ? 'block' : 'none' ?>;">
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="ad_title">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ad_title" value="<?php echo $offer_asset->getAdTitle() ?>" placeholder="Text Creative Name..." <?php echo $offer_asset->getAssetType() != 5 ? 'disabled' : '' ?> />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="text_source">Source</label>
                <div class="col-sm-10">
                    <textarea name="text_source" class="form-control" placeholder="Enter Text Source..." <?php echo $offer_asset->getAssetType() != 5 ? 'disabled' : '' ?>><?php echo $offer_asset->getTextSource() ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label hidden-xs" for="image_data">Upload Preview (125x125)</label>
                <div class="col-sm-10">
                   <input type="file" class="form-control" name="image_data" <?php echo $offer_asset->getAssetType() != 5 ? 'disabled' : '' ?> />
                </div>
            </div>
        </div>
    
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" name="__save" class="btn btn-success" value="Save" />
                <!-- 
                <a href="/offer/offer-asset-preview-modal?_id=<?php echo $offer_asset->getId() ?>" type="button" name="__preview" class="btn btn-info" data-toggle="modal" data-target="#offer_asset_preview_modal">Preview Asset</a>
                -->
                <input type="button" id="btn_delete" name="__delete" class="btn btn-danger" value="Delete Asset" />
            </div>
        </div>
    
    </form>
    
    <!-- Push offer to server modal -->
<div class="modal fade" id="offer_asset_preview_modal">
    <div class="modal-dialog">
        <div class="modal-content"></div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
//<!--
$(document).ready(function() {
    $('#offer_asset_form').form(function(data) {
        if (data.record) {
            $.rad.notify('Asset Updated', 'This asset has been updated in the system');
        }
    },{keep_form:1});
    
    $('#btn_delete').click(function() {
        if (confirm('Are you sure you want to remove this asset from this offer?')) {
            $.rad.del('/api', { func: '/offer/offer-asset/<?php echo $offer_asset->getId() ?>', '_id': '<?php echo $offer_asset->getId() ?>' }, function(data) {
                $.rad.notify('Asset Deleted', 'This asset has been removed from the system.');
                location.replace('/offer/offer?_id=<?php echo $offer_asset->getOfferId() ?>#tab-assets');
            });
        }
    });
    
	CKEDITOR.replace('html_source', {
		startupMode: 'source',
		allowedContent: true,
		height: 450
	});

	CKEDITOR.replace('text_source', {
		startupMode: 'source',
		allowedContent: true,
		height: 450
	});

	/**
	 * Banner update functions
	 */
	$('#banner_image_data').change(function(evt) {
		var files = evt.target.files; // FileList object

	    // Loop through the FileList and render image files as thumbnails.
	    for (var i = 0, f; f = files[i]; i++) {
	      // Only process image files.
	      if (!f.type.match('image.*')) {
	        continue;
	      }
	      var reader = new FileReader();
	      // Closure to capture the file information.
	      reader.onload = (function(theFile) {
	        return function(e) {
	          // Render thumbnail.
              $('#offer_asset_banner_preview').attr('src', e.target.result);
	        };
	      })(f);
	      // Read in the image file as a data URL.
	      reader.readAsDataURL(f);
	    }
	});
	$('#ad_link_banner').keyup(function() {
	    $('#offer_asset_banner_link').attr('href', $(this).val());
	});
	$('#banner_size').selectize().change(function() {
        var preview_size = { width: 0, height: 0 };
        switch (parseInt($('#banner_size').val())) {
            case 1:
            	preview_size.width = 728; preview_size.height = 90;
            	break;
            case 2:
            	preview_size.width = 336; preview_size.height = 280;
            	break;
            case 3:
            	preview_size.width = 300; preview_size.height = 250;
            	break;
            case 4:
            	preview_size.width = 240; preview_size.height = 400;
            	break;
            case 5:
            	preview_size.width = 180; preview_size.height = 150;
            	break;
            case 6:
            	preview_size.width = 300; preview_size.height = 100;
            	break;
            case 7:
            	preview_size.width = 468; preview_size.height = 60;
            	break;
            case 8:
            	preview_size.width = 234; preview_size.height = 60;
            	break;
            case 9:
            	preview_size.width = 120; preview_size.height = 240;
            	break;
            case 10:
            	preview_size.width = 160; preview_size.height = 600;
            	break;
            case 11:
            	preview_size.width = 120; preview_size.height = 600;
            	break;
            case 12:
            	preview_size.width = 720; preview_size.height = 300;
            	break;
            case 13:
            	preview_size.width = 125; preview_size.height = 125;
            	break;
            case 14:
            	preview_size.width = 120; preview_size.height = 90;
            	break;
            case 15:
            	preview_size.width = 120; preview_size.height = 60;
            	break;
        }
        if (preview_size.width > 0 && preview_size.height > 0) {
            $('#offer_asset_banner_preview').attr('width', preview_size.width);
            $('#offer_asset_banner_preview').attr('height', preview_size.height);
        } else {
        	$('#offer_asset_banner_preview').removeAttr('width');
            $('#offer_asset_banner_preview').removeAttr('height');
        }
    });

	/**
	 * Wall update functions
	 */
	$('#ad_title_wall').keyup(function() {
		 $('#offer_asset_wall_title').html($(this).val());
	});
	$('#ad_description_wall').keyup(function() {
		 $('#offer_asset_wall_description').html($(this).val());
	});
	$('#ad_link_wall').keyup(function() {
	    $('#offer_asset_wall_link').attr('href', $(this).val());
	});
	$('#image_data_wall').change(function(evt) {
		var files = evt.target.files; // FileList object

	    // Loop through the FileList and render image files as thumbnails.
	    for (var i = 0, f; f = files[i]; i++) {
	      // Only process image files.
	      if (!f.type.match('image.*')) {
	        continue;
	      }
	      var reader = new FileReader();
	      // Closure to capture the file information.
	      reader.onload = (function(theFile) {
	        return function(e) {
	          // Render thumbnail.
              $('#offer_asset_wall_preview').attr('src', e.target.result);
	        };
	      })(f);
	      // Read in the image file as a data URL.
	      reader.readAsDataURL(f);
	    }
	});

	/**
	 * Path update functions
	 */
	$('#ad_title_path').keyup(function() {
		 $('#offer_asset_path_title').html($(this).val());
	});
	$('#ad_description_path').keyup(function() {
		 $('#offer_asset_path_description').html($(this).val());
	});
	$('#ad_link_path').keyup(function() {
	    $('#offer_asset_path_link').attr('href', $(this).val());
	});
	$('#image_data_path').change(function(evt) {
		var files = evt.target.files; // FileList object

	    // Loop through the FileList and render image files as thumbnails.
	    for (var i = 0, f; f = files[i]; i++) {
	      // Only process image files.
	      if (!f.type.match('image.*')) {
	        continue;
	      }
	      var reader = new FileReader();
	      // Closure to capture the file information.
	      reader.onload = (function(theFile) {
	        return function(e) {
	          // Render thumbnail.
              $('#offer_asset_path_preview').attr('src', e.target.result);
	        };
	      })(f);
	      // Read in the image file as a data URL.
	      reader.readAsDataURL(f);
	    }
	});
	
    $('#asset_type').selectize({
    	valueField: 'asset_type',
        labelField: 'name',
        searchField: ['name', 'description'],
    	render: {
            item: function(item, escape) {
                return '<div>' +
                '<div class="media">' +
                '<span class="pull-left"><img src="' + escape(item.img ? item.img : '') + '" border="0" alt="' + escape(item.name ? item.name : 'No Name') + '" /></span>' +
                '<div class="media-body">' +
                '<span class="title">' + 
                '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                '</span>' +
                '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                '</div>' +
                '</div>' +
                '</div>';
            },
            option: function(item, escape) {
            	return '<div>' +
            	'<div class="media">' +
            	'<span class="pull-left"><img src="' + escape(item.img ? item.img : '') + '" border="0" alt="' + escape(item.name ? item.name : 'No Name') + '" /></span>' +
            	'<div class="media-body">' +
                '<span class="title">' + 
                '<span class="name">' + escape(item.name ? item.name : 'No Name') + '</span>' + 
                '</span>' +
                '<span class="description">' + escape(item.description ? item.description : 'no description') + '</span>' +
                '</div>' + 
                '</div>' + 
                '</div>';
            }
        },
        onChange: function(value) {
        	$('#banner-options,#wall-options,#path-options,#html-options,#text-options').hide();
        	$('#banner-options,#wall-options,#path-options,#html-options,#text-options :input').attr('disabled', 'disabled');
            if (value == 1) {
            	$('#banner-options :input').removeAttr('disabled');
                $('#banner-options').show();
            } else if (value == 2) {
            	$('#wall-options :input').removeAttr('disabled');
            	$('#wall-options').show();
            } else if (value == 3) {
            	$('#path-options :input').removeAttr('disabled');
            	$('#path-options').show();
            } else if (value == 4) {
            	$('#html-options :input').removeAttr('disabled');
            	$('#html-options').show();
            } else if (value == 5) {
            	$('#text-options :input').removeAttr('disabled');
            	$('#text-options').show();
            }
        }
    });
});
//-->
</script>
<?php } ?>