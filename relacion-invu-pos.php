<?php
/*
	Plugin Name: Relacion Invu Pos
	Plugin URI: http://www.jj.com/
	description: Primer Plugin de jj
	Version: 3.0.1
	Author: Joser
	Author URI: http://www.jj.com/
	License: GPL2
*/
//include("class.relacion-admin.php");
require_once("class.relacion-admin.php");

/*
**
	IMPORTAMOS ESTILOS
**
*/
add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');
function callback_for_setting_up_scripts() {
	wp_enqueue_style( 'relacion', plugins_url( '/css/relacion_css.css', __FILE__ ) );
	wp_enqueue_style( 'relacion' );
	//wp_enqueue_script( 'relacion', plugins_url( '/js/relacion_js.js', __FILE__ ), array('jquery'), '1.0', true );
}
wp_enqueue_style( 'relacion', plugins_url( '/css/relacion_css.css', __FILE__ ) );
wp_enqueue_style( 'relacion' );

//ESTE LO DEJAMOS AFUERA PORQUE POR ALGUNA RAZON NO SALIA BIEN DESDE LA FUNCTION\
wp_enqueue_script( 'relacion', plugins_url( '/js/relacion_js.js', __FILE__ ), array('jquery'), '1.0', true );


//AQUI TENEMOS EL CODIGO QUE ME GUARDARA LA URL EN JS
wp_enqueue_script('my-script', get_stylesheet_directory_uri() . '/js/my-script.js');
wp_localize_script('my-script', 'myScript', array(
    'pluginsUrl' => plugins_url(),
));


include("php/api.php");

/** Step 2 (from text above). */
add_action( 'admin_menu', 'menu_g_form_user_destination' );

/** Step 1. */
function menu_g_form_user_destination() {
	//add_options_page( 'Gf user Destination', 'GF usuarios', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
	//add_options_page( 'Gf user Destination', 'K Relacionador Invu POS', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );//ESTE ES EL QUE TENIAMOS ANTES DE TODO
	//ESTE DE ABAJO ES PARA CREAR UN MENU PRINCIPAL
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );


	add_menu_page( 'Importar Invu', 'Importar Invu', 'manage_options', 'generar-liga', 'my_plugin_options', 'dashicons-editor-paste-text', '35' );
	add_submenu_page( 'generar-liga', 'Categorias', 'Categorias', 'manage_options', 'mis-categorias', 'borrar_productos_web' );

	//SECCION DE CONFIGURACIONES
	add_submenu_page( 'generar-liga', 'Ajustes', 'Ajustes', 'manage_options', 'mis-ajustes', 'ajustes_productos_web' );
}
include("class.relacion-imagenes.php");
/** Step 3. */
function my_plugin_options() {
	global $wpdb;

	//INCLUIMOS LAS FUNCIONES DE ESTA PAGINA
	require_once("class.relacion-consult.php");

	//

	$mi_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 1 ");
	$url_api = "";
	foreach ($mi_api as $key) {
		$api_key = $key->nombre;
	}

	$url_api = "";
	$mi_url_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 2 ");
	$url_api = "";
	foreach ($mi_url_api as $key) {
		$url_api = $key->nombre;
	}

	//TABLAS EN DB
	global $table_categorias;
	global $table_terminos;
	global $table_relacion;
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	echo "<h1>Relacionar productos de Invu Pos:</h1>";



	?>
		<div class="wrap">
			<h1>Consultar productos:</h1>


			<div class='api_key' data-api="<?php echo $api_key; ?>"></div>
			<div class='url_api' data-api="<?php echo $url_api; ?>"></div>

			<script src="<?php echo plugins_url('./js/api.js', __FILE__ ); ?>"></script>
			<form method="post" action="">
			    <input type="text" name="relacionar" value="1" required style="display: none;" />
			    
			    <?php submit_button("Relacionar"); ?>
			</form>

			<!-- VEMOS TODOS LOS USUARIOS QUE TENEMOS -->
			<div class="categorias"></div>
			<div style="clear: both;"></div>
			<h1>Resultados: Faltan (<span class="faltantes"></span>)</h1>
			<div class="mis_resultados_eas">
				
			</div>
			<div style="clear: both;"></div>
			<table class="wp-list-table widefat fixed striped pages">
				<thead>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Seleccionar todos</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>
						<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
							<a href="#">
								<span>Título</span>
								<span class="sorting-indicator"></span>
							</a>
						</th>

						<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
							<a href="#">
								<span>Imagen</span>
								<span class="sorting-indicator"></span>
							</a>
						</th>

						<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
							<a href="#">
								<span>Categoria</span>
								<span class="sorting-indicator"></span>
							</a>
						</th>

						<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
							<a href="#">
								<span>Precio</span>
								<span class="sorting-indicator"></span>
							</a>
						</th>

						<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
							<a href="#">
								<span>Sku</span>
								<span class="sorting-indicator"></span>
							</a>
						</th>
						
					</tr>
				</thead>

				<?php
					$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

					$categoria1 = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms ");

					//echo "<h1>jejejejejej ".$categoria1[0]->term_id."</h1>";
				?>

				<tbody id="the-list" class="productosImportar">
					<tr id="post-2" class="iedit author-self level-0 post-2 type-page status-publish hentry  copiarFormulario">
						<th scope="row" class="check-column">			
							<label class="screen-reader-text" for="cb-select-2">Elige Página de ejemplo</label>
							<input id="cb-select-2" type="checkbox" name="post[]" value="2">
							<div class="locked-indicator">
								<span class="locked-indicator-icon" aria-hidden="true"></span>
								<span class="screen-reader-text">“Página de ejemplo” está bloqueado</span>
							</div>
						</th>
						<div></div>
						<td class="title column-title has-row-actions column-primary page-title" data-colname="Título">
							<div class="locked-info">
								<span class="locked-avatar"></span> 
								<span class="locked-text"></span>
							</div>
							<strong class="nombre">
								name
							</strong>

							<div class="row-actions">
								<span class="edit">
									<a href="<?php echo $actual_link."&amp;edit=".$key->id; ?>" aria-label="Editar “Página de ejemplo”">Editar</a> | 
								</span>

								<span class="trash">
									<a href="<?php echo $actual_link."&amp;eliminarUsuario=".$key->id; ?>" class="submitdelete" aria-label="Mover “Página de ejemplo” a la papelera">Papelera</a> | 
								</span>
							</div>
						</td>	

						<td>
							<strong class="imagen">
								<img src="" />
							</strong>
						</td>

						<td>
							<strong class="categoria">
								categ
							</strong>
						</td>

						<td>
							<strong class="precio">
								categ
							</strong>
						</td>

						<td>
							<strong class="sku">
								categ
							</strong>
						</td>
					</tr>



				</tbody>

			</table>
		</div>
		
	<?php
}



add_action( 'wp_ajax_my_action', 'my_action' );

function my_action() {
	global $wpdb; // this is how you get access to the database

	$productos = $_POST['productos'];

	//AQUI RECIBIMOS LOS PRODUCTOS ASI QUE DEBEREMOS ESTRUCTURAR TODO EL INSERT DESDE AQUI

	//echo count($productos)." ---> esto es lo que necesitamos";

	//RECORREMOS TODOS LOS PRODUCTOS SELECCIONADOS DESDE LA API PARA PODER INSERTARLOS O ACTUALIZARLOS

	$cuentas1 = $_POST['cuentas'];
	$cuentas2 = $_POST['cuentas2'];

	$sumasBorrar = 0;

	foreach ($productos as $key => $value) {
		$sumasBorrar++;
		//nombre
		//imagen
		//precio
		//catHija
		//catPadre
		//skus

		//if(!isset($_POST['cuentas'])){
			$id_producto 	= $value['id_producto'];

			$nombre 		= $value['nombre'];

			$nombreFinal 	= sanear_string2($nombre);
			$nombreFinal 	= str_replace(array("  ", " "), "-", $nombreFinal);
			$nombreFinal 	= strtolower($nombreFinal);

			$imagen 		= "https://admin.invupos.com/invuPos/images/banner/".$value['imagen'];
			$nombre_imagen 	= $value['imagen'];

			$descripcion 	= $value['descripcion'];

			$nombre_imagen = str_replace(array(".jpg", ".jpeg", ".png") , "", $nombre_imagen);
			$nombre_imagen = str_replace(array("  ") , " ", $nombre_imagen);
			$nombre_imagen = str_replace(array(" ", "  ") , "-", $nombre_imagen);
			$nombre_imagen = str_replace(array("--", "---") , "", $nombre_imagen);

			$precio 		= $value['precio'];

			$idHija 		= $value['idHija'];
			$idPadre 		= $value['idPadre'];

			$catPadre 		= $value['catPadre'];
			$catHija 		= $value['catHija'];
			$skus 			= $value['skus'];
			$stock 			= $value['stock'];

			//echo $nombre." ".$imagen." ".$precio." ".$catPadre." ".$catHija;

			$diaYhora = date("Y-m-d H:i:s");

			$inserta_n_prod = 0;
			$id_producc 	= 0;
			//PRIMERO CONSULTAMOS SI ESTE PRODUCTO EXISTE
			$mi_product = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mis_productos WHERE invu = '{$id_producto}' ");
			if(count($mi_product) <= 0){
				$wpdb->insert(
		                "{$wpdb->prefix}mis_productos",
		                array(
		                    'invu'			=>	$id_producto,
		                    'wp'			=> 0
		                    )
		                );
				$inserta_n_prod = $wpdb->insert_id;//ESTO LO DEBEMOS ACTUALIZAR MAS ADELANTE CON EL ID REAL DEL PRODUCTO CREADO
			}else{
				//SI ESTE PRODUCTO YA EXISTE EN INVU DEBEMOS BUSCAR EL ID DE WORDPRESS
				$id_producc = $mi_product[0]->wp;


				$consulta = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE ID = '{$id_producc}' AND post_type = 'product' AND post_status != 'trash' ");
				if(count($consulta) <= 0){
					$wpdb->delete( "{$wpdb->prefix}mis_productos", array( 'wp' => $id_producc ) );
				}

				//POR MOTIVOS DE CONVENIENCIA ES MEJOR REESCRIBIR EL NUMERO DEL POST
				/*
				$wpdb->update( 
					"{$wpdb->prefix}mis_productos",
					array( 
						'wp' 	=> $mi_product[0]->wp 
					), 
					array( 
						'invu' 	=> $mi_product[0]->invu 
					)
				);

				$id_producc = $id_producto;
				*/
			}

			//CONSULTAREMOS SI ESTE PRODUCTO EXISTE EN DB
			//$consulta = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_name = '{$nombreFinal}' AND post_status != 'trash' ");
			$consulta = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE ID = '{$id_producc}' AND post_type = 'product' AND post_status != 'trash' ");

			//SEGUNDA CONSULTA 
			//$tt_name = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_title = '{$nombre}' AND post_type = 'product' AND post_status != 'trash' ");

			//if(count($consulta) <= 0 && count($tt_name) <= 0){//ESTO ES QUE NO EXISTE
			if(count($consulta) <= 0){//ESTO ES QUE NO EXISTE

				echo "<h1> {$nombre} --> Agregado </h1>";
				//SI ESTO NO EXISTE PROCEDEMOS A CREARLO

				//$wpdb->insert_id

				//if($cuentas1 < 1000){

					$wpdb->insert(
			                "{$wpdb->prefix}posts",
			                array(
			                    'post_author'			=>	1,
			                    'post_date'				=>	$diaYhora,
			                    'post_date_gmt'			=>	$diaYhora,
			                    'post_content'			=>	$descripcion,
								'post_title'			=>	$nombre,
								'post_excerpt'			=>	'',
								'post_status'			=>	'publish',
								'comment_status'		=>	'open',
								'ping_status'			=>	'closed',
								'post_password'			=>	'',
								'post_name'				=>	$nombreFinal,
								'to_ping'				=>	'',
								'pinged'				=>	'',
								'post_modified'			=>	$diaYhora,
								'post_modified_gmt'		=>	$diaYhora,
								'post_content_filtered'	=>	'',
								'post_parent'			=>	0,
								'menu_order'			=>	0,
								'post_type'				=>	'product',
								'post_mime_type'		=>	'',
								'comment_count'			=>	0
			                  )
			                );
					$idProducto = $wpdb->insert_id;
					//MODIFICAMOS EL PORDUCTO CREADO PARA PODER AGREGARLE LA RUTA SEGUN SU ID
					$wpdb->update( 
			                  "{$wpdb->prefix}posts", 
			                  array( 
			                    'guid' => "https://misihome.com.pa/?post_type=product&#{$idProducto};p=9" 
			                  ), 
			                  array( 
			                    'ID' => $idProducto
			                    ) 
			                );

					if($inserta_n_prod != 0){
						$wpdb->update( 
								"{$wpdb->prefix}mis_productos",
								array( 
									'wp' 	=> $idProducto
								), 
								array( 
									'id' 	=> $inserta_n_prod
								)
							);
					}
					//MODIFICAMOS LA RELACION ENTRE EL PRODUCTO EN WP Y INVU
					/*
					$wpdb->update( 
			                  "{$wpdb->prefix}mis_productos", 
			                  array( 
			                    'wp' => $idProducto
			                  ), 
			                  array( 
			                    'id' => $inserta_n_prod
			                    ) 
			                );

					$wpdb->insert(
			                "{$wpdb->prefix}mis_productos",
			                array(
			                    'invu'			=>	$id_producto,
			                    'wp'			=> $idProducto
			                    )
			                );
					*/

					if($idProducto == 0){
						echo "<h1>ES 0 ---> {$nombre}</h1>";
					}

					if($idProducto == 0){
						echo "<h1>ES 0 ---> {$nombre}</h1>";
					}

					//INSERTAMOS LAS METRICAS DE LOS PRODUCTOS  	--------------------->
					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_wc_review_count',
								'meta_value'	=> '0'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_wc_rating_count',
								'meta_value'	=> 'a:0:{}'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_wc_average_rating',
								'meta_value'	=> '0'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_edit_last',
								'meta_value'	=> '0'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_edit_lock',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_sku',
								'meta_value'	=> $skus
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_regular_price',
								'meta_value'	=> $precio
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_sale_price',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_sale_price_dates_from',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_sale_price_dates_to',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> 'total_sales',
								'meta_value'	=> '0'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_tax_status',
								'meta_value'	=> 'taxable'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_tax_class',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_manage_stock',
								'meta_value'	=> 'yes'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_backorders',
								'meta_value'	=> 'notify'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_sold_individually',
								'meta_value'	=> 'no'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_weight',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_length',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_width',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_height',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_upsell_ids',
								'meta_value'	=> 'a:0:{}'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_crosssell_ids',
								'meta_value'	=> 'a:0:{}'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_purchase_note',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_default_attributes',
								'meta_value'	=> 'a:0:{}'
							)
					);


					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_virtual',
								'meta_value'	=> 'no'
							)
					);


					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_downloadable',
								'meta_value'	=> 'no'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_product_image_gallery',
								'meta_value'	=> ''
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_download_limit',
								'meta_value'	=> '-1'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_download_expiry',
								'meta_value'	=> '-1'
							)
					);

					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_stock',
								'meta_value'	=> $stock
							)
					);


					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_product_version',
								'meta_value'	=> '3.4.4'
							)
					);


					$wpdb->insert(
							"{$wpdb->prefix}postmeta",
							array(
								'post_id'		=> $idProducto,
								'meta_key'		=> '_price',
								'meta_value'	=> $precio
							)
					);

					//SI TENEMOS PRODUCTOS EN STOCK AGREGAMOS
					if($stock > 0){
						$wpdb->update( 
				                  "{$wpdb->prefix}postmeta", 
				                  array( 
				                    'meta_value' => 'instock' 
				                  ), 
				                  array( 
				                    'post_id' 			=> $idProducto,
				                    'meta_key'	=> '_stock_status'
				                  ) 
				                );
					}else{
						//SI NO TENEMOS PRODUCTOS EN ESTOCK
						$wpdb->update( 
				                  "{$wpdb->prefix}postmeta", 
				                  array( 
				                    'meta_value' => 'outofstock' 
				                  ), 
				                  array( 
				                    'post_id' 			=> $idProducto,
				                    'meta_key'	=> '_stock_status'
				                  ) 
				                );
					}


					//  	--------------------->

					$idCategoria = 0;

					$catPadre2	= sanear_string2($catPadre);
					$reemplaza  = array('  ', '   ', " ");
					$catPadre2 	= str_replace($reemplaza, "-", $catPadre2);
					$catPadre2 	= strtolower($catPadre2);

					//PRIMERO CONSULTAMOS SI LA CATEGORIA PADRE EXISTE
					$padre = $wpdb->get_results("SELECT wp FROM {$wpdb->prefix}mis_categorias WHERE  invu = '{$idPadre}' AND tipo = '1' ");
					if(count($padre) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA

						//CONSULTAMOS EL NOMBRE EN LAS CATEGORIAS DE WORDPRESS
						$categoria1 = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms WHERE  slug = '{$catPadre2}' ");
						if(count($categoria1) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA
							$wpdb->insert(
									"{$wpdb->prefix}terms",
									array(
										'name'			=> $catPadre,
										'slug'			=> $catPadre2,
										'term_group'	=> '0'
									)
							);

							//
							$idCategoria = $wpdb->insert_id;
						}else{
							$idCategoria = $categoria1[0]->term_id;
						}

						//LUEGO LA RELACIONAMOS ENTRE LOS PRODUCTOS
						$wpdb->insert(
								"{$wpdb->prefix}mis_categorias",
								array(
									'invu'			=> $idPadre,
									'wp'			=> $idCategoria,
									'tipo'			=> '1'
								)
						);

						//
						//$idCategoria = $wpdb->insert_id;
					}else{
						foreach ($padre as $mi_padre) {
							$idCategoria = $mi_padre->wp;
						}
						//$idCategoria = $padre[0]->wp;

						//TAMBIEN DEBEMOS ACTUALIZAR EL NOMBRE DE ESTA CATEGORIA
						$wpdb->update( 
								"{$wpdb->prefix}terms", 
								array( 
									'name' => $catPadre,	// string
									'alug' => $catPadre2	// integer (number) 
								), 
								array( 'term_id' => $idCategoria )
							);
					}

					//AGREGAREMOS LAS CATEGORIAS, PRIMERO DEBEMOS CONSULTAR SI EXISTE, SI NO EXISTE LA CREAMOS
					/*
					$categoria1 = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms WHERE  slug = '{$catPadre2}' ");

					if(count($categoria1) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA
						$wpdb->insert(
								"{$wpdb->prefix}terms",
								array(
									'name'			=> $catPadre,
									'slug'			=> $catPadre2,
									'term_group'	=> '0'
								)
						);

						//
						$idCategoria = $wpdb->insert_id;
					}else{
						$idCategoria = $categoria1[0]->term_id;
					}
					*/

					//PROCEDEMOS A CONSULTAR LA CATEGORIA HIJA

					$idCategoriaHija = 0;

					$catHija2	= sanear_string2($catHija);
					$reemplaza  = array('  ', '   ', " ");
					$catHija2 	= str_replace($reemplaza, "-", $catHija2);
					$catHija2 	= strtolower($catHija2);

					/*
					$categoria2 = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms WHERE  slug = '{$catHija2}' ");

					if(count($categoria2) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA
						$wpdb->insert(
								"{$wpdb->prefix}terms",
								array(
									'name'			=> $catHija,
									'slug'			=> $catHija2,
									'term_group'	=> '0'
								)
						);

						//
						$idCategoriaHija = $wpdb->insert_id;
					}else{
						$idCategoriaHija = $categoria2[0]->term_id;
					}
					*/

					//PRIMERO CONSULTAMOS SI LA CATEGORIA PADRE EXISTE
					$hija = $wpdb->get_results("SELECT wp FROM {$wpdb->prefix}mis_categorias WHERE  invu = '{$idHija}' AND tipo = '2' ");
					if(count($hija) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA

						//CONSULTAMOS EL NOMBRE EN LAS CATEGORIAS DE WORDPRESS
						$categoria2 = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms WHERE  slug = '{$catHija2}' ");
						if(count($categoria2) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA
							$wpdb->insert(
									"{$wpdb->prefix}terms",
									array(
										'name'			=> $catHija,
										'slug'			=> $catHija2,
										'term_group'	=> '0'
									)
							);

							//
							$idCategoriaHija = $wpdb->insert_id;
						}else{
							$idCategoriaHija = $categoria2[0]->term_id;
						}

						//LUEGO LA RELACIONAMOS ENTRE LOS PRODUCTOS
						$wpdb->insert(
								"{$wpdb->prefix}mis_categorias",
								array(
									'invu'			=> $idHija,
									'wp'			=> $idCategoriaHija,
									'tipo'			=> '2'
								)
						);

						//
						//$idCategoria = $wpdb->insert_id;
					}else{
						$idCategoria = $hija[0]->wp;
						foreach ($hija as $mi_hija) {
							$idCategoria = $mi_hija->wp;
						}

						//TAMBIEN DEBEMOS ACTUALIZAR EL NOMBRE DE ESTA CATEGORIA
						$wpdb->update( 
								"{$wpdb->prefix}terms", 
								array( 
									'name' => $catHija,	// string
									'slug' => $catHija2	// integer (number) 
								), 
								array( 'term_id' => $idCategoriaHija )
							);
					}

					//--->	//AQUI TENEMOS LAS RELACIONES ENTRE PRODUCTOS Y categorias
					//PADRE

					//VALIDAMOS QUE ESTA RELACION NO EXISTA
					$cat_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}term_relationships WHERE object_id = {$idProducto} AND term_taxonomy_id = {$idCategoria} " );

					if($cat_count == 0){
						$wpdb->insert(
									"{$wpdb->prefix}term_relationships",
									array(
										'object_id'			=> $idProducto,
										'term_taxonomy_id'	=> $idCategoria,
										'term_order'		=> '0'
									)
							);
					}
					
					//HIJA

					$cat_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}term_relationships WHERE object_id = {$idProducto} AND term_taxonomy_id = {$idCategoriaHija} " );

					if($cat_count == 0){
						$wpdb->insert(
									"{$wpdb->prefix}term_relationships",
									array(
										'object_id'			=> $idProducto,
										'term_taxonomy_id'	=> $idCategoriaHija,
										'term_order'		=> '0'
									)
							);
					}

					//RELACIONAREMOS LA CATEGORIA PADRE Y LA HIJA 
					//CONSULTAREMOS SI ESTAS CATEGORIAS YA ESTAN RELACIONADAS
					$padres = $wpdb->get_results("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id = {$idCategoria} AND taxonomy = 'product_cat' AND parent = '0' ");

					if(count($padres) <= 0){
						//SI NO EXISTE LA CATEGORIA PADRE LA CREAMOS
						$wpdb->insert(
									"{$wpdb->prefix}term_taxonomy",
									array(
										'term_taxonomy_id'	=>	$idCategoria,
										'term_id'			=>	$idCategoria,
										'taxonomy'			=>	'product_cat',
										'description'		=>	'',
										'parent'			=>	0,
										'count'				=>	1
									)
							);	
					}

					//HACEMOS LO MISMO CON LAS CATEGORIAS HIJAS
					$hijas = $wpdb->get_results("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id = {$idCategoriaHija} AND taxonomy = 'product_cat' ");

					if(count($hijas) <= 0){
						//SI NO EXISTE LA CATEGORIA PADRE LA CREAMOS
						$wpdb->insert(
									"{$wpdb->prefix}term_taxonomy",
									array(
										'term_taxonomy_id'	=>	$idCategoriaHija,
										'term_id'			=>	$idCategoriaHija,
										'taxonomy'			=>	'product_cat',
										'description'		=>	'',
										'parent'			=>	$idCategoria,
										'count'				=>	1
									)
							);	
					}else{
						//ACTUALIZAMOS LA CATEGORIA
						$wpdb->update( 
								"{$wpdb->prefix}term_taxonomy", 
								array( 
									'parent'			=>	$idCategoria
								), 
								array( 
									'term_taxonomy_id'	=>	$idCategoriaHija,
									'term_id'			=>	$idCategoriaHija
								)
							);
					}					

				//}

				//IMPORTAMOS LA IMAGEN EN LA DB
				//importarImagenesDB($imagen, $idProducto);

				//PRIMERO CONSULTAMOS SI ESTA IMAGEN EXISTE.
				$existe = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE guid LIKE '%".$nombre_imagen."%' ");
				if(count($existe) <= 0){
					importarImagenesDB($imagen, $idProducto);
				}else{
					$att_id = aladdin_get_image_id($imagen);
					foreach ($existe as $exss) {
						$att_id = $exss->ID;
					}
					set_post_thumbnail( $idProducto, $att_id );
				}

				if($nombre_imagen == ""){
					//$id_imagen_demo = 762;//ESTA ES LA IMAGEN DEMO
					//set_post_thumbnail( $idProducto, $id_imagen_demo );
					delete_post_thumbnail($idProducto);
				}
				echo "<h3>AGREGAMOS --> SELECT ID FROM {$wpdb->prefix}posts WHERE guid LIKE '%".$nombre_imagen."%' </h3>";

			}else{//ESTO ES QUE SI EXISTE
				//SI EXISTE PROCEDEMOS A ACTUALIZAR EL PRECIO DE ESTE PRODUCTO Y LA CANTIDAD DISPONIBLE

				$idProducto = 0;
				$idProducto = $consulta[0]->ID;

				if($idProducto == 0){
					//$idProducto = $tt_name[0]->ID;
				}
				//echo "--------------> {$idProducto} {$precio} {$stock} <-----------------";

				//MODIFICAMOS EL NOMBRE DEL PRODUCTO
				$wpdb->update( 
		                  "{$wpdb->prefix}posts", 
		                  array( 
		                    'post_title' 	=> $nombre,
		                    'post_name' 	=> $nombreFinal ,
		                    'post_content' 	=> $descripcion
		                  ), 
		                  array( 
		                    'ID' 		=> $idProducto
		                  ) 
		                );

				//MODIFICAMOS EL RESTO DE DATOS

				$wpdb->update( 
		                  "{$wpdb->prefix}postmeta", 
		                  array( 
		                    'meta_value' 	=> $precio 
		                  ), 
		                  array( 
		                    'post_id' 		=> $idProducto,
		                    'meta_key'		=> '_regular_price'
		                  ) 
		                );

				$wpdb->update( 
		                  "{$wpdb->prefix}postmeta", 
		                  array( 
		                    'meta_value' 	=> '' 
		                  ), 
		                  array( 
		                    'post_id' 		=> $idProducto,
		                    'meta_key'		=> '_sale_price'
		                  ) 
		                );

				$wpdb->update( 
		                  "{$wpdb->prefix}postmeta", 
		                  array( 
		                    'meta_value' 	=> $precio 
		                  ), 
		                  array( 
		                    'post_id' 		=> $idProducto,
		                    'meta_key'		=> '_price'
		                  ) 
		                );

				$wpdb->update( 
		                  "{$wpdb->prefix}postmeta", 
		                  array( 
		                    'meta_value' 	=> $stock 
		                  ), 
		                  array( 
		                    'post_id' 		=> $idProducto,
		                    'meta_key'		=> '_stock'
		                  ) 
		                );

				//SI TENEMOS PRODUCTOS EN STOCK AGREGAMOS
				if($stock > 0){
					$wpdb->update( 
			                  "{$wpdb->prefix}postmeta", 
			                  array( 
			                    'meta_value' 	=> 'instock' 
			                  ), 
			                  array( 
			                    'post_id' 		=> $idProducto,
			                    'meta_key'		=> '_stock_status'
			                  ) 
			                );
				}else{
					//SI NO TENEMOS PRODUCTOS EN ESTOCK
					$wpdb->update( 
			                  "{$wpdb->prefix}postmeta", 
			                  array( 
			                    'meta_value'	=> 'outofstock' 
			                  ), 
			                  array( 
			                    'post_id' 		=> $idProducto,
			                    'meta_key'		=> '_stock_status'
			                  ) 
			                );
				}

				$wpdb->update( 
		                  "{$wpdb->prefix}postmeta", 
		                  array( 
		                    'meta_value' 	=> 'notify' 
		                  ), 
		                  array( 
		                    'post_id' 		=> $idProducto,
		                    'meta_key'		=> '_backorders'
		                  ) 
		                );

				/*
				**
					HACEMOS LA SECCION DE CATEGORIAS
				**
				*/

				$idCategoria = 0;

				$catPadre2	= sanear_string2($catPadre);
				$reemplaza  = array('  ', '   ', " ");
				$catPadre2 	= str_replace($reemplaza, "-", $catPadre2);
				$catPadre2 	= strtolower($catPadre2);


				//PRIMERO CONSULTAMOS SI LA CATEGORIA PADRE EXISTE
				$padre = $wpdb->get_results("SELECT wp FROM {$wpdb->prefix}mis_categorias WHERE  invu = '{$idPadre}' AND tipo = '1' ");
				if(count($padre) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA

					//CONSULTAMOS EL NOMBRE EN LAS CATEGORIAS DE WORDPRESS
					$categoria1 = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms WHERE  slug = '{$catPadre2}' ");
					if(count($categoria1) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA
						$wpdb->insert(
								"{$wpdb->prefix}terms",
								array(
									'name'			=> $catPadre,
									'slug'			=> $catPadre2,
									'term_group'	=> '0'
								)
						);

						//
						$idCategoria = $wpdb->insert_id;
					}else{
						$idCategoria = $categoria1[0]->term_id;
					}

					//LUEGO LA RELACIONAMOS ENTRE LOS PRODUCTOS
					$wpdb->insert(
							"{$wpdb->prefix}mis_categorias",
							array(
								'invu'			=> $idPadre,
								'wp'			=> $idCategoria,
								'tipo'			=> '1'
							)
					);

					//
					//$idCategoria = $wpdb->insert_id;
				}else{
					$idCategoria = $padre[0]->wp;
					foreach ($padre as $mi_padre) {
						$idCategoria = $mi_padre->wp;
					}

					//TAMBIEN DEBEMOS ACTUALIZAR EL NOMBRE DE ESTA CATEGORIA
					$wpdb->update( 
							"{$wpdb->prefix}terms", 
							array( 
								'name' => $catPadre,	// string
								'alug' => $catPadre2	// integer (number) 
							), 
							array( 'term_id' => $idCategoria )
						);
				}

				//PROCEDEMOS A CONSULTAR LA CATEGORIA HIJA

				$idCategoriaHija = 0;

				$catHija2	= sanear_string2($catHija);
				$reemplaza  = array('  ', '   ', " ");
				$catHija2 	= str_replace($reemplaza, "-", $catHija2);
				$catHija2 	= strtolower($catHija2);

				//PRIMERO CONSULTAMOS SI LA CATEGORIA PADRE EXISTE
				$hija = $wpdb->get_results("SELECT wp FROM {$wpdb->prefix}mis_categorias WHERE  invu = '{$idHija}' AND tipo = '2' ");
				if(count($hija) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA

					//CONSULTAMOS EL NOMBRE EN LAS CATEGORIAS DE WORDPRESS
					$categoria2 = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms WHERE  slug = '{$catHija2}' ");
					if(count($categoria2) <= 0){//SI LA CATEGORIA NO EXISTE DEBEMOS CREARLA
						$wpdb->insert(
								"{$wpdb->prefix}terms",
								array(
									'name'			=> $catHija,
									'slug'			=> $catHija2,
									'term_group'	=> '0'
								)
						);

						//
						$idCategoriaHija = $wpdb->insert_id;
					}else{
						$idCategoriaHija = $categoria2[0]->term_id;
					}

					//LUEGO LA RELACIONAMOS ENTRE LOS PRODUCTOS
					$wpdb->insert(
							"{$wpdb->prefix}mis_categorias",
							array(
								'invu'			=> $idHija,
								'wp'			=> $idCategoriaHija,
								'tipo'			=> '2'
							)
					);

					//
					//$idCategoria = $wpdb->insert_id;
				}else{
					$idCategoriaHija = $hija[0]->wp;
					foreach ($hija as $mi_hija) {
						$idCategoriaHija = $mi_hija->wp;
					}

					//TAMBIEN DEBEMOS ACTUALIZAR EL NOMBRE DE ESTA CATEGORIA
					$wpdb->update( 
							"{$wpdb->prefix}terms", 
							array( 
								'name' => $catHija,	// string
								'slug' => $catHija2	// integer (number) 
							), 
							array( 'term_id' => $idCategoriaHija )
						);
				}

				//RELACIONAREMOS LA CATEGORIA PADRE Y LA HIJA 
				//CONSULTAREMOS SI ESTAS CATEGORIAS YA ESTAN RELACIONADAS
				$padres = $wpdb->get_results("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id = {$idCategoria} AND taxonomy = 'product_cat' AND parent = '0' ");

				if(count($padres) <= 0){
					//SI NO EXISTE LA CATEGORIA PADRE LA CREAMOS
					$wpdb->insert(
								"{$wpdb->prefix}term_taxonomy",
								array(
									'term_taxonomy_id'	=>	$idCategoria,
									'term_id'			=>	$idCategoria,
									'taxonomy'			=>	'product_cat',
									'description'		=>	'',
									'parent'			=>	0,
									'count'				=>	1
								)
						);	
				}

				//HACEMOS LO MISMO CON LAS CATEGORIAS HIJAS
				$hijas = $wpdb->get_results("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id = {$idCategoriaHija} AND taxonomy = 'product_cat' ");

				if(count($hijas) <= 0){
					//SI NO EXISTE LA CATEGORIA PADRE LA CREAMOS
					$wpdb->insert(
								"{$wpdb->prefix}term_taxonomy",
								array(
									'term_taxonomy_id'	=>	$idCategoriaHija,
									'term_id'			=>	$idCategoriaHija,
									'taxonomy'			=>	'product_cat',
									'description'		=>	'',
									'parent'			=>	$idCategoria,
									'count'				=>	1
								)
						);	
				}else{
					//ACTUALIZAMOS LA CATEGORIA
					$wpdb->update( 
							"{$wpdb->prefix}term_taxonomy", 
							array( 
								'parent'			=>	$idCategoria
							), 
							array( 
								'term_taxonomy_id'	=>	$idCategoriaHija,
								'term_id'			=>	$idCategoriaHija
							)
						);
				}
				/*
				**
					//--->	//AQUI TENEMOS LAS RELACIONES ENTRE PRODUCTOS Y categorias
				**
				*/
				//ELIMINAMOS LAS RELACIONES ANTERIORES DE LOS PRODUCTOS

				$wpdb->delete( 
							"{$wpdb->prefix}term_relationships", 
							array( 
								'object_id' 		=> $idProducto
								) 
					);

				
				//PROCEDEMOS REALIZAR LAS NUEVAS RELACIONES DE LAS CATEGORIAS Y LOS PRODUCTOS
				/*
				$wpdb->insert(
							"{$wpdb->prefix}term_relationships",
							array(
								'object_id'			=> $idProducto,
								'term_taxonomy_id'	=> $idCategoria,
								'term_order'		=> '0'
							)
					);
				*/
				//--->	//AQUI TENEMOS LAS RELACIONES ENTRE PRODUCTOS Y categorias
				//PADRE

				//VALIDAMOS QUE ESTA RELACION NO EXISTA
				$cat_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}term_relationships WHERE object_id = {$idProducto} AND term_taxonomy_id = {$idCategoria} " );

				if($cat_count == 0){
					$wpdb->insert(
								"{$wpdb->prefix}term_relationships",
								array(
									'object_id'			=> $idProducto,
									'term_taxonomy_id'	=> $idCategoria,
									'term_order'		=> '0'
								)
						);
				}
				
				//HIJA

				$cat_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}term_relationships WHERE object_id = {$idProducto} AND term_taxonomy_id = {$idCategoriaHija} " );

				if($cat_count == 0){
					$wpdb->insert(
								"{$wpdb->prefix}term_relationships",
								array(
									'object_id'			=> $idProducto,
									'term_taxonomy_id'	=> $idCategoriaHija,
									'term_order'		=> '0'
								)
						);
				}

				//POR ULTIMO DEBEMOS VALIDAR QUE LAS PADRES ESTEN RELACIONADAS CON LAS HIJAS
				//idCategoria
				//idCategoriaHija

				//IMPORTAMOS LA IMAGEN EN LA DB
				//importarImagenesDB($imagen, $idProducto);

				//PRIMERO CONSULTAMOS SI ESTA IMAGEN EXISTE.
				$existe = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE guid LIKE '%".$nombre_imagen."%' ");
				if(count($existe) <= 0){
					importarImagenesDB($imagen, $idProducto);
				}else{
					$att_id = aladdin_get_image_id($imagen);
					foreach ($existe as $exss) {
						$att_id = $exss->ID;
					}
					set_post_thumbnail( $idProducto, $att_id );
				}

				if($nombre_imagen == ""){
					//$id_imagen_demo = 762;//ESTA ES LA IMAGEN DEMO
					//set_post_thumbnail( $idProducto, $id_imagen_demo );
					delete_post_thumbnail($idProducto);
				}

				echo "<h1> SELECT ID FROM {$wpdb->prefix}posts WHERE guid LIKE '%".$nombre_imagen."%' -- {$nombre} --> Producto actualizado.</h1>";
			}
		//}
	}

	//LUEGO DE HACER TODO MODIFICAMOS LA CANTIDAD DE PRODUCTOS EN CADA CATEGORIA
	$categorias = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}terms ");
	foreach ($categorias as $key) {
		$cantidad = $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}term_relationships WHERE term_id = {$key->term_id} ");

		//REEMPLAZAMOS LA CANTIDAD ACTUAL POR LA NUEVA CANTIDAD
		$wpdb->update( 
                  $wpdb->prefix."term_taxonomy", 
                  array( 
                    'count' 	=> $cantidad 
                  ), 
                  array( 
                    'term_id' 	=> $key->term_id
                    ) 
                );
	}
	

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_consultamos_productos_completos', 'consultamos_productos_completos' );
//CONSULTAMOS TODOS LOS PRODUCTOS
function consultamos_productos_completos(){
	global $wpdb;

	$todos_productos = [];

	$named_array = array(
	    "nome_array" => array(
	        array(
	            "foo" => "bar"
	        ),
	        array(
	            "foo" => "baz"
	        )
	    )
	);

	$mis_productos = $wpdb->get_results(" SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'product' ");
	foreach ($mis_productos as $key){

		$categorias = [];
		//CONSULTAMOS LAS CATEGORIAS
		/*
		$cat = $wpdb->get_results("SELECT t.* FROM {$wpdb->prefix}terms AS t WHERE t.term_id = (SELECT r.term_taxonomy_id FROM {$wpdb->prefix}term_relationships AS r WHERE r.term_taxonomy_id = t.term_id AND r.object_id = '{$key->ID}' ) ");
		foreach ($cat as $cas) {
			array_push($categorias, $cas->term_id);
		}
		*/

		$momentaneo = [];
		//array_push($momentaneo, array('ID' => $key->ID, 'post_title' => $key->post_title));
		//array_push($momentaneo, array('post_title' => $key->post_title));
		//array_push($momentaneo, $categorias);

		//AGREGAMOS A LA VARIABLE GENERAL
		//array_push($todos_productos, array('ID' => $key->ID, 'post_title' => $key->post_title));
		$todos_productos["productos"] = array(array('ID' => $key->ID, 'post_title' => $key->post_title));

		$categorias = [];

		$momentaneo = [];
	}

	//$todos_productos = '{ "productos" : '.json_encode($todos_productos).'}';

	echo json_encode($todos_productos);
	//echo $todos_productos;
	wp_die();
}


function sanear_string2($string){
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", '"',
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             "."),
        '',
        $string
    );
 
 
    return $string;
}

//BORRAR TOS LOS PRODUCTOS DE LA WEB
function borrar_productos_web(){
	global $wpdb;

	echo "<h1>Configuraciones de Invu: </h1>";


	//REVISAMOS EL MATERIAL ENVIADO
	if(isset($_POST['eliminar_wordpress'])){
		//echo "<h1>Estamos agregando categorias para que no se eliminen</h1>";

		$cat = $_POST['cat_no'];

		//echo "<h1>".count($cat)."</h1>";
		if(count($cat) > 0){
			//PRIMERO ELIMINAMOS TODAS LAS CATEGORIAS QUE AGREGAMOS
			/*
			$wpdb->query( 
				$wpdb->prepare( 
					" DELETE FROM {$wpdb->prefix}no_editar WHERE id != 0 " 
			        )
			);
			*/

			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}no_editar");

			for ($i=0; $i < count($cat); $i++) { 
				//YA CON LAS CATEGORIAS PROCEDEMOS A INSERTARLAS
				$wpdb->insert(
		                "{$wpdb->prefix}no_editar",
		                array(
		                    'invu'			=>	"",
		                    'wp'			=> $cat[$i]
		                    )
		                );
			}
		}
		
	}
	?>
		<div class="formularios-approval quarterWidth formularioCertificados">
			<form method="post" action="">
			    <input type="text" name="eliminar_wordpress" value="1" required style="display: none;" />

				<!-- SECCION DE LAS CATEGORIAS -->
				<div class="form-group">
					<select name="cat_no[]" class="custom-select" multiple style="height: 600px;">
						<!-- SELECCIONAMOS TODAS LAS CATEGORIAS DE WOOCOMMERCE -->
					<?php
						//SELECCION
						$categorias = $wpdb->get_results("SELECT t.*, (SELECT s.id FROM {$wpdb->prefix}no_editar AS s WHERE s.wp = t.term_id ) selected FROM {$wpdb->prefix}terms AS t, {$wpdb->prefix}term_taxonomy AS x WHERE t.term_id = x.term_id AND x.taxonomy = 'product_cat' ORDER BY t.name ");
						//echo "<h1>SELECT t.* FROM {$wpdb->prefix}terms AS t, {$wpdb->prefix}term_taxonomy AS x WHERE t.term_id = x.term_id AND x.taxonomy = 'product_cat'  </h1>";
						foreach ($categorias as $key) {
							$selected = "";
							if($key->selected != ""){
								$selected = "selected";
							}
							echo "<option value='{$key->term_id}' {$selected}>{$key->name}</option>";
						}
					?>
					</select>
				</div>


				<!-- BOTON DE ENVIAR -->
				<?php submit_button("Guardar"); ?>
			</form>
		</div>

		<!-- CONFIGUREMOS LAS OPCIONES DE CONSULTA DEL PLUGIN -->
		<script>
			var data = {
				'action': 'total_productos_wp',
				'productos': ""
			};

			jQuery.post(ajaxurl, data, function(response) {
				//alert('Got this from the server: ' + response);

				console.log(response);
			});
		</script>
	<?php

	wp_die();
}

add_action('wp_ajax_total_productos_wp', 'total_productos_wp');
add_action('wp_ajax_nopriv_total_productos_wp', 'total_productos_wp');
// The function that handles the AJAX request
function total_productos_wp(){
	global $wpdb;

	$global_invu = [];
	$productos_invu = $_POST['productos'];

	$myObj->name = [];
	if($productos_invu != ""){
		for ($i=0; $i <= count($productos_invu); $i++) { 
			//echo $productos_invu[$i];
			//CONSULTAMOS EL ID EN WORDPRESS DE ESTE PRODUCTO
			$mi_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mis_productos WHERE invu = {$productos_invu[$i]}  ");
			foreach ($mi_id as $key) {


				array_push($global_invu, array( "id" => $key->wp ) );

				//$global_invu["productos"] = array(array("id" => $key->wp));
			}
		}

		//echo json_encode($global_invu);
		echo json_encode($global_invu);
	}else{
		$todos_productos = [];

		//CONSULTAREMOS TODOS LOS PRODUCTOS
		$total_pro = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'product' ");
		foreach ($total_pro as $key) {
			$no_guardar = 0;
			//REVISAMOS LA CATEGORIA DE ESTE PRODUCTO
			$categorias = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}term_relationships WHERE object_id = {$key->ID} ");
			foreach($categorias as $catt){
				//CONSULTAMOS SI ESTA ES UNA CATEGORIA PROIBIDA
				$relacion = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}no_editar WHERE wp = {$catt->term_taxonomy_id} ");
				foreach ($relacion as $rela) {
					//echo "----------------> {$key->ID} <---------------";
					$no_guardar++;
				}
			}

			if($no_guardar == 0){
				//SI LLEGAMOS HASTA AQUI DEBEMOS CONSULTAR EL ID DE INVU DE ESTE PRODUCTO
				array_push($todos_productos, array("id" => $key->ID));

				//$todos_productos["productos"] = array(array("id" => $key->ID));
			}
		}

		echo json_encode($todos_productos);
	}
	wp_die();
}


//FUNCTION PARA ELIMINAR LOS PRODUCTOS MALOS
add_action('wp_ajax_elimina_productos_malos', 'elimina_productos_malos');
add_action('wp_ajax_nopriv_elimina_productos_malos', 'elimina_productos_malos');
// The function that handles the AJAX request
function elimina_productos_malos() {
	global $wpdb;

	$productos = $_POST['productos'];

	for ($i=0; $i < count($productos); $i++) { 
		/*
		$wpdb->delete( "{$wpdb->prefix}posts", array( 'ID' => $productos[$i] ) );
		$wpdb->delete( "{$wpdb->prefix}mis_productos", array( 'wp' => $productos[$i] ) );
		*/
		$wpdb->update( 
				"{$wpdb->prefix}posts",
				array( 
					'post_status' 	=> 'trash' 
				), 
				array( 
					'ID' 	=> $productos[$i]
				)
			);

		echo "Eliminado el producto {$productos[$i]} ";
	}

	wp_die();
}

//FUNCION QUE ELIMINA LOS PRODUCTOS NO ACTIVOS
add_action('wp_ajax_eliminar_productos_n_web', 'eliminar_productos_n_web');
add_action('wp_ajax_nopriv_eliminar_productos_n_web', 'eliminar_productos_n_web');
// The function that handles the AJAX request
function eliminar_productos_n_web() {
	global $wpdb;

	$productos = $_POST['productos'];

	for ($i=0; $i < count($productos); $i++) { 
		/*
		$wpdb->delete( "{$wpdb->prefix}posts", array( 'ID' => $productos[$i] ) );
		$wpdb->delete( "{$wpdb->prefix}mis_productos", array( 'wp' => $productos[$i] ) );
		*/
		$wpdb->update( 
				"{$wpdb->prefix}posts",
				array( 
					'post_status' 	=> 'trash' 
				), 
				array( 
					'ID' 	=> $productos[$i]
				)
			);

		echo "**Eliminado el producto {$productos[$i]} ***";
	}

	wp_die();
}

//actualizar_productos_m
add_action('wp_ajax_actualizar_productos_m', 'actualizar_productos_m');
add_action('wp_ajax_nopriv_actualizar_productos_m', 'actualizar_productos_m');
// The function that handles the AJAX request
function actualizar_productos_m() {
	global $wpdb;

	/*

	$productos_INVU = $_POST['productos'];

	$total_p_invu = [];
	foreach ($productos_INVU as $key) {
		array_push($total_p_invu, $key->id_producto);
	}

	//CONSULTAREMOS TODOS LOS PRODUCTOS
	$web_relacionados = [];
	$total_web_no = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mis_productos ");
	foreach ($total_web_no as $key) {
		array_push($web_relacionados, $key->invu);
	}


	$productos_r = array_diff($web_relacionados, $total_p_invu);

	foreach ($productos_r as $key => $value) {
		
		//$wpdb->delete( "{$wpdb->prefix}mis_productos", array( 'invu' => $value ) );
		//$wpdb->delete( "{$wpdb->prefix}mis_productos", array( 'wp' => $productos[$i] ) );

		echo "Eliminado CON ID el producto {$value} ";
	}
	*/



	$productos = "";

	//PRIMERO 
	$todos_productos = [];

	//CONSULTAREMOS TODOS LOS PRODUCTOS
	$total_pro = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'product' AND post_parent = '0' ");
	foreach ($total_pro as $key) {
		$no_guardar = 0;
		//REVISAMOS LA CATEGORIA DE ESTE PRODUCTO
		$categorias = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}term_relationships WHERE object_id = {$key->ID} ");
		foreach($categorias as $catt){
			//CONSULTAMOS SI ESTA ES UNA CATEGORIA PROIBIDA
			$relacion = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}no_editar WHERE wp = {$catt->term_taxonomy_id} ");
			foreach ($relacion as $rela) {
				//echo "----------------> {$key->ID} <---------------";
				$no_guardar++;
			}
		}

		if($no_guardar == 0){
			//SI LLEGAMOS HASTA AQUI DEBEMOS CONSULTAR EL ID DE INVU DE ESTE PRODUCTO
			//array_push($todos_productos, array("id" => $key->ID));

			array_push($todos_productos, $key->ID);
		}
	}

	$total_invu = [];
	//AHORA CONSULTAMOS TODOS LOS PRODUCTOS DE LA TABLA DE INVU
	$productos_invu = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mis_productos ");
	foreach ($productos_invu as $key) {
		array_push($total_invu, $key->wp);
	}



	$productos = array_diff($todos_productos, $total_invu);

	$mi_resultado = json_encode($productos);
	//echo "<h1> ----> ELIMINAMOS: $mi_resultado </h1>";
	//for ($i=0; $i < count($productos); $i++) { 
	foreach ($productos as $key => $value) {
		
		//$wpdb->delete( "{$wpdb->prefix}posts", array( 'ID' => $productos[$i] ) );
		//$wpdb->delete( "{$wpdb->prefix}mis_productos", array( 'wp' => $productos[$i] ) );
		
		$wpdb->update( 
				"{$wpdb->prefix}posts",
				array( 
					'post_status' 	=> 'trash' 
				), 
				array( 
					'ID' 	=> $value
				)
			);

		echo "Eliminado el producto {$value} ";
	}

	//LUEGO DE BORRAR TODOS LOS PRODUCTOS QUE NO PERTENECEN A INVU DEBEMOS BORRAR TODOS LOS PRODUCTOS QUE 

	wp_die();
}
/*
//actualizar_productos_m
add_action('wp_ajax_actualizar_all_categorias', 'actualizar_all_categorias');
add_action('wp_ajax_nopriv_actualizar_all_categorias', 'actualizar_all_categorias');
// The function that handles the AJAX request
function actualizar_all_categorias() {
	global $wpdb;

	$categorias = $_POST['categorias'];


	//ACTUALIZAMOS TODAS LAS RELACIONES DE LAS CATEGORIAS
	for ($i=0; $i < count($categorias); $i++) { 
		$padre = $categorias[0];
		$hija = $categorias[1];
	}
	//ACTUALIZAMOS TODAS LAS RELACIONES DE LAS CATEGORIAS
	for ($i=0; $i < count($categorias); $i++){ 
		$padre = $categorias[0];
		$hija = $categorias[1];
	}

}
*/

//AQUI TENDREMOS LA INTEGRACION DE LA API
function ajustes_productos_web(){
	global $wpdb;

	echo "<h1>Configuraciones de Invu: Agregar la API </h1>";


	//REVISAMOS EL MATERIAL ENVIADO
	if(isset($_POST['eliminar_api_key'])){
		//echo "<h1>Estamos agregando categorias para que no se eliminen</h1>";

		$api_invu 		= $_POST['nombre_api_key'];
		$api_invu_url 	= $_POST['nombre_api_key_url'];

		//echo "<h1>".count($cat)."</h1>";
		if($api_invu != ""){
			//PRIMERO ELIMINAMOS TODAS LAS CATEGORIAS QUE AGREGAMOS
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}mi_api");

			//LUEGO INSERTAMOS LA API
			$wpdb->insert(
	                "{$wpdb->prefix}mi_api",
	                array(
	                    'nombre'			=> $api_invu,
	                    'numero'				=> 1
	                    )
	                );

			$wpdb->insert(
	                "{$wpdb->prefix}mi_api",
	                array(
	                    'nombre'			=> $api_invu_url,
	                    'numero'				=> 2
	                    )
	                );
		}
		
	}

	//CONSULTAMOS EL API PARA MOSTRARLO AL USUARIO
	$mi_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 1 ");

	//echo "<h1>SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 1 </h1>";

	$url_api = "";
	foreach ($mi_api as $key) {
		$api_key = $key->nombre;
	}

	$url_api = "";
	$mi_url_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 2 ");
	foreach ($mi_url_api as $key) {
		$url_api = $key->nombre;
	}

	?>
		<div class="formularios-approval quarterWidth formularioCertificados">
			<form method="post" action="">
			    <input type="text" name="eliminar_api_key" value="1" required style="display: none;" />

				<!-- SECCION DE LAS CATEGORIAS -->
				<div class="form-group">
					
					<input type="text" name="nombre_api_key" value="<?php echo $api_key; ?>" placeholder="Agregar el nombre de la API" required>
					<input type="text" name="nombre_api_key_url" value="<?php echo $url_api; ?>" placeholder="Prefijo del API" required>
				<!-- BOTON DE ENVIAR -->
				<?php submit_button("Guardar"); ?>
			</form>
		</div>

	<?php

	wp_die();
}

//FUNCION PARA CONSEGUIR API
add_action('wp_ajax_consigue_api', 'consigue_api');
add_action('wp_ajax_nopriv_consigue_api', 'consigue_api');
// The function that handles the AJAX request
function consigue_api() {
	global $wpdb;

	$mi_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 1 ");
	$url_api = "";
	foreach ($mi_api as $key) {
		$api_key = $key->nombre;
	}

	$url_api = "";
	$mi_url_api = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mi_api WHERE numero = 2 ");
	$url_api = "";
	foreach ($mi_url_api as $key) {
		$url_api = $key->nombre;
	}

	echo $api_key;

	wp_die();
}


