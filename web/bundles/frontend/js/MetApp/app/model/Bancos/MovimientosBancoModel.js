Ext.define('MetApp.model.Bancos.MovimientosBancoModel',{
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'numCbte', type: 'string' },
		{ name: 'banco', type: 'string' },
		{ name: 'fechaDiferida', type: 'datetime' },
		{ name: 'importe', type: 'float' },
		{ name: 'obsCbte', type: 'string' },
		{ name: 'idChequeTerceros', type: 'string' },
	]
});

