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
		'MetApp.resources.ux.ParametersSingleton'
	], 
	
	controllers: [
		'Security.SecurityController',
		'Calidad.CalidadController',
		'Calidad.CalidadReportesController',
		'Clientes.ClientesController',
		'Proveedores.ProveedoresController',
		'Articulos.ArticulosController',
		'Articulos.FormulasController',
		'Produccion.CalculoRadiadores.CalculoController',
		'Produccion.CalculoRadiadores.OtPanelesController',
		'Produccion.CalculoRadiadores.ParametrosBrazingController',		
		'Produccion.CalculoRadiadores.CalculoSeleccionController',
		'Produccion.PedidoClientes.PedidoClientesController',
		'Produccion.Programacion.ProgramacionController',
		'Produccion.Programacion.OperacionesController',
		'Produccion.Programacion.FormulasMoController',
		'Produccion.Maquinas.MaquinasController',
		'RRHH.PersonalController',
		'RRHH.SindicatosController',
		'RRHH.CategoriasController',
		'RRHH.ConceptosController',
		'MetApp.controller.RRHH.RecibosController',
		'MetApp.controller.RRHH.CuentaEmpleadosController',
		'MetApp.controller.Clientes.CCClientesController',
		'MetApp.controller.Proveedores.CCProveedoresController',
		'MetApp.controller.Proveedores.FacturaProveedoresController',
		'MetApp.controller.Proveedores.PagoProveedoresController',
		'MetApp.controller.Articulos.CentroCostosController',
		'MetApp.controller.Articulos.FamiliaController',
		'MetApp.controller.Articulos.SubFamiliaController',
		'MetApp.controller.Compras.OrdenDeCompraController',
		'MetApp.controller.Articulos.ListaDePreciosController'
	],
	name: 'MetApp',
	appFolder: '../bundles/frontend/js/App',
	
	launch: function(){	
		Ext.util.Observable.observe(Ext.data.Connection, {			
		    requestexception: function(conn, response, options) {
		    	var resp = Ext.JSON.decode(response.responseText);
		    	if(!resp.tipo){	
			       	Ext.Msg.show({		 		       				
		    			title: 'Error',
		    			msg: 'Codigo: '+response.status+' '+resp.msg,
		    			buttons: Ext.Msg.OK,
		    			icon: Ext.Msg.WARNING
		    		});
	    		}
	    	},
	    	requestcomplete: function(conn, response, options, eOpts){
	    		var jsonResp = Ext.JSON.decode(response.responseText);
	    		if(jsonResp.msg){
	    			Ext.Msg.show({		 			
		    			title: 'Atencion',
		    			msg: jsonResp.msg,
		    			buttons: Ext.Msg.OK,
		    			icon: Ext.Msg.WARNING
	    			});
	    		}
	    	}
	    });
	    
	    /* CARGO DESDE UNA CLASE SINGLETON AUXILIAR LOS PARAMETROS DE FACTURACION */
	    Ext.Ajax.request({
			url: Routing.generate('mbp_finanzas_initParams'),
			
			success: function(resp){
				jsonResp = Ext.JSON.decode(resp.responseText)
				MetApp.resources.ux.ParametersSingleton.setIva(jsonResp.data.iva);
				MetApp.resources.ux.ParametersSingleton.setDolarOficial(jsonResp.data.dolarOficial);
			}
		});
	},
	
	globals: {
		//role: role.res
	}
	
});



