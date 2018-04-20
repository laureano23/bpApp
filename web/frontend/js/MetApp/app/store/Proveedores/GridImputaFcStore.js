Ext.define('MetApp.store.Proveedores.GridImputaFcStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.FacturaProveedoresModel',
	alias: 'GridImputaFcStore',
	autoSync: false,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'GridImputaFcStore',
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_proveedores_listar_facturas'),
			create: Routing.generate('mbp_proveedores_crearFcProveedor'),
			//update: Routing.generate('mbp_proveedores_crearFcProveedor')
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
