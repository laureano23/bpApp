Ext.define('MetApp.model.Personal.ProvinciasModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idProvincia',
	fields: [
		{name: 'idProvincia', type: 'string'},
		{name: 'nombre', type: 'string'}
	]
});
