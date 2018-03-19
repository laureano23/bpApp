Ext.define('MetApp.model.Finanzas.TiposComprobantesModel',{
	extend: 'Ext.data.Model',
	idProperty: 'TiposComprobantesModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'descripcion', type: 'string'},
		{ name: 'esFactura', type: 'bool'},
		{ name: 'esNotaCredito', type: 'bool'},
		{ name: 'esNotaDebito', type: 'bool'},		
	]
});
