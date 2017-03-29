Ext.define('MetApp.store.Produccion.OrdenesTrabajo.CierreOTGridStore',{
	extend: 'Ext.data.Store',
	alias: 'CierreOTGridStore',	
	id: 'CierreOTGridStore',
	autoLoad: false,
	autoSync: false,	
	remoteFilter: false,	  
    pageSize: 1000,
	model: 'MetApp.model.Produccion.OrdenTrabajo.CierreOtModel',
	proxy: {
		type: 'memory',		
				
		reader: {
			type: 'json',
			root: 'items'			
		},
		
		writer: {
			type: 'json',
			root: 'items',
			encode: true
		}
	}
});
