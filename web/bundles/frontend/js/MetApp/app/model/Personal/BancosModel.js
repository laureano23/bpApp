Ext.define('MetApp.model.Personal.BancosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idBanco',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'nombre', type: 'string'},
	]
});
