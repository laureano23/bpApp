Ext.define('MetApp.controller.Articulos.StockController',{
	extend: 'Ext.app.Controller',	
	
	views: [
		'MetApp.view.Articulos.Stock.EntradaSalidaArticulos',	
		'MetApp.view.Proveedores.SearchGridProveedores',
		'MetApp.view.Clientes.SearchGridClientes',
		'MetApp.view.Articulos.WinArticuloSearch',
		'MetApp.view.Compras.PendienteArticuloComprasView',
		'MetApp.view.Articulos.Stock.ListadoIngresosView'
		],
		
	stores: [
		'MetApp.store.Articulos.ConceptosEntradaStore',
		'MetApp.store.Articulos.DepositoArticulosStore'
	],
	
	refs: [{
	    ref: 'EntradaSalidaArticulos',
	    selector: 'EntradaSalidaArticulos' // your xtype
	}],
	
	init: function(){
		var me = this;
		me.control({
				'#entradaSalidaStock': {
					click: this.addEntradaSalidaWin
				},
				'EntradaSalidaArticulos': {
					beforeclose: this.ListenerClose
				},
				'EntradaSalidaArticulos button[itemId=buscarOrigen]': {
					click: this.BuscarOrigen
				},
				'EntradaSalidaArticulos radiogroup[itemId=origen]': {
					change: this.ActualizarOrigen
				},
				'EntradaSalidaArticulos button[itemId=buscarArticulo]': {
					click: this.BuscarArticulo
				},
				'EntradaSalidaArticulos button[itemId=insert]': {
					click: this.InsertarArticulo
				},
				'EntradaSalidaArticulos textfield[itemId=oc]': {
					focus: this.BuscarOC
				},
				'PendienteArticuloComprasView button[itemId=ok]': {
					click: this.AgregarOC
				},
				'EntradaSalidaArticulos button[itemId=guardar]': {
					click: this.GuardarMovimiento
				},
				'EntradaSalidaArticulos actioncolumn[itemId=editar]': {
					click: this.EditarItem
				},
				'EntradaSalidaArticulos actioncolumn[itemId=eliminar]': {
					click: this.EliminarItem
				},
				'viewport menuitem[itemId=listadoIngresos]': {
					click: this.ListadoIngresos
				},
				'ListadoIngresosView button[itemId=buscarOrigen]': {
					click: this.BuscarOrigenStock
				},
				'ListadoIngresosView actioncolumn[itemId=imprimir]': {
					click: this.ImprimirEtiqueta
				},
		});		
	},
	
	EliminarItem: function(grid, colIndex, rowIndex){
		var win = grid.up('window');
		var form = win.queryById('formArticulo');
		var store = grid.getStore();
		var selection = store.getAt(rowIndex);
		store.remove(selection);
	},
	
	EditarItem: function(grid, colIndex, rowIndex){
		var win = grid.up('window');
		var form = win.queryById('formArticulo');
		var store = grid.getStore();
		var selection = store.getAt(rowIndex);
		
		form.getForm().setValues(selection.data);
		store.remove(selection);
	},
	
	ImprimirEtiqueta: function(grid, colIndex, rowIndex){
		var selection = grid.getStore().getAt(rowIndex);
		
		var myMask = new Ext.LoadMask(grid, {msg:"Cargando..."});
		myMask.show();
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_formulas_etiquetaIngresoMaterial'),
			
			params: {
				id: selection.data.id
			},
			
			success: function(resp){
				var ruta = Routing.generate('mbp_formulas_etiquetaIngresoMaterial_pdf');						
				window.open(ruta, 'location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				
				myMask.hide();
			},
			
			failure: function(resp){
				myMask.hide();
			}
		});
	},
	
	BuscarOrigenStock: function(btn){
		var win = btn.up('window');
		var values = win.down('form').getForm().getValues();
		
		var store;
		var winSearch;
		if(values.origen[0] == "proveedor"){
			winSearch = Ext.widget('ProveedoresSearchGrid');
			store = winSearch.down('grid').getStore();
		}else{
			winSearch = Ext.widget('clientesSearchGrid');
			store = winSearch.down('grid').getStore();
		}
		
		store.load();
		var btnOk = winSearch.down('button');
		btnOk.on('click', function(){
			var selection = winSearch.down('grid').getSelectionModel().getSelection();
			selection = selection[0];
			
			win.queryById('origen').setValue(selection.data.rsocial);
			win.queryById('idOrigen').setValue(selection.data.id);
			
			var form = win.down('form');
			
			form.submit({
				url: Routing.generate('mbp_articulos_listarIngresos'),
				
				params: {
					origen2: values.origen[0],
					idOrigen: values.idOrigen
				},
				
				success: function(form, action){
					var jsonResp = Ext.JSON.decode(action.response.responseText);
					var storeIngresos = win.down('grid').getStore();
					storeIngresos.loadRawData(jsonResp.data);
					winSearch.close();
				},
				
				failure: function(resp){
					
				}
			});
		});
	},
	
	ListadoIngresos: function(btn){
		Ext.widget('ListadoIngresosView'); 
	},
	
	ListenerClose: function(win, cond){
		var store = win.down('grid').getStore();
		if(store.getCount() != 0){
			Ext.Msg.show({
				 title:'Atención',
			     msg: 'Tiene registros sin guardar, desea salir de todas formas?',
			     buttons: Ext.Msg.YESNO,
			     icon: Ext.Msg.QUESTION,
			     fn: function(resp){
	     			if(resp == 'yes'){
	     				store.removeAll();
	     				win.close(true);			
	     			}
	     		}
			});
			return false;
		}
		
	},
	
	addEntradaSalidaWin: function(btn){
		var win = Ext.widget('EntradaSalidaArticulos');
		var conceptoStore = win.queryById('concepto').getStore();
		conceptoStore.removeAll();		
	},
	
	BuscarOrigen: function(btn){
		var win = btn.up('window');
		
		var origen = win.queryById('origen').getValue();
		
		var fuente;
		if(origen.origen == "proveedor"){
			fuente = Ext.widget('ProveedoresSearchGrid');
			fuente.down('grid').getStore().load();
		}else{
			fuente = Ext.widget('clientesSearchGrid');
			fuente.down('grid').getStore().load();	
		}
		
		fuente.down('button').on('click', function(btn){
			var selection = fuente.down('grid').getSelectionModel().getSelection()[0];
			win.queryById('idOrigen').setValue(selection.data.id);
			win.queryById('fuente').setValue(selection.data.rsocial);
			fuente.close();
			
			win.queryById('comprobante').focus('', 10);
		});
	},
	
	ActualizarOrigen: function(btn){
		var win = btn.up('window');
		
		win.queryById('idOrigen').reset();
		win.queryById('fuente').reset();
	},
	
	BuscarArticulo: function(btn){
		var winStock = btn.up('window');
		var win = Ext.widget('winarticulosearch');
		
		win.down('button').on('click', function(btn){
			var selection = win.down('grid').getSelectionModel().getSelection()[0];
			var form = winStock.queryById('formArticulo');
			form.loadRecord(selection);
			win.close();
			
			if(selection.data.codigo == 'ZZZ'){
				winStock.queryById('descripcion').focus('', 10);
			}else{
				winStock.queryById('cantidad').focus('', 10);
			}
			
		});		
		
	},
	
	InsertarArticulo: function(btn){
		var winStock = btn.up('window');
		var form = winStock.queryById('formArticulo');
		if(form.isValid()){
			var values = form.getForm().getValues();
			var model = Ext.create('MetApp.model.Compras.OrdenCompraModel');
			model.set(values);
			var store = winStock.down('grid').getStore();
			
			store.add(model);
			form.getForm().reset();	
			form.queryById('descripcion').setReadOnly(true);
		}else{
			form.getForm().markInvalid();
		} 
	},
	
	BuscarOC: function(btn){
		var winStock = btn.up('window');
		var win = Ext.widget('PendienteArticuloComprasView');
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_articulos_pendientesDeIngreso'),
			
			params: {
				codigo: winStock.queryById('codigo').getValue()
			},
			
			success: function(resp){
				var jsonResp = Ext.JSON.decode(resp.responseText);
				var store = win.down('grid').getStore();
				
				store.loadRawData(jsonResp.data);
			}
		});
	},
	
	AgregarOC: function(btn){
		var me = this;
		var winPendientes = btn.up('window');
		var selection = winPendientes.down('grid').getSelectionModel().getSelection()[0];
		
		var winStock = me.getEntradaSalidaArticulos();
		
		winStock.queryById('oc').setValue(selection.data.idOc);
		winPendientes.close();
	},
	
	GuardarMovimiento: function(btn){
		var win = btn.up('window');
		var form = win.queryById('formGral');
		var valuesGral = form.getForm().getValues();
		var grid = win.down('grid');
		var store = grid.getStore();
		
		var data = new Array(); 
		var i = 0;
		
		store.each(function(rec){
			data[i] = rec.getData();
			i++;	
		});
		
				
		if(form.isValid()){
			Ext.Ajax.request({
				url: Routing.generate('mbp_articulos_nuevoMovArt'),
				
				params: {
					data: Ext.JSON.encode(valuesGral),
					articulos: Ext.JSON.encode(data)
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						form.getForm().reset();
						store.removeAll();
					} 
				},
				
				failure: function(resp){
					Ext.Msg.show({
						 title:'Atención',
					     msg: 'Ocurrió un error en el ingreso',
					     buttons: Ext.Msg.OK,
					     icon: Ext.Msg.QUESTION,
					});
				}
			});
		}
	}
});










