Ext.define('MetApp.model.Finanzas.PosicionIvaModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idPosicionIva',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'posicion', type: 'string'},	
		{name: 'esResponsableInscripto', type: 'boolean'},
		{name: 'esResponsableNoInscripto', type: 'boolean'},
		{name: 'esExento', type: 'boolean'},
		{name: 'esResponsableMonotributo', type: 'boolean'},
		{name: 'esConsumidorFinal', type: 'boolean'},
		{name: 'esExportacion', type: 'boolean'},	
	],
	
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_finanzas_posicionesIva'),			
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
