Ext.define('MetApp.model.Finanzas.CCClientesModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idCCClientes',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'idF', type: 'int' },
		{ name: 'idCob', type: 'int' },
		{ name: 'emision', type: 'datetime'},
		{ name: 'concepto', type: 'string'},
		{ name: 'vencimiento', type: 'datetime', dateFormat: 'c'},
		{ name: 'debe', type: 'float' },
		{ name: 'haber', type: 'float' },
		{ name: 'saldo', type: 'float' },
		{ name: 'tipo', type: 'string' },
	]
});
