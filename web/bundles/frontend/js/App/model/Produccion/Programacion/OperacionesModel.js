Ext.define('MetApp.model.Produccion.Programacion.OperacionesModel', {
	extend: 'Ext.data.Model',
	idProperty : 'id',
	clientIdProperty: 'clientId',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'descripcion', type: 'string' },
		{ name: 'centroCosto', type: 'auto' }
	]
});