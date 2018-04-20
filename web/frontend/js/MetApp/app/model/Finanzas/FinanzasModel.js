Ext.define('MetApp.model.Finanzas.FinanzasModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idBanco',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'nombre', type: 'string'},
	]
});
