var cat1 = $('#categoria1');
var cat2 = $('#categoria2');
var cat3 = $('#categoria3');
var cat4 = $('#categoria4');

cat1.click(function(){
	controlAsideProductos(cat1);
});

cat2.click(function(){
	controlAsideProductos(cat2);
});

cat3.click(function(){
	controlAsideProductos(cat3);
});

cat4.click(function(){
	controlAsideProductos(cat4);
});

function controlAsideProductos(categoria){
	switch(categoria.attr('id')) {
	    case 'categoria1':
	        cat1.attr('class', 'list-group-item active');
	        cat2.attr('class', 'list-group-item');
	        cat3.attr('class', 'list-group-item');
	        cat4.attr('class', 'list-group-item');
	        consultaCategoria(categoria);
	        break;
	    case 'categoria2':
	        cat1.attr('class', 'list-group-item');
	        cat2.attr('class', 'list-group-item active');
	        cat3.attr('class', 'list-group-item');
	        cat4.attr('class', 'list-group-item');
	        consultaCategoria(categoria);
	        break;
	    case 'categoria3':
	        cat1.attr('class', 'list-group-item');
	        cat2.attr('class', 'list-group-item');
	        cat3.attr('class', 'list-group-item active');
	        cat4.attr('class', 'list-group-item');
	        consultaCategoria(categoria);
	        break;
	    case 'categoria4':
	        cat1.attr('class', 'list-group-item');
	        cat2.attr('class', 'list-group-item');
	        cat3.attr('class', 'list-group-item');
	        cat4.attr('class', 'list-group-item active');
	        consultaCategoria(categoria);
	        break;
	    default:

	}
}

function consultaCategoria(cat){
	$.ajax({
	    // la URL para la petición
	    url : 'respuesta.php',

	    // la información a enviar
	    // (también es posible utilizar una cadena de datos)
	    data : { cat : 1 },
	 
	    // especifica si será una petición POST o GET
	    type : 'GET',
	 
	    // el tipo de información que se espera de respuesta
	    dataType : 'json',
	 
	    // código a ejecutar si la petición es satisfactoria;
	    // la respuesta es pasada como argumento a la función
	    success : function(json) {
	       // $('#contenedorProductos').html(json).appendTo('body');
	       $('#descripcionProducto').html(json.descripcion);
	    },
	 
	    // código a ejecutar si la petición falla;
	    // son pasados como argumentos a la función
	    // el objeto de la petición en crudo y código de estatus de la petición
	    error : function(xhr, status) {
	    	console.log(status);
	    	console.log(xhr);
	        alert('Disculpe, existió un problema');
	    },
	 
	    // código a ejecutar sin importar si la petición falló o no
	    complete : function(xhr, status) {
	       // alert('Petición realizada');
	    }
	});	
}

