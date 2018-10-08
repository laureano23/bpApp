Ext.define('MetApp.model.Proveedores.CCProveedoresModel',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'idF', type: 'int'},
		{name: 'idOP', type: 'int'},
		{name: 'fechaEmision', type: 'datetime'},
		{name: 'fechaVencimiento', type: 'datetime'},
		{name: 'concepto', type: 'string'},
		{name: 'debe', type: 'float'},
		{name: 'haber', type: 'float'},
		{name: 'saldo', type: 'float'},
		{name: 'detalle', type: 'boolean'},
		{name: 'imputado', type: 'boolean'},
		{name: 'valorImputado', type: 'float'},
		{name: 'pagado', type: 'boolean'}
	]
});
