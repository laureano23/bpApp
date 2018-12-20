$(function () {	

	$("#tipo").change(function(){
		var categoria = $("#tipo").val();
		$.ajax({
		    // la URL para la petición
		    url : Routing.generate("listarAplicaciones", {tipo: categoria}),
		    
		    type : 'GET',

		    dataType : 'json',
		 
		    success : function(resp) {
		       $('#aplicacion').empty();
		       $('#tablaProductos > tbody').empty();

		       $("#aplicacion").append($('<option>', { 
			        value: 'all',
			        text : '---Todos---'
			    }));

		       //COMPLETA EL SELECT DE TIPOS DE RADIADORES
				$.each(resp.aplicaciones, function (i, item) {
				    $("#aplicacion").append($('<option>', { 
				        value: item.id,
				        text : item.aplicacion,
				    }));
				});

				//COMPLETA LA TABLA
				var pathImg = resp.pathImg;

				$.each(resp.lista, function(i, item){
					completarTabla(i, item, pathImg);
				});

				previewImage();
		    },
		 
		    // el objeto de la petición en crudo y código de estatus de la petición
		    error : function(xhr, status) {

		    },		 
		});	
	});

	$("#aplicacion").change(function(){
		var aplicacion = $("#aplicacion").val();
		var tipo = $("#tipo").val();
		$.ajax({
		    // la URL para la petición
		    url : Routing.generate("listarMarcasAplicacion", {aplicacion: aplicacion, tipo: tipo}),
		    
		    type : 'GET',

		    dataType : 'json',
		 
		    success : function(resp) {
		       $('#marca').empty();
		       $('#tablaProductos > tbody').empty();

		       $("#marca").append($('<option>', { 
			        value: 'all',
			        text : '---Todos---'
			    }));
				$.each(resp.marcas, function (i, item) {
				    $("#marca").append($('<option>', { 
				        value: item.id,
				        text : item.marca,
				    }));
				});

				//COMPLETA LA TABLA
				var pathImg = resp.pathImg;
				$.each(resp.lista, function(i, item){
					completarTabla(i, item, pathImg);
				});

				previewImage();
				
		    },
		 
		    // el objeto de la petición en crudo y código de estatus de la petición
		    error : function(xhr, status) {

		    },		 
		});	
	});

	$("#marca").change(function(){
		var aplicacion = $("#aplicacion").val();
		var tipo = $("#tipo").val();
		var marca = $("#marca").val();
		$.ajax({
		    // la URL para la petición
		    url : Routing.generate("filtrarPorMarca", {aplicacion: aplicacion, tipo: tipo, marca: marca}),
		    
		    type : 'GET',

		    dataType : 'json',
		 
		    success : function(resp) {
		       $('#tablaProductos > tbody').empty();

				//COMPLETA LA TABLA
				var pathImg = resp.pathImg;
				$.each(resp.lista, function(i, item){
					completarTabla(i, item, pathImg);
				});

				previewImage();
				
		    },
		 
		    // el objeto de la petición en crudo y código de estatus de la petición
		    error : function(xhr, status) {
		    	//console.log(status);
		    	//console.log(xhr);
		    },		 
		});	
	});

	
    function previewImage(){
	    $("th > a").on("click", function() {
		   $('#imagepreview').attr('src', $(this).attr('value')); // here asign the image to the modal when the user click the enlarge link
		   $('#myModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
		});	
	}
	
	

    function completarTabla(i, item, pathImg){
    	$("#tablaProductos").append("<tr><th>"+item.codigo+"</th><th>"+item.descripcion+"</th><th>"+item.oem+"</th><th>"+item.marca+"</th><th><a href='#' value="+pathImg+item.imagen+"><span class='glyphicon glyphicon-camera'></span></a></th></tr>");
    }



});