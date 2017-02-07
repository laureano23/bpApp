Ext.define('MetApp.controller.Compras.OrdenDeCompraController',{
	extend: 'Ext.app.Controller',	
	views: [
		'MetApp.view.Compras.OrdenCompraView',
		],
	stores: [
		'Articulos.Articulos',
		'MetApp.store.Compras.OrdenCompraStore'
	],
	
	refs:[
		{
			ref:'OrdenCompraView',
			selector: 'OrdenCompraView'
		},
	],
	
	init: function(){
		var me = this;
		
		var store = Ext.getStore('MetApp.store.Compras.OrdenCompraStore');
		store.on({
			'datachanged': me.callbackOrdenCompraStore
		});
		
		me.control({
				'#ordenDeCompra': {
					click: this.addOcWin
				},
				'OrdenCompraView button[itemId=buscaProveedor]': {
					click: this.BuscaProveedor
				},
				'OrdenCompraView button[itemId=buscarArt]': {
					click: this.BuscaArt
				},
				'OrdenCompraView button[itemId=btnNew]': {
					click: this.InsertarItem
				},
				'OrdenCompraView button[itemId=btnDelete]': {
					click: this.EliminarItem
				},
				'OrdenCompraView button[itemId=btnEdit]': {
					click: this.EditarItem
				},
				'OrdenCompraView button[itemId=btnSave]': {
					click: this.GenerarOc
				},	
				'OrdenCompraView combobox[itemId=monedaOC]': {
					change: this.ListenChangeTc
				},			
		});		
	},
	
	callbackOrdenCompraStore: function(st, opts){
		var total=0;
		st.each(function(rec){
			var data = rec.getData();
			total += data.cant * data.costo;			
		});		
		var view = MetApp.getApplication().getController('MetApp.controller.Compras.OrdenDeCompraController').getOrdenCompraView();
		var desc = view.queryById('desc').getValue();
		total = total - desc * total / 100;
		view.queryById('total').setValue(total);
	},
	
	//VENTANA DE ORDEND DE COMPRA
	addOcWin: function(){			
		var me = this;
		var win = Ext.widget('OrdenCompraView');
		var store = win.down('grid').getStore();
		var desc = win.queryById('desc');
		
		//HOT KEY DE LA TABLA LIQUIDACION
		var map = new Ext.util.KeyMap({
		    target: win.getId(),	
		   	binding: [
		   		{
		   			key: Ext.EventObject.F5,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.GenerarOc(win.queryById('btnSave'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F1,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EditarItem(win.queryById('btnEdit'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F3,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){
		   				me.InsertarItem(win.queryById('btnNew'));
		   			}
		   		},
		   		{
		   			key: Ext.EventObject.F8,
		   			defaultEventAction: 'preventDefault',
		   			fn: function(){ 
		   				me.EliminarItem(win.queryById('btnDelete'));
		   			}
		   		},
		   	]
		});	
				
		desc.on('blur', function(){
			var total=0;
			store.each(function(rec){
				var data = rec.getData();
				total += data.cant * data.costo;			
			});
			var desc = win.queryById('desc').getValue();
			total = total - desc * total / 100;
			win.queryById('total').setValue(total);
		});
	},	
	
	BuscaProveedor: function(btn){
		var me = this;
		var winOrdenDeCompra = btn.up('window');
		var win = Ext.widget('ProveedoresSearchGrid');
		var grid = win.down('grid');
		var form = winOrdenDeCompra.queryById('formProveedor');
		grid.getStore().load();
		
		var btnInsert = win.queryById('insertProveedor');
		btnInsert.on('click', function(){
			var record = grid.getSelectionModel().getSelection()[0];
			form.loadRecord(record);
			win.close();
		});
	},
	
	BuscaArt: function(btn){
		var winOc = btn.up('window');
		var formArt = winOc.queryById('formArticulos');
		var winArticulos = Ext.widget('winarticulosearch');
		var btnAceptar = winArticulos.queryById('insertArt');
		
		btnAceptar.on('click', function(){
			var grid = winArticulos.down('grid');
			var selection = grid.getSelectionModel().getSelection()[0];
			console.log(selection);
			
			formArt.loadRecord(selection);
			winArticulos.close();
			winOc.queryById('cant').focus('', 100);
		});		
	},
	
	InsertarItem: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var form = win.queryById('formArticulos');
		var values = form.getForm().getValues();
		var tc = MetApp.resources.ux.ParametersSingleton.dolarOficial;
		values.tcArticulo = tc;
		
		store.loadRawData(values, true);
		form.getForm().reset();
	},
	
	EliminarItem: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		store.remove(selection);
	},
	
	EditarItem: function(btn){
		var win = btn.up('window');
		var form = win.queryById('formArticulos');
		var grid = win.down('grid');
		var store = grid.getStore();
		var selection = grid.getSelectionModel().getSelection()[0];
		
		store.remove(selection);
		form.loadRecord(selection);
	},
	
	GenerarOc: function(btn){
		var win = btn.up('window');
		var store = win.down('grid').getStore();
		var formProv = win.queryById('formProveedor');
		var data = new Array(); 
		var i = 0;
		
		store.each(function(rec){
			data[i] = rec.getData();
			i++;	
		});
		
		var formValues = formProv.getForm().getValues();
		var descuentoGral = win.queryById('desc');
		formValues.descuentoGral = descuentoGral.getValue();
		var jsonDataDetalle = Ext.JSON.encode(data);
		var jsonOrdenData = Ext.JSON.encode(formValues);
		
		if(formProv.isValid() && store.getCount() != 0){
			Ext.Ajax.request({
				url: Routing.generate('mbp_compras_nuevaOrden'),
				params: {
					detalle: jsonDataDetalle,
					orden: jsonOrdenData
				},
				success: function(resp){
					console.log(resp);
					formProv.getForm().reset();
					store.loadRawData([]);
					descuentoGral.setValue(0);
				}
			})
		}
	},
	
	ListenChangeTc: function(combo, newValue, oldValue, eOpts){
		var view = combo.up('window');
		var tc = view.queryById('tc');
		
		if(combo.value == "U$D"){
			tc.setValue(MetApp.resources.ux.ParametersSingleton.dolarOficial);
			tc.setReadOnly(false);
		}else{
			tc.setValue("");
		}
	}
});










