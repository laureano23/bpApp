Ext.define('MetApp.store.Personal.RecibosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.RecibosModel',
	alias: 'store.recibosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'recibosStore',
    storeId: 'recibosStore',
	
	proxy: {
		type: 'ajax',
		
		extraParams: {
			pagoTipo: '',
			mes: '',
			anio: ''
		},
				
		api: {
			read: Routing.generate('mbp_personal_recibosLectura'),
			create: Routing.generate('mbp_personal_recibosLiquida'),
			update: '',
			destroy: '',
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