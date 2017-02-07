Ext.define('MetApp.store.Produccion.Programacion.SectoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.Programacion.SectoresModel',
	alias: 'sectoresStore',	
	id: 'sectoresStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,
			
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_produccion_seleccion_sector')		
		},
				
		reader: {
			type: 'json',		
		},
				
		writer: {
			type: 'json',
			root: 'pedidos',
			encode: true
		},
		
		extraParams: {
			sector: ''
		},
	}
});
