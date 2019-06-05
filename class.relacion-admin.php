<?php
/*
	*
	* EN ESTE ARCHIVO TENDREMOS LA INSTALACION Y LA CREACION DE LAS TABLAS EN LA DB
	*
*/
	//echo "<h1>JOSER</h1>";
	//ESTO SE AGREGA SOLO CUANDO EL CLIENTE AGREGA POR PRIMERA VES EL PLUGIN, ASI PODEMOS CREAR LAS TABLAS ADECUADAMENTE
	register_activation_hook( __FILE__, 'jal_install_jj' );
	register_activation_hook( __FILE__, 'jal_install_data' );

	//FUNCTION PARA CREAR TABLA EN DB

	global $jal_db_version_jj;
	$jal_db_version_jj = '3.0.1';


	global $wpdb;

	//EN ESTE CASO VAMOS A NECESITAR LAS CATEGORIAS, LOS TERMINOS SEGUN CATEGORIA, LA RELACION ENTRE CATEGORIA Y TERMINOS

	$table_categorias = $wpdb->prefix.'rela_categoria';
	$table_terminos = $wpdb->prefix.'rela_terminos';
	$table_relacion = $wpdb->prefix.'rela_relacion';

	//TABLAS QUE SON PARA LAS CATEGORIAS
	$tabla_categorias 	= $wpdb->prefix.'mis_categorias';
	$tabla_productos 	= $wpdb->prefix.'mis_productos';

	$tabla_no_edit 		= $wpdb->prefix.'no_editar';
	$tabla_api 			= $wpdb->prefix.'mi_api';

	function jal_install_jj() {
		///echo "<h1>Pasa por el install</h1>";
		global $wpdb;
		global $jal_db_version_jj;

		//AQUI ESTAN LAS TABLAS DE LA DB
		global $table_categorias;
		global $table_terminos;
		global $table_relacion;

		//
		global $tabla_categorias;
		global $tabla_productos;

		global $tabla_no_edit;
		global $tabla_api;
		
		$charset_collate = $wpdb->get_charset_collate();

		//CREAMOS LA TABLA DE LA CATEGORIA
		$sql = "CREATE TABLE $table_categorias (
			id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			nombre varchar(100)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'jal_db_version_jj', $jal_db_version_jj );

		//CREAMOS LA TABLA DE LOS TERMINOS
		$sql2 = "CREATE TABLE $table_terminos (
			id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			nombre varchar(100)
		) $charset_collate;";
		dbDelta( $sql2 );

		//CREAMOS LA TABLA DONDE RELACIONAREMOS TODO
		$sql3 = "CREATE TABLE $table_relacion (
			id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			categoria varchar(100),
			termino varchar(100)
		) $charset_collate;";
		dbDelta( $sql3 );

		//EN ESTE MODULO AGREGAMOS LA TABLA DE RELACION DE CATEGORIAS Y PRODUCTOS
		$categoria = "CREATE TABLE $tabla_categorias (
			id 		mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			invu 	varchar(100),
			wp 		varchar(100),
			tipo 	varchar(100)
		) $charset_collate;";
		dbDelta( $categoria );

		//CREAMOS LA DE LOS PRODUCTOS
		$categoria = "CREATE TABLE $tabla_productos (
			id 		mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			invu 	varchar(100),
			wp 		varchar(100)
		) $charset_collate;";
		dbDelta( $categoria );

		//TABLA DONDE GUARDAREMOS LAS CATEGORIAS QUE NO MODIFICAREMOS
		//CREAMOS LA DE LOS PRODUCTOS
		$categoria = "CREATE TABLE $tabla_no_edit (
			id 		mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			invu 	varchar(100),
			wp 		varchar(100)
		) $charset_collate;";
		dbDelta( $categoria );

		//CREAMOS LA TABLA DE LA API
		$categoria = "CREATE TABLE $tabla_api (
			id 		mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			nombre 	varchar(100)
		) $charset_collate;";
		dbDelta( $categoria );


		update_option( "jal_db_version_jj", $jal_db_version_jj );
	}

	function jal_install_data() {
		global $wpdb;
		
		$welcome_name = 'Mr. WordPress';
		$welcome_text = 'Congratulations, you just completed the installation!';
		
		global $table_terminos;
		

		//ANTES DE HACER ESTO DEBERIAMOS CONSULTAR SI YA ESTAN CREADOS
		$activo = $wpdb->get_var("SELECT COUNT(*) FROM {$table_terminos} WHERE nombre = 'Activo' ");

		if($activo <= 0){
			$wpdb->insert( 
				$table_terminos, 
				array( 
					'nombre' => 'Activo'
				) 
			);
		}

		$activo = $wpdb->get_var("SELECT COUNT(*) FROM {$table_terminos} WHERE nombre = 'Desactivado' ");

		if($activo <= 0){
			$wpdb->insert( 
				$table_terminos, 
				array( 
					'nombre' => 'Desactivado'
				) 
			);
		}

		$activo = $wpdb->get_var("SELECT COUNT(*) FROM {$table_terminos} WHERE nombre = 'Eliminado' ");

		if($activo <= 0){
			$wpdb->insert( 
				$table_terminos, 
				array( 
					'nombre' => 'Eliminado'
				) 
			);
		}
	}

function myplugin_update_db_check() {
    global $jal_db_version_jj;
    if ( get_site_option( 'jal_db_version_jj' ) != $jal_db_version_jj ) {
        jal_install_jj();
    }
}
add_action( 'plugins_loaded', 'myplugin_update_db_check' );
	
?>