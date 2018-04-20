Ext.define('MetApp.store.Personal.LiquidacionStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.LiquidacionModel',
	alias: 'store.liquidacionStore',
	autoSync: true,
	autoLoad: false,
	storeId: 'LiquidacionStore',
	remoteFilter: false,	  
    pageSize: 10,
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
            root: 'items'
        },
        
        writer: {
			type: 'json',
			root: 'items',
			encode: true
		}
    }
});