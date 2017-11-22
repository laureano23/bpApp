Ext.define('MetApp.store.Proveedores.FacturaProveedoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.FacturaProveedoresModel',
	alias: 'FacturaProveedoresStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'FacturaProveedoresStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_proveedores_listar_facturas'),
			create: Routing.generate('mbp_proveedores_crearFcProveedor'),
			update: Routing.generate('mbp_proveedores_crearFcProveedor')
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
