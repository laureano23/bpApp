Ext.define('MetApp.store.Produccion.PedidoClientes.PedidoClientesGridStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.PedidoClientes.PedidosClientesModel',
	alias: 'pedidoClientesGridStore',	
	id: 'pedidoClientesGridStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',		
		
		api: {
			read: Routing.generate('mbp_produccion_formulasMo_list'),
		},
		
		reader: {
			type: 'json',
			root: 'pedidos'			
		},
				
		writer: {
			type: 'json',
			root: 'pedidos',
			encode: true
		}
	}
});
