Ext.define('MetApp.store.Proveedores.ProveedoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.ProveedoresModel',
	alias: 'ProveedoresStore',
	autoSync: false,
	autoLoad: true,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'ProveedoresStore',
	
	
});
