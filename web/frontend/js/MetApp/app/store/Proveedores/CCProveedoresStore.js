Ext.define('MetApp.store.Proveedores.CCProveedoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Proveedores.CCProveedoresModel',
	alias: 'CCProveedoresStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'CCProveedoresStore',
    listeners: {
    	remove: {
    		fn: function(st){
    			var response = st.getProxy().getReader();
    			console.log(response);//st.load();
    		}
    	}
    },
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_proveedores_listarCC'),
			destroy: Routing.generate('mbp_proveedores_eliminarComprobante')
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
