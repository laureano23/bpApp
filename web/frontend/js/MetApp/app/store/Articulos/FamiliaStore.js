Ext.define('MetApp.store.Articulos.FamiliaStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.Familia',
	alias: 'familiaStore',	
	storeId: 'familiaStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',	
		
		api: {
			create: Routing.generate('mbp_articulos_crearFamilia'),
			read: Routing.generate('mbp_articulos_listarFamilias'),
			update: Routing.generate('mbp_articulos_actualizarFamilia'),
			destroy: Routing.generate('mbp_articulos_eliminarFamilia'),
		},
		
		reader: {
			type: 'json',
			root: 'data',
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		},
	}
});
