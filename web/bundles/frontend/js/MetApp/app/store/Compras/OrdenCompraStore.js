Ext.define('MetApp.store.Compras.OrdenCompraStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Compras.OrdenCompraModel',
	alias: 'store.OrdenCompraStore',
	autoSync: true,
	autoLoad: false,
    id: 'OrdenCompraStore',
    storeId: 'OrdenCompraStore',
	
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