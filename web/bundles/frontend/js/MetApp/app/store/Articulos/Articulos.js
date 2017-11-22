Ext.define('MetApp.store.Articulos.Articulos',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.Articulos',
	alias: 'articuloStore',	
	storeId: 'articuloStore',
	autoLoad: true,
	autoSync: true,	
	remoteFilter: false,	  
    pageSize: 1000,
			
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		
		api: {
			create: Routing.generate('mbp_articulos_create'),
			read: Routing.generate('mbp_articulos_read'),
			update: Routing.generate('mbp_articulos_create'),
			destroy: Routing.generate('mbp_articulos_destroy'),
		},
		
		reader: {
			type: 'json',
			root: 'data',
			successProperty: 'success',
			totalProperty: 'total_art'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		},
		
		/*afterRequest: function(request, success){
			if(request.action == 'create' & success == true){
				var form = Ext.ComponentQuery.query('#articulosForm form')[0];				
				form.loadRecord(request.records[0]);				
			}
		}*/
	}
});
