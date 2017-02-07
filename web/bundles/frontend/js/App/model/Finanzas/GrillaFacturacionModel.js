Ext.define('MetApp.model.Finanzas.GrillaFacturacionModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idGrillaFacturacionModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'codigo', type: 'string'},
		{ name: 'descripcion', type: 'string'},
		{ name: 'cantidad', type: 'float' },
		{ name: 'costo', type: 'float' },
		{ name: 'precio', type: 'float' },
		{ name: 'parcial', type: 'float' }
	]
});
