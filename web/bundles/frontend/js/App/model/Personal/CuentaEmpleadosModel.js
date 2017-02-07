Ext.define('MetApp.model.Personal.CuentaEmpleadosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idCategoria',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'periodo', type: 'string'},
		{ name: 'mes', type: 'int'},
		{ name: 'anio', type: 'int'},
		{ name: 'neto', type: 'float'},
		{ name: 'saldo', type: 'float'},
		{ name: 'pagado', type: 'float'},
		{ name: 'compensatorio', type: 'int'},
		{ name: 'idPersonal', type: 'int'}
	]
});
