Ext.define('MetApp.model.Proveedores.PagoProveedoresModel',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'idCheque', type: 'int'},
		{name: 'formaPago', type: 'string'},
		{name: 'numero', type: 'string'},
		{name: 'banco', type: 'string'},
		{name: 'importe', type: 'float'},
		{name: 'diferido', type: 'datetime'},
		{name: 'conceptoBancario', type: 'string'},
	]
});
