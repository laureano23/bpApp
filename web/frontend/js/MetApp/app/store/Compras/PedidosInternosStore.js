Ext.define('MetApp.store.Compras.PedidosInternosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Compras.OrdenCompraModel',
	alias: 'store.PedidosInternosStore',
	autoSync: true,
	autoLoad: false,
    id: 'PedidosInternosStore',
    storeId: 'PedidosInternosStore',
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
           // root: 'items'
        },
    }
});