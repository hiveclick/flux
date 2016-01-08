<?php
header("Content-Type: image/png");
	/* @var $ajax_form BasicAjaxForm */
	$ajax_form = $this->getContext()->getRequest()->getAttribute('ajax_form');
	if (is_null($ajax_form)) {
		$ajax_form = new BasicAjaxForm();
	}
?>
<?php
	$record = $ajax_form->getRecord();
	if (method_exists($record, 'getImageData')) {
		if (trim($record->getImageData()) != '') {
			if (isset($_GET['show_thumbnail']) && $_GET['show_thumbnail'] == true) {
				// Generate a thumbnail preview
				if (base64_decode($record->getImageData()) != '') {
					$src_img = imagecreatefromstring(base64_decode($record->getImageData()));
					$src_background = imagecolorallocate($src_img, 255, 255, 255);
					imagecolortransparent($src_img, $src_background);

					$dest_img_width = isset($_GET['width']) ? $_GET['width'] : 128;
					$dest_img_height = isset($_GET['height']) ? $_GET['height'] : $dest_img_width;
					$dest_padding = 5;
					$dest_width = isset($_GET['width']) ? $_GET['width'] : 128;
					$dest_height = isset($_GET['height']) ? $_GET['height'] : $dest_width;
					$src_width = imagesx($src_img);
					$src_height = imagesy($src_img);
					$src_proportion = 1;
					if ($src_width > $src_height) {
						$dest_proportion =  $src_height / $src_width;
						$dest_height = $dest_img_height * $dest_proportion - $dest_padding;
						$dest_width -= $dest_padding;
					} else {
						$dest_proportion = $src_width / $src_height;
						$dest_width = $dest_img_width * $dest_proportion - $dest_padding;
						$dest_height -= $dest_padding;
					}


					$dest_x = ($dest_img_width / 2) - ($dest_width / 2);
					$dest_y = ($dest_img_height / 2) - ($dest_height / 2);

					$dest_img = imagecreatetruecolor($dest_img_width, $dest_img_height);
					$dest_background = imagecolorallocate($dest_img, 255, 255, 255);
					imagealphablending($dest_img, false);
					imagesavealpha($dest_img, true);
					if (function_exists('imageantialias')) {
						imageantialias($dest_img, true);
					}
					imagefilledrectangle($dest_img, 0, 0, $dest_img_width, $dest_img_height, $dest_background);

					imagecopyresampled($dest_img, $src_img, $dest_x, $dest_y, 0, 0, $dest_width, $dest_height, $src_width, $src_height);

					echo imagepng($dest_img);
					imagedestroy($dest_img);
				}
			} else {
				// If we specify the width or height, then resize the image
				if (isset($_GET['width']) || isset($_GET['height'])) {
					$src_img = imagecreatefromstring(base64_decode($record->getImageData()));
					$src_width = imagesx($src_img);
					$src_height = imagesy($src_img);
					if (isset($_GET['width'])) {
						$dest_img_width = isset($_GET['width']) ? $_GET['width'] : $src_width;
						$dest_proportion =  $dest_img_width / $src_width;
						$dest_img_height = $src_height * $dest_proportion;
					} else if (isset($_GET['height'])) {
						$dest_img_height = isset($_GET['height']) ? $_GET['height'] : $src_height;
						$dest_proportion =  $dest_img_height / $src_height;
						$dest_img_width = $src_width * $dest_proportion;
					}


					$dest_width = $dest_img_width;
					$dest_height = $dest_img_height;

					$dest_img = imagecreatetruecolor($dest_width, $dest_height);
					imagecolortransparent($dest_img, imagecolorallocatealpha($dest_img, 0, 0, 0, 127));
					imagealphablending($dest_img, false);
					imagesavealpha($dest_img, true);
					if (function_exists('imageantialias')) {
						imageantialias($dest_img, true);
					}
					imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
					echo imagepng($dest_img);
					imagedestroy($dest_img);
				} else {
					echo base64_decode($record->getImageData());
				}
			}
		} else {
			$dest_img_width = isset($_GET['width']) ? $_GET['width'] : 128;
			$dest_img_height = isset($_GET['height']) ? $_GET['height'] : $dest_img_width;
			$dest_img = imagecreatetruecolor($dest_img_width, $dest_img_height);
			imagealphablending($dest_img, true); // setting alpha blending on
			imagesavealpha($dest_img, true); // save alphablending setting (important)
			$trans_colour = imagecolorallocatealpha($dest_img, 255, 255, 255, 127);
			imagefill($dest_img, 0, 0, $trans_colour);
			$color = imagecolorallocatealpha($dest_img, 200, 200, 200, 0);
			imagerectangle($dest_img, 0, 0, $dest_img_width - 2, $dest_img_height - 2, $color);
			echo imagepng($dest_img);
			imagedestroy($dest_img);
		}
	} else {
		$dest_img_width = isset($_GET['width']) ? $_GET['width'] : 128;
		$dest_img_height = isset($_GET['height']) ? $_GET['height'] : $dest_img_width;
		$dest_img = imagecreatetruecolor($dest_img_width, $dest_img_height);
		imagealphablending($dest_img, true); // setting alpha blending on
		imagesavealpha($dest_img, true); // save alphablending setting (important)
		$trans_colour = imagecolorallocatealpha($dest_img, 255, 255, 255, 127);
		imagefill($dest_img, 0, 0, $trans_colour);
		$color = imagecolorallocatealpha($dest_img, 200, 200, 200, 0);
		imagerectangle($dest_img, 0, 0, $dest_img_width - 2, $dest_img_height - 2, $color);
		echo imagepng($dest_img);
		imagedestroy($dest_img);
	}
?>