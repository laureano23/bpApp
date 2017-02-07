Ext.define('MetApp.store.Personal.PersonalStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.PersonalModel',
	alias: 'store.personalStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    storeId: 'personalStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',	
		method: 'POST',	
		api: {
			read: Routing.generate('mbp_personal_list'),
			create: Routing.generate('mbp_personal_create'),
			update: Routing.generate('mbp_personal_create'),
			destroy: Routing.generate('mbp_personal_delete')			
		},
		
		params: {
			isFull: 0
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