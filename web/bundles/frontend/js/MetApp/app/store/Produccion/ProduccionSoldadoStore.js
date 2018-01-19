Ext.define('MetApp.store.Produccion.ProduccionSoldadoStore', {
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.ProduccionSoldadoModel',	
	alias: 'ProduccionSoldadoStore',
	autoSync: true,
	autoLoad: false,
	buffered: true,
	pageSize: 300,
	
	proxy: {
		type: 'ajax',
		api: {
			create: Routing.generate('mbp_produccion_nuevoRegistroSoldado'),
			read: Routing.generate('mbp_produccion_listarProduccionSoldado'),
			update: Routing.generate('mbp_produccion_nuevoRegistroSoldado'),
			destroy: Routing.generate('mbp_produccion_borrarRegistroSoldado'),					
		},
		
		reader: {
			type: 'json',
			root: 'data',
			messageProperty: 'msg'
		},
		
		sorters: {
	        property: 'id',
	        direction: 'ASC'
	     },
		
		
		writer: {
			type: 'json',
			root: 'data',
			encode: 'true'
		}
	}
});

