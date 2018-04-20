Ext.define('MetApp.store.Articulos.SubFamiliaStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.SubFamilia',
	alias: 'subFamiliaStore',	
	storeId: 'subFamiliaStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',	
		
		api: {
			create: Routing.generate('mbp_articulos_crearSubFamilia'),
			read: Routing.generate('mbp_articulos_listarSubFamilias'),
			update: Routing.generate('mbp_articulos_actualizarSubFamilia'),
			destroy: Routing.generate('mbp_articulos_eliminarSubFamilia'),
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
