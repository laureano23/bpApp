Ext.define('MetApp.controller.Produccion.OrdenesTrabajo.OTController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.OrdenesTrabajo.NuevaOTView',
		'MetApp.view.Clientes.SearchGridClientes',
		'MetApp.view.Articulos.WinArticuloSearch',
		'MetApp.view.Produccion.OrdenesTrabajo.CierreOTView'
	],
	
	stores: [
		'Produccion.OrdenesTrabajo.CierreOTGridStore'
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#nuevaOt': {
				click: this.NuevaOT
			},
			'NuevaOTView button[itemId=nueva]': {
				click: this.HabilitaForm
			},
			'NuevaOTView datefield[itemId=emision]': {
				blur: this.BlurEmision
			},
			'NuevaOTView button[itemId=guardar]': {
				click: this.GuardarOT
			},
			'NuevaOTView button[itemId=btnCliente]': {
				click: this.BuscarCliente
			},
			'NuevaOTView button[itemId=btnCodigo]': {
				click: this.BuscarCodigo
			},
			'#cierreOt': {
				click: this.CerrarOT
			},
			'CierreOTView button[itemId=guardar]': {
				click: this.GuardarCierreOt
			},
		});
	},
		
	NuevaOT: function(btn){
		Ext.widget('NuevaOTView');
	},
	
	HabilitaForm: function(btn){
		var form = btn.up('form');
		form.query('field').forEach(function(field){
			field.setReadOnly(false);		
		});
		form.queryById('emision').setReadOnly(true);
		form.queryById('btnCliente').focus('', 20);
	},
	
	BlurEmision: function(field){
		var win = field.up('window');
		win.queryById('btnCliente').focus('', 20);
	},
	
	BuscarCliente: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		var viewClientes = Ext.widget('clientesSearchGrid');
		viewClientes.down('grid').getStore().load();
		
		var btnInsert = viewClientes.queryById('insertCliente');
		btnInsert.on('click', function(){
			var selection = viewClientes.down('grid').getSelectionModel().getSelection()[0];
			form.loadRecord(selection);
			viewClientes.close();
			win.queryById('btnCodigo').focus('', 20);
		});
	},
	
	BuscarCodigo: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		var winArticulos = Ext.widget('winarticulosearch');
		
		var btnInsert = winArticulos.queryById('insertArt');
		btnInsert.on('click', function(){
			var selection = winArticulos.down('grid').getSelectionModel().getSelection()[0];
			form.loadRecord(selection);
			winArticulos.close();
			win.queryById('cantidad').focus('', 20);
		});
	},
	
	GuardarOT: function(btn){
		var win = btn.up('window');
		var form = win.down('form');
		
		if(form.isValid()){
			var values = form.getValues();
			
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_nuevaot'),
				
				params: {
					data: Ext.JSON.encode(values)
				},
				
				success: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					if(jsonResp.success == true){
						form.getForm().reset();
						form.query('field').forEach(function(field){
							field.setReadOnly(true);		
						});
					}
				},
				
				failure: function(resp){
					var jsonResp = Ext.JSON.decode(resp.responseText);
					form.getForm().markInvalid(jsonResp.errors);
				}
			});
		}
		
	},
	
	CerrarOT: function(btn){
		var view = Ext.widget('CierreOTView');
		var grid = view.down('grid');
		var store = grid.getStore();
		
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_CerrarOtListado'),
			
			success: function(resp){
				jsonData = Ext.JSON.decode(resp.responseText);
				store.loadRawData(jsonData.items);
			},
			
			failure: function(resp){
				
			}
		})
	},
	
	GuardarCierreOt: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var store = grid.getStore();
		var modifiedRecords = store.getModifiedRecords();
		var arrayParam = [];
		
		for(var i=0; i<modifiedRecords.length; i++){
			arrayParam.push(modifiedRecords[i].getData());
		}
		
		Ext.Ajax.request({
			url: Routing.generate('mbp_produccion_ActualizarCerradoOt'),
			
			params: {
				items: Ext.JSON.encode(arrayParam)
			},
			
			success: function(resp){
				jsonResp = Ext.JSON.decode(resp.responseText);
				if(jsonResp.success == true){
					store.sync();
				}
			},
			
			failure: function(resp){
				
			}
		})
	}
});


















