Ext.define('MetApp.model.Parametros.CentroCostosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idCentroCostosModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'nombre', type: 'string'},
		{ name: 'costo', type: 'float' },		
	]
});
