<?php
	//echo "<h1>---------------------------------------------------------> JOSER </h1>";
	function convertToReadableSize2($size){
	  $base = log($size) / log(1024);
	  $suffix = array("", "KB", "MB", "GB", "TB");
	  $f_base = floor($base);
	  return round(pow(1024, $base - floor($base)), 1);
	}

	function getRemoteFileSize($url) {
	    $info = get_headers($url,1);

	    if (is_array($info['Content-Length'])) {
	        $info = end($info['Content-Length']);
	    }
	    else {
	        $info = $info['Content-Length'];
	    }

	    return $info;
	}
	//AQUI TENEMOS EL CODIGO PARA IMPORTAR LAS IMAGENES
	function importarImagenesDB($imagen, $post_id){
		//get image from external url and save to wordpress media library and set as featured image
 
		//$url = "https://admin.invupos.com/invuPos/images/banner/14830.jpg";
		$url = $imagen;

		//PRIMERO VALIDAREMOS QUE ESTA IMAGEN TENGA EL PESO IDEAL
		$tamano 		= getRemoteFileSize($url);
		$tamano_final 	= convertToReadableSize2($tamano);

		echo "el tamano es: $tamano_final <---";
		if($tamano_final < 300){
			//$post_id = 1;
			$att_id = aladdin_get_image_id($url);
			if($att_id){
				set_post_thumbnail( $post_id, $att_id );
			}else{
				// Need to require these files
				if ( !function_exists('media_handle_upload') ) {
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					require_once(ABSPATH . "wp-admin" . '/includes/file.php');
					require_once(ABSPATH . "wp-admin" . '/includes/media.php');
				}
				 
				$tmp = download_url( $url );
				if( is_wp_error( $tmp ) || $tmp == ""){
					// download failed, handle error

					$url = str_replace(" ", "-", $imagen);

					$tmp = download_url($url);

					if( is_wp_error( $tmp )  || $tmp == ""){
						$url = str_replace(" ", "%20", $imagen);

						$tmp = download_url($url);
					}
				}
				 
				$desc = get_the_title($post_id);
				$file_array = array();
				 
				// Set variables for storage
				// fix file filename for query strings
				preg_match('/[^?]+.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
				$file_array['name'] = basename($matches[0]);
				$file_array['tmp_name'] = $tmp;
				 
				// If error storing temporarily, unlink
				if ( is_wp_error( $tmp ) ) {
					@unlink($file_array['tmp_name']);
					$file_array['tmp_name'] = '';
				}
				 
				// do the validation and storage stuff
				$id = media_handle_sideload( $file_array, $post_id, $desc );
				 
				// If error storing permanently, unlink
				if ( is_wp_error($id) ) {
					@unlink($file_array['tmp_name']);
					return $id;
				}
				 
				set_post_thumbnail( $post_id, $id );
			 
			}
		}else{
			//$id_imagen_demo = 762;//ESTA ES LA IMAGEN DEMO
			//set_post_thumbnail( $post_id, $id_imagen_demo );
			delete_post_thumbnail($post_id);
		}
		
		
	}

	// retrieves the attachment ID from the file URL
	function aladdin_get_image_id($image_url) {
		global $wpdb;

		//echo "-----".$image_url."----";
		$image_url = str_replace(array(".jpg", ".jpeg", ".png") , "", $image_url);
		$image_url = str_replace(array("  ") , " ", $image_url);
		$image_url = str_replace(array(" ", "  ") , "-", $image_url);
		$image_url = str_replace(array("--", "---") , "", $image_url);

		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s%'", $image_url ));

		$imagen_id = "";

		$querys = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE guid='%".$image_url."%'");
		foreach ($querys as $key) {
			$imagen_id = $key->ID;
		}

		//return $attachment[0];
		return $imagen_id;
	}


	
?>