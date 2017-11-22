Ext.define('MetApp.store.Produccion.CalculoRadiadores.Ot',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.CalculoRadiadores.OtModel',
	
	alias: 'OtStore',
	storeId: 'OtStore',
	autoSync: true,
	autoLoad: false,
	autoSave: true,
	
	
	proxy: {
		type: 'ajax',
		api: {
			create: Routing.generate('mbp_produccion_nuevaotpanel'),	
			read: Routing.generate('mbp_produccion_readOtPaneles'),			
		},
		
		reader: {
			type: 'json',
			root: 'data',
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: 'true'
		},
		
		listeners: {
			exception: function(proxy, response, operation){
				Ext.MessageBox.show({
						title: 'ERROR',
						msg: 'Error status: ' + operation.getError().status,
						icon: Ext.MessageBox.ERROR,
						buttons: Ext.Msg.OK
				});
			}
		},
		
		/*afterRequest: function(request, success){
			if(request.action == 'create' & success == true){
				var form = Ext.ComponentQuery.query('#otWinPaneles form')[0];		
				form.getForm().reset();	
			}
		}*/
	}
});