Ext.define('MetApp.store.Produccion.CalculoRadiadores.BrazingParams', {
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.CalculoRadiadores.BrazingParams',
	
	alias: 'brazingParamsStore',
	autoSync: true,
	autoLoad: false,
	autoSave: true,	
	
	proxy: {
		type: 'ajax',
		api: {
			create: '',
			read: Routing.generate('mbp_produccion_readbrazingparams'),
			update: Routing.generate('mbp_produccion_updatebrazingparams'),					
		},
		
		reader: {
			type: 'json',
			root: 'data',
		},
		
		extraParams: {
			tipoCarga: 'Liviana'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: 'true'
		}
	}
});

