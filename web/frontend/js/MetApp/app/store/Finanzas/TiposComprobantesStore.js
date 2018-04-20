Ext.define('MetApp.store.Finanzas.TiposComprobantesStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.TiposComprobantesModel',
	alias: 'store.TiposComprobantesStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,
    id: 'TiposComprobantesStore',
    storeId: 'TiposComprobantesStore',
	
	proxy: {
		type: 'ajax',
		
		api: {
			read: Routing.generate('mbp_CCClientes_listarTiposComprobantes'), 
		},			
		
		reader: {
			type: 'json',
			root: 'data'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});