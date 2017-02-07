Ext.define('MetApp.store.Personal.CategoriasStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.CategoriasModel',
	alias: 'store.categoriasStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'categoriasStore',
    storeId: 'categoriasStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',
		
		api: {
			read: Routing.generate('mbp_personal_categoriasList'), 
			create: Routing.generate('mbp_personal_categoriasCreate'),
			update: Routing.generate('mbp_personal_categoriasCreate'),
			destroy: Routing.generate('mbp_personal_categoriasDelete'),
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