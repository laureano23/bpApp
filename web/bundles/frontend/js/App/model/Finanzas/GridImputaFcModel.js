Ext.define('MetApp.model.Finanzas.GridImputaFcModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idGridImputaFcModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'numFc', type: 'string'},
		{ name: 'haber', type: 'float'},
		{ name: 'vencimiento', type: 'datetime' },
		{ name: 'valorAplicado', type: 'float' },
		{ name: 'pendiente', type: 'float' },
		{ name: 'aplicar', type: 'float' }
	]
});
