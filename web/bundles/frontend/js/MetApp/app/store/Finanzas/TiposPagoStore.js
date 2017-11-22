Ext.define('MetApp.store.Finanzas.TiposPagoStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.TiposPagoModel',
	alias: 'store.TiposPagoStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'TiposPagoStore',
    storeId: 'TiposPagoStore',
	
	proxy: {
		type: 'ajax',
		
		api: {
			read: Routing.generate('mbp_finanzas_listaTipos'), 
		},			
		
		reader: {
			type: 'json',
			root: 'items'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});