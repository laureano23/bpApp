Ext.define('MetApp.store.Produccion.Programacion.ProgramacionStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.Programacion.ProgramacionModel',
	alias: 'programacionStore',	
	id: 'programacionStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,
				
	proxy: {
		type: 'memory',
		api: {
			read: Routing.generate('mbp_produccion_formulasMo_list'),
			create: Routing.generate('mbp_produccion_programacion_select'),
			update: Routing.generate('mbp_produccion_programacion_controlRecurso'),
			//destroy: Routing.generate('mbp_produccion_programacion_select'),
		},
				
		reader: {
			type: 'json',
			root: 'items',
			messageProperty : 'message'	
		},
		
		extraParams: {
			codigo: '',
			cantidad: ''			
		},
				
		writer: {
			type: 'json',
			encode: true,
			root: 'data'
		},
		
    	afterRequest: function(request, success) {
			var processor = Ext.create('MetApp.resources.ux.RequestMessageProcessor');
			processor.Message(request.scope, request.operation.response);
		}
	}
});
