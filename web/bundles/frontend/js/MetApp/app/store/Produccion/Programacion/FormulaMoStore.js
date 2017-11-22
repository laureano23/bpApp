Ext.define('MetApp.store.Produccion.Programacion.FormulaMoStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.Programacion.FormulasMoModel',
	alias: 'formulasMoStore',	
	id: 'formulasMoStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,
			
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_produccion_formulasMo_list'),
			create: Routing.generate('mbp_produccion_formulasMo_create'),
			update: Routing.generate('mbp_produccion_formulasMo_create'),
			destroy: Routing.generate('mbp_produccion_formulasMo_delete')
		},
				
		reader: {
			type: 'json',
			root: 'items'		
		},
		
		extraParams: {
			codigo: ''
		},
				
		writer: {
			type: 'json',
			encode: true,
			root: 'data'
		},
		
		listeners: {
		 	exception: function(a, response, operation, eOpts ){
		 		Ext.Msg.show({
	    			title: 'Error',
	    			msg: 'Codigo: '+response.status+' '+response.statusText,
	    			buttons: Ext.Msg.OK,
	    			icon: Ext.Msg.WARNING
	    		});
			}
    	}
	}
});
