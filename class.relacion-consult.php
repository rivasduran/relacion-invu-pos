<?php
global $wpdb;

include("php/functions.php");
/*
	*
	* EN ESTE ARCHIVO TENDREMOS LAS CONSULTAS A LA DB
	*
*/
	function ListarItems(){//ESTA DEVUELVE LOS PRODUCTOS
		global $wpdb;
		
		$mi_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api ");
		$api_key = "";
		foreach ($mi_api as $key) {
			$api_key = $key->nombre;
		}

		?>
			<div class='api_key' data-api="<?php echo $api_key ?>"></div>
			<script type="text/javascript" >
				var Mi_api = {
					'api_key': jQuery(".api_key").attr("data-api")
				}

				var productos = ""; 
				var categorias = "";
				var categoriasDos = "";
				var relacionados = "";

				var valorEnviar = {
					"APIKEY": Mi_api.api_key
				}
				
				//LLAMAMOS TODOS LOS PRODUCTOS
				jQuery.ajax({
					data: valorEnviar,
					headers: { 
	        			APIKEY : Mi_api.api_key
	        		},
					type: "GET",
					//url: "https://api.invupos.com/invuApiPos/index.php?r=menu/ListarItems",
					//url: "https://api.invupos.com/invuApiPos/index.php?r=menu/ListarItems/checkStock/true",
					//url: "https://api.invupos.com/invuApiPos/index.php?r=menu/listarItems/online/1",
					url: "https://api.invupos.com/invuApiPos/index.php?r=menu/listarItems/online/1/checkStock/true",
					beforeSend: function(){

					},
					success: function(resultado){
						//console.log(resultado);
						var productos = resultado;
						//var productos = enviarInformacion(resultado, "productos");

						//ENVIAMOS LOS PRODUCTOS A LAS CATEGORIAS PARA PODER RELACIONAR
						listarCategorias(productos)
					}
				});

				//DEBEMOS RELACIONAR TODO LO QUE TENEMOS EN LO QUE REALMENTE NECESITAMOS Y ELIMINAR LO QUE NO NECESITAMOS
				//productos.map();

				var arreglos = ["joser", "Miguel", "Javier"];

				arreglos.map(numero => {
					//console.log(numero);
				});

				//console.log("ejejejjee");

			</script>
		<?php
	}


	//AQUI SE LLEGA CUANDO LE DAMOS CLICK AL BOTON DE RALACIONAR
	if(isset($_POST['relacionar'])){
		//ESTO ES SI EL USUARIO LE DIO CLICK A RELACIONAR

		echo "<h1> ---> ".ListarItems()."</h1>";
		//add_action( 'admin_footer', 'ListarItems' );
	}

	//YA CUANDO TRAEMOS LOS RESULTAMOS ACOMODADOS PROCEDEMOS A REALIZAR LAS CONSULTAS PERTINENTES Y A INSERTAR
	if(isset($_POST['insertarDB'])){
		global $wpdb;

		//FORMATO DE LA FECHA Y HORA PARA AGREGAR A WORDPRESS
		$fechaHora = date("Y-m-d ")." ".strftime("%H:%M:%S");

		$datos = $_POST['insertarDB'];

		$datos = json_encode($datos);
		$datos = json_decode($datos);

		//echo "<h1>llega todo esto ".count($datos)."</h1>";

		//AQUI RECORREMOS EL ARREGLO CON TODOS LOS DATOS QUE TENEMOS
		$cuantos = 0;
		$importar = "";

		for ($i=0; $i < count($datos); $i++) { //RECORREREMOS TODO EL ARREGLO PARA PODER IMPORTAR LOS PRODUCTOS

			//echo $datos[$i]->imagen."<br>";
			$imagen = "";
			if($datos[$i]->imagen != ""){
				//echo "<img src='https://admin.invupos.com/invuPos/images/banner/".$datos->data[$i]->imagen."' />";
				$imagen = "https://admin.invupos.com/invuPos/images/banner/".$datos[$i]->imagen;
			}

			//----->
			$nombre = strtolower($datos[$i]->nombre);
			$nombre = str_replace(" ", "-", $nombre);

			//echo "-- SELECT * FROM {$wpdb->prefix}posts WHERE post_name = '{$nombre}' -- ";
			echo $wpdb->prefix;
			/*
			$existe = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_name = '".$nombre."}' ");
			if(count($existe) <= 0){
				echo "<h1>{$nombre} No existe</h1>";
			}
			*/


			//echo "<h1>".sanear_string($datos->data[$i]->nombre)."</h1>";

			$cuantos++;
		}

		#sanear_string
		
		/*
		nombre
		imagen
		catPadre
		catHija
		precio
		skus
		*/

		echo "<h1>AQUI llega</h1>";
	}



	/*
	**
		OTRAS FUNCIONES QUE SE ENCARGAN DE ARREGLAR LA DATA
	**
	*/
	//INCLUIMOS 
	$importar = "";
	//EN ESTA SECION RECIBIMOS LAS VARIALES
	if (isset($_POST['datos']) && $_POST['datos'] != ""){
		
		$datos1 = $_POST['datos'];

		$datos = json_encode($datos1);
		$datos = json_decode($datos);

		if($_POST['accion'] == 'categorias'){
			//echo "<h1>".$datos->data[0]->idcategoria."</h1><br><br>";

			//echo "<h1>".count($datos)."</h1>";
			$cuantos = 0;
			for ($i=0; $i < count($datos->data); $i++) { 
				//echo "<h1>".$datos->data[$i]->idcategoria." ".$datos->data[$i]->nombre."</h1> <br>";

				if($cuantos == 0){
					$importar .= "('".$datos->data[$i]->idcategoria."', '".$datos->data[$i]->nombre."')";
				}else{
					$importar .= " ,('".$datos->data[$i]->idcategoria."', '".$datos->data[$i]->nombre."')";
				}

				$cuantos++;
			}
			//echo "<h1>".$datos->data[0]->idcategoria." ".$datos->data[0]->nombre."</h1> <br>";

			//echo $importar;

			//$conexion->insertarCategorias($importar);

			//echo "<br><br>";

		}else if($_POST['accion'] == 'productos'){ 
			//echo json_decode($_POST['datos']);
			//echo "<h1>".$datos->data[0]->nombre."</h1> <br>";

			//echo "<h1>joser</h1>";

			
			$cuantos = 0;
			//for ($i=0; $i < count($datos->data); $i++) { 
			for ($i=0; $i < count($datos->data); $i++) { 
				//echo "<h1>".$datos->data[$i]->idcategoriamenu." ".$datos->data[$i]->nombre." ".$datos->data[$i]->imagen."</h1> <br>";
				$imagen = "";
				if($datos->data[$i]->imagen != ""){
					//echo "<img src='https://admin.invupos.com/invuPos/images/banner/".$datos->data[$i]->imagen."' />";
					$imagen = "https://admin.invupos.com/invuPos/images/banner/".$datos->data[$i]->imagen;
				}

				//if($cuantos < 150){
					if($cuantos == 0){
						$importar .= "('".$datos->data[$i]->idcategoriamenu."', ".sanear_string($datos->data[$i]->nombre).", ".sanear_string($imagen).", '".$datos->data[$i]->precioSugerido."', '".$datos->data[$i]->codigo."')";
					}else{
						$importar .= " ,('".$datos->data[$i]->idcategoriamenu."', ".sanear_string($datos->data[$i]->nombre).", ".sanear_string($imagen).", '".$datos->data[$i]->precioSugerido."', '".$datos->data[$i]->codigo."')";
					}
				/*}else{

					$conexion->insertarProducto($importar);//EN ESTA ACCION IMPORTAMOS TODA LA INFORMACION
					$i = $i++;
					sigueImportando($datos, $i);//ENVIAMOS A LA FUNCION QUE SEGUIRA IMPORTANDO TODO

					$i = count($datos->data) + 1;

					break;	
				}*/

				echo "<h1>".sanear_string($datos->data[$i]->nombre)."</h1>";

				$cuantos++;
			}
			//echo "<h1>".$datos->data[0]->idcategoria." ".$datos->data[0]->nombre."</h1> <br>";

			//echo $cuantos;
			//echo $importar;

			$conexion->insertarProducto($importar);//EN ESTA ACCION IMPORTAMOS TODA LA INFORMACION
			
		}else if($_POST['accion'] == 'categoriasDos'){
			echo "<h1>".count($datos)."</h1>";
			$cuantos = 0;
			for ($i=0; $i < count($datos->data); $i++) { 
				//echo "<h1>".$datos->data[$i]->idcategoria." ".$datos->data[$i]->nombre."</h1> <br>";

				if($cuantos == 0){
					
					$importar .= "('".$datos->data[$i]->idcategoriamenu."', ".'"'.$datos->data[$i]->nombremenu.'"'.", '".$datos->data[$i]->porcentaje."', '".$datos->data[$i]->impuesto."', '".$datos->data[$i]->codigo."', '".$datos->data[$i]->imagen."', '".$datos->data[$i]->orden."', '".$datos->data[$i]->idSubCategoriaMenu."')";
				}else{
					$importar .= " ,('".$datos->data[$i]->idcategoriamenu."', ".'"'.$datos->data[$i]->nombremenu.'"'.", '".$datos->data[$i]->porcentaje."', '".$datos->data[$i]->impuesto."', '".$datos->data[$i]->codigo."', '".$datos->data[$i]->imagen."', '".$datos->data[$i]->orden."', '".$datos->data[$i]->idSubCategoriaMenu."')";
				}

				$cuantos++;
			}
			//echo "<h1>".$datos->data[0]->idcategoria." ".$datos->data[0]->nombre."</h1> <br>";

			//echo $importar;

			//$conexion->insertarCategoriasDos($importar);
			
		}else if($_POST['accion'] == "relacionados"){
			echo "<h1>".count($datos)."</h1>";
			$cuantos = 0;
			for ($i=0; $i < count($datos->data); $i++) { 
				//echo "<h1>".$datos->data[$i]->idcategoria." ".$datos->data[$i]->nombre."</h1> <br>";

				//RECORREMOS EL ARREGLO DE ESTA CATEGORIA
				for ($s=0; $s < count($datos->data[$i]->categorias); $s++) { 
					if($cuantos == 0){
					
						$importar .= "('".$datos->data[$i]->descripcion."', '".$datos->data[$i]->id."', '".$datos->data[$i]->categorias[$s]."')";
					}else{
						$importar .= " ,('".$datos->data[$i]->descripcion."', '".$datos->data[$i]->id."', '".$datos->data[$i]->categorias[$s]."')";
					}
					$cuantos++;
				}

				$cuantos++;
			}
			
			echo "<h1>{$importar}</h1>";

			$conexion->relacionador($importar);
		}
		
	}

?>