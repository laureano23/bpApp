Ext.define('MetApp.store.Personal.ProvinciasStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.ProvinciasModel',
	alias: 'store.provinciasStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'personalStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		url: Routing.generate('mbp_personal_provinciasList'),
		
		reader: {
			type: 'json',
			root: 'items'
		},
		
				
		writer: {
			type: 'json',
			root: 'data'
		}
	}
});