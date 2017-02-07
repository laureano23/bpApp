Ext.define('MetApp.store.Personal.LocalidadesStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.LocalidadesModel',
	alias: 'store.localidadesStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'localidadesStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		url: Routing.generate('mbp_personal_localidadesList'),
		
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