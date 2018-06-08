Ext.define('MetApp.store.Clientes.CotizacionDetalleStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Clientes.CotizacionModel',
	alias: 'clientesStore',
	autoSync: false,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'clientesStore',
	
	/*proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
           // root: 'items'
        },
    }*/
});
