Ext.define('MetApp.model.Produccion.ProduccionSoldadoModel', {
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'fecha', type: 'date', dateFormat: 'd/m/Y' },
		{ name: 'idP', type: 'string' },
		{ name: 'nombre', type: 'string' },
		{ name: 'ot', type: 'string' },
		{ name: 'cantidad', type: 'int' },
		{ name: 'operacionId', type: 'string' },
		{ name: 'operacion', type: 'string' },
		{ name: 'hsInicio', type: 'datetime', dateFormat: 'H:i' },
		{ name: 'hsFin', type: 'datetime', dateFormat: 'H:i' },
		{ name: 'observaciones', type: 'string' },
	]
});