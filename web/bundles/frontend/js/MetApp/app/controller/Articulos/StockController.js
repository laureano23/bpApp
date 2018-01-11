Ext.define('MetApp.controller.Articulos.StockController',{
	extend: 'Ext.app.Controller',	
	
	views: [
		'MetApp.view.Articulos.Stock.EntradaSalidaArticulos',	
		'MetApp.view.Proveedores.SearchGridProveedores',
		'MetApp.view.Clientes.SearchGridClientes',
		'MetApp.view.Articulos.WinArticuloSearch',
		'MetApp.view.Compras.PendienteArticuloComprasView'	
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
		});		
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
			console.log(selection);
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
			winStock.queryById('cantidad').focus('', 10);
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
					console.log(jsonResp);
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










