Ext.define('MetApp.store.Calidad.Estanqueidad',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Calidad.Estanqueidad',
	autoLoad: false,
	autoSync: true,
	id: 'estanqueidadStore',
    //buffered: true,
    pageSize: 500,
	
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_calidad_listEstanqueidad'),
			create: Routing.generate('mbp_calidad_addEstanqueidadReg'),	
			update: Routing.generate('mbp_calidad_addEstanqueidadReg'),
			destroy: Routing.generate('mbp_calidad_deleteEstanqueidad')
		},
		
		reader: {
			type: 'json',
			root: 'data',
			idProperty: 'id',
			messageProperty: 'message',
			successProperty	: 'success',
			totalProperty: 'total'
		},
		
		writer: {
			type: 'json',
			root: 'data',	
			encode: true						
		},
		
		extraParams: {
			idReg: ''
		},
		
		afterRequest: function(request, success){				
				if(success==true & request.action == 'create' || request.action == 'update'){
					var form = Ext.ComponentQuery.query('#estanqueidadForm form')[0];
					form.getForm().reset();
				}
		},
	}
});
