Ext.define('MetApp.model.Calidad.Correlativos',{
	extend: 'Ext.data.Model',
	idProperty: 'idCorrelativos',
	fields: [
		{name: 'idCorrelativos', type: 'int'},
		{name: 'numCorrelativo', type: 'int'},
		{name: 'num_hasta', type: 'int'},		
		{name: 'cant', type: 'int'},
		{name: 'fecha.date', type: 'datetime', dateFormat: 'c'},
		{name: 'otEnf', type: 'int'},		
		{name: 'est_hasta', type: 'int'},		
		{name: 'ot1panel', type: 'int'},
		{name: 'ot2panel', type: 'int'},
		{name: 'ot3panel', type: 'int'},
		{name: 'ot4panel', type: 'int'},
		{name: 'id_articulos', type: 'int'},
		{name: 'id_cliente', type: 'int'},
		{name: 'obs', type: 'string'},
	]
});
