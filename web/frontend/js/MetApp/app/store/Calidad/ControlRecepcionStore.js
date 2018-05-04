Ext.define('MetApp.store.Calidad.ControlRecepcionStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Compras.OrdenCompraModel',
	autoLoad: false,
	autoSync: false,
	
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_articulos_listarPendientesVerificacion'),			
			update: Routing.generate("mbp_calidad_controlIngreso"),
		},
		
		reader: {
			type: 'json',
			root: 'data',
			idProperty: 'id',
			messageProperty: 'msg',
			successProperty	: 'success'
		},
		
		writer: {
			type: 'json',
			allowSingle: false,
			encode: true,
			root: 'data',							
		},
	}
});
