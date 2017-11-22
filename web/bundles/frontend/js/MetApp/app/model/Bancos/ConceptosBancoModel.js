Ext.define('MetApp.model.Bancos.ConceptosBancoModel',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'concepto', type: 'string'},
		{name: 'conceptoBancarioId', type: 'string'},		
	]
});

