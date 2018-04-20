Ext.define('MetApp.store.Produccion.OrdenesTrabajo.OTStoreEmisor',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.OrdenTrabajo.CierreOtModel',
	alias: 'store.OTStoreEmisor',
	autoSync: true,
	autoLoad: false,
    id: 'OTStoreEmisor',
    storeId: 'OTStoreEmisor',
	
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