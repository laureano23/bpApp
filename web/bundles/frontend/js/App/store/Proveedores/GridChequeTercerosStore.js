Ext.define('MetApp.store.Proveedores.GridChequeTercerosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.GridChequeTercerosModel',
	alias: 'GridChequeTerceros',
	autoSync: true,
	autoLoad: false,
    storeId: 'GridChequeTerceros',
	
	proxy: {
		type: 'memory',
				
		reader: {
			type: 'json',
			root: 'data'
		},		
	}
});
