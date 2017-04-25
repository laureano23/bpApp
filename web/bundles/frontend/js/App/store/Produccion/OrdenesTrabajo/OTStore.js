Ext.define('MetApp.store.Produccion.OrdenesTrabajo.OTStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.OrdenTrabajo.CierreOtModel',
	alias: 'store.OTStore',
	autoSync: true,
	autoLoad: false,
    id: 'OTStore',
    storeId: 'OTStore',
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
           // root: 'items'
        },
        
        /*writer: {
			type: 'json',
			root: 'items',
			encode: true
		}*/
    }
});