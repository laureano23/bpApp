Ext.define('MetApp.model.Personal.RecibosModel',{
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'idRecibo', type: 'int' },
		{ name: 'idConcepto', type: 'int' },
		{ name: 'cantidadConcepto', type: 'float'},
		{ name: 'subTotal', type: 'float'},
	]
});
