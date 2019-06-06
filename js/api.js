var Mi_api = {
	'api_key': jQuery(".api_key").attr("data-api"),
	'url_api': jQuery(".url_api").attr("data-api")
}
			

			/*
**
	FUNCIONES PARA RELACIONAR TODA LA INFORMACION
**
*/
//alert("llegamos a este relacionador");

//PRUEBA PARA ORDENAR ARREGLOS EN JS
var months = ['March', 'Jan', 'Feb', 'Dec'];
months.sort();
console.log(months);

var href = myScript.pluginsUrl + '/relacion-invu-pos/';

function enviarInformacion(informacion, seccion){
	var valorEnviar = {
		"datos": informacion,
		"accion": seccion
	}
	//var url2 = href+"class.relacion-consult.php";
	jQuery.ajax({
		data: valorEnviar,
		type: "POST",
		url: href+"class.relacion-consult.php",
		beforeSend: function(){

		},
		success: function(resultado){
			//jQuery(".categorias").html(resultado);

			return resultado;
		}
	});
}


//AQUI TRAEMOS EL ARREGLO FINAL ORDENADO PARA INSERTAR EN DB
function relacionarEnviado(){
	//alert("click en el boton "+Productos.datosOrdenados.length);
	var valorEnviar = {
		"insertarDB": Productos.datosOrdenados
	}
	//var url2 = href+"class.relacion-consult.php";
	jQuery.ajax({
		data: valorEnviar,
		type: "POST",
		url: href+"class.relacion-consult.php",
		beforeSend: function(){

		},
		success: function(resultado){
			//jQuery(".categorias").html(resultado);

			//return resultado;
			console.log(resultado);
		}
	});
}

jQuery(function(){
	//alert("hola "+href+"class.relacion-consult.php");
});


//ACCION DE CONSULTAR LISTAR CATEGORIAS
function listarCategorias(productos){
	//LLAMAMOS A LAS SUB CATEGORIAS HIJAS
	//ListarCategorias
	jQuery.ajax({
		data: valorEnviar,
		headers: { 
			APIKEY : Mi_api.api_key
		},
		type: "GET",
		url: "https://"+Mi_api.url_api+".invupos.com/invuApiPos/index.php?r=menu/ListarCategorias",
		beforeSend: function(){

		},
		success: function(resultado){
			var categoriasDos = resultado;
			//console.log(resultado);
			//var categoriasDos = enviarInformacion(resultado, "categoriasDos");

			//AHORA CONSULTAREMOS LAS CATEGORIAS PADRES
			categoria(productos, categoriasDos);
		}
	});
}

//AQUI LLAMAMOS A LAS CATEGORIAS PADRES
function categoria(productos, categoriasDos){
	//LLAMAR LAS CATEGORIAS PADRES
	jQuery.ajax({
		data: valorEnviar,
		headers: { 
			APIKEY : Mi_api.api_key
		},
		type: "GET",
		url: "https://"+Mi_api.url_api+".invupos.com/invuApiPos/index.php?r=categoria",
		beforeSend: function(){
			//console.log(Mi_api.api_key+" -- https://"+Mi_api.url_api+".invupos.com/invuApiPos/index.php?r=categoria");
		},
		success: function(resultado){
			//alert(resultado);
			var categorias = resultado;
			console.log(resultado);
			//var categorias = enviarInformacion(resultado, "categorias");

			//YA CON MIS CATEGORIAS PADRES MANDAMOS AL RELACIONADOR
			listarSubcategorias(productos, categoriasDos, categorias);
		}
	});
}

//AQUI LLAMAMOS A LAS RELACIONES ENTRE CATEGORIAS PADRES Y CATEGORIAS HIJOS
function listarSubcategorias(productos, categoriasDos, categorias){
	//LLAMAMOS LA RELACIONES DE LAS CATEGORIAS
	jQuery.ajax({
		data: valorEnviar,
		headers: { 
			APIKEY : Mi_api.api_key
		},
		type: "GET",
		url: "https://"+Mi_api.url_api+".invupos.com/invuApiPos/index.php?r=menu/ListarSubcategorias",
		beforeSend: function(){

		},
		success: function(resultado){
			var relacionados = resultado;
			//console.log(resultado);
			//var relacionados = enviarInformacion(resultado, "relacionados");

			//ENVIAMOS EL RESULTADO AL RELACIONADOR GLOBAL
			relacionaTodo(productos, categoriasDos, categorias, relacionados);
		}
	});
}

//VAMOS A NECESITAR ESTOS PARAMETROS
/*
	nombre
	sku
	hijo
	padre
	precio
	imagen
*/

//FUNCTION LIMPIA STRING API
function stringApi(valor){
	var imagenFinal = valor;

	var cadena = imagenFinal;
	var re = /\+/g;
	var resultado = cadena.replace(re, '-');
	//console.log(resultado);
	imagenFinal = resultado;

	var cadena = imagenFinal;
	var re = / /g;
	var resultado = cadena.replace(re, '-');
	//console.log(resultado);
	imagenFinal = resultado;

	var cadena = imagenFinal;
	var re = /--/g;
	var resultado = cadena.replace(re, '-');
	//console.log(resultado);
	imagenFinal = resultado;

	return imagenFinal;
	//return valor;
}

//CREAREMOS EL OBJETO DE MIS PRODUCTOS
var Productos = {
	datos: [],
	datosOrdenados: [],
	urlImagen: "https://admin.invupos.com/invuPos/images/banner/"
}

/*
jQuery(function(){
	var data = {
		'action': 'consultamos_productos_completos',
		'productos': ''
	};

	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);
		console.log("este llega");
		console.log("----> "+response);

		
		response.map(valores => {
			console.log(valores.post_title);
		});
		
	});
});
*/

//FUNCION QUE DEPURA
function depurador_productos(wordpress1, invu){

	//console.log("llega al depurador <------------------------------; ");
	//console.log(invu);
	//var productos_invu 		= [];
	var productos_borrar 	= [];

	var productos_wp = [];
	var productos_iv = [];

	/*
	ordenados.map(productos => {
		productos_invu.push(productos.id_producto);
	});
	*/

	//RECORREREMOS LOS PRODUCTOS DE WORDPRESS
	/*
	for(var i = 0; i < wordpress1.length; i++){
		if(invu.indexOf(wordpress1[i]) != -1){
		   // element found
		   //alert("Existe en el arreglo");
			console.log("existe en el arreglo ["+wordpress1[i]+"]");
		}else{
			//alert("no existe");
			productos_borrar.push(wordpress1[i]);

			console.log("<<<<<<<<<<<<<<<<<<<<<<< Este producto no existe "+wordpress1[i]);
		}
	}
	*/
	wordpress1 = JSON.parse(wordpress1);
	invu = JSON.parse(invu);

	wordpress1.map(valor => {
		//console.log("mi valor -> "+valor.id);

		/*
		if(invu.indexOf(wordpress1[i]) != -1){
		   // element found
		   //alert("Existe en el arreglo");
			console.log("existe en el arreglo ["+wordpress1[i]+"]");
		}else{
			//alert("no existe");
			productos_borrar.push(wordpress1[i]);

			console.log("<<<<<<<<<<<<<<<<<<<<<<< Este producto no existe "+wordpress1[i]);
		}
		*/

		productos_wp.push(valor.id);
	});

	invu.map(valor => {
		productos_iv.push(valor.id);
	});

	for(var i = 0;i < productos_wp.length;i++) { 
		//console.log("si pasa papa --->"+productos_wp[i]);
		if(productos_iv.indexOf(productos_wp[i]) != -1){
		   // element found
		   //alert("Existe en el arreglo");
			console.log("existe en el arreglo ["+productos_wp[i]+"]");
		}else{
			//alert("no existe");
			productos_borrar.push(productos_wp[i]);

			console.log("<<<<<<<<<<<<<<<<<<<<<<< Este producto no existe "+productos_wp[i]);
		}
	}
	

	
	if(productos_borrar.length > 0){
		var data = {
			'action': 'elimina_productos_malos',
			'productos': productos_borrar
		};

		jQuery.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
			console.log("este llega");
			console.log("----> "+response);			
		});
	}
	
	

}
//alert("hola");
//ESTA FUNCION ES LA QUE RELACIONA TODO
function relacionaTodo(productos, categoriasDos, categorias, relacionados){
	//var productos = new Productos();
	var productos_off = [];

	//EN ESTA SECCION BUSCAREMOS AGREGAR VALORES A ESTOS 
	productos.data.map(valor => {

		//console.log(valor.nombre+" "+valor.precioSugerido);

		//ID DEL PRODUCTO
		var id_producto = valor.idmenu;

		//NOMBRE PRODUCTO
		var nombre = valor.nombre;
		//IMAGEN DEL PRODUCTO
		var imagen = valor.imagen;
		//PRECIO DEL PRODUCTO
		var precio = valor.precioSugerido;
		//CATEGORIA HIJA
		var idHija = valor.idcategoriamenu;

		//descripcion 
		var descripcion = valor.descripcion;

		//CATEGORIA PADRE
		var idPadre = "";

		//SKU
		var skus = valor.codigo;

		//STOCK
		var stock = valor.checkStock;

		//VENTA ONLINE
		var venta = valor.venta_online;

		//NOMBRE CATEGORIA HIJA
		var catHija = "";
		categoriasDos.data.map(categoria => {
			if(categoria.idcategoriamenu == idHija){
				//GUARDAMOS EL NOMBRE DE LA CATEGORIA EN LA VARIABLE
				catHija = categoria.nombremenu;
				//break;
			}
		});

		//BUSCAMOS LA RELACION ENTRE LA CATEGORIA HIJA Y LA PADRE
		var relaHija = "";
		var catPadre = "";

		relacionados.data.map(rela => {
			rela.categorias.map(idCat => {
				if(idHija == idCat){
					//SI MI CATEGORIA HIJA ESTA RELACIONADA CON LA CATEGORIA PADRE GUARDAMOS SU NOMBRE
					//console.log(idCat);
					idPadre 	= rela.id;
					catPadre 	= rela.descripcion;

					//break;
				}
			});
		});

		//MOMENTANEO
		var momentaneo = {
			id_producto: id_producto,
			nombre: nombre,
			descripcion: descripcion,
			imagen: imagen,
			precio:	precio,
			idHija: idHija,
			catHija: catHija,
			idPadre: idPadre,
			catPadre: catPadre,
			skus: skus,
			stock: stock
		};

		//REVISAMOS TODAS LAS CATEGORIAS
		console.log("categoria padre: "+catPadre+" ("+idPadre+") categoria hija: "+catHija+" ("+idHija+") ");

		//GUARDAMOS TODO ESTO EN NUESTRO ARREGLO PRINCIPAL
		if(venta === true){
			Productos.datos.push(momentaneo);	
		}else{
			productos_off.push(id_producto);
		}
		

		//VACIAMOS EL ARREGLO
		momentaneo = [];
		//console.log("nombre "+nombre);

	});

	//ORDENANDO LAS CATEGORIAS
	var padresC = [];// CREAMOS EL ARREGLO QUE VAMOS A ORDENAR
	relacionados.data.map(rela => {
		padresC.push(rela.descripcion);
	});

	var alfabeticoC = padresC.sort();//YA AQUI ESTA ORDENADA LA INFORMACION DE LAS CATEGORIAS

	//RECORREMOS EL ARREGLO YA ORDENADO PARA REASIGNAR LOS PRODUCTOS A LOS ARREGLOS ARREGLADOS
	for(var i = 0; i <= alfabeticoC.length; i++){
		//console.log("---------> "+alfabeticoC[i]);

		//AQUI RECORREMOS EL ARREGLO PARA PODER GUARDAR LA DATA BIEN
		Productos.datos.map(productos => {
			if(alfabeticoC[i] == productos.catPadre){//VALIDAMOS QUE EL NOMBRE DE LA CATEGORIA PADRE ORDENADA SEA LA MISMA QUE LA CATEGORIA DEL PRODUCTO
				//GUARDAMOS ESTE PRODUCTO EN EL ARREGLO
				Productos.datosOrdenados.push(productos);
			}
		});
	}

	console.log("Este es el nuevo <-------------");

	console.log(Productos.datos);

	var id_ordenador = [];
	Productos.datos.map(productos => {
		id_ordenador.push(productos.id_producto);
	});

	//CONSULTAMOS LOS ID DE WORDPRESS DE LOS PRODUCTOS DE INVU
	//ANTES DE SEGUIR CONSULTAREMOS TODOS LOS PRODUCTOS DE LA WEB
	var data = {
		'action': 'total_productos_wp',
		'productos': id_ordenador
	};

	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ');

		var id_ordenador = response;

		//console.log(response);
		//ANTES DE SEGUIR CONSULTAREMOS TODOS LOS PRODUCTOS DE LA WEB
		var data2 = {
			'action': 'total_productos_wp',
			'productos': ""
		};

		jQuery.post(ajaxurl, data2, function(respuesta) {
			//alert('Got this from the server: ' + response);

			//console.log(response);

			//ENVIAMOS AL DEPURADOR
			//depurador_productos(respuesta, id_ordenador);

			console.log("-------------------------------------------------------------");

			console.log(respuesta);
			console.log("-------------------------------------------------------------");
			console.log(id_ordenador);
		});
		//ENVIAMOS AL DEPURADOR
		//depurador_productos(response, Productos.datosOrdenados);
	});

	//MANDAMOS A ELIMINAR LOS PRODUCTOS QUE ESTAN OCULTOS EN INVU
	var variables_eli = {
		'action': 'eliminar_productos_n_web',
		'productos': productos_off
	}

	jQuery.post(ajaxurl, variables_eli, function(response) {
		console.log(">>>>>>>>>>>>>>>>>>>>>>>>>>Eliminamos productos no web");
	});

	//console.log(alfabeticoC);

	//console.log("Recorremos el nuevo opjeto");

	//DATOS
	//copiarFormulario
	var estrugtura = jQuery(".copiarFormulario").html();

	jQuery(".copiarFormulario").html("");

	var urlImagen = "https://admin.invupos.com/invuPos/images/banner/";

	var posicion = 0;
	//Productos.datos.map(productos => {
	Productos.datosOrdenados.map(productos => {
		posicion++;
		if(posicion < 50){
			//Agregamos los productos
			jQuery(".productosImportar").append("<tr class='miPosicion"+posicion+"'>"+estrugtura+"</tr>");

			jQuery(".miPosicion"+posicion+" .nombre").text(productos.nombre);
			if(productos.imagen != ""){
				//TENEMOS QUE ARREGLAR LA IMAGEN
				var imagenFinal = productos.imagen;
		
				//imagenFinal = stringApi(productos.imagen);	
				imagenFinal = productos.imagen;	

				productos.imagen = imagenFinal;			

				jQuery(".miPosicion"+posicion+" .imagen img").attr("src", urlImagen+productos.imagen);
			}
			jQuery(".miPosicion"+posicion+" .categoria").text(productos.catPadre+" -> "+productos.catHija);
			jQuery(".miPosicion"+posicion+" .precio").text(productos.precio);
			jQuery(".miPosicion"+posicion+" .sku").text(productos.skus);


		}
		//console.log(productos.nombre+" "+productos.precio);
	});

	//AL FINALIZAR AGREGAMOS BOTON PARA IMPORTAR LA INFORMACION
	jQuery(".categorias").append("<buttom class='button-primary' onClick='relacionarEnviado();'>Importar ("+Productos.datosOrdenados.length+")</buttom>");
	console.log("llegamos para poder importar");


	//AL FINAL MANDAMOS A INSERTAR
	var data = {
		'action': 'my_action',
		'productos': Productos.datosOrdenados
	};

	deunoUno(Productos.datosOrdenados, 1);//COMENTADO SOLO PARA REALIZAR PRUEBAS

	/*
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);

		console.log(response);
	});
	*/
		
	console.log("es el final");

	//sigueEnviando(Productos.datosOrdenados);
}

console.log("lo haremos de 1 en 1 2");

function jejejeje(){

}
function deunoUno(productos, posicion){

	console.log("pasa por de 15 en 15 "+posicion);

	//PRODUCTO A ENVIAR
	var cualProducto = [];

	var cual = 0;
	var posicion_nueva = parseFloat(posicion) + 15;

	Productos.datosOrdenados.map(productos => {
		cual++;

		//if(cual == posicion){
		if(cual >= posicion && cual <= posicion_nueva){

			var momentaneo = {
				id_producto: productos.id_producto,
				nombre: productos.nombre,
				descripcion: productos.descripcion,
				imagen: productos.imagen,
				precio:	productos.precio,
				idHija: productos.idHija,
				catHija: productos.catHija,
				idPadre: productos.idPadre,
				catPadre: productos.catPadre,
				skus: productos.skus,
				stock: productos.stock
			};

			//GUARDAMOS TODO ESTO EN NUESTRO ARREGLO PRINCIPAL
			cualProducto.push(momentaneo);

			//VACIAMOS EL ARREGLO
			momentaneo = [];

			//cualProducto = momentaneo;

			console.log("el producto es :"+ productos.nombre);
		}
	});

	var data = {
		'action': 'my_action',
		'productos': cualProducto
	};

	var faltan = 1;

	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);

		console.log(response);
		if(posicion <= Productos.datosOrdenados.length){
			posicion++;

			jQuery(".mis_resultados_eas").prepend("<div class='resultados_j'>"+response+"</div>");

			//ACTUALIZAMOS LA CUENTA QUE FALTA
			var son = Productos.datosOrdenados.length;

			faltan = son - posicion_nueva;

			jQuery(".faltantes").text(faltan+" de: "+son);

			//deunoUno(Productos.datosOrdenados, posicion);
			deunoUno(Productos.datosOrdenados, posicion_nueva);

			
		}
	});

	// ES FALTANTE
	if(posicion >= Productos.datosOrdenados.length){
		//SI YA LOS PRODUCTOS ESTAN COMPLETOS PROCEDEMOS A BORRAR TODOS LOS PRODUCTOS QUE NO ESTEN EN INVU
		console.log("Pasamos por el faltante");

		var data = {
			'action': 'actualizar_productos_m',
			//'productos': Productos.datos,
			'productos': Productos.datos
		};


		var mis_categorias = [];

		jQuery.post(ajaxurl, data, function(response) {
			console.log("Se borraron los productos de mas ("+response+")");

			/*

			//POR ULTIMO DEBEMOS ENVIAR UN VALIDADOR DE CATEGORIAS
			Productos.datosOrdenados.map(productos => {
				var momentaneo = {
					idHija: productos.idHija,
					idPadre: productos.idPadre
				}

				mis_categorias.push(momentaneo);
				momentaneo = [];
			});

			//DESPUES DE HACER TODO ESTE ARREGLO ENVIAMOS AL ORDENADOR DE CATEGORIAS
			var ordenaras = {
				'action': 'actualizar_all_categorias',
				'categorias': mis_categorias
			}

			jQuery.post(ajaxurl, ordenaras, function(response_cat) {
				console.log("------------> actualizamos todas las categorias <-------------");
			});
			*/
			
		});
	}
}

console.log("final demo jjjj 2234");

console.log("pasa pasa");

function sigueEnviando(productos){

	console.log("--------------> pasamos");
	
	numero = 0;
	var arreglos = [];

	//ARREGLO QUE ENVIAREMOS
	productos.map(productoss => {
		numero++;

		arreglos.push(productoss);

		if (numero == 150){
			arreglos = [];
			numero = 0;

			arreglos.push(productoss);
			
		}else{
			

			//setTimeout(function(){
				//

				var data = {
					'action': 'my_action',
					'productos': arreglos
				};

				jQuery.post(ajaxurl, data, function(response) {
					//alert('Got this from the server: ' + response);

					console.log(response);

					jQuery(".mis_resultados_eas").preppend("<div class='resultados_j'>"+response+"</div>");
				});

				console.log("----> Pasamos de nuevo "+numero);
			//}, 5000);
		}
	});


	console.log('Culminamos la importacion ');
}
	
	console.log("sistema 2.0");