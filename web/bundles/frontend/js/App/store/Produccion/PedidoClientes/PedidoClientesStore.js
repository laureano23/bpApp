Ext.define('MetApp.store.Produccion.PedidoClientes.PedidoClientesStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.PedidoClientes.PedidosClientesModel',
	alias: 'pedidoClientesStore',	
	id: 'pedidoClientesStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',		
		
		api: {
			read: Routing.generate('mbp_produccion_listar_pedidos'),
		},
		
		reader: {
			type: 'json',		
		},
				
		writer: {
			type: 'json',
			root: 'pedidos',
			encode: true
		}
	}
});
