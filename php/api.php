<?php
//API KEY
function mi_api(){
	global $wpdb;
	$mi_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 1 ");
	$api_key = "";
	foreach ($mi_api as $key) {
		$api_key = $key->nombre;
	}

	return $api_key;
}

function url_api(){
	global $wpdb;
	$mi_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 2 ");
	$api_key = "";
	foreach ($mi_api as $key) {
		$api_key = $key->nombre;
	}

	return $api_key;
}

?>