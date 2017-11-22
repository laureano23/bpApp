Ext.define('MetApp.model.Finanzas.TiposPagoModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idTiposPagoModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'descripcion', type: 'string'},
		{ name: 'conceptoBancario', type: 'string'},			
	]
});
