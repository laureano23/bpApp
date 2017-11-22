Ext.define('MetApp.model.Articulos.ConceptosEntradaModel',{
	extend: 'Ext.data.Model',
	idProperty: 'EntradaModel',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'concepto', type: 'string'},	
	]
});

