Ext.define('MetApp.store.Clientes.Clientes',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Clientes.ClientesModel',
	alias: 'clientesStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'clientesStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_clientes_search')			
		},
		
		reader: {
			type: 'json',
			root: 'data'
		},
		
		writer: {
			type: 'json',
			root: 'data'
		}
	}
});
