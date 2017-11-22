Ext.define('MetApp.model.Personal.LiquidacionModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idP',
	fields: [
		{ name: 'idRecibo', type: 'int' },
		{ name: 'idConcepto', type: 'int' },
		{ name: 'descripcionConcepto', type: 'string' },
		{ name: 'unidad', type: 'string' },
		{ name: 'cantidadConcepto', type: 'float' },
		{ name: 'totalConcepto', type: 'int' },
		{ name: 'importe', type: 'float' },
		{ name: 'subTotal', type: 'float' },
		{ name: 'compensatorio', type: 'bool' },					
	],
});