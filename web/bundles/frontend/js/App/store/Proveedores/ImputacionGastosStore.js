Ext.define('MetApp.store.Proveedores.ImputacionGastosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.ImputacionGastosModel',
	alias: 'ImputacionGastosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'ImputacionGastosStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_proveedores_listarGastos')			
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
