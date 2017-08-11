Ext.define('MetApp.store.Produccion.Programacion.OperacionesStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.Programacion.OperacionesModel',
	alias: 'operacionesStore',	
	id: 'operacionesStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,
			
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_produccion_operaciones_list'),
			create: Routing.generate('mbp_produccion_operaciones_create'),
			update: Routing.generate('mbp_produccion_operaciones_create')	
		},
				
		reader: {
			type: 'json',
			root: 'items'		
		},
				
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		},
		
		extraParams: {
			sector: '' //CARGAR EL SECTOR SEGUN SECTORES DE BD
		}
	}
	 
});
