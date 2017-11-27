Ext.define('MetApp.model.Personal.SindicatosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{ name: 'idSindicato', type: 'int' },
		{ name: 'sindicato', type: 'string' },
	]
});
