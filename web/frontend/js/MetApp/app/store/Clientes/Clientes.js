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
			create: Routing.generate('mbp_clientes_new'),
			read: Routing.generate('mbp_clientes_search'),			
			update: Routing.generate('mbp_clientes_new'),
			destroy: Routing.generate('mbp_clientes_new')			
		},
		
		reader: {
			type: 'json',
			root: 'data',
			idProperty: 'id'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});
