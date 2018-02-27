Ext.define('MetApp.store.Bancos.CuentasBancoStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Bancos.CuentasBancoModel',
	alias: 'CuentasBancoStore',	
	storeId: 'CuentasBancoStore',
	autoLoad: false,
	autoSync: true,	
			
	proxy: {
		type: 'ajax',	
		
		api: {
			create: '',
			read: Routing.generate('mbp_finanzas_listaCuentas'),
			update: '',
			destroy: ''
		},
		
		reader: {
			type: 'json',
			root: 'data',
		},
		
	}
});
