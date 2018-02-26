Ext.define('MetApp.model.Finanzas.TiposPagoModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idTiposPagoModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'descripcion', type: 'string'},
		{ name: 'esBancario', type: 'bool'},
		{ name: 'retencionIIBB', type: 'bool'},
		{ name: 'retencionIVA21', type: 'bool'},
		{ name: 'chequeTerceros', type: 'bool'},	
		{name: 'esChequePropio', type: 'bool'},		
	]
});
