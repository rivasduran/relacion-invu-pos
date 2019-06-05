---- ACCIONES DE ESTE PLUGIN ----
Developer: Joser Rivas

->	Consultar en la api los productos y armar un arreglo de cada uno, con sus categorias, precios y metricas.
->	Consultar en db si estos productos existen, si si existen solo modifican los valores, Si no existen insertar.
->	Posibilidad de importar solo categorias especificas o eliminar no permitir que siertas categorias se inserten.
<!-- OTROS POSIBLES ACCIONES -->
->	Eventualmente poder agregar los parametros de la API que se desea consultar
-> 	En un futuro poder tener un organizador de RETURN, para poder asignar de manera manual en la instalacion los parametros que el plugin tomara para hacer el inserter tomando en cuenta que la API utilizada no sea Invu Pos


->	Este plugin fue creado para poder consultar en la API de Invu Pos y poder relacionar automaticamente los productos de este sistema en la tienda online.



//DATOS QUE VAMOS UTILIZANDO A LO LARGO DEL PROCESO
--->	wp_posts

ID						=>	9
post_author				=>	1
post_date				=>	'2018-08-15 15:42:00'
post_date_gmt			=>	'2018-08-15 15:42:00'	
post_content			=>	''
post_title				=>	'demo joser'
post_excerpt			=>	''
post_status				=>	'publish'
comment_status			=>	'open'
ping_status				=>	'closed'
post_password			=>	''
post_name				=>	'demo-joser'
to_ping					=>	''
pinged					=>	''
post_modified			=>	'2018-08-15 15:53:53'
post_modified_gmt		=>	'2018-08-15 15:53:53'
post_content_filtered	=>	''
post_parent				=>	0
guid					=>	'http://localhost/demo-woocommerce/?post_type=product&#038;p=9'
menu_order				=>	0
post_type				=>	'product'
post_mime_type			=>	''
comment_count			=>	0

//

//DATOS DE LAS METAS
--->	wp_postmeta

->	meta_id
->	post_id
->	meta_key
->	meta_value

//EN ESTE CASO DEBEMOS GUARDAR LAS DISTINTAS METAS DEL POST ID
post_id	->	9	->	_wc_review_count		->	0
post_id	->	9	->	_wc_rating_count		->	a:0:{}
post_id	->	9	->	_wc_average_rating		->	0
post_id	->	9	->	_edit_last				->	1
post_id	->	9	->	_edit_lock				->	1534348434:1
post_id	->	9	->	_sku					->	sskkss
post_id	->	9	->	_regular_price			->	10
post_id	->	9	->	_sale_price				->	''
post_id	->	9	->	_sale_price_dates_from	->	''
post_id	->	9	->	_sale_price_dates_to	->	''
post_id	->	9	->	total_sales				->	0
post_id	->	9	->	_tax_status				->	taxable
post_id	->	9	->	_tax_class				->	''
post_id	->	9	->	_manage_stock			->	yes
post_id	->	9	->	_backorders				->	no
post_id	->	9	->	_sold_individually		->	no
post_id	->	9	->	_weight					->	''
post_id	->	9	->	_length					->	''
post_id	->	9	->	_width					->	''
post_id	->	9	->	_height					->	''
post_id	->	9	->	_upsell_ids				->	a:0:{}
post_id	->	9	->	_crosssell_ids			->	a:0:{}
post_id	->	9	->	_purchase_note			->	''
post_id	->	9	->	_default_attributes		->	a:0:{}
post_id	->	9	->	_virtual				->	no
post_id	->	9	->	_downloadable			->	no
post_id	->	9	->	_product_image_gallery	->	''
post_id	->	9	->	_download_limit			->	-1
post_id	->	9	->	_download_expiry		->	-1
post_id	->	9	->	_stock					->	5
post_id	->	9	->	_stock_status			->	instock
post_id	->	9	->	_product_version		->	3.4.4
post_id	->	9	->	_price					->	10


//AQUI TENEMOS LAS CATEGORIAS DE LOS PRODUCTOS PARA PODER SER RELACIONADAS
--->	wp_terms

term_id		->	16
name 		->	nueva
slug		->	nueva
term_group	->	0

//AQUI TENEMOS LAS RELACIONES ENTRE PRODUCTOS Y categorias
---> wp_term_relationships

object_id			->	9
term_taxonomy_id	->	17
term_order			->	0


//AQUI TENEMOS LA RELACION ENTRE CATEGORIAS Y SUB CATEGORIAS
---> wp_term_taxonomy

//CATEGORIA PADRE:

term_taxonomy_id	->	16
term_id				->	16
taxonomy			->	product_cat
description			->	''
parent				->	0
count				->	1


//CATEGORIA HIJO

term_taxonomy_id	->	17	
term_id				->	17	
taxonomy			->	product_cat
description			->	''
parent				->	16
count				->	1



//NOS QUEDAMOS EN RELACION-INVU-POS.PHP EN LA LINEA 247