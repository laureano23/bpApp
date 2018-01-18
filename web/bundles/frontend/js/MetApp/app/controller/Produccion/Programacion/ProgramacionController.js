Ext.define('MetApp.controller.Produccion.Programacion.ProgramacionController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'Produccion.Programacion.Programacion',
		'Produccion.Programacion.FormProgramacion',
		'MetApp.view.Articulos.ArticuloSearchGrd'
	],
	
	stores: [
		'Produccion.Programacion.ProgramacionStore',
		'Produccion.Programacion.FormulaMoStore',
		'MetApp.store.Produccion.OrdenesTrabajo.OTStore',
		'MetApp.store.Produccion.OrdenesTrabajo.OTStoreEmisor'
	],
	
	refs: [
		{
			ref: 'winProg',
			selector: '#tablaProgramacion'	
		}
	],
	
	init: function(){
		var me = this;
		me.control({
			'#programarPedidos': {
				click: this.TablaProgramacion
			},
			'programacion button[itemId=programar]': {
				click: this.NuevaOT
			},
			'programacion actioncolumn[itemId=formula]': {
				click: this.DetalleFormula
			},			
			'programacion button[itemId=actualizar]': {
				click: this.ActualizarProgramacion
			},
			'programacion actioncolumn[itemId=otImprimir]': {
				click: this.ImprimirOt
			},
			'programacion actioncolumn[itemId=otEliminar]': {
				click: this.EliminarOT
			},
			'programacion grid[itemId=gridMisPedidos]': {
				gridcellchanging: this.CambiarFechaEntrega
			},
								
		});
	},	
	
	CambiarFechaEntrega: function(field, row){
		var selection = field.up('grid').getSelectionModel().getSelection()[0];
		var date = Ext.Date.format(field.getValue(), 'd/m/Y');
		console.log(date);
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_cambiarFechaEntrega'),
			
			params: {
				fecha: date,
				ot: selection.data.otNum
			}
		})
	},
	
	
	TablaProgramacion: function(){
		var programacionWin = Ext.widget('programacion');
		
		//GRILLA DE MIS PENDIENTES
		var grid = programacionWin.queryById('gridMisPendientes');
		var store = grid.getStore();
		
		//GRILLA DE MIS PEDIDOS A OTRAS AREAS
		var grid = programacionWin.queryById('gridMisPedidos');
		var storePedidos = grid.getStore();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ListarOrdenesProg'),
						
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				store.loadRawData(jsonResp.data);
			},
			
			failure: function(resp){
				
			}
		})
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ListarOrdenesProgEmisor'),
					
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				storePedidos.loadRawData(jsonResp.data);
			},
			
			failure: function(resp){
				
			}
		})
		
		
		//SI HAY ALGUN CAMBIO EN LA GRILLA HACEMOS LA LLAMADA AL SERVER
		store.on('update', function(st, rec, op){
			if(rec.data.estado == "Terminada" && rec.data.aprobado == 0){
				Ext.Msg.show({
					title: 'Atención',
					msg: 'El campo cantidad debe ser mayor a 0 para cerrar la OT',
					buttons: Ext.Msg.OK,
	     			icon: Ext.Msg.ALERT,
	     			fn: function(opt){
	     			}
				});
				
			}else{
				Ext.Ajax.request({
					url: Routing.generate('mbp_produccion_ActualizarEstadoOt'),
					
					params: {
						data: Ext.JSON.encode(rec.data)
					},
							
					success: function(resp){
					},
					
					failure: function(resp){
						
					}
				})	
			}				
		});
		
		programacionWin.on('close', function(){
			store.clearListeners();
		});
		
	},
	
	NuevaOT: function(btn){
		Ext.widget('NuevaOTView');
	},
	
	DetalleFormula: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		var win = grid.up('window');
		var form = win.down('form');
		
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_articulos_DBF_verEstructura'),
			
			params: {
				codigo: selection.data.codigo
			},
			
			success: function(resp){				
				jsonResp = Ext.JSON.decode(resp.responseText);
				console.log(jsonResp);
				Ext.Msg.show({
	    			title: 'Formula',
	    			msg: jsonResp.data,
	    			buttons: Ext.Msg.OK,
	    			icon: Ext.Msg.INFO
	    		});
	    		
	    		myMask.hide();
			},
			
			failure: function(resp){
				myMask.hide();
			}
		});
	},
	
	

	ImprimirOt: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		var win = grid.up('window');
		var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
		myMask.show();


		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_generarOt'),
			
			params: {
				ot: selection.data.otNum
			},
			
			success: function(resp){
				var jResp = Ext.JSON.decode(resp.responseText);
				if(jResp.success == true){
					var ruta = Routing.generate('mbp_produccion_verOt');
					window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');		
				}
				myMask.hide();
			},
			
			failure: function(resp){
				myMask.hide();
			}
		})
	},
	
	ActualizarProgramacion: function(btn){
		var programacionWin=btn.up('window');
		
		//GRILLA DE MIS PENDIENTES
		var grid = programacionWin.queryById('gridMisPendientes');
		var store = grid.getStore();
		
		//GRILLA DE MIS PEDIDOS A OTRAS AREAS
		var grid = programacionWin.queryById('gridMisPedidos');
		var storePedidos = grid.getStore();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ListarOrdenesProg'),
						
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				store.loadRawData(jsonResp.data);
			},
			
			failure: function(resp){
				
			}
		})
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ListarOrdenesProgEmisor'),
					
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				storePedidos.loadRawData(jsonResp.data);
			},
			
			failure: function(resp){
				
			}
		})
	},
	
	EliminarOT: function(grid, colIndex, rowIndex){
		Ext.Msg.show({
			title: 'Atencion ',
			msg: "Esta por borrar una OT, desea continuar?",
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.ALERT,
			fn:function(btn){
				if(btn == 'yes'){
					var store = grid.getStore();
					var selection = store.getAt(rowIndex);
					var win = grid.up('window');
					var myMask = new Ext.LoadMask(win, {msg:"Cargando..."});
					myMask.show();
							
					Ext.Ajax.request({
						url: Routing.generate('mbp_produccion_eliminarOT'),
						
						params: {
							ot: selection.data.otNum
						},
						
						success: function(resp){
							Ext.Msg.show({
				    			title: 'Formula',
				    			msg: "La OT fué eliminada correctamente",
				    			buttons: Ext.Msg.OK,
				    			icon: Ext.Msg.INFO
				    		});
				    		
				    		myMask.hide();
				    		store.remove(selection);
						},
						
						failure: function(resp){
							
						}
					})
				}
			}
		});		
	}
});










