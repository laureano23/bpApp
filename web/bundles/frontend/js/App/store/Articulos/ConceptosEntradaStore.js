Ext.define('MetApp.store.Articulos.ConceptosEntradaStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.ConceptosEntradaModel',
	alias: 'ConceptosEntradaStore',	
	storeId: 'ConceptosEntradaStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',	
		
		api: {
			read: Routing.generate('mbp_articulos_listarConceptosStock'),
		},
		
		reader: {
			type: 'json',
			root: 'data',
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		},
	}
});
