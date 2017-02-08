Ext.define('MetApp.store.Calidad.Correlativos',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Calidad.Correlativos',
	autoLoad: false,
	autoSync: true,
	autoSave: true,	
	sorters: [{
        property: 'numCorrelativo',
        direction: 'DESC'
    }],
    pageSize: 100,
	
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_calidad_correlativos'),
			create: Routing.generate('mbp_calidad_newcorrelativo'),			
			update: Routing.generate('mbp_calidad_updatecorrelativo'),
			destroy: Routing.generate('mbp_calidad_destroycorrelativo'),
		},
		
		reader: {
			type: 'json',
			root: 'data',
			idProperty: 'idCorrelativos',
			messageProperty: 'message',
			successProperty	: 'success'
		},
		
		writer: {
			type: 'json',
			encode: true,
			root: 'data',							
		},
		
		afterRequest: function(request, success){
								
		},
		
		listeners: {
			exception: function(proxy, response, operation){
				Ext.MessageBox.show({
						title: 'ERROR',
						msg: operation.getError(),
						icon: Ext.MessageBox.ERROR,
						buttons: Ext.Msg.OK
				});
			}					
		}
	}
});
