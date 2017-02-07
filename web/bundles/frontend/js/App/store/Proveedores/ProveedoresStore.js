Ext.define('MetApp.store.Proveedores.ProveedoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.ProveedoresModel',
	alias: 'ProveedoresStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'ProveedoresStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_proveedores_listar')			
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
