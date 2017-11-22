Ext.define('MetApp.model.Personal.CategoriasModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idCategoria',
	fields: [
		{ name: 'idCategoria', type: 'int' },
		{ name: 'idSindicato', type: 'int'},
		{ name: 'sindicato' },
		{ name: 'salario', type: 'string' },
		{ name: 'categoria', type: 'string' },
	]
});
