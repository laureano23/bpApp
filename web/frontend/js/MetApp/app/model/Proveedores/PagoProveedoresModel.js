Ext.define('MetApp.model.Proveedores.PagoProveedoresModel',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'fid', type: 'int'},
		{name: 'idCheque', type: 'int'},
		{name: 'formaPago', type: 'string'},
		{name: 'numero', type: 'string'},
		{name: 'banco', type: 'string'},
		{name: 'importe', type: 'float'},
		{name: 'diferido', type: 'date', dateFormat: 'd/m/Y', submitFormat:'d/m/Y'},
		{name: 'conceptoBancario', type: 'string'},
		{name: 'retencionIIBB', type: 'bool'},
		{name: 'cuenta', type: 'int'},			
	]
});
