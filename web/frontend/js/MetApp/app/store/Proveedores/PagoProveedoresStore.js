Ext.define('MetApp.store.Proveedores.PagoProveedoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.PagoProveedoresModel',
	alias: 'PagoProveedoresStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'PagoProveedoresStore',
	
	proxy: {
		type: 'memory',
				
		reader: {
			type: 'json',
			root: 'data'
		},
		
		
	}
});
