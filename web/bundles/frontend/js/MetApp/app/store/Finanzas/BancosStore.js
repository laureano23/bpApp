Ext.define('MetApp.store.Finanzas.BancosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.BancosModel',
	alias: 'store.BancosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'BancosStore',
    storeId: 'BancosStore',
	
	proxy: {
		type: 'ajax',
		
		api: {
			read: Routing.generate('mbp_finanzas_listaBancos'), 
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