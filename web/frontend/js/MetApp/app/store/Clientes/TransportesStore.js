Ext.define('MetApp.store.Clientes.TransportesStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Clientes.TransportesModel',
	alias: 'TrasnportesStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'TrasnportesStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_clientes_listarTransportes'),
			create: Routing.generate('mbp_clientes_crearTransporte'),
			update: Routing.generate('mbp_clientes_crearTransporte'),
			destroy: Routing.generate('mbp_clientes_borrarTransporte'),
		},
		
		reader: {
			type: 'json',
			root: 'data'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});
