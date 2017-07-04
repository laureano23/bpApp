Ext.define('MetApp.store.Articulos.EnfriadoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.Articulos',
	alias: 'enfriadoresStore',	
	storeId: 'enfriadoresStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		
		api: {
			read: Routing.generate('mbp_articulos_enfriadores_leer'),
		},
		
		reader: {
			type: 'json',
			root: 'data',
			successProperty: 'success'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});
