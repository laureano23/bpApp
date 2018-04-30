Ext.define('MetApp.store.Clientes.CotizacionStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Clientes.CotizacionModel',
	alias: 'clientesStore',
	autoSync: false,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'clientesStore',
	
	
});
