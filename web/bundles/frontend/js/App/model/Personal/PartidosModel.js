Ext.define('MetApp.model.Personal.PartidosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idPartido',
	fields: [
		{name: 'idPartido', type: 'int'},
		{name: 'nombre', type: 'string'},
		{name: 'idProvincia'}
	]
});
