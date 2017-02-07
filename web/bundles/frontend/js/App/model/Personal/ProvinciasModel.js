Ext.define('MetApp.model.Personal.ProvinciasModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idProvincia',
	fields: [
		{name: 'idProvincia', type: 'int'},
		{name: 'nombre', type: 'string'}
	]
});
