<?php

$goods_photo = json_decode(!empty($goods_vendors[$vendor_id]) ? $goods_vendors[$vendor_id] : '{}', true);

$photo_count = 0;

foreach ($goods_photo as $photo_value) {
				
	if ($photo_value != 'no_image.png') {
		
		$photo_count++;

	}

}

$photo = array();

if (count($ware->images->src) > 0) {
//if ($photo) {

	if ($photo_count != count($ware->images->src)) {

		$i = 0;

		foreach ($ware->images->src as $picture) {

			$filename = basename($picture);

			$filename_time = time() .'_' . $n . '_' . $i;
			$filename_new = $filename_time . '.' . substr(strrchr($filename, '.'), 1);

			$uploaddir = __DIR__ . '/../../../images/goods/';

			$uploaddir_filename = $uploaddir.$filename_new;

			if (!file_exists($uploaddir_filename)) {

				if (filter_var($picture, FILTER_VALIDATE_URL) !== false) {
								
					$headers = get_headers($picture, 1);
								
					if (stripos($headers[0], "200 OK")) {
									
						if (strpos($headers["Content-Type"], 'image') !== false) {

							if (copy($picture, $uploaddir_filename)) {

								if (file_exists($uploaddir_filename)) {

									$image = new SimpleImage();
									$image->load($uploaddir_filename);

									if ($image->getWidth() >= $image->getHeight()) {

										if ($image->getWidth() > 1024) {

											$image->resizeToWidth(1024);
											$image->save($uploaddir_filename);

										}

									} else {

										if ($image->getHeight() > 1024) {

											$image->resizeToHeight(1024);
											$image->save($uploaddir_filename);

										}

									}

									$uploaddir_filename_thumb = __DIR__ . '/../../../images/goods_thumb/'.$filename_new;

									$image_thumb = new SimpleImage();
									$image_thumb->load($uploaddir_filename);

									if ($image_thumb->getWidth() >= $image_thumb->getHeight()) {

										if ($image_thumb->getWidth() > 256) {

											$image_thumb->resizeToWidth(256);
											$image_thumb->save($uploaddir_filename_thumb);

										} else {

											copy($uploaddir_filename, $uploaddir_filename_thumb);

										}

									} else {

										if ($image_thumb->getHeight() > 256) {

											$image_thumb->resizeToHeight(256);
											$image_thumb->save($uploaddir_filename_thumb);

										} else {

											copy($uploaddir_filename, $uploaddir_filename_thumb);

										}

									}

								}

								$photo['img'.$i] = $filename_new;

							}

						}

					}

				}

			} else {

				if (!getimagesize($uploaddir_filename)) {

					if (file_exists($uploaddir_filename)) unlink($uploaddir_filename);

				}

			}

			$i++;

		}
					
	}

} else {

	$photo['img0'] = 'no_image.png';

}

$photo_str = json_encode($photo);

?>