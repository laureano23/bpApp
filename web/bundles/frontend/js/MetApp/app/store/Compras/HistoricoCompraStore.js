Ext.define('MetApp.store.Compras.HistoricoCompraStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Compras.OrdenCompraModel',
	alias: 'store.HistoricoCompraStore',
	autoSync: true,
	autoLoad: false,
    id: 'HistoricoCompraStore',
    storeId: 'HistoricoCompraStore',
	
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