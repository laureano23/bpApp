Ext.define('MetApp.store.Finanzas.CCClientesStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.CCClientesModel',
	alias: 'store.CCClientesStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'CCClientesStore',
    storeId: 'CCClientesStore',
	
	proxy: {
		type: 'ajax',
		
		api: {
			read: Routing.generate('mbp_CCClientes_listar'), 
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