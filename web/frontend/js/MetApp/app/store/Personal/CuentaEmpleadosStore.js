Ext.define('MetApp.store.Personal.CuentaEmpleadosStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Personal.CuentaEmpleadosModel',
	alias: 'store.cuentaEmpleadosStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 10,
    id: 'cuentaEmpleadosStore',
    storeId: 'cuentaEmpleadosStore',
	
	proxy: {
		type: 'ajax',
		
		api: {
			/*read: Routing.generate('mbp_personal_cuentaEmpleadosList'), 
			create: Routing.generate('mbp_personal_cuentaEmpleadosCreate'),
			update: Routing.generate('mbp_personal_cuentaEmpleadosCreate'),
			destroy: Routing.generate('mbp_personal_cuentaEmpleadosDelete'),*/
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