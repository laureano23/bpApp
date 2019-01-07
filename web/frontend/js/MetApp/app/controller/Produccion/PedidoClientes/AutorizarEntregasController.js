Ext.define('MetApp.controller.Produccion.PedidoClientes.AutorizarEntregasController', {
	extend: 'Ext.app.Controller',
	views: [
		'Produccion.Pedidos.AutorizarEntregaView'
	],
	stores: [
		'Produccion.PedidoClientes.AutorizarEntregaStore',
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#autorizarEntregas': {
				click: this.AddWin 
			},
			'AutorizarEntregaView textfield[itemId=cliente]': {
				keyup: this.FiltrarCliente
			},
		});
	},

	FiltrarCliente: function(txt){
		var win=txt.up('window');
		var grid=win.down('grid');
		var store=grid.getStore();

		store.clearFilter(true);
		store.filter('clienteDesc', txt.getValue());
	},

	AddWin: function(btn){
		var win = Ext.widget('AutorizarEntregaView');
		var grid=win.down('grid');
		var store = grid.getStore();
		
		store.clearFilter(true);
		store.load();
	}
	
	
});



























