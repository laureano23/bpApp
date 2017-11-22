Ext.define('MetApp.store.Personal.BancosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.BancosModel',
	alias: 'store.bancosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'bancosStore',
    storeId: 'bancosStore',
	
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