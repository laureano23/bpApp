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
			'RemitoClienteView textfield[itemId=oc]': {
				specialkey: this.BuscarOC
			},
			'RemitoClienteView button[itemId=insertarItem]': {
				click: this.InsertarItem
			},
			'RemitoClienteView button[itemId=editarItem]': {
				click: this.EditarItem
			},
			'RemitoClienteView button[itemId=borrarItem]': {
				click: this.BorrarItem
			},
			'RemitoClienteView button[itemId=guardarRemito]': {
				click: this.GuardarRemito
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
			winRemito.queryById('unidad').setValue(selection.data.unidad);

			winArt.close();
			winRemito.queryById('cantidad').focus('', 20);
		});
	},


	BuscarOC: function(txt, ev){
		if (ev.getKey() != ev.ENTER) {
            return;
        }

        var winRemito = txt.up('window');
        var winPendientes = Ext.widget('ArticulosOrdenCompraView');
        var grid = winPendientes.down('grid');

        Ext.Ajax.request({
        	url: Routing.generate('mbp_produccion_pedidos_articulo_cliente'),

        	params: {
        		idCliente: winRemito.queryById('idCliente').getValue(),
        		codigo: winRemito.queryById('codigo').getValue(),
        	},

        	success: function(resp){
        		grid.getStore().loadRawData(resp);

        		winPendientes.queryById('ingresaOC').on('click', function(){
		        	
		        	var selection = grid.getSelectionModel().getSelection();
		        	selection = selection[0];

		        	winRemito.queryById('oc').setValue(selection.data.oc);
		        	winRemito.queryById('pedidoNum').setValue(selection.data.pedidoNum);

		        	winPendientes.close();
		        });
        	}
        });     
	},

	InsertarItem: function(btn){
		var winRemito = btn.up('window');
		var grid = winRemito.down('grid');
		var form = winRemito.down('form');
		var values = form.getForm().getValues();

		if(form.isValid()){
			grid.getStore().add(values);
			form.getForm().reset();
		}	

	},

	EditarItem: function(btn)
	{
		var winRemito = btn.up('window');
		var grid = winRemito.down('grid');
		var form = winRemito.down('form');
		var selection = grid.getSelectionModel().getSelection();
		selection = selection[0];
		
		form.loadRecord(selection);
		grid.getStore().remove(selection);
	},

	BorrarItem: function(btn){
		var winRemito = btn.up('window');
		var grid = winRemito.down('grid');
		var selection = grid.getSelectionModel().getSelection();
		selection = selection[0];

		grid.getStore().remove(selection);
	},

	GuardarRemito: function(btn){
		var winRemito = btn.up('window');
		var grid = winRemito.down('grid');
		var store = grid.getStore();
		var data = [];

		store.each(function(rec){
			data.push(rec.data);
		});

		console.log(data);
		Ext.Ajax.request({
			url: Routing.generate('mbp_articulos_generarRemitoCliente'),

			params: {
				items: Ext.JSON.encode(data),
				clienteId: winRemito.queryById('idCliente').getValue()
			},

			success: function(resp){
				console.log(resp);
				var response = Ext.JSON.decode(resp.responseText);				
			}
		});
	}
});











