Ext.define('MetApp.store.Bancos.ConceptosBancoStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Bancos.ConceptosBancoModel',
	alias: 'ConceptosBancoStore',	
	storeId: 'ConceptosBancoStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',	
		
		api: {
			create: '',
			read: Routing.generate('mbp_bancos_listarConceptosBanco'),
			update: '',
			destroy: ''
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
