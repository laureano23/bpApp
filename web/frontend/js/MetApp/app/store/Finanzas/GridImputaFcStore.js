Ext.define('MetApp.store.Finanzas.GridImputaFcStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.GridImputaFcModel',
	alias: 'store.GridImputaFcStore',
	autoSync: true,
	autoLoad: false,
	storeId: 'GridImputaFcStore',
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