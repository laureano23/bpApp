Ext.define('MetApp.store.Personal.PersonalDatosFijosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.PersonalModel',
	alias: 'store.personalDatosFijosStore',
	autoSync: true,
	autoLoad: false,
	storeId: 'PersonalDatosFijosStore',
	remoteFilter: false,	  
    pageSize: 10,
	
	proxy: {
		type: 'ajax',
		method: 'POST',	
		api: {
			read: Routing.generate('mbp_personal_datosFijosPersonaRead'),
			create: Routing.generate('mbp_personal_datosFijosPersonaCreate'),
			destroy: Routing.generate('mbp_personal_datosFijosPersonaDelete')
		},
				
		reader: {
			type: 'json',
			root: 'items',
			idProperty: 'idP',
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		},
		
		
	}
});