Ext.define('MetApp.controller.Produccion.OrdenesTrabajo.OTController', {
	extend: 'Ext.app.Controller',
	
	views: [
		'MetApp.view.Produccion.OrdenesTrabajo.NuevaOTView',
		'MetApp.view.Clientes.SearchGridClientes',
		'MetApp.view.Articulos.WinArticuloSearch'
	],
	
	stores: [
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#nuevaOt': {
				click: this.NuevaOT
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
		});
	},
		
	NuevaOT: function(btn){
		Ext.widget('NuevaOTView');
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
		});
	},
	
	GuardarOT: function(btn){
		
	}
});


















