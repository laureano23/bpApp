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
		'RRHH.PersonalController',
		'RRHH.SindicatosController',
		'RRHH.CategoriasController',
		'RRHH.ConceptosController',
		'RRHH.RecibosController',
		'RRHH.CuentaEmpleadosController',
		'RRHH.LiquidacionEnLoteController',
		'Clientes.CCClientesController',
		'Proveedores.CCProveedoresController',
		'Proveedores.FacturaProveedoresController',
		'Proveedores.PagoProveedoresController',
		'Articulos.CentroCostosController',
		'Articulos.FamiliaController',
		'Articulos.SubFamiliaController',
		'Compras.OrdenDeCompraController',
		'Articulos.ListaDePreciosController',
		'Articulos.RemitosController',
		'Security.UserParamsController',
		'Compras.ReportesCompraController',
		'Reportes.ReportesController',
		'MetApp.controller.Bancos.BancosController',
		'MetApp.controller.Articulos.EnfriadoresController'
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
	},
	
	globals: {
		//role: role.res
	}
	
});



