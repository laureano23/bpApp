Ext.define('MetApp.model.Produccion.Programacion.SectoresModel', {
	extend: 'Ext.data.Model',
	idProperty : 'id',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'descripcion', type: 'string' },
		{ name: 'costo', type: 'int' },
		{ name: 'tiempo', type: 'int' },
		{ name: 'personal' }
	]
});