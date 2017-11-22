Ext.define('MetApp.store.Articulos.DepositoArticulosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.DepositoArticulosModel',
	alias: 'DepositoArticulosStore',	
	storeId: 'DepositoArticulosStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',	
		
		api: {
			read: Routing.generate('mbp_articulos_listarDepositos'),
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
