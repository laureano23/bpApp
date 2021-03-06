Ext.define('MetApp.store.Produccion.Programacion.MaquinasStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.Programacion.MaquinasModel',
	alias: 'sectoresStore',	
	id: 'sectoresStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,
			
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_produccion_maquinas_sector')		
		},
				
		reader: {
			type: 'json',		
		},
				
		writer: {
			type: 'json',
			root: 'pedidos',
			encode: true
		}
	}
});
