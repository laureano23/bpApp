Ext.define('MetApp.store.Finanzas.GrillaFacturacionStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.GrillaFacturacionModel',
	alias: 'store.GrillaFacturacionStore',
	autoSync: true,
	autoLoad: false,
	storeId: 'GrillaFacturacionStore',
	remoteFilter: false,	  
    pageSize: 10,
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
            root: 'items'
        },
        
        /*writer: {
			type: 'json',
			root: 'items',
			encode: true
		}*/
    }
});