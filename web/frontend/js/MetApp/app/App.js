Ext.Loader.setConfig({
	enabled	: true,
	paths	: {
		MetApp	: "App", //<-Es el Nombre del Name Space Principal
	}
});


			

Ext.application({
	requires: [
		'MetApp.resources.overrides.menu', //REQUIERE OVERRIDE DE ESTA CLASE PARA ARREGLAR UN BUG DE CHROME DONDE DESAPARECEN LOS SUB MENUES
		'Ext.grid.plugin.DragDrop',
		'MetApp.resources.ux.ParametersSingleton',
	], 
	
	
	
	controllers: [
		'Security.SecurityController',
		'Calidad.CalidadController',
		'Calidad.CalidadReportesController',
		'Calidad.RecepcionController',
		'Calidad.TrazabilidadController',
		'Clientes.ClientesController',		
		'Proveedores.ProveedoresController',
		'Articulos.ArticulosController',
		'Articulos.FormulasController',
		'Produccion.CalculoRadiadores.CalculoController',
		'Produccion.CalculoRadiadores.ParametrosBrazingController',		
		'Produccion.CalculoRadiadores.CalculoSeleccionController',
		'Produccion.PedidoClientes.PedidoClientesController',
		'Produccion.Programacion.ProgramacionController',
		'Produccion.Programacion.OperacionesController',
		'Produccion.Programacion.FormulasMoController',
		'Produccion.Maquinas.MaquinasController',
		'Produccion.OrdenesTrabajo.OTController',
		'Produccion.OrdenesTrabajo.SeguimientoOTController',
		'Produccion.SoldaduraController',
		'Produccion.ReportesProduccionController',
		'Produccion.PedidoClientes.AutorizarEntregasController',
		'RRHH.PersonalController',
		'RRHH.SindicatosController',
		'RRHH.CategoriasController',
		'RRHH.ConceptosController',
		'RRHH.RecibosController',
		'RRHH.CuentaEmpleadosController',
		'RRHH.LiquidacionEnLoteController',
		'Clientes.CCClientesController',
		'Clientes.TransportesController',
		'Proveedores.CCProveedoresController',
		'Proveedores.FacturaProveedoresController',
		'Proveedores.PagoProveedoresController',
		'Articulos.CentroCostosController',
		'Articulos.FamiliaController',
		'Articulos.SubFamiliaController',
		'Articulos.ParametrosController',
		'Compras.OrdenDeCompraController',
		'Compras.PedidosInternosController',
		'Articulos.ListaDePreciosController',
		'Articulos.RemitosController',
		'Security.UserParamsController',
		'Compras.ReportesCompraController',
		'Reportes.ReportesController',
		'MetApp.controller.Bancos.BancosController',
		'MetApp.controller.Bancos.ReportesController',
		'MetApp.controller.Articulos.EnfriadoresController',
		'MetApp.controller.Articulos.StockController',
		'Utilitarios.UtilitariosController',
		'Utilitarios.HojaDeRutaController',
		'Utilitarios.CITIVentasController',
		'Utilitarios.VerificacionComprobanteController'
	],
	name: 'MetApp',
	appFolder: '../frontend/js/MetApp/app',
	
	launch: function(){			
		Ext.util.Observable.observe(Ext.data.Connection, {			
		    requestexception: function(conn, response, options) {
				var resp = Ext.JSON.decode(response.responseText);
		    	if(!resp.tipo){	
			       	Ext.Msg.show({	
						   scope: this,	 		       				
		    			title: 'Error',
		    			msg: 'Codigo: '+response.status+' '+resp.msg,
		    			buttons: Ext.Msg.OK,
		    			icon: Ext.Msg.WARNING
		    		});
	    		}
	    	},
	    	requestcomplete: function(conn, response, options, eOpts){
	    		var jsonResp = Ext.JSON.decode(response.responseText);
	    		if(jsonResp.msg && !jsonResp.tipo){
	    			Ext.Msg.show({		 			
		    			title: 'Atencion',
		    			msg: jsonResp.msg,
		    			buttons: Ext.Msg.OK,
		    			icon: Ext.Msg.WARNING
	    			});
	    		}
	    	}
	    });
	    
	     // Enable pusher logging - don't include this in production
	    Pusher.logToConsole = false;
	
	    var pusher = new Pusher('afe4f1c54cf0b11142f7', {
	      cluster: 'us2',
	      encrypted: true
	    });
	    
		var channel = pusher.subscribe('my-channel');
		
		
		
		channel.bind('my-event', function(data) {
		    if(msg.sectorReceptor == MetApp.User.name.sector && msg.env==MetApp.User.name.env){
		    	//btnNotificacion.addCls('nuevaNotificacion'); 
		    	Ext.create('widget.uxNotification', {
					title: 'Notification',
					position: 'tr',
					manager: 'instructions',
					cls: 'ux-notification-light',
					autoClose: false,
					slideBackDuration: 500,
					slideInAnimation: 'bounceOut',
					slideBackAnimation: 'easeIn',
					html: msg.message
				}).show();
		    }
		});
	},

	
	
	
	globals: {
		//role: role.res
	}
	
});



