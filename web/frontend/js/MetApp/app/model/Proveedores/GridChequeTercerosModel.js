Ext.define('MetApp.model.Proveedores.GridChequeTercerosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'banco', type: 'string'},
		{name: 'emision', type: 'datetime'},
		{name: 'diferido', type: 'date', dateFormat: 'd/m/Y'},
		{name: 'numero', type: 'string'},
		{name: 'librador', type: 'string'},
		{name: 'importe', type: 'float'},
		{name: 'marca', type: 'boolean'},
		{name: 'fid', type: 'int'},
	]
});
