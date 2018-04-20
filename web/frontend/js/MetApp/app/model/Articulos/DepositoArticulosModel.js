Ext.define('MetApp.model.Articulos.DepositoArticulosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'EntradaModel',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'deposito', type: 'string'},	
	]
});

