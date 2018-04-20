Ext.define('MetApp.model.Personal.LocalidadesModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idLocalidad',
	fields: [
		{name: 'idLocalidad', type: 'int'},
		{name: 'nombre', type: 'string'},
		{name: 'idDepartamento'}
	]
});
