Ext.define('MetApp.store.Finanzas.GrillaPagosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.GrillaPagosModel',
	alias: 'store.GrillaPagosStore',
	autoSync: true,
	autoLoad: false,
	storeId: 'GrillaPagosStore',
	remoteFilter: false,	  
    pageSize: 10,
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
            root: 'items'
        },
    }
});