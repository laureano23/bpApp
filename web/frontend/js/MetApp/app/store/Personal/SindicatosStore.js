Ext.define('MetApp.store.Personal.SindicatosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.SindicatosModel',
	alias: 'store.sindicatosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    storeId: 'sindicatosStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',
		
		api: {			
			create: Routing.generate('mbp_personal_sindicatoCreate'),
			read: Routing.generate('mbp_personal_sindicatosList'),
			update: Routing.generate('mbp_personal_sindicatoCreate'),  
			destroy: Routing.generate('mbp_personal_sindicatoDelete'),
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