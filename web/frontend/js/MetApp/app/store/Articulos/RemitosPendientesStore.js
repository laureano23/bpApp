Ext.define('MetApp.store.Articulos.RemitosPendientesStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.RemitosModel',
	alias: 'store.RemitosPendientesStore',
	autoSync: true,
	autoLoad: false,
    id: 'RemitosPendientesStore',
    storeId: 'RemitosPendientesStore',
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
        },
        
    }
});