Ext.define('MetApp.store.Parametros.CentroCostosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Parametros.CentroCostosModel',
	alias: 'store.CentroCostosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	
    id: 'CentroCostosStore',
    storeId: 'CentroCostosStore',
	
	proxy: {
		type: 'ajax',
		
		api: {
			create: Routing.generate('mbp_finanzas_nuevoCentroCostos'),
			update: Routing.generate('mbp_finanzas_nuevoCentroCostos'),
			read: Routing.generate('mbp_finanzas_centroCostos'), 
			destroy: Routing.generate('mbp_finanzas_borrarCentroCostos'),
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