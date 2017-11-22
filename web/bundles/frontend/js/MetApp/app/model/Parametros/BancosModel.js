Ext.define('MetApp.model.Finanzas.BancosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idBancosModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'nombre', type: 'string'},		
	]
});
