Ext.define('MetApp.store.Finanzas.VendedorStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.VendedorModel',
	alias: 'store.VendedorStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    storeId: 'VendedorStore',
	
});