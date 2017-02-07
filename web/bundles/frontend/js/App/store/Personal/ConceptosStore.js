Ext.define('MetApp.store.Personal.ConceptosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.ConceptosModel',
	alias: 'store.categoriasStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'conceptosStore',
    storeId: 'conceptosStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',
		
		api: {
			create: Routing.generate('mbp_personal_conceptosCreate'),
			read: Routing.generate('mbp_personal_conceptosRead'), 
			update: Routing.generate('mbp_personal_conceptosCreate'),
			destroy: Routing.generate('mbp_personal_conceptosDelete'),
		},		
		
		extraParams: {
			variable: 0,
			idP: 0
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