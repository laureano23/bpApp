Ext.define('MetApp.controller.Articulos.RemitosController',{
	extend: 'Ext.app.Controller',	
	views: [
		'Articulos.Stock.Remitos.ArticulosOrdenCompraView',
		'Articulos.Stock.Remitos.RemitoClienteView',
		'Clientes.SearchGridClientes',
		'Articulos.WinArticuloSearch'
		],
	stores: [],
	
	init: function(){
		var me = this;
		me.control({
			'#remitoCliente': {
				click: this.RemitoClienteView
			},
			'RemitoClienteView button[itemId=buscaCliente]': {
				click: this.BuscarCliente
			},
			'RemitoClienteView button[itemId=buscaArt]': {
				click: this.BuscarArticulo
			},
		});
	},
	
	RemitoClienteView: function(btn){
		Ext.widget('RemitoClienteView');
	},

	BuscarCliente: function(btn){
		var winRemito = btn.up('window');
		var winClientes = Ext.widget('clientesSearchGrid');
		var grid = winClientes.down('grid');
		grid.getStore().load();

		winClientes.queryById('insertCliente').on('click', function(){
			var selection = grid.getSelectionModel().getSelection();
			selection = selection[0];

			winRemito.queryById('idCliente').setValue(selection.data.id);
			winRemito.queryById('cliente').setValue(selection.data.rsocial);

			winClientes.close();
		});
	},

	BuscarArticulo: function(btn){
		var winRemito = btn.up('window');
		var winArt = Ext.widget('winarticulosearch');
		var grid = winArt.down('grid');

		winArt.queryById('insertArt').on('click', function(){
			var selection = grid.getSelectionModel().getSelection();
			selection = selection[0];

			winRemito.queryById('codigo').setValue(selection.data.codigo);
			winRemito.queryById('descripcion').setValue(selection.data.descripcion);

			winArt.close();
			winRemito.queryById('cantidad').focus('', 20);
		});
	},

	
	
});










