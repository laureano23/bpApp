Ext.define('MetApp.store.Personal.PartidosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.PartidosModel',
	alias: 'store.partidosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'partidosStore',
	
		
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		url: Routing.generate('mbp_personal_partidosList'),
		
		reader: {
			type: 'json',
			root: 'items'
		},
		
		extraParams: {
			provinciaId: ''
		},
		
		writer: {
			type: 'json',
			root: 'data'
		}
	}
});